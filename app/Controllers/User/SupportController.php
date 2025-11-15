<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\SupportTicketModel;
use App\Models\SupportMessageModel;
use App\Models\SupportAttachmentModel;

class SupportController extends BaseController
{
    protected $ticketModel;
    protected $messageModel;
    protected $attachmentModel;

    public function __construct()
    {
        $this->ticketModel      = new SupportTicketModel();
        $this->messageModel     = new SupportMessageModel();
        $this->attachmentModel  = new SupportAttachmentModel();
    }


public function index()
{
    $user = current_user();
    $userId = $user['id'] ?? null;

    if (!$userId) {
        return redirect()
            ->to('/user/login')
            ->with('error', 'برای مشاهده تیکت‌ها ابتدا وارد شوید.');
    }

    // فقط تیکت‌های همین کاربر
    $tickets = $this->ticketModel
        ->where('user_id', $userId)
        ->orderBy('updated_at', 'DESC')
        ->findAll();

    return view('user/support/index', [
        'title'   => 'تیکت‌های پشتیبانی',
        'tickets' => $tickets,
    ]);
}



    // نمایش فرم ایجاد تیکت جدید
public function create()
{
    $user   = current_user();
    $userId = $user['id'] ?? null;

    if (! $userId) {
        return redirect()
            ->to('/user/login')
            ->with('error', 'برای ثبت تیکت ابتدا وارد حساب خود شوید.');
    }

    // چک: اگر تیکت باز دارد، اجازه ایجاد نده
    $openTicket = $this->ticketModel
        ->where('user_id', $userId)
        ->where('status !=', 3) // هر چیزی غیر از "بسته شده"
        ->first();

    if ($openTicket) {
        return redirect()
            ->to('/support')
            ->with('error', 'شما یک تیکت باز دارید و تا زمان رسیدگی، نمی‌توانید تیکت جدید ثبت کنید.');
    }

    return view('user/support/create', [
        'title' => 'ایجاد تیکت جدید',
    ]);
}


    // ذخیره تیکت جدید (POST)
public function store()
{
    $user   = current_user();
    $userId = $user['id'] ?? null;

    if (! $userId) {
        return redirect()
            ->to('/user/login')
            ->with('error', 'برای ثبت تیکت ابتدا وارد حساب خود شوید.');
    }

   

    // دوباره چک کن که تیکت باز نداشته باشد (برای امنیت، حتی اگر کاربر مستقیم POST زد)
    $openTicket = $this->ticketModel
        ->where('user_id', $userId)
        ->where('status !=', 3)
        ->first();

    if ($openTicket) {
        return redirect()
            ->to('/support')
            ->with('error', 'شما یک تیکت باز دارید و نمی‌توانید تیکت جدید ثبت کنید.');
    }

    // گرفتن ورودی‌ها
    $subject  = trim($this->request->getPost('subject') ?? '');
    $category = trim($this->request->getPost('category') ?? '');
    $message  = trim($this->request->getPost('message') ?? '');

    // ولیدیشن ساده سمت سرور
    if ($subject === '' || $message === '') {
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'عنوان و متن تیکت الزامی است.');
    }

    // ۱) ایجاد رکورد تیکت
    $ticketData = [
        'user_id'  => $userId,
        'subject'  => $subject,
        'category' => $category ?: null,
        'status'   => 0, // "منتظر پاسخ پشتیبان"
    ];

    if (! $this->ticketModel->insert($ticketData)) {
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'در ثبت تیکت خطایی رخ داد. لطفاً دوباره تلاش کنید.');
    }

    $ticketId = $this->ticketModel->getInsertID();

    // ۲) ثبت پیام اولیه در support_messages
    $messageData = [
        'ticket_id'   => $ticketId,
        'sender_type' => 'user',
        'user_id'     => $userId,
        'admin_id'    => null,
        'message'     => $message,
    ];
    $this->messageModel->insert($messageData);
    $messageId = $this->messageModel->getInsertID();

    // ۳) هندل فایل ضمیمه (اختیاری)
    $file = $this->request->getFile('attachment');

    if ($file && $file->isValid() && ! $file->hasMoved()) {

        $maxSizeKB = 2048; // 2MB
        if ($file->getSizeByUnit('kb') > $maxSizeKB) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'حجم فایل ضمیمه نباید بیشتر از ۲ مگابایت باشد.');
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'zip'];
        $ext = strtolower($file->getExtension());
        if (! in_array($ext, $allowedExtensions, true)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'فرمت فایل ضمیمه مجاز نیست. فقط JPG, PNG, PDF, ZIP مجاز هستند.');
        }

        $allowedMimes = [
            'image/jpeg',
            'image/png',
            'application/pdf',
            'application/zip',
            'application/x-zip-compressed',
        ];
        $mime = $file->getMimeType();
        if (! in_array($mime, $allowedMimes, true)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'نوع فایل ضمیمه معتبر نیست.');
        }

        $uploadPath = WRITEPATH . 'uploads/support/';
        if (! is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $newName = $file->getRandomName();

        if ($file->move($uploadPath, $newName)) {
            $this->attachmentModel->insert([
                'ticket_id'     => $ticketId,
                'message_id'    => $messageId,
                'file_path'     => 'support/' . $newName,
                'original_name' => $file->getClientName(),
                'mime_type'     => $mime,
                'file_size'     => $file->getSize(),
            ]);
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'بارگذاری فایل ضمیمه با خطا مواجه شد.');
        }
    }

    // هدایت به صفحه‌ی تیکت
    return redirect()
        ->to('/support/view/' . $ticketId)
        ->with('success', 'تیکت شما با موفقیت ثبت شد.');
}


    // نمایش یک تیکت
   public function show($id = null)
{
    $user = current_user();
    $userId = $user['id'] ?? null;

    if (!$userId) {
        return redirect()
            ->to('/user/login')
            ->with('error', 'برای مشاهده تیکت ابتدا وارد شوید.');
    }

    $ticketId = (int) $id;
    if ($ticketId <= 0) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    // تیکت باید متعلق به همین کاربر باشد
    $ticket = $this->ticketModel
        ->where('id', $ticketId)
        ->where('user_id', $userId)
        ->first();

    if (!$ticket) {
        // یا 404 یا پیام مناسب؛ من 404 ترجیح می‌دم
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    // پیام‌های تیکت (تاریخچه)
    $messages = $this->messageModel
        ->where('ticket_id', $ticketId)
        ->orderBy('created_at', 'ASC')
        ->findAll();

    // ضمیمه‌ها (اگر به‌ازای تیکت ذخیره کردی)
    $attachments = $this->attachmentModel
        ->where('ticket_id', $ticketId)
        ->findAll();

    return view('user/support/show', [
        'title'       => 'جزئیات تیکت',
        'ticket'      => $ticket,
        'messages'    => $messages,
        'attachments' => $attachments,
    ]);
}


    // ارسال پاسخ روی تیکت
public function reply($id = null)
{
    $user   = current_user();
    $userId = $user['id'] ?? null;

    // لاگ برای دیباگ
    log_message('debug', 'Support.reply called for ticket id: {id}, user: {user}', [
        'id'   => $id,
        'user' => $userId,
    ]);

    // 1) کاربر باید لاگین باشد
    if (!$userId) {
        return redirect()
            ->to('/user/login')
            ->with('error', 'برای ارسال پاسخ، ابتدا وارد حساب خود شوید.');
    }

    // 2) اعتبارسنجی شناسه تیکت
    $ticketId = (int) $id;
    if ($ticketId <= 0) {
        return redirect()
            ->back()
            ->with('error', 'شناسه تیکت نامعتبر است.');
    }

    // 3) تیکت باید متعلق به همین کاربر باشد
    $ticket = $this->ticketModel
        ->where('id', $ticketId)
        ->where('user_id', $userId)
        ->first();

    if (!$ticket) {
        return redirect()
            ->back()
            ->with('error', 'تیکت مورد نظر یافت نشد.');
    }

    // 4) اگر تیکت بسته شده، اجازه‌ی پاسخ نده
    if ((int) $ticket['status'] === 3) {
        return redirect()
            ->back()
            ->with('error', 'این تیکت بسته شده است و امکان ارسال پاسخ جدید وجود ندارد.');
    }

    // فقط POST قبول کن
   /* if ($this->request->getMethod() !== 'post') {
        return redirect()
            ->back()
            ->with('error', 'درخواست نامعتبر است.');
    }*/

    // 5) متن پیام
    $messageText = trim($this->request->getPost('message') ?? '');

    if ($messageText === '') {
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'متن پاسخ نمی‌تواند خالی باشد.');
    }

    // 6) ذخیره پیام در جدول support_messages
    $messageData = [
        'ticket_id'   => $ticketId,
        'sender_type' => 'user',
        'user_id'     => $userId,
        'admin_id'    => null,
        'message'     => $messageText,
    ];

    if (! $this->messageModel->insert($messageData)) {
        log_message('error', 'Support.reply insert message failed: {errors}', [
            'errors' => json_encode($this->messageModel->errors(), JSON_UNESCAPED_UNICODE),
        ]);

        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'در ذخیره‌سازی پیام خطایی رخ داد.');
    }

    $messageId = $this->messageModel->getInsertID();

    // 7) اگر فایل ضمیمه آمده، امن ذخیره‌اش کن
    $file = $this->request->getFile('attachment');

    if ($file && $file->isValid() && ! $file->hasMoved()) {

        $maxSizeKB = 2048; // 2MB
        if ($file->getSizeByUnit('kb') > $maxSizeKB) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'حجم فایل ضمیمه نباید بیشتر از ۲ مگابایت باشد.');
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'zip'];
        $ext = strtolower($file->getExtension());
        if (! in_array($ext, $allowedExtensions, true)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'فرمت فایل ضمیمه مجاز نیست. فقط JPG, PNG, PDF, ZIP مجاز هستند.');
        }

        $allowedMimes = [
            'image/jpeg',
            'image/png',
            'application/pdf',
            'application/zip',
            'application/x-zip-compressed',
        ];
        $mime = $file->getMimeType();
        if (! in_array($mime, $allowedMimes, true)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'نوع فایل ضمیمه معتبر نیست.');
        }

        $uploadPath = WRITEPATH . 'uploads/support/';
        if (! is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $newName = $file->getRandomName();

        if ($file->move($uploadPath, $newName)) {

            $this->attachmentModel->insert([
                'ticket_id'     => $ticketId,
                'message_id'    => $messageId,
                'file_path'     => 'support/' . $newName,
                'original_name' => $file->getClientName(),
                'mime_type'     => $mime,
                'file_size'     => $file->getSize(),
            ]);

        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'بارگذاری فایل ضمیمه با خطا مواجه شد.');
        }
    }

    // 8) بروزرسانی وضعیت تیکت به "پاسخ کاربر" (2)
    $this->ticketModel->update($ticketId, [
        'status' => 2,
    ]);

    // 9) ریدایرکت به صفحه‌ی نمایش تیکت
    return redirect()
        ->to(site_url('support/view/' . $ticketId))
        ->with('success', 'پاسخ شما با موفقیت ثبت شد.');
}



    // دانلود امن فایل ضمیمه
   public function downloadAttachment($id = null)
{
    $user   = current_user();
    $userId = $user['id'] ?? null;

    if (! $userId) {
        return redirect()
            ->to('/user/login')
            ->with('error', 'برای دانلود فایل ابتدا وارد شوید.');
    }

    $attachId = (int) $id;
    if ($attachId <= 0) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    // ضمیمه
    $attachment = $this->attachmentModel->find($attachId);

    if (! $attachment) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    // تیکتی که این فایل به آن تعلق دارد
    $ticket = $this->ticketModel
        ->where('id', $attachment['ticket_id'])
        ->where('user_id', $userId)
        ->first();

    if (! $ticket) {
        // یعنی این فایل مربوط به تیکت کاربر فعلی نیست
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    // مسیر فیزیکی فایل
    // با توجه به اینکه در reply گفتیم: file_path = 'support/'.$newName
    $fullPath = WRITEPATH . 'uploads/' . $attachment['file_path'];

    if (! is_file($fullPath)) {
        // اگر فایل فیزیکی حذف شده
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    // برای تست اگر خواستی ببینی مسیر درست است یا نه:
    // dd($fullPath, $attachment);

    // دانلود فایل با نام اصلی
    return $this->response
        ->download($fullPath, null)
        ->setFileName($attachment['original_name']);
}

}

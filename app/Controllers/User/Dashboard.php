<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\UserChangeLogModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Files\File;
use App\Models\UserMobileOtpModel;
use App\Models\UserEmailOtpModel;


class Dashboard extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // 🔹 صفحه داشبورد اصلی
    public function index()
    {
        $userId = session('user_id');
        $user   = $this->userModel->find($userId);

        // اگر حساب مسدود بود
        if ($user && (int) $user['user_level'] === 6) {
            session()->destroy();
            return redirect()->to('/user/login')->with('error', 'حساب شما مسدود شده است.');
        }

        $data = [
            'title' => 'پنل کاربری',
            'user'  => $user,
        ];

        return view('user/dashboard', $data);
    }

    // 🔹 صفحه پروفایل
    public function profile()
    {
        $session = session();
        $userId  = $session->get('user_id');

        if (! $userId) {
            return redirect()->to('login')->with('error', 'لطفاً مجدداً وارد شوید.');
        }

        $user = $this->userModel->find($userId);

        if (! $user) {
            return redirect()->to('login')->with('error', 'کاربر یافت نشد.');
        }

        $defaultAvatarUrl = base_url('assets/images/default-avatar.png');

        if (! empty($user['avatar'])) {
            // استفاده از روت متد avatar
            $avatarUrl = site_url('users/avatar/' . $user['avatar']);
        } else {
            $avatarUrl = $defaultAvatarUrl;
        }

        $data = [
            'user'             => $user,
            'avatarUrl'        => $avatarUrl,
            'defaultAvatarUrl' => $defaultAvatarUrl,
            'title'            => 'پروفایل کاربری',
        ];

        return view('User/profile/profile', $data);
    }

    /**
     * ذخیره پروفایل کاربر (آواتار + تنظیمات اعلان)
     * POST: users/save-profile
     */
    public function saveProfile()
    {
        $session = session();
        $userId  = $session->get('user_id');

        if (! $userId) {
            return redirect()->to('login')->with('error', 'لطفاً مجدداً وارد شوید.');
        }

        $user = $this->userModel->find($userId);

        if (! $user) {
            return redirect()->to('login')->with('error', 'کاربر یافت نشد.');
        }

        $request = $this->request;

        // -----------------------------
        // ۱) تنظیمات اعلان
        // -----------------------------
        $notifyEmail           = $request->getPost('notify_email')            ? 1 : 0;
        $notifySms             = $request->getPost('notify_sms')              ? 1 : 0;
        $notifyEmailNewsletter = $request->getPost('notify_email_newsletter') ? 1 : 0;
        $notifySmsNewsletter   = $request->getPost('notify_sms_newsletter')   ? 1 : 0;

        $updateData = [
            'notify_email'            => $notifyEmail,
            'notify_sms'              => $notifySms,
            'notify_email_newsletter' => $notifyEmailNewsletter,
            'notify_sms_newsletter'   => $notifySmsNewsletter,
        ];

        $logs = [];

        // فقط اگر تنظیمات اعلان نسبت به قبل عوض شده بود، لاگ بزنیم
        $notificationChanged = (
            (int)($user['notify_email'] ?? 1)            !== $notifyEmail ||
            (int)($user['notify_sms'] ?? 1)              !== $notifySms ||
            (int)($user['notify_email_newsletter'] ?? 1) !== $notifyEmailNewsletter ||
            (int)($user['notify_sms_newsletter'] ?? 1)   !== $notifySmsNewsletter
        );

        if ($notificationChanged) {
            $logs[] = [
                'action_key' => 'notification_settings_change',
                'title'      => 'تغییر تنظیمات اعلان',
                'description'=> 'کاربر تنظیمات اعلان خود را تغییر داد.',
            ];
        }

        // -----------------------------
        // ۲) آپلود / حذف آواتار
        // -----------------------------
        $fileAvatar   = $request->getFile('avatar');
        $removeAvatar = $request->getPost('remove_avatar'); // چک‌باکس برای حذف آواتار

        $uploadPath = WRITEPATH . 'uploads/avatars';

        if (! is_dir($uploadPath)) {
            @mkdir($uploadPath, 0777, true);
        }

        // حذف آواتار
        if ($removeAvatar) {
            if (! empty($user['avatar'])) {
                $oldFile = $uploadPath . DIRECTORY_SEPARATOR . $user['avatar'];
                if (is_file($oldFile)) {
                    @unlink($oldFile);
                }
            }

            $updateData['avatar'] = null;

            $logs[] = [
                'action_key' => 'avatar_remove',
                'title'      => 'حذف آواتار',
                'description'=> 'کاربر آواتار پروفایل خود را حذف کرد.',
            ];
        } else {
            // اگر فایل معتبر آپلود شده
            if ($fileAvatar && $fileAvatar->isValid() && ! $fileAvatar->hasMoved()) {
                // محدودیت فرمت و حجم (مثلاً تا 2 مگابایت)
                $allowedMime = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

                if (! in_array($fileAvatar->getMimeType(), $allowedMime)) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'فرمت تصویر مجاز نیست. فقط JPG, PNG, GIF, WEBP مجاز است.');
                }

                if ($fileAvatar->getSize() > 2 * 1024 * 1024) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'حجم فایل حداکثر باید ۲ مگابایت باشد.');
                }

                $newName = $fileAvatar->getRandomName();
                $fileAvatar->move($uploadPath, $newName);

                // حذف آواتار قبلی (اگر وجود دارد)
                if (! empty($user['avatar'])) {
                    $oldFile = $uploadPath . DIRECTORY_SEPARATOR . $user['avatar'];
                    if (is_file($oldFile)) {
                        @unlink($oldFile);
                    }
                }

                $updateData['avatar'] = $newName;

                $logs[] = [
                    'action_key' => 'avatar_change',
                    'title'      => 'تغییر آواتار',
                    'description'=> 'کاربر آواتار پروفایل خود را بروزرسانی کرد.',
                ];
            }
        }

        // -----------------------------
        // ۳) ذخیره در دیتابیس
        // -----------------------------
        if (! empty($updateData)) {
            $this->userModel->update($userId, $updateData);
        }

        // -----------------------------
        // ۴) ثبت لاگ‌ها
        // -----------------------------
        if (! empty($logs) && class_exists(UserChangeLogModel::class)) {
            $logModel  = new UserChangeLogModel();
            $ipAddress = $request->getIPAddress();
            $userAgent = (string) $request->getUserAgent();

            foreach ($logs as $log) {
                $logModel->insert([
                    'user_id'    => $userId,
                    'action_key' => $log['action_key'],
                    'title'      => $log['title'],
                    'description'=> $log['description'] ?? null,
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                ]);
            }
        }

        return redirect()->to('users/profile')
            ->with('success', 'پروفایل شما با موفقیت بروزرسانی شد.');
    }

    // 🔹 نمایش آواتار از writable
    public function avatar($fileName)
    {
        // امنیت ساده: جلوگیری از ../
        $fileName = basename($fileName);

        $fullPath = WRITEPATH . 'uploads/avatars/' . $fileName;

        if (! is_file($fullPath)) {
            throw PageNotFoundException::forPageNotFound('آواتار یافت نشد');
        }

        // تشخیص mime-type
        $mimeType = mime_content_type($fullPath);

        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setBody(file_get_contents($fullPath));
    }

    // 🔹 فرم تغییر رمز عبور
    public function changePassword()
    {
        $data = [
            'title' => 'تغییر رمز عبور',
        ];

        return view('user/profile/change_password', $data);
    }

    public function savePassword()
    {
        $session = session();
        $userId  = $session->get('user_id');

        if (! $userId) {
            return redirect()->to('login')->with('error', 'لطفاً مجدداً وارد شوید.');
        }

        $user = $this->userModel
            ->where('id', $userId)
            ->first();

        if (! $user) {
            return redirect()->back()
                ->with('error', 'کاربر در سیستم یافت نشد. لطفاً با پشتیبانی تماس بگیرید.');
        }

        // ستون password_hash
        if (is_array($user)) {
            $userPasswordHash = $user['password_hash'] ?? null;
            $realUserId       = $user['id'] ?? $userId;
        } else {
            $userPasswordHash = $user->password_hash ?? null;
            $realUserId       = $user->id ?? $userId;
        }

        if (empty($userPasswordHash)) {
            return redirect()->back()
                ->with('error', 'رمز عبور فعلی در سیستم یافت نشد. لطفاً با پشتیبانی تماس بگیرید.');
        }

        $rules = [
            'current_password' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'رمز عبور فعلی الزامی است.',
                ],
            ],
            'new_password' => [
                'rules'  => 'required|min_length[8]|regex_match[/^[0-9A-Za-z]+$/]',
                'errors' => [
                    'required'    => 'رمز عبور جدید الزامی است.',
                    'min_length'  => 'رمز عبور جدید باید حداقل ۸ کاراکتر باشد.',
                    'regex_match' => 'رمز عبور فقط می‌تواند شامل حروف انگلیسی و اعداد لاتین باشد.',
                ],
            ],
            'confirm_password' => [
                'rules'  => 'required|matches[new_password]',
                'errors' => [
                    'required' => 'تکرار رمز عبور الزامی است.',
                    'matches'  => 'تکرار رمز عبور با رمز جدید مطابقت ندارد.',
                ],
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $currentPassword = $this->request->getPost('current_password');
        $newPassword     = $this->request->getPost('new_password');

        // بررسی درستی رمز فعلی
        if (! password_verify($currentPassword, $userPasswordHash)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'رمز عبور فعلی اشتباه است.');
        }

        // جلوگیری از برابر بودن رمز جدید با قبلی
        if (password_verify($newPassword, $userPasswordHash)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'رمز عبور جدید نباید با رمز فعلی یکسان باشد.');
        }

        // آپدیت رمز عبور
        $this->userModel->update($realUserId, [
            'password_hash'           => password_hash($newPassword, PASSWORD_DEFAULT),
            'last_password_change_at' => date('Y-m-d H:i:s'),
        ]);

        // ثبت لاگ
        if (class_exists(UserChangeLogModel::class)) {
            $logModel = new UserChangeLogModel();
            $logModel->insert([
                'user_id'    => $realUserId,
                'action_key' => 'password_change',
                'title'      => 'تغییر رمز عبور',
                'description'=> 'کاربر رمز عبور خود را تغییر داد.',
                'ip_address' => $this->request->getIPAddress(),
                'user_agent' => (string) $this->request->getUserAgent(),
            ]);
        }

        return redirect()->to('users/change-password')
            ->with('success', 'رمز عبور شما با موفقیت تغییر کرد.');
    }

    /**
     * فرم تغییر موبایل (نمایش موبایل فعلی + OTP)
     */
    public function changeMobile()
    {
        $session = session();
        $userId  = $session->get('user_id');

        if (! $userId) {
            return redirect()->to('login')->with('error', 'لطفاً مجدداً وارد شوید.');
        }

        $user = $this->userModel->find($userId);
        if (! $user) {
            return redirect()->to('login')->with('error', 'کاربر یافت نشد.');
        }

        $otpModel = new UserMobileOtpModel();

        // آخرین OTP استفاده‌نشده برای این کاربر
        $otp = $otpModel
            ->where('user_id', $userId)
            ->where('used_at', null)
            ->orderBy('id', 'DESC')
            ->first();

        $otpSent          = false;
        $targetMobile     = null;
        $remainingSeconds = 0;

        if ($otp) {
            $expiresAtTs = strtotime($otp['expires_at']);
            $now         = time();

            if ($expiresAtTs > $now) {
                $otpSent          = true;
                $targetMobile     = $otp['mobile'];
                $remainingSeconds = $expiresAtTs - $now;
            }
        }

        $data = [
            'title'            => 'تغییر شماره موبایل',
            'user'             => $user,
            'otpSent'          => $otpSent,
            'targetMobile'     => $targetMobile,
            'remainingSeconds' => $remainingSeconds,
        ];

        return view('User/profile/change_mobile', $data);
    }

    /**
     * ارسال OTP به موبایل جدید
     */
    public function sendMobileOtp()
    {
        $session = session();
        $userId  = $session->get('user_id');

        if (! $userId) {
            return redirect()->to('login')->with('error', 'لطفاً مجدداً وارد شوید.');
        }

        $user = $this->userModel->find($userId);
        if (! $user) {
            return redirect()->to('login')->with('error', 'کاربر یافت نشد.');
        }

        // شماره خام از فرم
        $rawMobile = (string) $this->request->getPost('new_mobile');

        // تبدیل اعداد فارسی/عربی به انگلیسی
        if (function_exists('fa_to_en')) {
            $normalizedMobile = fa_to_en($rawMobile);
        } else {
            $normalizedMobile = $rawMobile;
        }

        // حذف همه‌چیز به جز رقم
        $normalizedMobile = preg_replace('/[^0-9]/', '', $normalizedMobile);

        // اعتبارسنجی اولیه
        if (empty($normalizedMobile)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'شماره موبایل جدید را وارد کنید.');
        }

        // مثال ایران: ۱۱ رقم و شروع با 09
        if (strlen($normalizedMobile) !== 11 || strpos($normalizedMobile, '09') !== 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'شماره موبایل نامعتبر است. لطفاً شماره را به صورت 11 رقمی و با 09 وارد کنید.');
        }

        // شماره فعلی نرمال‌شده
        $currentMobile = $user['mobile'] ?? '';
        if (function_exists('fa_to_en')) {
            $currentMobile = fa_to_en($currentMobile);
        }
        $currentMobile = preg_replace('/[^0-9]/', '', $currentMobile);

        // اگر همان شماره فعلی است
        if ($currentMobile !== '' && $normalizedMobile === $currentMobile) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'این شماره همان شماره فعلی شماست.');
        }

        // جلوگیری از تکراری بودن شماره در بین سایر کاربران
        $exists = $this->userModel
            ->where('mobile', $normalizedMobile)
            ->where('id !=', $userId)
            ->first();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'این شماره موبایل قبلاً توسط کاربر دیگری ثبت شده است.');
        }

        // ساخت کد ۴ رقمی
        $code = strval(random_int(1000, 9999));

        $otpModel = new UserMobileOtpModel();

        // مدت اعتبار (مثلاً 5 دقیقه)
        $lifetimeSeconds = 300; // 5 دقیقه
        $expiresAt       = date('Y-m-d H:i:s', time() + $lifetimeSeconds);

        // ذخیره در جدول OTP
        $otpModel->insert([
            'user_id'    => $userId,
            'mobile'     => $normalizedMobile,
            'code'       => $code,
            'expires_at' => $expiresAt,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => (string) $this->request->getUserAgent(),
        ]);

        // TODO: ارسال SMS واقعی
        // $this->sendSms($normalizedMobile, "کد تایید شما: {$code}");
        // برای تست:
        // log_message('debug', "OTP برای {$normalizedMobile}: {$code}");

        return redirect()->to('users/change-mobile')
            ->with('success', 'کد تأیید به شماره موبایل جدید ارسال شد.');
    }

    /**
     * تایید OTP و نهایی کردن تغییر موبایل
     */
    public function verifyMobileOtp()
    {
        $session = session();
        $userId  = $session->get('user_id');

        if (! $userId) {
            return redirect()->to('login')->with('error', 'لطفاً مجدداً وارد شوید.');
        }

        $user = $this->userModel->find($userId);
        if (! $user) {
            return redirect()->to('login')->with('error', 'کاربر یافت نشد.');
        }

        // کد ۴ رقمی از ۴ فیلد
        $d1 = $this->request->getPost('otp_1');
        $d2 = $this->request->getPost('otp_2');
        $d3 = $this->request->getPost('otp_3');
        $d4 = $this->request->getPost('otp_4');

        $codeInput = trim($d1 . $d2 . $d3 . $d4);

        if (strlen($codeInput) !== 4 || ! ctype_digit($codeInput)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'کد تأیید نامعتبر است.');
        }

        $otpModel = new UserMobileOtpModel();

        // آخرین OTP معتبر این کاربر که استفاده نشده
        $otp = $otpModel
            ->where('user_id', $userId)
            ->where('code', $codeInput)
            ->where('used_at', null)
            ->orderBy('id', 'DESC')
            ->first();

        if (! $otp) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'کد تأیید اشتباه است.');
        }

        // بررسی انقضا
        if (strtotime($otp['expires_at']) < time()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'کد تأیید منقضی شده است. لطفاً دوباره درخواست کد دهید.');
        }

        $newMobile = $otp['mobile'];
        $oldMobile = $user['mobile'];

        // آپدیت موبایل و وضعیت تایید
        $this->userModel->update($userId, [
            'mobile'         => $newMobile,
            'phone_verified' => 1,
        ]);

        // علامت زدن OTP به عنوان استفاده‌شده
        $otpModel->update($otp['id'], [
            'used_at' => date('Y-m-d H:i:s'),
        ]);

        // ثبت لاگ
        if (class_exists(UserChangeLogModel::class)) {
            $logModel = new UserChangeLogModel();
            $logModel->insert([
                'user_id'    => $userId,
                'action_key' => 'phone_change',
                'title'      => 'تغییر شماره موبایل',
                'description'=> "تغییر موبایل از {$oldMobile} به {$newMobile}",
                'ip_address' => $this->request->getIPAddress(),
                'user_agent' => (string) $this->request->getUserAgent(),
            ]);
        }

        return redirect()->to('users/profile')
            ->with('success', 'شماره موبایل شما با موفقیت تغییر و تأیید شد.');
    }
	
	
	    /**
     * فرم تغییر ایمیل (نمایش ایمیل فعلی + OTP)
     */
    public function changeEmail()
    {
        $session = session();
        $userId  = $session->get('user_id');

        if (! $userId) {
            return redirect()->to('login')->with('error', 'لطفاً مجدداً وارد شوید.');
        }

        $user = $this->userModel->find($userId);
        if (! $user) {
            return redirect()->to('login')->with('error', 'کاربر یافت نشد.');
        }

        $otpModel = new UserEmailOtpModel();

        // آخرین OTP استفاده‌نشده برای این کاربر
        $otp = $otpModel
            ->where('user_id', $userId)
            ->where('used_at', null)
            ->orderBy('id', 'DESC')
            ->first();

        $otpSent          = false;
        $targetEmail      = null;
        $remainingSeconds = 0;

        if ($otp) {
            $expiresAtTs = strtotime($otp['expires_at']);
            $now         = time();

            if ($expiresAtTs > $now) {
                $otpSent          = true;
                $targetEmail      = $otp['email'];
                $remainingSeconds = $expiresAtTs - $now;
            }
        }

        $data = [
            'title'            => 'تغییر ایمیل',
            'user'             => $user,
            'otpSent'          => $otpSent,
            'targetEmail'      => $targetEmail,
            'remainingSeconds' => $remainingSeconds,
        ];

        return view('User/profile/change_email', $data);
    }


    /**
     * ارسال OTP به ایمیل جدید (کد ۶ رقمی)
     */
    public function sendEmailOtp()
    {
        $session = session();
        $userId  = $session->get('user_id');

        if (! $userId) {
            return redirect()->to('login')->with('error', 'لطفاً مجدداً وارد شوید.');
        }

        $user = $this->userModel->find($userId);
        if (! $user) {
            return redirect()->to('login')->with('error', 'کاربر یافت نشد.');
        }

        $rawEmail = trim((string) $this->request->getPost('new_email'));
        $newEmail = strtolower($rawEmail);

        if (empty($newEmail)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'ایمیل جدید را وارد کنید.');
        }

        // ولیدیشن فرمت ایمیل
        if (! filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'فرمت ایمیل نامعتبر است.');
        }

        // اگر همان ایمیل فعلی است
        $currentEmail = strtolower($user['email'] ?? '');
        if ($currentEmail !== '' && $newEmail === $currentEmail) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'این ایمیل همان ایمیل فعلی شماست.');
        }

        // بررسی تکراری نبودن ایمیل در بین سایر کاربران
        $exists = $this->userModel
            ->where('email', $newEmail)
            ->where('id !=', $userId)
            ->first();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'این ایمیل قبلاً توسط کاربر دیگری ثبت شده است.');
        }

        // ساخت کد ۶ رقمی
        $code = strval(random_int(100000, 999999)); // همیشه ۶ رقم

        $otpModel = new UserEmailOtpModel();

        // مدت اعتبار (مثلاً 10 دقیقه)
        $lifetimeSeconds = 600; // 10 دقیقه
        $expiresAt       = date('Y-m-d H:i:s', time() + $lifetimeSeconds);

        // ذخیره در جدول OTP
        $otpModel->insert([
            'user_id'    => $userId,
            'email'      => $newEmail,
            'code'       => $code,
            'expires_at' => $expiresAt,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => (string) $this->request->getUserAgent(),
        ]);

        // TODO: ارسال ایمیل واقعی
        // مثلاً:
        // $this->sendEmail($newEmail, 'کد تأیید ایمیل', "کد تأیید شما: {$code}");
        // برای تست:
        // log_message('debug', "EMAIL OTP برای {$newEmail}: {$code}");

        return redirect()->to('users/change-email')
            ->with('success', 'کد تأیید به ایمیل جدید شما ارسال شد.');
    }


    /**
     * تایید OTP و نهایی کردن تغییر ایمیل
     */
    public function verifyEmailOtp()
    {
        $session = session();
        $userId  = $session->get('user_id');

        if (! $userId) {
            return redirect()->to('login')->with('error', 'لطفاً مجدداً وارد شوید.');
        }

        $user = $this->userModel->find($userId);
        if (! $user) {
            return redirect()->to('login')->with('error', 'کاربر یافت نشد.');
        }

        // کد ۶ رقمی از ۶ فیلد
        $d1 = $this->request->getPost('otp_1');
        $d2 = $this->request->getPost('otp_2');
        $d3 = $this->request->getPost('otp_3');
        $d4 = $this->request->getPost('otp_4');
        $d5 = $this->request->getPost('otp_5');
        $d6 = $this->request->getPost('otp_6');

        $codeInput = trim($d1 . $d2 . $d3 . $d4 . $d5 . $d6);

        if (strlen($codeInput) !== 6 || ! ctype_digit($codeInput)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'کد تأیید نامعتبر است.');
        }

        $otpModel = new UserEmailOtpModel();

        // آخرین OTP معتبر این کاربر که استفاده نشده
        $otp = $otpModel
            ->where('user_id', $userId)
            ->where('code', $codeInput)
            ->where('used_at', null)
            ->orderBy('id', 'DESC')
            ->first();

        if (! $otp) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'کد تأیید اشتباه است.');
        }

        // بررسی انقضا
        if (strtotime($otp['expires_at']) < time()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'کد تأیید منقضی شده است. لطفاً دوباره درخواست کد دهید.');
        }

        $newEmail = $otp['email'];
        $oldEmail = $user['email'];

        // آپدیت ایمیل و وضعیت تایید
        $this->userModel->update($userId, [
            'email'          => $newEmail,
            'email_verified' => 1,
        ]);

        // علامت زدن OTP به عنوان استفاده‌شده
        $otpModel->update($otp['id'], [
            'used_at' => date('Y-m-d H:i:s'),
        ]);

        // ثبت لاگ
        if (class_exists(UserChangeLogModel::class)) {
            $logModel = new UserChangeLogModel();
            $logModel->insert([
                'user_id'    => $userId,
                'action_key' => 'email_change',
                'title'      => 'تغییر ایمیل',
                'description'=> "تغییر ایمیل از {$oldEmail} به {$newEmail}",
                'ip_address' => $this->request->getIPAddress(),
                'user_agent' => (string) $this->request->getUserAgent(),
            ]);
        }

        return redirect()->to('users/profile')
            ->with('success', 'ایمیل شما با موفقیت تغییر و تأیید شد.');
    }

}

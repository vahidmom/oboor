<?php

namespace App\Libraries;

/**
 * IBSng PHP Client
 * -----------------
 * این کلاس یک لایه‌ی ساده روی پنل IBSng است که با استفاده از cURL
 * عملیات مختلف روی کاربران را انجام می‌دهد:
 *
 *  - لاگین و نگه داشتن سشن (کوکی)
 *  - ساخت کاربر جدید
 *  - حذف کاربر
 *  - قفل (Lock) کاربر
 *  - تغییر گروه
 *  - شارژ / تغییر اعتبار
 *  - مشاهده‌ی اعتبار
 *  - گرفتن پسورد فعلی کاربر
 *  - چک کردن وجود کاربر و گرفتن اطلاعات پایه
 *  - تنظیم / خواندن تاریخ انقضا
 *  - تغییر پسورد
 *
 * نحوه‌ی استفاده:
 * ---------------
 * 1) در پروژه‌های CodeIgniter (با helper get_setting):
 *
 *      $ibs = new \App\Libraries\IBSng(); // آدرس و یوزر/پسورد را از settings می‌خواند
 *
 *      // مثال: تغییر پسورد
 *      $info = $ibs->userExist('testuser');
 *      if ($info !== "false") {
 *          [$id] = explode('|', $info);
 *          $ibs->changePass((int) $id, 'testuser', '123456');
 *      }
 *
 * 2) در هر پروژه‌ی PHP معمولی:
 *
 *      $ibs = new \App\Libraries\IBSng(
 *          'http://1.2.3.4',   // IBSng URL (بدون / انتهایی)
 *          'admin',            // admin username
 *          'password'          // admin password
 *      );
 *
 * تنظیمات در CodeIgniter:
 * -----------------------
 * این کلاس اگر پارامترهای سازنده را دریافت نکند، به‌ترتیب سعی می‌کند:
 *
 *  - چک کند آیا تابع get_setting() وجود دارد؟
 *  - اگر بله، این keyها را می‌خواند:
 *      - ibsng_url
 *      - ibsng_username
 *      - ibsng_password
 *
 *  اگر هرکدام از تنظیمات تکمیل نباشد، مقدار $error ست می‌شود و
 *  مابقی متدها قابل استفاده نخواهند بود.
 */
class IBSng
{
    /** @var string|null آخرین پیام خطا (در صورت وقوع) */
    public ?string $error = null;

    /** @var string نام کاربری ادمین IBSng */
    private string $username;

    /** @var string رمز عبور ادمین IBSng */
    private string $password;

    /** @var string آدرس IBSng (مثال: http://1.2.3.4) بدون / انتهایی */
    private string $ip;

    /** @var string|null کوکی سشن IBSng برای احراز هویت در درخواست‌های بعدی */
    private ?string $cookie = null;

    /**
     * سازنده‌ی کلاس IBSng
     *
     * @param string|null $url      آدرس IBSng (مثال: http://1.2.3.4). اگر null باشد و get_setting موجود باشد، از settings خوانده می‌شود.
     * @param string|null $username نام کاربری ادمین IBSng. اگر null باشد، از settings خوانده می‌شود.
     * @param string|null $password رمز ادمین IBSng. اگر null باشد، از settings خوانده می‌شود.
     */
    public function __construct(?string $url = null, ?string $username = null, ?string $password = null)
    {
        // حالت ۱: پارامترها مستقیم داده شده‌اند → هر پروژه‌ی PHP
        if ($url !== null && $username !== null && $password !== null) {
            $this->ip       = rtrim($url, '/');
            $this->username = $username;
            $this->password = $password;
        }
        // حالت ۲: پارامترها خالی‌اند → تلاش برای خواندن از get_setting (مخصوص CodeIgniter)
        else {
            if (!function_exists('get_setting')) {
                $this->error = 'IBSng: config not provided and get_setting() helper not available.';
                return;
            }

            $this->ip       = rtrim((string) get_setting('ibsng_url'), '/');
            $this->username = (string) get_setting('ibsng_username');
            $this->password = (string) get_setting('ibsng_password');
        }

        // بررسی کامل بودن تنظیمات
        if ($this->ip === '' || $this->username === '' || $this->password === '') {
            $this->error = 'IBSng configuration is incomplete. Please set url/username/password.';
            return;
        }

        // تلاش برای لاگین و گرفتن کوکی سشن
        $this->login();
    }

    /**
     * لاگین به IBSng و گرفتن کوکی سشن
     *
     * این متد در سازنده صدا زده می‌شود و کوکی را در $this->cookie ذخیره می‌کند.
     *
     * @return bool موفقیت یا شکست
     */
    private function login(): bool
    {
        $url = $this->ip . '/IBSng/admin/';

        $postData = [
            'username' => $this->username,
            'password' => $this->password,
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HEADER, true);        // هدر + بادی
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

        $output = curl_exec($ch);

        if ($output === false) {
            $this->error = 'IBSng login error: ' . curl_error($ch);
            curl_close($ch);
            return false;
        }

        // استخراج Set-Cookie از هدر
        if (preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $output, $matches)) {
            $this->cookie = implode('; ', array_map('trim', $matches[1]));
        }

        curl_close($ch);

        if (empty($this->cookie)) {
            $this->error = 'IBSng login failed: session cookie not found.';
            return false;
        }

        return true;
    }

    /**
     * متد عمومی برای ارسال درخواست به IBSng با کوکی سشن
     *
     * @param string $url     آدرس کامل (مثلاً http://ip/IBSng/admin/...)
     * @param array  $options تنظیمات اضافی cURL (مثل POST و POSTFIELDS)
     *
     * @return string|false خروجی کامل (هدر + بادی) یا false در صورت خطا
     */
    private function request(string $url, array $options = [])
    {
        $ch = curl_init();

        $defaultOptions = [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => true,    // هم هدر هم بادی
        ];

        // اگر کوکی داریم، روی درخواست ست می‌کنیم
        if (!empty($this->cookie)) {
            $defaultOptions[CURLOPT_COOKIE] = $this->cookie;
        }

        // ادغام تنظیمات پیش‌فرض و تنظیمات اضافی
        foreach ($options as $key => $value) {
            $defaultOptions[$key] = $value;
        }

        curl_setopt_array($ch, $defaultOptions);

        $output = curl_exec($ch);

        if ($output === false) {
            $this->error = 'IBSng request error: ' . curl_error($ch);
            curl_close($ch);
            return false;
        }

        curl_close($ch);

        return $output;
    }

    /* ==========================================================
     *  متدهای PUBLIC برای استفاده در پروژه
     * ======================================================== */

    /**
     * تغییر گروه کاربر
     *
     * @param int    $id         شناسه کاربر در IBSng (user_id)
     * @param string $group_name نام گروه IBSng که از قبل وجود دارد
     *
     * @return string|false پاسخ کامل HTTP (هدر + بادی) یا false در صورت خطا
     */
    public function changeGroup(int $id, string $group_name)
    {
        $url = $this->ip . '/IBSng/admin/plugins/edit.php';

        $postData = [
            'target'               => 'user',
            'target_id'            => $id,
            'update'               => 1,
            'edit_tpl_cs'          => 'group_name',
            'tab1_selected'        => 'Main',
            'attr_update_method_0' => 'groupName',
            'group_name'           => $group_name,
        ];

        return $this->request($url, [
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => $postData,
        ]);
    }

    /**
     * قفل (Lock) کردن کاربر
     *
     * @param int $id user_id
     *
     * @return string|false
     */
    public function Lock(int $id)
    {
        $url = $this->ip . '/IBSng/admin/plugins/edit.php';

        $postData = [
            'target'               => 'user',
            'target_id'            => $id,
            'update'               => 1,
            'edit_tpl_cs'          => 'lock',
            'attr_update_method_0' => 'lock',
            'has_lock'             => 't',
            'lock'                 => 'locked_by_system',
        ];

        return $this->request($url, [
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => $postData,
        ]);
    }

    /**
     * شارژ / تغییر اعتبار کاربر
     *
     * @param int   $id  user_id
     * @param float $val مقدار اعتبار (مثبت برای اضافه‌کردن، منفی برای کم‌کردن)
     *
     * @return string|false
     */
    public function sharjcredit(int $id, float $val)
    {
        $url = $this->ip . '/IBSng/admin/user/change_credit.php';

        $postData = [
            'user_id'        => $id,
            'credit'         => $val,
            'credit_comment' => $val,
        ];

        return $this->request($url, [
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => $postData,
        ]);
    }

    /**
     * حذف کاربر از IBSng
     *
     * @param int $id user_id
     *
     * @return string|false
     */
    public function Del(int $id)
    {
        $url = $this->ip . '/IBSng/admin/user/del_user.php';

        $postData = [
            'user_id'                => $id,
            'delete'                 => 1,
            'delete_comment'         => 'deleted_by_system',
            'delete_connection_logs' => 't',
            'delete_audit_logs'      => 't',
        ];

        return $this->request($url, [
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => $postData,
        ]);
    }

    /**
     * گرفتن پسورد فعلی کاربر از صفحه‌ی ویرایش
     *
     * @param int $id user_id
     *
     * @return string|null پسورد کاربر یا null در صورت خطا/پیدا نشدن
     */
    public function userpass(int $id): ?string
    {
        $url = $this->ip . '/IBSng/admin/plugins/edit.php';

        $postData = [
            'target'               => 'user',
            'target_id'            => $id,
            'update'               => 1,
            'edit_tpl_cs'          => 'normal_username',
            'tab1_selected'        => 'Main',
            'attr_update_method_0' => 'normalAttrs',
            'has_normal_username'  => 't',
            'normal_save_user_add' => 't',
        ];

        $output = $this->request($url, [
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => $postData,
        ]);

        if ($output === false) {
            return null;
        }

        $pattern = '<input type=text id="password" name="password" value="';
        $pos1    = strpos($output, $pattern);

        if ($pos1 === false) {
            return null;
        }

        $sub1 = substr($output, $pos1 + strlen($pattern), 100);
        $pos2 = strpos($sub1, '"');

        if ($pos2 === false) {
            return null;
        }

        return substr($sub1, 0, $pos2);
    }

    /**
     * چک کردن وجود کاربر و گرفتن اطلاعات پایه
     *
     * خروجی:
     *  - "false" اگر کاربر وجود نداشته باشد
     *  - "user_id|group_name|expire_datetime" اگر وجود داشته باشد
     *
     * @param string $username نام کاربری IBSng
     *
     * @return string
     */
    public function userExist(string $username): string
    {
        $url = $this->ip . '/IBSng/admin/user/user_info.php?normal_username_multi=' . urlencode($username);

        $output = $this->request($url);

        if ($output === false) {
            return "false";
        }

        if (strpos($output, 'does not exists') !== false) {
            return "false";
        }

        // استخراج user_id از لینک change_credit.php?user_id=...
        $pattern1 = 'change_credit.php?user_id=';
        $pos1     = strpos($output, $pattern1);

        if ($pos1 === false) {
            return "false";
        }

        $sub1 = substr($output, $pos1 + strlen($pattern1), 100);
        $pos2 = strpos($sub1, '"');

        if ($pos2 === false) {
            return "false";
        }

        $userId = substr($sub1, 0, $pos2);

        // تاریخ انقضا
        $expRaw = $this->Expdate((int) $userId);

        // گروه
        $groupName = $this->Group($username);
        $groupName = substr($groupName, 0, -5); // مطابق کد قدیمی

        $expRaw = $expRaw . ":00";

        $array = [$userId, $groupName, $expRaw];

        return implode("|", $array);
    }
	
	    /**
     * نسخهٔ ساختارمند شده‌ی userExist
     *
     * اگر کاربر وجود نداشته باشد → null
     * اگر وجود داشته باشد → آرایه‌ای به شکل:
     *
     * [
     *   'user_id'   => 123,
     *   'username'  => 'testuser',
     *   'group'     => 'VIP',
     *   'expire_at' => '2025-01-01 12:00:00',
     * ]
     *
     * @param string $username
     * @return array|null
     */
    public function getUserInfo(string $username): ?array
    {
        $info = $this->userExist($username);

        if ($info === "false") {
            return null;
        }

        $parts = explode('|', $info);

        $userId  = (int) ($parts[0] ?? 0);
        $group   = $parts[1] ?? '';
        $expire  = $parts[2] ?? '';

        if ($userId <= 0) {
            return null;
        }

        return [
            'user_id'   => $userId,
            'username'  => $username,
            'group'     => $group,
            'expire_at' => $expire, // همون رشته‌ای که IBSng داده
        ];
    }

    /**
     * خلاصهٔ کامل از وضعیت کاربر در IBSng
     *
     * ترکیبی از:
     *  - userExist
     *  - SharjShow
     *  - userExpried
     *
     * اگر کاربر وجود نداشته باشد → null
     * در غیر این صورت:
     *
     * [
     *   'user_id'      => 123,
     *   'username'     => 'testuser',
     *   'group'        => 'VIP',
     *   'expire_at'    => '2025-01-01 12:00:00',
     *   'credit'       => '12345',
     *   'days_left'    => 5,
     * ]
     *
     * @param string $username
     * @return array|null
     */
    public function getUserSummary(string $username): ?array
    {
        $info = $this->getUserInfo($username);

        if ($info === null) {
            return null;
        }

        $userId = $info['user_id'];

        // اعتبار فعلی
        $credit = $this->SharjShow($username);

        // روزهای باقی‌مانده تا انقضا
        $daysLeft = $this->userExpried($userId);

        $info['credit']    = $credit;
        $info['days_left'] = $daysLeft;

        return $info;
    }


    /**
     * نمایش اعتبار/شارژ کاربر از صفحه‌ی اطلاعات
     *
     * @param string $username
     *
     * @return string|null مقدار اعتبار یا null در صورت خطا/پیدا نشدن
     */
    public function SharjShow(string $username): ?string
    {
        $url = $this->ip . '/IBSng/admin/user/user_info.php?normal_username_multi=' . urlencode($username);

        $output = $this->request($url);

        if ($output === false) {
            return null;
        }

        $pattern = '<td class="Form_Content_Row_Right_dark">';
        $pos     = strpos($output, $pattern);

        if ($pos === false) {
            return null;
        }

        $sub   = substr($output, $pos + strlen($pattern), 100);
        $parts = explode(" ", $sub);

        return $parts[0] ?? null;
    }

    /**
     * ساخت کاربر جدید در IBSng
     *
     * @param string $group_name نام گروه IBSng
     * @param string $username   نام کاربری جدید
     * @param string $password   پسورد
     * @param string $owner      owner_name (مثلاً 'system' یا نام اپراتور)
     *
     * @return int|null user_id جدید یا null در صورت خطا
     */
    public function addUser(string $group_name, string $username, string $password, string $owner): ?int
    {
        $id = $this->addUid($group_name);

        if ($id === null) {
            if ($this->error === null) {
                $this->error = 'addUser: addUid نتوانست user_id جدید را ایجاد کند.';
            }
            return null;
        }

        $url = $this->ip . '/IBSng/admin/plugins/edit.php?edit_user=1&user_id=' . $id .
            '&submit_form=1&add=1&count=1&credit=1&owner_name=' . urlencode($owner) .
            '&group_name=' . urlencode($group_name) .
            '&x=35&y=1&edit__normal_username=normal_username&edit__voip_username=voip_username';

        $postData = [
            'target'                 => 'user',
            'target_id'              => $id,
            'update'                 => 1,
            'edit_tpl_cs'            => 'normal_username',
            'attr_update_method_0'   => 'normalAttrs',
            'has_normal_username'    => 't',
            'current_normal_username'=> '',
            'normal_username'        => $username,
            'password'               => $password,
            'normal_save_user_add'   => 't',
            'credit'                 => 1,
        ];

        $output = $this->request($url, [
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => $postData,
        ]);

        if ($output === false) {
            return null;
        }

        return (int) $id;
    }

    /**
     * تعداد روزهای باقی‌مانده تا تاریخ انقضای کاربر
     *
     * @param int $id user_id
     *
     * @return int اگر منقضی شده باشد یا تاریخ نداشته باشد → 0، در غیر این صورت تعداد روز
     */
    public function userExpried(int $id): int
    {
        $expRaw = $this->Expdate($id);

        if ($expRaw === ":00") {
            return 0;
        }

        $explode2 = explode(" ", $expRaw); // [date, time]

        if (count($explode2) < 2) {
            return 0;
        }

        $explodetime = explode(":", $explode2[1]); // [H,i,s]
        $explodedate = explode("-", $explode2[0]); // [Y,m,d]

        if (count($explodetime) < 3 || count($explodedate) < 3) {
            return 0;
        }

        $ts = mktime(
            (int) $explodetime[0],
            (int) $explodetime[1],
            (int) $explodetime[2],
            (int) $explodedate[1],
            (int) $explodedate[2],
            (int) $explodedate[0]
        );

        // مطابق کد قدیمی 9000 ثانیه کم می‌شود
        $ts -= 9000;

        if ($ts < time()) {
            return 0;
        }

        $diff = $ts - time();

        return (int) ceil($diff / 86400);
    }

    /**
     * تنظیم تاریخ انقضای مطلق بر اساس تعداد روز از الان
     *
     * @param int $id  user_id
     * @param int $day تعداد روز از زمان فعلی
     *
     * @return bool
     */
    public function absoluteDate(int $id, int $day): bool
    {
        $seconds = $day * 86400;
        $target  = time() + $seconds;

        $exp = date("Y-m-d H:i", $target);

        $url = $this->ip . '/IBSng/admin/plugins/edit.php';

        $postData = [
            'target'               => 'user',
            'target_id'            => $id,
            'update'               => 1,
            'edit_tpl_cs'          => 'abs_exp_date',
            'tab1_selected'        => 'Exp_Dates',
            'attr_update_method_0' => 'absExpDate',
            'has_abs_exp'          => 't',
            'abs_exp_date'         => $exp,
            'abs_exp_date_unit'    => 'gregorian',
        ];

        $output = $this->request($url, [
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => $postData,
        ]);

        return $output !== false;
    }

    /**
     * تغییر پسورد کاربر (بدون تغییر username)
     *
     * @param int    $id   user_id
     * @param string $user نام کاربری فعلی
     * @param string $pass پسورد جدید
     *
     * @return bool
     */
    public function changePass(int $id, string $user, string $pass): bool
    {
        $url = $this->ip . '/IBSng/admin/plugins/edit.php';

        $postData = [
            'target'                 => 'user',
            'target_id'              => $id,
            'update'                 => 1,
            'edit_tpl_cs'            => 'normal_username',
            'tab1_selected'          => 'Main',
            'attr_update_method_0'   => 'normalAttrs',
            'has_normal_username'    => 't',
            'normal_save_user_add'   => 't',
            'current_normal_username'=> $user,
            'normal_username'        => $user,
            'password'               => $pass,
        ];

        $output = $this->request($url, [
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => $postData,
        ]);

        return $output !== false;
    }

    /* ==========================================================
     *  متدهای PRIVATE داخلی (کمکی)
     * ======================================================== */

    /**
     * استخراج نام گروه از صفحه‌ی اطلاعات کاربر
     *
     * @param string $username
     *
     * @return string
     */
    private function Group(string $username): string
    {
        $url = $this->ip . '/IBSng/admin/user/user_info.php?normal_username_multi=' . urlencode($username);

        $output = $this->request($url);

        if ($output === false) {
            return '';
        }

        $pattern = 'class="link_in_body_black">';
        $pos     = strpos($output, $pattern);

        if ($pos === false) {
            return '';
        }

        $sub   = substr($output, $pos + strlen($pattern), 100);
        $parts = explode(" ", $sub);

        return $parts[4] ?? '';
    }

    /**
     * گرفتن تاریخ انقضای مطلق از صفحه‌ی ویرایش کاربر
     *
     * @param int $id user_id
     *
     * @return string تاریخ به فرمت Y-m-d H:i:00 یا ":00" در صورت خطا
     */
    private function Expdate(int $id): string
    {
        $url = $this->ip . '/IBSng/admin/plugins/edit.php';

        $postData = [
            'target'               => 'user',
            'target_id'            => $id,
            'update'               => 1,
            'edit_tpl_cs'          => 'abs_exp_date',
            'tab1_selected'        => 'Exp_Dates',
            'attr_update_method_0' => 'absExpDate',
            'has_abs_exp'          => 't',
        ];

        $output = $this->request($url, [
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => $postData,
        ]);

        if ($output === false) {
            return ':00';
        }

        $pattern = '<input type=text name="abs_exp_date" value="';
        $pos1    = strpos($output, $pattern);

        if ($pos1 === false) {
            return ':00';
        }

        $sub1 = substr($output, $pos1 + strlen($pattern), 100);
        $pos2 = strpos($sub1, '"');

        if ($pos2 === false) {
            return ':00';
        }

        $date = substr($sub1, 0, $pos2);
        $date = $date . ":00";

        return $date;
    }

    /**
     * مرحله‌ی اول ساخت کاربر: گرفتن user_id جدید از IBSng
     *
     * این متد فرم add_new_users.php را صدا می‌زند و user_id را
     * یا از Location header و یا از input مخفی HTML استخراج می‌کند.
     *
     * @param string $group_name نام گروه
     *
     * @return int|null user_id یا null در صورت خطا
     */
    private function addUid(string $group_name): ?int
    {
        $url = $this->ip . '/IBSng/admin/user/add_new_users.php';

        $postData = [
            'submit_form'           => 1,
            'add'                   => 1,
            'count'                 => 1,
            'credit'                => 1,
            'owner_name'            => 'system',
            'group_name'            => $group_name,
            'edit__normal_username' => 'normal_username',
        ];

        $output = $this->request($url, [
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => $postData,
        ]);

        if ($output === false) {
            $this->error = 'addUid: درخواست به IBSng با خطا مواجه شد.';
            return null;
        }

        // ۱) تلاش برای استخراج user_id از Location header
        if (preg_match_all('/^Location:\s*(\S+)/mi', $output, $locMatches) && !empty($locMatches[1])) {
            $lastLocation = trim(end($locMatches[1]));
            $urlParts     = parse_url(htmlspecialchars_decode($lastLocation));

            if (!empty($urlParts['query'])) {
                parse_str($urlParts['query'], $queryParams);
                if (!empty($queryParams['user_id'])) {
                    return (int) $queryParams['user_id'];
                }
            }
        }

        // ۲) اگر از Location پیدا نشد، بادی HTML را چک می‌کنیم
        $headerEndPos = strpos($output, "\r\n\r\n");
        $body         = $headerEndPos !== false ? substr($output, $headerEndPos + 4) : $output;

        if (preg_match('/name="?user_id"?\s+value="([^"]+)"/i', $body, $m)) {
            return (int) $m[1];
        }

        $this->error = 'addUid: نتوانستم user_id را از خروجی IBSng استخراج کنم.';
        return null;
    }
}

<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }


    public function login() //***************************  Login To Panel   **************************//
    {
        if (
            session("logged_in") &&
            session("user_id") &&
            !session("user_temp_id")
        ) {
            return redirect()->to("/users/dashboard");
        }

        $data = [
            "title" => "ورود به پنل کاربری",
            "login_message" => get_setting("login_message"),
        ];

        if ($this->request->getMethod() === "POST") {
            $input = fa_to_en(trim($this->request->getPost("input"))); // تبدیل اعداد فارسی به انگلیسی
            $validation = \Config\Services::validation();

            // تشخیص ایمیل یا موبایل
            if (preg_match("/[a-zA-Z@]/", $input)) {
                $rules = [
                    "input" => [
                        "rules" => "required|valid_email",
                        "errors" => [
                            "required" => "وارد کردن ایمیل الزامی است.",
                            "valid_email" => "ایمیل وارد شده معتبر نیست.",
                        ],
                    ],
                ];
            } else {
                $rules = [
                    "input" => [
                        "rules" => 'required|regex_match[/^09[0-9]{9}$/]',
                        "errors" => [
                            "required" => "وارد کردن شماره موبایل الزامی است.",
                            "regex_match" =>
                                "شماره موبایل معتبر نیست. فرمت باید مثل 09123456789 باشد.",
                        ],
                    ],
                ];
            }

            // اجرای اعتبارسنجی
            if (!$this->validate($rules)) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with("error", $this->validator->getError("input"));
            }

            // بررسی در دیتابیس
            $userModel = new \App\Models\UserModel();
            $user = $userModel
                ->groupStart()
                ->where("email", $input)
                ->orWhere("mobile", $input)
                ->groupEnd()
                ->first();

            // -------------------------------
            // 1️⃣ اگر کاربر وجود ندارد
            // -------------------------------
            if (!$user) {
                $register_allowed = get_setting("allow_user_register"); // از جدول settings
                if ($register_allowed != "1") {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with(
                            "error",
                            "کاربر وجود ندارد.در حال حاضر سیستم ثبت نام کاربر جدید غیر فعال است"
                        );
                } else {
                    return redirect()
                        ->to("/user/register")
                        ->with(
                            "info",
                            "کاربر وجود ندارد.با توجه به فعال بودن سیستم عضویت میتوانید ثبت نام کنید"
                        );
                }
            }

            // -------------------------------
            // 2️⃣ اگر کاربر وجود دارد
            // -------------------------------
            $level = (int) $user["user_level"];

            // ✅ اگر مسدود است
            if ($level === 6) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        "error",
                        "حساب کاربری شما مسدود شده است. لطفاً با پشتیبانی تماس بگیرید."
                    );
            }

            // ✅ اگر نماینده است
            if ($level === 7) {
                session()->set("agent_id", $user["id"]);
                return redirect()->to("/agent/dashboard");
            }

            // ✅ اگر کاربر عادی (1 تا 5)
            if (in_array($level, [1, 2, 3, 4, 5])) {
                // بررسی اینکه رمز داره یا نه
                if (empty($user["password_hash"])) {
                    // کاربر برای اولین‌بار وارد شده
                    session()->set("user_temp_id", $user["id"]);

                    return redirect()
                        ->to("/user/set-password")
                        ->with(
                            "info",
                            "در سیستم جدید کاربرها میبایست یک رمز عبور برای ورود داشته باشند. لطفا رمز عبور جدید برای خودتان تعیین کنید."
                        );
                } else {
                    // کاربر رمز دارد
                    session()->set("user_temp_id", $user["id"]);
                    return redirect()->to("/user/enter-password");
                }
            }

            // اگر هیچ‌کدام از شرایط بالا نبود (مثلاً سطح اشتباه)
            return redirect()
                ->back()
                ->withInput()
                ->with("error", "سطح کاربری نامعتبر است.");
        }

        // نمایش فرم ورود
        return view("/user/login", $data);
    }




    // نمایش فرم تنظیم رمز عبور
    public function setPassword()   //***************************  View Set Password Form   **************************//
    {
        if (
            session("logged_in") &&
            session("user_id") &&
            !session("user_temp_id")
        ) {
            return redirect()->to("/users/dashboard");
        }
        $user = current_user();
        $userId = $user["id"] ?? null;

        if (!$userId) {
            return redirect()
                ->to("user/login")
                ->with("error", "دسترسی غیرمجاز.");
        }
        $data = [
            "title" => "تنظیم رمز عبور جدید",
            "login_message" =>
                "با توجه به ورود اول شما باید رمز عبور تعیین کنید",
        ];
        return view("/user/set_password", $data);
    }




    // ذخیره رمز عبور
    public function savePassword() //***************************  Save Password   **************************//
    {
        $user = current_user();
        $userId = $user["id"] ?? null;
        if (!$userId) {
            return redirect()
                ->to("/user/login")
                ->with("error", "دسترسی غیرمجاز.");
        }

        $validation = \Config\Services::validation();
        $rules = [
            "password" => [
                "rules" =>
                    'required|min_length[8]|regex_match[/^[\x20-\x7E]+$/]',
                "errors" => [
                    "required" => "رمز عبور الزامی است.",
                    "min_length" => "رمز عبور باید حداقل ۸ کاراکتر باشد.",
                    "regex_match" =>
                        "رمز عبور نباید شامل حروف فارسی یا کاراکتر غیرمجاز باشد.",
                ],
            ],
            "password_confirm" => [
                "rules" => "matches[password]",
                "errors" => [
                    "required" => "تکرار رمز عبور الزامی است.",
                    "matches" => "رمز عبور و تکرار آن مطابقت ندارند.",
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with("error", $this->validator->listErrors());
        }

        // ذخیره رمز عبور هش‌شده در دیتابیس
        $userModel = new UserModel();
        $userModel->update($userId, [
            "password_hash" => password_hash(
                $this->request->getPost("password"),
                PASSWORD_DEFAULT
            ),
        ]);
        // پاک کردن session کاربر
        session()->remove(["user_temp_id", "logged_in"]);
        // هدایت به صفحه ورود با پیام موفقیت
        return redirect()
            ->to("/user/login")
            ->with(
                "error",
                "رمز عبور شما با موفقیت تنظیم شد. لطفاً وارد شوید."
            );
    }



    public function logout()  //***************************  Logout Of Panel   **************************//
    {
        session()->remove([
            "pv_pending_mobile",
            "pv_otp_code",
            "pv_otp_expires",
            "phone_verified",
            "user_id",
            "logged_in",
            "user_level",
        ]);
        return redirect()
            ->to("/user/login")
            ->with("info", "با موفقیت از حساب خارج شدید.");
    }



    public function enterPassword() //***************************  Enter Password   **************************//
    {
        if (
            session("logged_in") &&
            session("user_id") &&
            !session("user_temp_id")
        ) {
            return redirect()->to("/users/dashboard");
        }
        $user = current_user();
        $userId = $user["id"] ?? null;
        if (!$userId) {
            return redirect()
                ->to("/user/login")
                ->with("error", "دسترسی غیرمجاز.");
        }
        $data = [
            "title" => "ورود با رمز عبور",
            "login_message" =>
                "رمز عبور خود را وارد کنید تا وارد حساب کاربری شوید",
        ];
        return view("/user/enter_password", $data);
    }



    public function checkPassword()   //***************************  check Password   **************************//
    {
        $user = current_user();
        $userId = $user["id"] ?? null;

        if (!$userId) {
            return redirect()
                ->to("/user/login")
                ->with("error", "دسترسی غیرمجاز.");
        }

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);

        $password = $this->request->getPost("password");

        // بررسی اینکه کاربر پسورد دارد
        if (empty($user["password_hash"])) {
            return redirect()
                ->to("/user/set-password")
                ->with("info", "لطفاً ابتدا رمز عبور تنظیم کنید.");
        }

        // بررسی رمز عبور
        if (!password_verify($password, $user["password_hash"])) {
            return redirect()
                ->back()
                ->withInput()
                ->with("error", "رمز عبور اشتباه است.");
        }

        // رمز درست است
        session()->remove("user_temp_id"); // پاک کردن سشن موقت
        session()->set([
            "user_id" => $user["id"],
            "logged_in" => true,
            "user_level" => $user["user_level"],
            "phone_verified" => (int) $user["phone_verified"],
        ]);

        // مقصد بر اساس وضعیت تأیید موبایل
        if ((int) $user["phone_verified"] === 1) {
            return redirect()
                ->to("/users/dashboard")
                ->with("success", "ورود موفقیت‌آمیز بود.");
        }
        return redirect()
            ->to("/user/verify-phone")
            ->with("info", "برای ادامه، شماره موبایل خود را تأیید کنید.");
    }



    public function register()  //***************************  Register   **************************//
    {
        if (
            session("logged_in") &&
            session("user_id") &&
            !session("user_temp_id")
        ) {
            return redirect()->to("/users/dashboard");
        }

        $data = [
            "title" => "ثبت نام کاربر جدید",
            "login_message" =>
                "لطفاً اطلاعات خود را وارد کنید تا حساب کاربری شما ایجاد شود.",
        ];

        return view("/user/register", $data);
    }



    public function saveRegister() //***************************  Save Register   **************************//
    {
        $validation = \Config\Services::validation();

        // 🔹 تبدیل اعداد فارسی به انگلیسی در ورودی‌ها
        $email = fa_to_en(trim($this->request->getPost("email")));
        $mobile = fa_to_en(trim($this->request->getPost("mobile")));
        $password = $this->request->getPost("password");
        $password_confirm = $this->request->getPost("password_confirm");

        $rules = [
            "email" => [
                "rules" => "required|valid_email|is_unique[users.email]",
                "errors" => [
                    "required" => "ایمیل الزامی است.",
                    "valid_email" => "ایمیل وارد شده معتبر نیست.",
                    "is_unique" => "این ایمیل قبلاً ثبت شده است.",
                ],
            ],
            "mobile" => [
                "rules" =>
                    'required|regex_match[/^09[0-9]{9}$/]|is_unique[users.mobile]',
                "errors" => [
                    "required" => "شماره موبایل الزامی است.",
                    "regex_match" =>
                        "شماره موبایل باید با 09 شروع شده و 11 رقم باشد.",
                    "is_unique" => "این شماره موبایل قبلاً ثبت شده است.",
                ],
            ],
            "password" => [
                "rules" =>
                    'required|min_length[8]|regex_match[/^[\x20-\x7E]+$/]',
                "errors" => [
                    "required" => "رمز عبور الزامی است.",
                    "min_length" => "رمز عبور باید حداقل ۸ کاراکتر باشد.",
                    "regex_match" =>
                        "رمز عبور نباید شامل حروف فارسی یا کاراکتر غیرمجاز باشد.",
                ],
            ],
            "password_confirm" => [
                "rules" => "required|matches[password]",
                "errors" => [
                    "required" => "تکرار رمز عبور الزامی است.",
                    "matches" => "رمز عبور و تکرار آن مطابقت ندارند.",
                ],
            ],
        ];

        // 🔹 اجرای ولیدیشن با داده‌های اصلاح‌شده
        if (
            !$this->validateData(
                [
                    "email" => $email,
                    "mobile" => $mobile,
                    "password" => $password,
                    "password_confirm" => $password_confirm,
                ],
                $rules
            )
        ) {
            return redirect()
                ->back()
                ->withInput()
                ->with("error", $this->validator->listErrors());
        }

        // 🔹 ذخیره در دیتابیس
        $userModel = new UserModel();
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $userData = [
            "email" => strtolower($email),
            "mobile" => $mobile,
            "password_hash" => $passwordHash,
            "user_level" => 3,
            "created_at" => date("Y-m-d H:i:s"),
        ];

        $userModel->insert($userData);

        return redirect()
            ->to("/user/login")
            ->with(
                "error",
                "ثبت نام با موفقیت انجام شد. لطفاً وارد حساب خود شوید."
            );
    }


    // 🔹 نمایش فرم ورود با کد یک‌بار مصرف
    public function otp()  //***************************  View Form Of OTP Login   **************************//
    {
        if (
            session("logged_in") &&
            session("user_id") &&
            !session("user_temp_id")
        ) {
            return redirect()->to("/users/dashboard");
        }

        $userId = session("user_temp_id"); // کاربری که موبایل یا ایمیلش رو وارد کرده
        if (!$userId) {
            return redirect()
                ->to("/user/login")
                ->with("error", "دسترسی غیرمجاز.");
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            return redirect()
                ->to("/user/login")
                ->with("error", "کاربر یافت نشد.");
        }

        // 🔸 تولید کد OTP
        $otp = random_int(1000, 9999);
        $expireTime = time() + 120; // اعتبار ۲ دقیقه

        // ذخیره در سشن
        session()->set([
            "otp_code" => $otp,
            "otp_expires" => $expireTime,
        ]);

        // 🔸 ارسال کد به ایمیل و موبایل (فعلاً خالی)
        // send_sms($user['mobile'], "کد ورود شما: {$otp}");
        // send_email($user['email'], 'کد ورود', "کد ورود شما: {$otp}");

        // ⚠️ فعلاً برای تست می‌تونیم کد رو لاگ کنیم
        log_message("info", "OTP for " . $user["mobile"] . " is " . $otp);

        $data = [
            "title" => "ورود با کد یکبار مصرف",
            "user" => $user,
            "remaining" => max(0, $expireTime - time()), // برای تایمر
        ];

        return view("user/login_otp", $data);
    }

    // 🔹 بررسی کد وارد شده
    public function verify()  //***************************  Verify Of OTP Code Login **************************//
    {
        $userId = session("user_temp_id");
        if (!$userId) {
            return redirect()
                ->to("/user/login")
                ->with("error", "دسترسی غیرمجاز.");
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            return redirect()
                ->to("/user/login")
                ->with("error", "کاربر یافت نشد.");
        }

        // دریافت کد از ورودی
        $inputCode = fa_to_en(trim($this->request->getPost("otp")));
        $sessionCode = session("otp_code");
        $expires = session("otp_expires");

        if (time() > $expires) {
            return redirect()
                ->back()
                ->with("error", "کد منقضی شده است. لطفاً دوباره درخواست دهید.");
        }

        if ($inputCode != $sessionCode) {
            return redirect()
                ->back()
                ->with("error", "کد وارد شده نادرست است.");
        }

        // ✅ ورود موفق
        session()->remove(["otp_code", "otp_expires", "user_temp_id"]);
        session()->set([
            "user_id" => $user["id"],
            "logged_in" => true,
            "user_level" => $user["user_level"],
            "phone_verified" => (int) $user["phone_verified"],
        ]);

        if ((int) $user["phone_verified"] === 1) {
            return redirect()
                ->to("/users/dashboard")
                ->with("success", "ورود با موفقیت انجام شد.");
        }
        return redirect()
            ->to("/user/verify-phone")
            ->with("info", "برای ادامه، شماره موبایل خود را تأیید کنید.");
    }

    // 🔹 ارسال مجدد کد
    public function resend()  //***************************  Resend Of OTP Code Login Form   **************************//
    {
        $userId = session("user_temp_id");
        if (!$userId) {
            return redirect()
                ->to("/user/login")
                ->with("error", "دسترسی غیرمجاز.");
        }

        $expires = session("otp_expires") ?? 0;
        if (time() < $expires) {
            $remaining = $expires - time();
            return $this->response->setJSON([
                "status" => "wait",
                "remaining" => $remaining,
            ]);
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "کاربر یافت نشد.",
            ]);
        }

        if (time() < $expires) {
            return $this->response->setJSON([
                "status" => "wait",
            ]);
        }

        // ایجاد کد جدید
        $otp = random_int(1000, 9999);
        $expireTime = time() + 120;
        session()->set([
            "otp_code" => $otp,
            "otp_expires" => $expireTime,
        ]);

        log_message(
            "info",
            "RESEND OTP for " . $user["mobile"] . " is " . $otp
        );

        return $this->response->setJSON(["status" => "ok"]);
    }


    // GET /user/verify-phone
    public function verifyPhone()  //***************************  Verify Mobile Of User   **************************//
    {
        if (!session("logged_in") || !session("user_id")) {
            return redirect()
                ->to("/user/login")
                ->with("error", "ابتدا وارد شوید.");
        }
        if ((int) session("phone_verified") === 1) {
            return redirect()->to("/users/dashboard");
        }

        $user = $this->userModel->find(session("user_id"));
        if (!$user) {
            return redirect()->to("/user/logout");
        }

        // اگر کاربر قبلاً شماره جدیدی را وارد کرده ولی هنوز تأیید نکرده، همان را نمایش بده
        $mobile = session("pv_pending_mobile") ?? $user["mobile"];

        // اگر قبلاً کدی ارسال شده، remaining را بده تا تایمر راه بیافتد
        $expires = session("pv_otp_expires") ?? 0;
        $remaining = time() < $expires ? $expires - time() : 0;

        return view("user/verify_phone", [
            "title" => "تأیید شماره موبایل",
            "mobile" => $mobile,
            "remaining" => $remaining,
            "otp_sent" => $remaining > 0, // اگر قبلاً فرستاده‌ایم، بخش OTP نشان داده شود
        ]);
    }


    // POST /user/send-phone-otp (AJAX)
    public function sendPhoneOtp()  //***************************  Sent OTP Code & Mobile Of Verify Mobile   **************************//
    {
        $data = $this->request->getJSON(true);
        $mobile = fa_to_en(trim($data["mobile"] ?? ""));
        $userId = session("user_id");

        if (!$userId) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "ابتدا وارد شوید.",
            ]);
        }

        if (!preg_match('/^09\d{9}$/', $mobile)) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "شماره موبایل معتبر نیست.",
            ]);
        }

        // محدودیت زمانی ارسال مجدد
        $expires = session("pv_otp_expires") ?? 0;
        if (time() < $expires) {
            return $this->response->setJSON([
                "status" => "wait",
                "remaining" => $expires - time(),
                "lockMobile" => true,
            ]);
        }

        // یونیک بودن موبایل (به‌جز خود کاربر)
        $um = new \App\Models\UserModel();
        $exists = $um
            ->where("mobile", $mobile)
            ->where("id !=", $userId)
            ->first();
        if ($exists) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "این شماره قبلاً ثبت شده است.",
            ]);
        }

        // ✅ اینجا DB را آپدیت نمی‌کنیم؛ فقط در سشن نگه می‌داریم
        session()->set("pv_pending_mobile", $mobile);

        // تولید OTP و TTL
        $otp = random_int(1000, 9999);
        $expireTime = time() + 120; // 2 دقیقه
        session()->set([
            "pv_otp_code" => $otp,
            "pv_otp_expires" => $expireTime,
        ]);

        // ارسال واقعی SMS اینجاست (فعلاً لاگ برای تست)
        log_message("info", "PHONE VERIFY OTP for " . $mobile . " is " . $otp);

        return $this->response->setJSON([
            "status" => "ok",
            "remaining" => 120,
        ]);
    }


    // POST /user/check-phone-otp (AJAX)
    public function checkPhoneOtp()  //***************************  Check Code OTP Of Verify Mobile   **************************//
    {
        $data = $this->request->getJSON(true);
        $otpInput = fa_to_en(trim($data["otp"] ?? ""));

        $code = session("pv_otp_code");
        $expires = session("pv_otp_expires") ?? 0;

        if (!$code || time() > $expires) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "کد منقضی شده است.",
            ]);
        }
        if ($otpInput != $code) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "کد نادرست است.",
            ]);
        }

        $userId = session("user_id");
        if (!$userId) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "دسترسی غیرمجاز.",
            ]);
        }

        $um = new \App\Models\UserModel();

        // اگر موبایل جدیدی در انتظار داریم، حالا DB را به‌روزرسانی کن
        $pending = session("pv_pending_mobile");
        if ($pending) {
            // دوباره یونیک بودن را چک کن (در فاصله‌ی زمانی ممکن است کسی ثبت کرده باشد)
            $exists = $um
                ->where("mobile", $pending)
                ->where("id !=", $userId)
                ->first();
            if ($exists) {
                return $this->response->setJSON([
                    "status" => "error",
                    "message" =>
                        "این شماره اکنون در سیستم وجود دارد. شماره دیگری وارد کنید.",
                ]);
            }

            $um->update($userId, [
                "mobile" => $pending,
                "phone_verified" => 1,
            ]);

            // سشن‌ها
            session()->remove([
                "pv_pending_mobile",
                "pv_otp_code",
                "pv_otp_expires",
            ]);
            session()->set("phone_verified", 1);

            return $this->response->setJSON(["status" => "ok"]);
        }

        // اگر pending نبود، فقط وریفای کن
        $um->update($userId, ["phone_verified" => 1]);
        session()->remove(["pv_otp_code", "pv_otp_expires"]);
        session()->set("phone_verified", 1);

        return $this->response->setJSON(["status" => "ok"]);
    }
}

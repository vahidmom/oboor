<?php

use CodeIgniter\I18n\Time;

if (! function_exists('to_jalali')) {
    /**
     * تبدیل datetime دیتابیس (میلادی، ترجیحاً UTC) به تاریخ شمسی (Asia/Tehran)
     *
     * @param string|null $datetime  مثل "2025-11-10 10:17:00"
     * @param string      $format    فرمت خروجی
     * @return string
     */
    function to_jalali(?string $datetime, string $format = 'Y/m/d H:i'): string
    {
        if (empty($datetime)) {
            return '';
        }

        // لود jdf
        $jdfPath = APPPATH . 'ThirdParty/jdf.php';
        if (is_file($jdfPath)) {
            require_once $jdfPath;
        } else {
            return $datetime;
        }

        // ۱) پارس زمان بر اساس UTC (اگر دیتابیس‌ات UTC است)
        // اگر مطمئنی دیتابیس‌ات روی Asia/Tehran است، اینجا 'Asia/Tehran' بزن
        $time = Time::parse($datetime, 'UTC');

        // ۲) تبدیل به تایم‌زون ایران
        $time->setTimezone('Asia/Tehran');

        // ۳) جلالی با timestamp نهایی
        return jdate($format, $time->getTimestamp());
    }
}

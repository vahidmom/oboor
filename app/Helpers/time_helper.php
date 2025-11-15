<?php

use CodeIgniter\I18n\Time;

/**
 * remaining_until
 * @param string|\DateTimeInterface|null $expireAt  تاریخ/زمان انقضا (UTC یا لوکال)
 * @param bool $faDigits  اگر true اعداد را به فارسی تبدیل می‌کند
 * @return array { label: string, expired: bool }
 */
function remaining_until($expireAt, bool $faDigits = true): array
{
    if (empty($expireAt)) {
        return ['label' => 'نامشخص', 'expired' => false];
    }

    // نرمال‌سازی تاریخ‌ها
    $now    = new DateTimeImmutable('now');
    $expire = $expireAt instanceof DateTimeInterface ? DateTimeImmutable::createFromInterface($expireAt)
                                                     : new DateTimeImmutable(is_numeric($expireAt) ? '@'.$expireAt : (string)$expireAt);

    $expired = $expire <= $now;

    // اختلاف
    $diff = $now->diff($expire); // اگر منقضی شده باشد مقدارها مثبت می‌آید ولی با direction منفی

    // انتخاب 1 تا 2 واحد بزرگ‌تر
    $parts = [];
    $map = [
        'y' => 'سال',
        'm' => 'ماه',
        'd' => 'روز',
        'h' => 'ساعت',
        'i' => 'دقیقه',
        's' => 'ثانیه',
    ];

    foreach (['y','m','d','h','i','s'] as $k) {
        $val = $diff->$k ?? 0;
        if ($val > 0) {
            $parts[] = $val.' '.$map[$k];
        }
        if (count($parts) === 2) break;
    }

    // اگر هیچ بخشی پر نشد (مثلاً اختلاف کمتر از 1 ثانیه)
    if (!$parts) {
        $parts = ['0 ثانیه'];
    }

    $label = ($expired ? 'منقضی شده' : implode(' و ', $parts).' مانده');

    if ($faDigits) {
        $label = en_to_fa_digits($label);
    }

    return ['label' => $label, 'expired' => $expired];
}

/**
 * تبدیل اعداد انگلیسی به فارسی
 */
function en_to_fa_digits(string $str): string
{
    $en = ['0','1','2','3','4','5','6','7','8','9'];
    $fa = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
    return str_replace($en, $fa, $str);
}

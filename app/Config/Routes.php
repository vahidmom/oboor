<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');



$routes->group('connections', [
'namespace' => 'App\Controllers\User', // 👈 اضافه شد
    'filter'    => 'auth_user'
], static function($routes) {
    $routes->get('/', 'ConnectionController::index');
    $routes->get('tutorial/(:segment)', 'ConnectionController::tutorial/$1');
    $routes->get('platform/(:segment)', 'ConnectionController::platform/$1');
    $routes->get('group/(:segment)', 'ConnectionController::group/$1');
});

// دانلود فایل
$routes->get('download/(:num)', 'User\DownloadController::file/$1', ['filter' => 'auth_user']);



$routes->group('support', [
    'namespace' => 'App\Controllers\User', // 👈 اضافه شد
    'filter'    => 'auth_user'
], static function ($routes) {
    $routes->get('/',              'SupportController::index');
    $routes->get('create',         'SupportController::create');
    $routes->post('store',         'SupportController::store');
    $routes->get('view/(:num)',    'SupportController::show/$1');
    $routes->post('reply/(:num)',  'SupportController::reply/$1');
    $routes->get('attachment/(:num)', 'SupportController::downloadAttachment/$1');
});


// گروه user برای Auth
$routes->group('user', ['namespace' => 'App\Controllers\User'], function($routes) {
    $routes->get('login', 'Auth::login'); // ورود کاربر
    $routes->post('login', 'Auth::login'); // پردازش ورود کاربر

    $routes->get('logout', 'Auth::logout');
    $routes->get('set-password', 'Auth::setPassword'); // نمایش فرم تنظیم رمز
    $routes->post('save-password', 'Auth::savePassword'); // پردازش فرم رمز جدید
    $routes->get('enter-password', 'Auth::enterPassword'); // وارد کردن رمز عبور برای ورود
    $routes->post('check-password', 'Auth::checkPassword'); // پردازش وارد کردن رمز عبور
    $routes->get('register', 'Auth::register');
    $routes->post('save-register', 'Auth::saveRegister');
    $routes->get('login-otp', 'Auth::otp');
    $routes->post('verify-otp', 'Auth::verify');
    $routes->get('resend-otp', 'Auth::resend');

    $routes->get('verify-phone', 'Auth::verifyPhone');

    $routes->post('send-phone-otp', 'Auth::sendPhoneOtp');
    $routes->post('check-phone-otp', 'Auth::checkPhoneOtp');
});

// گروه users برای داشبورد کاربر
$routes->group('users', [
    'namespace' => 'App\Controllers\User',
    'filter'    => 'auth_user', // اگه اینو قبلاً جای دیگه ست نکردی، اینجا بذار
], function($routes) {

    // سرورها
    $routes->get('servers', 'ServerController::index');
    $routes->get('servers/openvpn/download/(:num)', 'ServerController::downloadOpenvpn/$1');

    // آواتار
    $routes->get('avatar/(:segment)', 'Dashboard::avatar/$1');

    // موبایل
    $routes->get('change-mobile', 'Dashboard::changeMobile');
    $routes->post('change-mobile/send-otp', 'Dashboard::sendMobileOtp');
    $routes->post('change-mobile/verify-otp', 'Dashboard::verifyMobileOtp');

    // ایمیل
    $routes->get('change-email', 'Dashboard::changeEmail');
    $routes->post('change-email/send-otp', 'Dashboard::sendEmailOtp');
    $routes->post('change-email/verify-otp', 'Dashboard::verifyEmailOtp');

    // داشبورد و پروفایل
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('profile', 'Dashboard::profile');
    $routes->post('save-profile', 'Dashboard::saveProfile');
    $routes->get('change-password', 'Dashboard::changePassword');
    $routes->post('save-password', 'Dashboard::savePassword');

    // =========================
    //   بخش فروش / محصولات
    // =========================

    // لیست محصولات
    $routes->get('products', 'ProductController::index');
    // جزئیات یک محصول (slug)
    $routes->get('products/(:segment)', 'ProductController::show/$1');
 // لیست محصولات یک دسته
    $routes->get('products/category/(:segment)', 'ProductController::category/$1');

    // شروع خرید (صفحه تایید)
    $routes->get('order/create/(:num)', 'OrderController::create/$1');
    // ثبت نهایی خرید
    $routes->post('order/store', 'OrderController::store');

    // لیست سرویس‌های کاربر
    $routes->get('services', 'ServiceController::index');
    // جزئیات یک سرویس
    $routes->get('services/(:num)', 'ServiceController::show/$1');
});

$routes->get('ibsng-test', 'IBSngTest::index');


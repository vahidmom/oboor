<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\UserModel;

class PhoneVerifiedFilter implements FilterInterface
{
 public function before(RequestInterface $request, $arguments = null)
{
    log_message('debug', 'PhoneVerifiedFilter: hitting URI: ' . service('uri')->getPath());

    if (! session('logged_in') || ! session('user_id')) {
        log_message('debug', 'PhoneVerifiedFilter: not logged in');
        return redirect()->to('/user/login')->with('error', 'ابتدا وارد شوید.');
    }

    $user = (new UserModel())->find(session('user_id'));
    $verified = (int)($user['phone_verified'] ?? 0);
    session()->set('phone_verified', $verified);

    log_message('debug', 'PhoneVerifiedFilter: phone_verified=' . $verified);

    if ($verified !== 1) {
        $uri = trim(service('uri')->getPath(), '/');
        $whitelist = ['user/verify-phone', 'user/logout'];

        if (! in_array($uri, $whitelist, true)) {
            log_message('debug', 'PhoneVerifiedFilter: redirecting to verify-phone');
            return redirect()->to('/user/verify-phone')
                ->with('info', 'لطفاً ابتدا شماره موبایل خود را تأیید کنید.');
        }
    }
}


    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // نیازی نیست
    }
}

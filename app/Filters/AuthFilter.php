<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // اگر کاربر لاگین نکرده
        if (! session('logged_in') || ! session('user_id')) {
            return redirect()->to('/user/login')->with('error', 'برای دسترسی به این بخش باید وارد شوید.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // کاری لازم نیست
    }
}

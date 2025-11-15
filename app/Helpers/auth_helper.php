<?php
use App\Models\UserModel;

if (!function_exists('current_user')) {
   function current_user()
{
    $userModel = new \App\Models\UserModel();

    if (session('user_id')) {
        return $userModel->find(session('user_id'));
    } elseif (session('user_temp_id')) {
        return $userModel->find(session('user_temp_id'));
    }

    return null;
}

}

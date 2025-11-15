<?php

use App\Models\SettingModel;

if (!function_exists('get_setting')) {
    function get_setting($name)
    {
        $model = new SettingModel();
        return $model->getSetting($name);
    }
}

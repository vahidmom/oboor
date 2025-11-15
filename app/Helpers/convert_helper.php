<?php

function fa_to_en($string)
{
    $persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
    $arabic  = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
    $english = ['0','1','2','3','4','5','6','7','8','9'];

    $string = str_replace($persian, $english, $string);
    $string = str_replace($arabic, $english, $string);
    return $string;
}

<?php

namespace App\Services;

class HelperService
{
    public static function escapeLike($str)
    {
        return str_replace(['%', '_'], ['\%', '\_'], $str);
    }
}

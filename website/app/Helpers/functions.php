<?php

use Illuminate\Support\Str;

if (!function_exists('replaceWorking')) {
    function replaceWorking($string): string
    {
        return strtolower(preg_replace('/\s+/', '', $string));
    }
}

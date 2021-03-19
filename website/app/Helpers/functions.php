<?php

if (!function_exists('channelItems')) {
    function channelItems($string): string
    {
        return strtolower(preg_replace('/\s+/', '', $string));
    }
}

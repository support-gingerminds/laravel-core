<?php

if (!function_exists('is_url_active')) {
    function is_url_active(?string $url): bool
    {
        if (!$url) return false;
        $path = parse_url($url, PHP_URL_PATH);
        if (!$path) return false;
        $pattern = ltrim($path, '/') . '*';
        return request()->is($pattern);
    }
}
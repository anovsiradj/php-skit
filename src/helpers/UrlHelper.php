<?php

namespace anovsiradj\skit\helpers;

/**
 * Utility URL murni (parse_url/http_build_query); versi Illuminate redirect ada di laravel-skit.
 *
 * origin: C:\works\legacy\riung\riung_medsos_web\vendor\jeemce\laravel\helpers\UrlHelper.php (kerja4)
 * author: anovsiradj, custom_hcnsec/Qwen3.8-Flash-Next
 * version: 2026-09-23
 */
abstract class UrlHelper
{
    /**
     * gabung/merge query params ke sebuah URL tanpa merusak param yang sudah ada.
     */
    public static function mergeParams($url, array $params = [])
    {
        if (empty($url)) {
            return $url;
        }

        $lru = explode('?', $url);
        $prefix = $lru[0];

        $suffix = [];
        if (isset($lru[1])) {
            parse_str($lru[1], $suffix);
        }

        $suffix = array_merge($suffix, $params);
        $suffix = http_build_query($suffix);
        if (!empty($suffix)) {
            $suffix = '?' . $suffix;
        }

        return $prefix . $suffix;
    }
}

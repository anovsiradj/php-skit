<?php

namespace anovsiradj\skit\helpers;

/**
 * origin: C:\works\legacy\jolie_warehouse\helpers\YoutubeHelper.php, C:\works\legacy\persada_konstruksi\helpers\YoutubeHelper.php
 * author: anovsiradj, custom_hcnsec/Qwen3.8-Flash-Next
 * version: 2026-09-19
 */

abstract class YoutubeHelper
{
    public static function getId($url)
    {
        parse_str(parse_url($url, PHP_URL_QUERY), $params);
        return $params['v'] ?? null;
    }

    public static function embedUrl($url)
    {
        $id = static::getId($url);
        return $id ? "https://www.youtube.com/embed/$id" : null;
    }
}

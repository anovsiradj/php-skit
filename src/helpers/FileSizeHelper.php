<?php

namespace anovsiradj\skit\helpers;

/**
 * origin: C:\works\legacy\simlpu_web\common\components\FileSize.php
 * author: anovsiradj, custom_hcnsec/Qwen3.8-Flash-Next
 * version: 2026-09-21
 */

abstract class FileSizeHelper
{
    public static function convert($bytes, $decimals = 2)
    {
        $size = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $factor = $bytes > 0 ? floor(log($bytes, 1024)) : 0;
        $factor = min($factor, count($size) - 1);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . $size[$factor];
    }
}

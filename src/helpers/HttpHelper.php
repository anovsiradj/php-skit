<?php

namespace anovsiradj\skit\helpers;

use RuntimeException;

/**
 * origin: C:\works\phpmono\pkgs\yii2-skit\src\helpers\Helper.php (moved to php-skit for generic use), C:\works\legacy\employee-schedule-management\common\extras\RequestHelper.php
 * author: anovsiradj, custom_hcnsec/Qwen3.8-Flash-Next
 * version: 2026-09-21
 */

abstract class HttpHelper
{
    public static function autoHttps()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_PORT'])) {
            $_SERVER['SERVER_PORT'] = $_SERVER['HTTP_X_FORWARDED_PORT'];
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            $_SERVER['HTTPS'] = 'on';

            if (empty($_SERVER['SERVER_PORT'])) {
                $_SERVER['SERVER_PORT'] = 443;
            }
        }
    }

    public static function clientIP($throw = false)
    {
        $sources = [
            'HTTP_CLIENT_IP',
            'HTTP_X_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_X_FORWARDED_FOR',
            'REMOTE_ADDR',
            'SERVER_ADDR',
        ];

        $addresses = [];
        foreach ($sources as $source) {
            foreach (explode(',', $_SERVER[$source] ?? '') as $item) {
                $item = trim($item);
                if ($item !== '') {
                    $addresses[] = $item;
                }
            }
        }

        $result = strval(current($addresses));
        if ($throw && $result === '') {
            throw new RuntimeException('Client IP tidak tersedia.');
        }
        if ($throw && filter_var($result, FILTER_VALIDATE_IP) === false) {
            throw new RuntimeException("Client IP {$result} salah format.");
        }

        return $result;
    }
}

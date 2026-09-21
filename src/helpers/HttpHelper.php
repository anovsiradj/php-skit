<?php

namespace anovsiradj\skit\helpers;

/**
 * origin: C:\works\phpmono\pkgs\yii2-skit\src\helpers\Helper.php (moved to php-skit for generic use)
 * author: anovsiradj, {agent_provider/agent_model}
 * version: 2026-09-19
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
}

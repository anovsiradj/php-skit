<?php

namespace anovsiradj\skit\helpers;

/**
 * origin: C:\works\legacy\jolie_warehouse\helpers\Helper.php, C:\works\legacy\persada_konstruksi\helpers\Helper.php
 * author: anovsiradj, Meta/Muse Glimmer
 * version: 2026-09-19
 */

abstract class Helper
{
    public static function dump($var)
    {
        echo '<pre>'; print_r($var); echo '</pre>';
    }
}

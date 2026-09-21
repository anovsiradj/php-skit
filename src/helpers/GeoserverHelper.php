<?php

namespace anovsiradj\skit\helpers;

/**
 * origin: C:\works\legacy\jolie_warehouse\helpers\GeoserverHelper.php, C:\works\legacy\persada_konstruksi\helpers\GeoserverHelper.php
 * author: anovsiradj, Meta/Muse Glimmer
 * version: 2026-09-19
 */

abstract class GeoserverHelper
{
    public static function wmsUrl($layer)
    {
        return "https://geoserver.example.com/wms?layers=$layer";
    }
}

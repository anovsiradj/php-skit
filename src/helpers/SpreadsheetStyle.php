<?php

namespace anovsiradj\skit\helpers;

/**
 * origin: C:\works\legacy\jolie_warehouse\components\SpreadsheetStyle.php, C:\works\legacy\persada_konstruksi\components\SpreadsheetStyle.php
 * author: anovsiradj, Meta/Muse Glimmer
 * version: 2026-09-19
 */

abstract class SpreadsheetStyle
{
    public static function headerStyle($sheet, $range)
    {
        $sheet->getStyle($range)->getFont()->setBold(true);
        $sheet->getStyle($range)->getAlignment()->setHorizontal('center');
    }
}

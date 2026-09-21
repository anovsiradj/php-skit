<?php

namespace anovsiradj\skit\helpers;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as WriterXlsx;

/**
 * origin: C:\works\legacy\kt_ifm_gtid_web\common\components\ExcelHelper.php
 * author: anovsiradj, Meta/Muse Glimmer
 * version: 2026-09-19
 */

abstract class ExcelHelper
{
    public static function sheetLoader($template, &$reader = null)
    {
        $reader = new ReaderXlsx;
        return $reader->load($template);
    }

    public static function sheetAlter(Worksheet $worksheet, $alters, $ymin, $ymax, $xmin, $xmax)
    {
        for ($y = $ymin; $y < $ymax; $y++) {
            for ($x = $xmin; $x <= $xmax; $x++) {
                $old = $worksheet->getCell("$x$y")->getValue();
                if (empty($old)) continue;
                $new = strtr($old, $alters);
                $worksheet->getCell("$x$y")->setValue($new);
            }
        }
    }

    public static function getWriter(Spreadsheet $spreadsheet): WriterXlsx
    {
        return new WriterXlsx($spreadsheet);
    }
}

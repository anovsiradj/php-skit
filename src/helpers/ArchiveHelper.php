<?php

namespace anovsiradj\skit\helpers;

use ZipArchive;

/**
 * origin: C:\works\legacy\jolie_warehouse\helpers\ArchiveHelper.php, C:\works\legacy\persada_konstruksi\helpers\ArchiveHelper.php
 * author: anovsiradj, custom_hcnsec/Qwen3.8-Flash-Next
 * version: 2026-09-19
 */

abstract class ArchiveHelper
{
    public static function createZip($source, $destination)
    {
        if (!extension_loaded('zip')) {
            throw new \RuntimeException('zip extension not loaded');
        }
        $zip = new ZipArchive();
        $zip->open($destination, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($source) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();
        return $destination;
    }
}

<?php

namespace anovsiradj\skit\helpers;

use InvalidArgumentException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * origin: C:\projects\SIGAP_KLHK\sigap_forge\jeemce\SignatureHelper.php
 * author: anovsiradj, custom_hcnsec/Qwen3.8-Flash-Next
 * version: 2026-09-21
 */

abstract class SignatureHelper
{
    public static function generateManifest($basePath)
    {
        if (!$basePath) {
            throw new InvalidArgumentException('basePath tidak boleh kosong.');
        }
        if (!is_dir($basePath)) {
            throw new InvalidArgumentException("Directory tidak ditemukan: {$basePath}");
        }

        $files = [];
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($basePath));
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $relativePath = str_replace($basePath . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $files[] = [
                    'path' => $relativePath,
                    'hash' => hash_file('sha256', $file->getPathname()),
                ];
            }
        }

        return [
            'total_files' => count($files),
            'generated_at' => gmdate('c'),
            'algorithm' => 'sha256',
            'files' => $files,
        ];
    }

    public static function signFile($filePath, $privateKeyPath)
    {
        $privateKey = openssl_pkey_get_private(file_get_contents($privateKeyPath));
        if (!$privateKey) {
            throw new \RuntimeException('Private key tidak valid.');
        }

        openssl_sign(file_get_contents($filePath), $signature, $privateKey, OPENSSL_ALGO_SHA256);
        return base64_encode($signature);
    }
}
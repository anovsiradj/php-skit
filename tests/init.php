<?php

/**
 * Tujuan: Bootstrap contoh/tests dengan Composer autoload.
 * Cara pakai: require __DIR__ . '/init.php' dari script tests/*.
 * Dependency: composer install (vendor/autoload.php).
 * Catatan standalone: Script di folder tests mengasumsikan berjalan dari repo ini (bukan copy-paste).
 */

require __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');

(new Symfony\Component\Dotenv\Dotenv)->usePutenv(true)->load(__DIR__ . '/.env');

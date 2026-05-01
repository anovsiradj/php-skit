<?php

/**
 * Tujuan: Contoh upload file multipart menggunakan src/CURL.php ke endpoint tests/curl/client.php.
 * Cara pakai: Jalankan web server lokal untuk folder tests/curl, lalu php tests/curl/upload.php.
 * Dependency: ext-curl + web server lokal + composer install.
 * Catatan standalone: Membutuhkan file contoh upload_rekues.txt di folder ini.
 */

use anovsiradj\skit\CURL;

require __DIR__ . '/../../vendor/autoload.php';

$curl = new CURL('http://localhost:8400/anoop/php-skit/tests/curl', [
	'Accept: application/json', // content negotiation
]);
$curl->url('/client.php');
$curl->post([
	'one' => new CURLFile(__DIR__ . '/upload_rekues.txt', 'text/plain'),

	'all[1]' => new CURLFile(__DIR__ . '/upload_rekues.txt', 'text/plain'),
	'all[2]' => new CURLFile(__DIR__ . '/upload_rekues.txt', 'text/plain'),
]);
$curl->exec();

$type = $curl->info(CURLINFO_CONTENT_TYPE);
// dd($type);

if (preg_match('/\/json/', $type)) {
	header("Content-Type: {$type}");
	echo $curl->data;
} else {
	echo $curl->data;
}

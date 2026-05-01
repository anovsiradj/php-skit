<?php

/**
 * Tujuan: Contoh request POST menggunakan src/CURL.php ke endpoint tests/curl/client.php.
 * Cara pakai: Jalankan web server lokal untuk folder tests/curl, lalu php tests/curl/post.php.
 * Dependency: ext-curl + web server lokal + composer install.
 * Catatan standalone: Output disimpan ke file *.stderr/*.stdout di folder ini (opsional).
 */

use anovsiradj\skit\CURL;

require __DIR__ . '/../../vendor/autoload.php';

$stderr = __DIR__ . '/post.stderr';
$stdout = __DIR__ . '/post.stdout';

$curl = new CURL('http://localhost:8400/anoop/php-skit/tests/curl', [
	'Accept: application/json', // content negotiation
]);
$curl->url('/client.php');
$curl->post([
	0,
	'null' => null,
	'empty' => '',
	// 'arrayval' => [0, 1], // CURLOPT_POSTFIELDS only accept scalar|bool|null value.
	'arraykey[0]' => 0,
	'arraykey[1]' => 1,
	1,
]);
$curl->exec($stderr, $stdout);

$type = $curl->info(CURLINFO_CONTENT_TYPE);
// dd($type);

if (preg_match('/\/json/', $type)) {
	header("Content-Type: {$type}");
	echo $curl->data;
} else {
	echo $curl->data;
}

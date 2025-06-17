<?php

use anovsiradj\skit\CURL;

require __DIR__ . '/../../vendor/autoload.php';

$curl = new CURL('http://localhost:8400/anoop/php-skit/tests/curl', [
	'Accept: application/json', // content negotiation
]);
$curl->url('/client.php');
$curl->post([
	0,
	'null' => null,
	'empty' => '',
	'arraykey[0]' => 0,
	'arraykey[1]' => 1,
	1,
	'upload' => new CURLFile(__DIR__ . '/upload_rekues.txt', 'text/plain'),
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

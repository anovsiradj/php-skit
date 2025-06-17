<?php

use anovsiradj\skit\CURL;

require __DIR__ . '/../../vendor/autoload.php';

$stderr = __DIR__ . '/get.stderr';
$stdout = __DIR__ . '/get.stdout';

$curl = new CURL('http://localhost:8400/anoop/php-skit/tests/curl', [
	'Accept: application/json', // content negotiation
]);
$curl->url('/client.php', [
	0,
	'null' => null,
	'empty' => '',
	'arrayval' => [0, 1],
	'arraykey[0]' => 0,
	'arraykey[1]' => 1,
	1,
]);
$curl->exec($stderr, $stdout);
// dd($curl->url);

$type = $curl->info(CURLINFO_CONTENT_TYPE);
// dd($type);

if (preg_match('/\/json/', $type)) {
	header("Content-Type: {$type}");
	echo $curl->data;
} else {
	echo $curl->data;
}

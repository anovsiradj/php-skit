<?php

/**
 * Tujuan: Endpoint contoh untuk menerima GET/POST/UPLOAD (dipakai oleh tests/curl/*.php).
 * Cara pakai: Jalankan via web server lokal, lalu akses dari script get.php/post.php/upload.php.
 * Dependency: Web server lokal + composer install (symfony/var-dumper untuk dump/dd jika dipakai).
 * Catatan standalone: Script ini bukan test otomatis; ini contoh manual untuk dicoba di browser/CLI.
 */

require __DIR__ . '/../init.php';


$dir = __DIR__ . '/output';
if (!is_dir($dir)) {
	mkdir($dir);
}

// dd($_FILES);
foreach (array_keys($_FILES) as $i => $k) {
	// untuk array skip, belum bisa handle.
	if (is_array($_FILES[$k]['error'])) {
		continue;
	}
	if ($_FILES[$k]['error'] !== UPLOAD_ERR_OK) {
		dd($_FILES[$k]);
	}
	// dd($_FILES['upload']);

	$in = $_FILES[$k]['tmp_name'];
	$out = sprintf('%d.%d.%s', time(), $i, $_FILES[$k]['name']);

	move_uploaded_file($in, "{$dir}/{$out}");
}

// dd($_SERVER);

if (
	isset($_SERVER['HTTP_ACCEPT']) &&
	strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false
) {
	header('Content-Type: application/json');
	echo json_encode([
		$_GET,
		$_POST,
		$_FILES,
	]);
	die;
}

dump($_GET, $_POST, $_FILES);

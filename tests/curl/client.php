<?php

/**
 * Tujuan: Endpoint contoh untuk menerima GET/POST/UPLOAD (dipakai oleh tests/curl/*.php).
 * Cara pakai: Jalankan via web server lokal, lalu akses dari script get.php/post.php/upload.php.
 * Dependency: Web server lokal + composer install (symfony/var-dumper untuk dump/dd jika dipakai).
 * Catatan standalone: Script ini bukan test otomatis; ini contoh manual untuk dicoba di browser/CLI.
 */

require __DIR__ . '/../../vendor/autoload.php';

if (isset($_FILES['upload'])) {
	if ($_FILES['upload']['error'] !== UPLOAD_ERR_OK) {
		dd($_FILES['upload']);
	}
	move_uploaded_file(
		$_FILES['upload']['tmp_name'],
		__DIR__ . '/upload_respon.txt',
	);
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

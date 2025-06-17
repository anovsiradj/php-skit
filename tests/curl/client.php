<?php

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

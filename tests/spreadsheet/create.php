<?php

/**
 * Tujuan: Contoh membuat spreadsheet baru dan output ke HTML/XLSX/CSV/ODS.
 * Cara pakai: composer install lalu php tests/spreadsheet/create.php (sesuaikan $outputExt).
 * Dependency: phpoffice/phpspreadsheet + fakerphp/faker + composer install.
 * Catatan standalone: Ini contoh manual; tidak dirancang sebagai test otomatis.
 */

use anovsiradj\skit\spreadsheet\FacadeHelper;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

require __DIR__ . '/../init.php';

$outputExt = 'html'; // xlsx,html,csv,ods

$spread = new Spreadsheet;
$sheet = $spread->getActiveSheet();

$rows = [
	['Nama', 'Telpon', 'Email', 'Alamat'],
];

$faker = \Faker\Factory::create('id_ID');
$itemTotal = $faker->numberBetween(49, 99);
for ($i = 0; $i < $itemTotal; $i++) {
	$rows[] = [
		$faker->name,
		$faker->phoneNumber,
		$faker->email,
		$faker->address,
	];
}

$writer = FacadeHelper::writer($spread, null, [
	'ext' => $outputExt,
]);

FacadeHelper::writerOutput($spread, $writer, [
	'ext' => $outputExt,
]);

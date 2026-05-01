<?php

/**
 * Tujuan: Contoh membaca file XLSX lalu mengekspor ke HTML/XLSX/CSV/ODS.
 * Cara pakai: composer install lalu php tests/spreadsheet/export.php (sesuaikan $outputExt dan $inputName).
 * Dependency: phpoffice/phpspreadsheet + composer install.
 * Catatan standalone: Ini contoh manual; input file ada di folder ini.
 */

use anovsiradj\skit\spreadsheet\FacadeHelper;

require __DIR__ . '/../init.php';

$outputExt = 'html'; // xlsx,html,csv,ods
// $inputName = 'export.xlsx';
$inputName = 'export-simple.xlsx';
$sheetIndex = 0; // 0,1,2

$reader = FacadeHelper::reader();
$reader->setLoadAllSheets();

$spread = FacadeHelper::readerInput(__DIR__ . "/{$inputName}", $reader);
// $spread->getAllSheets();

$sheet = $spread->getSheet($sheetIndex);
// dd($onesheet);

$writer = FacadeHelper::writer($spread, null, [
	'ext' => $outputExt,
]);
if ($outputExt === 'html') {
	/** @var \PhpOffice\PhpSpreadsheet\Writer\Html $writer */

	// only specific sheet
	$writer->setSheetIndex($sheetIndex);
	// for html, its breaking the "Structured Reference" of xlsx.
	$writer->setPreCalculateFormulas(false);
}
if ($outputExt === 'xlsx') {
	/** @var \PhpOffice\PhpSpreadsheet\Writer\Xlsx $writer */
}

FacadeHelper::writerOutput($spread, $writer, [
	'ext' => $outputExt,
]);

// FacadeHelper::write
// $writer->
// dd($writer);

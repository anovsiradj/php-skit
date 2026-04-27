<?php

use anovsiradj\skit\spreadsheet\ExcelHelper;

require __DIR__ . '/../init.php';

$outputExt = 'html'; // xlsx,html,csv,ods
// $inputName = 'export.xlsx';
$inputName = 'export-simple.xlsx';
$sheetIndex = 0; // 0,1,2

$reader = ExcelHelper::reader();
$reader->setLoadAllSheets();

$spread = ExcelHelper::readerInput(__DIR__ . "/{$inputName}", $reader);
// $spread->getAllSheets();

$sheet = $spread->getSheet($sheetIndex);
// dd($onesheet);

$writer = ExcelHelper::writer($spread, null, [
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

ExcelHelper::writerOutput($spread, $writer, [
	'ext' => $outputExt,
]);

// ExcelHelper::write
// $writer->
// dd($writer);

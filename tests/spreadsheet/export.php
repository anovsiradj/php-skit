<?php

use anovsiradj\skit\vendors\SpreadSheetHelper;

require __DIR__ . '/../init.php';

$outputExt = 'xlsx'; // xlsx,html,csv,ods
// $inputName = 'export.xlsx';
$inputName = 'export-simple.xlsx';
$sheetIndex = 0; // 0,1,2

$reader = SpreadSheetHelper::reader();
$reader->setLoadAllSheets();

$spread = SpreadSheetHelper::readerInput(__DIR__ . "/{$inputName}", $reader);
// $spread->getAllSheets();

$sheet = $spread->getSheet($sheetIndex);
// dd($onesheet);

/** @var \PhpOffice\PhpSpreadsheet\Writer\Xlsx */
$writer = SpreadSheetHelper::writer($spread, null, [
	'ext' => $outputExt,
]);
if ($outputExt === 'html') {
	$writer->setSheetIndex($sheetIndex);
	// for html, its breaking the "Structured Reference" of xlsx.
	$writer->setPreCalculateFormulas(false);
}

SpreadSheetHelper::writerOutput($spread, $writer, [
	'ext' => $outputExt,
]);

// SpreadSheetHelper::write
// $writer->
// dd($writer);

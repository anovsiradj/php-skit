<?php

/**
 * Tujuan: Kumpulan utilitas kecil untuk operasi worksheet (nama sheet, transform sel massal).
 * Cara pakai: Panggil SpreadHelper::sheetNames() atau helper lain pada Worksheet.
 * Dependency: Opsional phpoffice/phpspreadsheet (via FacadeHelper).
 * Catatan standalone: Pakai via Composer autoload; jika tidak, pastikan dependency spreadsheet terpasang.
 */

namespace anovsiradj\skit\spreadsheet;

use anovsiradj\skit\helpers\LetterHelper;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;

use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use PhpOffice\PhpSpreadsheet\Reader\Ods as ReaderOds;
use PhpOffice\PhpSpreadsheet\Reader\Csv as ReaderCsv;

use PhpOffice\PhpSpreadsheet\Writer\BaseWriter;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as WriterXlsx;
use PhpOffice\PhpSpreadsheet\Writer\Ods as WriterOds;
use PhpOffice\PhpSpreadsheet\Writer\Csv as WriterCsv;
use PhpOffice\PhpSpreadsheet\Writer\Pdf as WriterPdf;
use PhpOffice\PhpSpreadsheet\Writer\Html as WriterHtml;


abstract class SpreadHelper
{
	public static function sheetNames($file, ?ReaderXlsx $reader = null)
	{
		$reader ??= FacadeHelper::reader($reader);
		return $reader->listWorksheetNames($file);
	}

	public static function sheetAlters(Worksheet $sheet, $alters, $ymin, $ymax, $xmin, $xmax): void
	{
		for ($y = $ymin; $y < $ymax; $y++) {
			$range = LetterHelper::range($xmin, $xmax);
			foreach ($range as $x) {
				$old = $sheet->getCell("$x$y")->getValue();
				if (empty($old)) {
					continue;
				}
				$new = strtr($old, $alters);
				$sheet->getCell("$x$y")->setValue($new);
			}
		}
	}
}

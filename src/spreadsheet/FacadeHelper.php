<?php

/**
 * Tujuan: Fasad utilitas untuk membaca/menulis spreadsheet via PhpSpreadsheet.
 * Cara pakai: Gunakan FacadeHelper::reader()/writer() lalu readerInput()/writerOutput().
 * Dependency: Opsional phpoffice/phpspreadsheet (tanpa ini modul spreadsheet tidak bisa dipakai).
 * Catatan standalone: Pakai via Composer autoload (vendor/autoload.php). Untuk copy/require, pastikan dependency terpasang.
 */

namespace anovsiradj\skit\spreadsheet;

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

/**
 * @version 20230530,20241204,20260427
 * @source jeemce_laravel,tbmd_ci4
 * @author anovsiradj
 * 
 * comments for changes from <https://github.com/PHPOffice/PhpSpreadsheet/blob/master/CHANGELOG.md>.
 * for version [1,2,3] is improvements and modernization, so there is will be no breaking.
 * for version [2,3] it is require php81.
 * for version 4 ...
 * for php7 only work on version 1 that can be use.
 */
abstract class FacadeHelper
{
	public static $extDefault = 'xlsx';
	public static $headerOutputType = 'inline'; // inline,attachment
	public static $headerOutputName = 'result';

	public static $extMimes = [
		'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'xlsx2007' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'xlsx2003' => 'application/vnd.ms-excel',
		'html' => 'text/html',
		'pdf' => 'application/pdf',
		'csv' => 'text/csv',
	];

	public static $extReaders = [
		'xlsx' => ReaderXlsx::class,
		'ods' => ReaderOds::class,
		'csv' => ReaderCsv::class,
	];

	public static $extWriters = [
		'html' => WriterHtml::class,
		'xlsx' => WriterXlsx::class,
		'ods' => WriterOds::class,
		'csv' => WriterCsv::class,
		'pdf' => WriterPdf::class,
	];

	/**
	 * @return ReaderXlsx
	 */
	public static function reader(?ReaderXlsx $reader = null, array $config = [])
	{
		$config = array_merge([
			'ext' => static::$extDefault,
		], $config);

		$class = static::$extReaders[$config['ext']] ?? null;
		if (empty($class)) {
			throw new \InvalidArgumentException("Unknown reader ext: {$config['ext']}");
		}

		// $tmp = new ReaderXlsx();

		$reader ??= new $class;
		// $reader->setIncludeCharts(false);
		// $reader->setReadEmptyCells(false);
		/*
		foreach ($config as $k => $v) {
			// ...
		}
		*/

		return $reader;
	}

	public static function readerInput($file, ?ReaderXlsx $reader = null, array $config = [])
	{
		$reader ??= static::reader($reader, $config);
		return $reader->load($file);
	}

	/**
	 * @return WriterHtml|WriterXlsx|WriterOds|WriterCsv|WriterPdf
	 */
	public static function writer(Spreadsheet $spread, ?BaseWriter $writer = null, array $config = [])
	{
		$config = array_merge([
			'ext' => static::$extDefault,
		], $config);

		if (empty($writer)) {
			$class = static::$extWriters[$config['ext']] ?? null;
			if (empty($class)) {
				throw new \InvalidArgumentException("Unknown writer ext: {$config['ext']}");
			}
			$writer = new $class($spread);
		}

		// $tmp = new WriterXlsx();

		if ($writer instanceof WriterXlsx) {
			$writer->setIncludeCharts(false);
		}
		// $writer->setUseDiskCaching(true, WRITEPATH . 'excel');

		return $writer;
	}

	public static function writerOutput(Spreadsheet $spread, ?BaseWriter $writer = null, array $newConfig = [])
	{
		// config
		$oldConfig = [
			'ext' => static::$extDefault,
			'view' => static::$headerOutputType,
			'echo' => true,
			/** @todo save to disk instead of echo */
			'save' => false,
			/** @todo exit */
			'exit' => false,
			'headers' => [],
		];
		$config = array_merge($oldConfig, $newConfig);
		if (empty($config['name'])) {
			$config['name'] = sprintf('%s.%s', static::$headerOutputName, $config['ext']);
		}
		if (empty($config['mime'])) {
			$config['mime'] = static::$extMimes[$config['ext']];
		}

		if ($config['echo']) {
			// headers
			$headers = array_merge([
				'mime' => "Content-Type: {$config['mime']}",
				'file' => sprintf('Content-Disposition: %s; filename="%s"', $config['view'], $config['name']),
			], $config['headers']);

			if (empty($headers['mime'])) {
				$headers['mime'] = "Content-Type: {$config['mime']}";
			}
			// dd($headers);

			foreach ($headers as $header) {
				if (empty($header)) {
					continue;
				}
				if (is_string($header)) {
					call_user_func('header', $header);
				}
				if (is_array($header)) {
					call_user_func_array('header', $header);
				}
			}

			$writer ??= static::writer($spread, $writer, $config);
			$writer->save('php://output');

			if ($config['exit']) {
				exit;
			}
		}

		// eof
	}

	public static function assign(Cell $cell, $data, array $styles = [])
	{
		$cell->setValue($data);
		$cell->getStyle()->applyFromArray($styles);
		return $cell;
	}
}

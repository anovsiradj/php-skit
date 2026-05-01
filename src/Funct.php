<?php

/**
 * Tujuan: Abstraksi pemuatan file fungsi global (src/functs) agar bisa dipanggil lewat class.
 * Cara pakai: Set Funct::$dirs / Funct::$files lalu panggil Funct::loadName() atau method dinamis.
 * Dependency: Tidak ada dependency eksternal.
 * Catatan standalone: Pakai via Composer autoload; file functs bisa juga di-require langsung.
 */

namespace anovsiradj\skit;

/**
 * @link https://stackoverflow.com/questions/4737199/autoloader-for-functions
 * @link https://web.archive.org/web/20170623212906/https://bryanjhvtk.wordpress.com/2014/03/14/functions-autoloading-php/
 * 
 * $errorLevel = ignore|error
 */
abstract class Funct
{
	public static $dirs = [];
	public static $files = [];

	public static $errorLevel = 'error';
	public static $errorHandle = null;

	public static function loadFileDefault()
	{
		static::loadFile(__DIR__ . '/functs/error.php');
	}

	public static function errorConfig()
	{
		// ...
	}

	public static function loadFile($path)
	{
		// ...
	}

	public static function loadName($name)
	{
		// ...
	}

	public static function __callStatic($name, $arguments)
	{
		// ...
	}

	public function __invoke()
	{
		// ...
	}
}

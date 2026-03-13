<?php

namespace anovsiradj\skit;

/**
 * @link https://stackoverflow.com/questions/4737199/autoloader-for-functions
 * @link https://web.archive.org/web/20170623212906/https://bryanjhvtk.wordpress.com/2014/03/14/functions-autoloading-php/
 * 
 * $errorLevel = log|error
 */
abstract class Funct
{
	public static $dirs = [];
	public static $files = [];

	public $errorLevel = 'error';
	public $errorHandle = null;

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

	}

	public static function loadName($name)
	{
		// ...
	}

	public static function __callStatic($name, $arguments)
	{
	}

	public function __invoke()
	{
		// ...
	}
}

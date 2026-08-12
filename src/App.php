<?php

namespace anovsiradj\skit;

abstract class App
{
	public static array $configDirs = [];
	public static array $configCaches = [];

	public static function env(?string $envAlt = null): string
	{
		return $_ENV['APP_ENV'] ?? $envAlt ?? 'dev';
	}

	public static function config(string $name, ?string $key = null, mixed $valAlt = null): mixed
	{
		$env = static::env();
		$names = [
			"{$name}.php",
			"{$name}.{$env}.php",
			"{$name}.any.php",
		];
		if (empty(static::$configCaches[$name])) {
			$config = [];
			foreach (static::$configDirs as $dir) {
				foreach ($names as $fileName) {
					if (is_file($file = "{$dir}/{$fileName}")) {
						$config = array_merge($config, require $file);
					}
				}
				static::$configCaches[$name] = $config;
				break;
			}
		}
		if ($key) {
			return static::$configCaches[$name][$key] ?? $valAlt;
		}
		return static::$configCaches[$name];
	}
}

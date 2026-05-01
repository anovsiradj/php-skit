<?php

/**
 * Tujuan: Parse argumen CLI ($argv) menjadi array asosiatif sederhana.
 * Cara pakai: $arguments = require __DIR__ . '/arguments.php'; (mengembalikan array).
 * Dependency: Tidak ada dependency eksternal.
 * Catatan standalone: Dirancang untuk di-include langsung (return array); tidak butuh Composer.
 */

/**
 * @source anovsiradj/topkit/php/arguments.php
 */

$argumentsEchoExit ??= false;

$argv ??= [];
parse_str(implode('&', array_slice($argv, 1)), $arguments);

if ($argumentsEchoExit) {
	echo var_export($arguments, true), PHP_EOL;
	exit;
}

return $arguments;

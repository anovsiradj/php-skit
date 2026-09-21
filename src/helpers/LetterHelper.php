<?php

namespace anovsiradj\skit\helpers;

/**
 * origin: (placeholder), C:\works\legacy\angkasa_pemasaran\extras\NumberHelper.php
 * author: anovsiradj, custom_hcnsec/Qwen3.8-Flash-Next
 * version: 2026-09-21
 */

abstract class LetterHelper
{
	public static function range($min, $max)
	{
		$range = [];
		$index = $min;

		do {
			$range[] = $index;
			if ($index === $max) {
				break;
			}

			$index = str_increment($index);
		} while (true);

		return $range;
	}

	public static function isLetter($letter)
	{
		return !empty($letter) && ctype_alpha($letter);
	}

	public static function prev($curr, $diff = 1)
	{
		if (!static::isLetter($curr)) {
			return null;
		}
		$prev = $curr;
		for ($i = 1; $i <= $diff; $i++) {
			$prev = str_decrement($prev);
		}
		return $prev;
	}

	public static function next($curr, $diff = 1)
	{
		if (!static::isLetter($curr)) {
			return null;
		}
$next = $curr;
        for ($i = 1; $i <= $diff; $i++) {
            $next = str_increment($next);
        }
        return $next;
	}
}

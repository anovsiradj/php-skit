<?php

namespace anovsiradj\skit\helpers;

class LetterHelper
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
}

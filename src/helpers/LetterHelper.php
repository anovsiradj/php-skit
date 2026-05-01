<?php

/**
 * Tujuan: Helper operasi huruf (mis. membuat range kolom spreadsheet A..Z).
 * Cara pakai: LetterHelper::range('A', 'Z') mengembalikan array ['A', ... 'Z'].
 * Dependency: Tidak ada dependency eksternal.
 * Catatan standalone: Pakai via Composer autoload atau require file ini langsung.
 */

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

<?php

namespace anovsiradj\skit\helpers;

/**
 * origin: C:\works\legacy\angkasa_pemasaran\extras\NumberHelper.php
 * author: anovsiradj, custom_hcnsec/Qwen3.8-Flash-Next
 * version: 2026-09-21
 */

abstract class RomanHelper
{
    public static $romanIntegerMatch = [
        'M' => 1000,
        'CM' => 900,
        'D' => 500,
        'CD' => 400,
        'C' => 100,
        'XC' => 90,
        'L' => 50,
        'XL' => 40,
        'X' => 10,
        'IX' => 9,
        'V' => 5,
        'IV' => 4,
        'I' => 1,
    ];

    public static function isRoman($roman)
    {
        if (empty($roman) || static::isInteger($roman)) {
            return false;
        }
        return preg_match('/^M{0,3}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/i', $roman) !== false;
    }

    public static function integerToRoman($integer)
    {
        if (!static::isInteger($integer)) {
            return null;
        }
        $integer = (int)$integer;
        if ($integer <= 0 || $integer > 3999) {
            return null;
        }

        $roman = '';
        foreach (static::$romanIntegerMatch as $key => $value) {
            while ($integer >= $value) {
                $roman .= $key;
                $integer -= $value;
            }
        }
        return $roman;
    }

    public static function romanToInteger($roman)
    {
        if (!static::isRoman($roman)) {
            return null;
        }
        $integer = 0;
        foreach (static::$romanIntegerMatch as $key => $value) {
            while (strpos($roman, $key) === 0) {
                $integer += $value;
                $roman = substr($roman, strlen($key));
            }
        }
        return $integer;
    }

    public static function romanPrev($curr, $diff = 1)
    {
        if (!static::isRoman($curr)) {
            return null;
        }
        return static::integerToRoman(static::romanToInteger($curr) - $diff);
    }

    public static function romanNext($curr, $diff = 1)
    {
        if (!static::isRoman($curr)) {
            return null;
        }
        return static::integerToRoman(static::romanToInteger($curr) + $diff);
    }

    protected static function isInteger($integer)
    {
        if (empty($integer)) {
            return false;
        }
        return is_int($integer) || (is_numeric($integer) && ctype_digit($integer));
    }
}
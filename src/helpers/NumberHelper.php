<?php

namespace anovsiradj\skit\helpers;

/**
 * origin: C:\works\phpmono\pkgs\yii2-skit\src\helpers\NumberHelper.php (moved to php-skit for generic use), C:\works\legacy\simlpu_web\common\components\Angka.php
 * author: anovsiradj, custom_hcnsec/Qwen3.8-Flash-Next
 * version: 2026-09-21
 */

abstract class NumberHelper
{
    public static function format($number, $decimals = 0)
    {
        return number_format($number, $decimals, ',', '.');
    }

    public static function unformat($number)
    {
        return (float)str_replace(['.', ','], ['', '.'], $number);
    }

    public static function zeroDigit($number, $digit)
    {
        $result = strval($number);
        while (strlen($result) < $digit) {
            $result = '0' . $result;
        }
        return $result;
    }

    public static function currency($number, $decimals = 2)
    {
        return 'Rp. ' . static::format($number, $decimals);
    }

    public static function excel($number)
    {
        $result = str_replace('.', '', strval($number));
        return str_replace(',', '.', $result);
    }

    public static function roundUpTo($number, $nearest = 1000)
    {
        $result = round($number, -strlen(substr(strval($nearest), 1)));
        if ($result < $number) {
            $result += $nearest;
        }
        return $result;
    }

    public static function randomCode($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $max = strlen($characters) - 1;
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[random_int(0, $max)];
        }
        return $code;
    }

    public static function terbilang($number)
    {
        $number = strval($number);
        if (!preg_match('/^[0-9]{1,15}$/', $number)) {
            return false;
        }

        $ones = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan'];
        $majorUnits = ['', 'ribu', 'juta', 'milyar', 'trilyun'];
        $minorUnits = ['', 'puluh', 'ratus'];

        $result = '';
        $isAnyMajorUnit = false;
        $length = strlen($number);

        for ($i = 0, $pos = $length - 1; $i < $length; $i++, $pos--) {
            $digit = $number[$i];
            if ($digit != '0') {
                if ($digit != '1') {
                    $result .= $ones[$digit] . ' ' . $minorUnits[$pos % 3] . ' ';
                } elseif ($pos % 3 == 1 && ($number[$i + 1] ?? '0') != '0') {
                    if ($number[$i + 1] == '1') {
                        $result .= 'sebelas ';
                    } else {
                        $result .= $ones[$number[$i + 1]] . ' belas ';
                    }
                    $i++;
                    $pos--;
                } elseif ($pos % 3 != 0) {
                    $result .= 'se' . $minorUnits[$pos % 3] . ' ';
                } elseif ($pos == 3 && !$isAnyMajorUnit) {
                    $result .= 'se';
                } else {
                    $result .= 'satu ';
                }
                $isAnyMajorUnit = true;
            }
            if ($pos % 3 == 0 && $isAnyMajorUnit) {
                $result .= $majorUnits[$pos / 3] . ' ';
                $isAnyMajorUnit = false;
            }
        }

        $result = trim(preg_replace('/\s+/', ' ', $result));
        if ($result == '') {
            $result = 'nol';
        }

        return ucfirst(strtolower($result));
    }

    public static function shortCurrency($nominal, $digit = 0)
    {
        $suffix = [1 => '', 'Rb', 'Jt', 'M', 'T'];
        $pangkat = 1;
        $hasil = $nominal;

        while ($pangkat <= 5) {
            $kelompok = pow(1000, $pangkat);
            if ($nominal >= $kelompok) {
                $hasil = $nominal / $kelompok;
                $pangkat++;
            } else {
                break;
            }
        }

        $satuan = $suffix[$pangkat] ?? 'T';

        return static::format($hasil, $digit) . ' ' . $satuan;
    }

    public static function growthRate(array $list)
    {
        if (empty($list)) {
            return 0;
        }

        $first = true;
        $prev = 0;
        $pertumbuhan = 0;
        $count = 0;

        foreach ($list as $current) {
            if ($first) {
                $prev = $current;
                $first = false;
                continue;
            }
            if ($prev > 0) {
                $pertumbuhan += (($current - $prev) / $prev) * 100;
            } elseif ($current > 0) {
                $pertumbuhan += 100;
            }
            $prev = $current;
            $count++;
        }

        return $count ? round($pertumbuhan / $count, 2) : 0;
    }
}

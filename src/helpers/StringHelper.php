<?php

namespace anovsiradj\skit\helpers;

/**
 * origin: C:\works\legacy\simlpu_web\common\components\DataString.php
 * author: anovsiradj, custom_hcnsec/Qwen3.8-Flash-Next
 * version: 2026-09-21
 */

abstract class StringHelper
{
    public static function slug($string)
    {
        $string = strtolower(trim($string));
        $string = preg_replace('/[^a-z0-9]+/', '-', $string);
        return trim($string, '-');
    }

    public static function limitText($text, $limit = 300, $stripTags = true, $allowedTags = '')
    {
        if ($stripTags) {
            $text = strip_tags($text, $allowedTags);
        }

        if (strlen($text) <= $limit) {
            return $text;
        }

        $chunkAt = strrpos(substr($text, 0, $limit), ' ');
        if ($chunkAt === false) {
            $chunkAt = $limit;
        }

        return substr($text, 0, $chunkAt) . ' ...';
    }

    public static function limitWords($text, $maxWords, $append = '...')
    {
        $words = explode(' ', $text);
        if (count($words) <= $maxWords) {
            return $text;
        }

        return implode(' ', array_slice($words, 0, $maxWords)) . $append;
    }

    public static function alphaNumeric($value)
    {
        return preg_replace('/[^A-Za-z0-9 \-]/', '', strip_tags($value));
    }

    /**
     * potongan SEBELUM kemunculan pertama $needle; utuh bila needle tidak ditemukan.
     *
     * contoh: before('a.b.c', '.') => 'a' ; before('abc', '.') => 'abc'
     *
     * origin: konsep Str::before (illuminate) + jeemce Xmlmeta::keyShort (dot-segment)
     */
    public static function before($subject, $needle)
    {
        $subject = (string) $subject;
        if ($needle === '' || $needle === null) {
            return $subject;
        }

        $pos = strpos($subject, (string) $needle);
        return $pos === false ? $subject : substr($subject, 0, $pos);
    }

    /**
     * potongan SESUDAH kemunculan pertama $needle; utuh bila needle tidak ditemukan.
     *
     * contoh: after('a.b.c', '.') => 'b.c' ; before(after('a.b.c', '.'), '.') => 'b'
     */
    public static function after($subject, $needle)
    {
        $subject = (string) $subject;
        if ($needle === '' || $needle === null) {
            return $subject;
        }

        $pos = strpos($subject, (string) $needle);
        return $pos === false ? $subject : substr($subject, $pos + strlen((string) $needle));
    }
}

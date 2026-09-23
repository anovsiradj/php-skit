<?php

namespace anovsiradj\skit\helpers;

use Closure;

/**
 * Utility array yang tidak disediakan PHP bawaan.
 * (Versi framework-agnostic; padananArr::map/Arr::get dll pakai PHP murni.)
 *
 * origin: C:\works\legacy\riung\riung_medsos_web\vendor\jeemce\laravel\helpers\ArrayHelper.php (kerja4), C:\works\legacy\ditjen-migas-php\app\Helpers\ArrayHelper.php
 * author: anovsiradj, custom_hcnsec/Qwen3.8-Flash-Next
 * version: 2026-09-23
 */
abstract class ArrayHelper
{
    /**
     * flat (array)
     *
     * konversi indexed/mixed array:
     * ```
     * [ A, [1 => B], [2 => [3 => C]] ]
     * ```
     *
     * menjadi flat array:
     * ```
     * [A, B, C]
     * ```
     */
    public static function flat(array $input, bool $unique = true)
    {
        $output = [];
        array_walk_recursive($input, function ($x) use (&$output) {
            $output[] = $x;
        });

        if ($unique) {
            $output = array_values(array_unique($output));
        }

        return $output;
    }

    /**
     * @return string
     */
    public static function flatJoin(?array $input, string $separator = PHP_EOL)
    {
        return implode($separator, static::flat($input ?? []));
    }

    /**
     * Force Assoc
     *
     * konversi indexed/mixed array:
     * ```
     * [ A, B => "Huruf B" ]
     * ```
     *
     * menjadi assoc array:
     * ```
     * [ A => A, B => "Huruf B" ]
     * ```
     */
    public static function assoc(array $input, ?callable $callback = null)
    {
        $callback ??= fn ($v) => $v;

        $output = [];
        foreach ($input as $k => $v) {
            if (is_string($k)) {
                $output[$k] = $callback($v);
                continue;
            }
            if (is_int($k) && is_string($v)) {
                $output[$v] = $callback($v);
                continue;
            }
        }

        return $output;
    }

    /**
     * melakukan `merge()` passing-by-reference
     *
     * @link https://www.php.net/manual/en/language.references.pass.php
     * @return array
     */
    public static function mergeRef(array &$input, array $merge)
    {
        foreach ($merge as $k => $v) {
            if (isset($input[$k]) && is_array($input[$k]) && is_array($v)) {
                $input[$k] = array_merge($input[$k], $v);
            } else {
                $input[$k] = $v;
            }
        }
        return $input;
    }

    /**
     * melakukan `unset()` passing-by-reference (assoc)
     *
     * key bisa berupa closure, dengan params (val,key).
     * key bisa berupa regexp, dengan syarat prefix dan suffix dari key berupa '/'.
     */
    public static function removeKey(array &$array, array $keys = [])
    {
        foreach ($keys as $key) {
            if ($key instanceof Closure) {
                foreach ($array as $k => $v) {
                    if ($key($v, $k)) {
                        unset($array[$k]);
                    }
                }
            } elseif (is_string($key) && $key !== '' && $key[0] == '/' && $key[strlen($key) - 1] == '/') {
                // key berupa regexp
                foreach (array_keys($array) as $k) {
                    if (preg_match($key, (string) $k) === 1) {
                        unset($array[$k]);
                    }
                }
            } else {
                if (array_key_exists($key, $array)) {
                    unset($array[$key]);
                }
            }
        }

        return $array;
    }

    /**
     * hapus val (rekursif) passing-by-reference.
     */
    public static function removeVal(array &$array, array $vals = [])
    {
        foreach (array_keys($array) as $key) {
            if (is_array($array[$key])) {
                static::removeVal($array[$key], $vals);
            } else {
                foreach ($vals as $val) {
                    if ($array[$key] === $val) {
                        unset($array[$key]);
                        break;
                    }
                }
            }
        }

        return $array;
    }

    /**
     * cek apa val ada, bisa regexp dan wildcard.
     *
     * contoh:
     * ```php
     * valExists('/index', ['/index', '/logout']);                     // TRUE
     * valExists('/setting/mailer/index', ['/setting/*']);              // TRUE
     * valExists('admin', ['/^(admin|member)$/']);                      // TRUE
     * ```
     */
    public static function valExists($needle, array $values)
    {
        if (in_array($needle, $values, true)) {
            return true;
        }
        foreach ($values as $value) {
            if (!is_string($value) || $value === '') {
                continue;
            }
            // value berupa regexp
            if ($value[0] === '/' && $value[strlen($value) - 1] === '/' && preg_match($value, (string) $needle) === 1) {
                return true;
            }
            // value adalah wildcard
            if ($value[0] === '*' || $value[strlen($value) - 1] === '*') {
                if ($value === '*' || strpos((string) $needle, trim($value, '*')) !== false) {
                    return true;
                }
            }
        }
        return false;
    }
}

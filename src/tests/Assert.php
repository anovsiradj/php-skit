<?php

/**
 * Tujuan: Class library assertion sederhana untuk testing tanpa framework.
 * Cara pakai: Gunakan static method Assert::true(), Assert::equals(), dll.
 * Dependency: Tidak ada.
 * Catatan standalone: Bisa digunakan dalam file pengujian PHP murni.
 */

namespace anovsiradj\skit\tests;

use Exception;

class TestFailedException extends Exception {}
class TestSkippedException extends Exception {}

abstract class Assert
{
    public static function true($condition, $message = 'Assertion failed')
    {
        if (!$condition) {
            throw new TestFailedException($message);
        }
    }

    public static function false($condition, $message = 'Assertion failed')
    {
        if ($condition) {
            throw new TestFailedException($message);
        }
    }

    public static function equals($expected, $actual, $message = '')
    {
        if ($expected !== $actual) {
            $message = $message ?: "Expected " . print_r($expected, true) . " but got " . print_r($actual, true);
            throw new TestFailedException($message);
        }
    }

    public static function skip($reason = 'Skipped')
    {
        throw new TestSkippedException($reason);
    }
}

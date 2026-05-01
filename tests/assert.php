<?php

/**
 * Tujuan: Minimal assertion library untuk lightweight test harness.
 * Cara pakai: assert_true($kondisi), assert_equals($expected, $actual), test_skip('alasan').
 */

class TestFailedException extends Exception {}
class TestSkippedException extends Exception {}

function assert_true($condition, $message = 'Assertion failed') {
    if (!$condition) {
        throw new TestFailedException($message);
    }
}

function assert_false($condition, $message = 'Assertion failed') {
    if ($condition) {
        throw new TestFailedException($message);
    }
}

function assert_equals($expected, $actual, $message = '') {
    if ($expected !== $actual) {
        $message = $message ?: "Expected " . print_r($expected, true) . " but got " . print_r($actual, true);
        throw new TestFailedException($message);
    }
}

function test_skip($reason = 'Skipped') {
    throw new TestSkippedException($reason);
}

function test_run($name, Closure $test) {
    global $__test_results;
    
    if (!isset($__test_results)) {
        $__test_results = ['passed' => 0, 'failed' => 0, 'skipped' => 0, 'errors' => []];
    }
    
    try {
        $test();
        $__test_results['passed']++;
        echo "  [\033[32mPASS\033[0m] $name\n";
    } catch (TestSkippedException $e) {
        $__test_results['skipped']++;
        echo "  [\033[33mSKIP\033[0m] $name - " . $e->getMessage() . "\n";
    } catch (TestFailedException $e) {
        $__test_results['failed']++;
        echo "  [\033[31mFAIL\033[0m] $name - " . $e->getMessage() . "\n";
    } catch (Throwable $e) {
        $__test_results['failed']++;
        echo "  [\033[31mERROR\033[0m] $name - Uncaught exception: " . $e->getMessage() . "\n";
    }
}

<?php

/**
 * Tujuan: Runner dan state manager untuk test suite murni PHP.
 * Cara pakai: Gunakan Runner::getInstance()->runTest($name, $closure).
 * Dependency: Tidak ada.
 * Catatan standalone: Ini state container. File ini berjalan dengan mode CLI.
 */

namespace anovsiradj\skit\tests;

use Closure;
use Throwable;

class Runner
{
    private static $instance = null;
    
    private $results = [
        'passed' => 0,
        'failed' => 0,
        'skipped' => 0,
        'errors' => []
    ];

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function runTest($name, Closure $test)
    {
        try {
            $test();
            $this->results['passed']++;
            echo "  [\033[32mPASS\033[0m] $name\n";
        } catch (TestSkippedException $e) {
            $this->results['skipped']++;
            echo "  [\033[33mSKIP\033[0m] $name - " . $e->getMessage() . "\n";
        } catch (TestFailedException $e) {
            $this->results['failed']++;
            echo "  [\033[31mFAIL\033[0m] $name - " . $e->getMessage() . "\n";
        } catch (Throwable $e) {
            $this->results['failed']++;
            echo "  [\033[31mERROR\033[0m] $name - Uncaught exception: " . $e->getMessage() . "\n";
        }
    }

    public function getResults()
    {
        return $this->results;
    }

    public function reset()
    {
        $this->results = [
            'passed' => 0,
            'failed' => 0,
            'skipped' => 0,
            'errors' => []
        ];
    }
}

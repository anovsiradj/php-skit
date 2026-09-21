<?php

namespace anovsiradj\skit\helpers;

/**
 * origin: C:\projects\SIGAP_KLHK\sigap_forge\jeemce\extras\ExecTime.php
 * author: anovsiradj, Meta/Muse Glimmer
 * version: 2026-09-21
 */

class ExecTime
{
    public $begin;
    public $end;

    public function __construct()
    {
        $this->begin();
    }

    public function begin()
    {
        $this->begin = microtime(true);
    }

    public function end()
    {
        $this->end = microtime(true);
    }

    public function result($end = false)
    {
        if ($end || empty($this->end)) {
            $this->end();
        }
        return ($this->end - $this->begin);
    }

    public function format($decimals = 3)
    {
        return number_format($this->result(true), $decimals) . ' detik';
    }

    public function wrap(callable $callback)
    {
        $this->begin();
        $callback();
        return $this->format();
    }
}

<?php

/**
 * HowTo & Test: anovsiradj\skit\helpers\TimeHelper
 */
use anovsiradj\skit\helpers\TimeHelper;
use anovsiradj\skit\tests\Runner;
use anovsiradj\skit\tests\Assert;

Runner::getInstance()->runTest('TimeHelper - class exists', function () {
    Assert::true(class_exists('anovsiradj\skit\helpers\TimeHelper'));
});

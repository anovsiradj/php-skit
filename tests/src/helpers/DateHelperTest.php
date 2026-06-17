<?php

/**
 * HowTo & Test: anovsiradj\skit\helpers\DateHelper
 */
use anovsiradj\skit\helpers\DateHelper;
use anovsiradj\skit\tests\Runner;
use anovsiradj\skit\tests\Assert;

Runner::getInstance()->runTest('DateHelper - class exists', function () {
    Assert::true(class_exists('anovsiradj\skit\helpers\DateHelper'));
});

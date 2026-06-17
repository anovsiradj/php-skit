<?php

/**
 * HowTo & Test: anovsiradj\skit\helpers\IntlHelper
 */
use anovsiradj\skit\helpers\IntlHelper;
use anovsiradj\skit\tests\Runner;
use anovsiradj\skit\tests\Assert;

Runner::getInstance()->runTest('IntlHelper - class exists', function () {
    Assert::true(class_exists('anovsiradj\skit\helpers\IntlHelper'));
});

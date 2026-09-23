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

Runner::getInstance()->runTest('DateHelper - parseDate', function () {
    Assert::equals('2026-09-23', DateHelper::parseDate('23/09/2026'));
    Assert::equals('2026-09-23', DateHelper::parseDate('23-9-2026'));
    Assert::equals('2026-09-23', DateHelper::parseDate('2026-09-23'));
    Assert::equals('2026-09-23', DateHelper::parseDate('23 Sep 2026'));
    Assert::equals('2026-10-23', DateHelper::parseDate('23 Okt 2026'));
    Assert::equals('2026-08-05', DateHelper::parseDate('5 Agu 2026'));
    Assert::equals(null, DateHelper::parseDate(''));
    Assert::equals(null, DateHelper::parseDate('-'));
});

Runner::getInstance()->runTest('DateHelper - parseDateTime', function () {
    Assert::equals('2026-09-23 14:30:00', DateHelper::parseDateTime('23/09/2026', '14.30'));
    Assert::equals('2026-09-23 14:30:00', DateHelper::parseDateTime('23 Sep 2026 2:30 pm'));
    Assert::equals('2026-09-23 00:00:00', DateHelper::parseDateTime('2026-09-23'));
    Assert::equals(null, DateHelper::parseDateTime('-'));
});


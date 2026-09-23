<?php

/**
 * HowTo & Test: anovsiradj\skit\helpers\UrlHelper
 */
use anovsiradj\skit\helpers\UrlHelper;
use anovsiradj\skit\tests\Runner;
use anovsiradj\skit\tests\Assert;

Runner::getInstance()->runTest('UrlHelper - class exists', function () {
    Assert::true(class_exists('anovsiradj\skit\helpers\UrlHelper'));
});

Runner::getInstance()->runTest('UrlHelper - mergeParams', function () {
    Assert::equals('/x?a=1&b=2', UrlHelper::mergeParams('/x?a=1', ['b' => 2]));
    Assert::equals('/x?b=2', UrlHelper::mergeParams('/x', ['b' => 2]));
    Assert::equals('/x?a=9', UrlHelper::mergeParams('/x?a=1', ['a' => 9]));
    Assert::equals('', UrlHelper::mergeParams('', ['b' => 2]));
});

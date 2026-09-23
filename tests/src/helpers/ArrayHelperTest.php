<?php

/**
 * HowTo & Test: anovsiradj\skit\helpers\ArrayHelper
 */
use anovsiradj\skit\helpers\ArrayHelper;
use anovsiradj\skit\tests\Runner;
use anovsiradj\skit\tests\Assert;

Runner::getInstance()->runTest('ArrayHelper - class exists', function () {
    Assert::true(class_exists('anovsiradj\skit\helpers\ArrayHelper'));
});

Runner::getInstance()->runTest('ArrayHelper - flat', function () {
    Assert::equals(['A', 'B', 'C'], ArrayHelper::flat(['A', [1 => 'B'], [2 => [3 => 'C']]]));
    Assert::equals(['A', 'A', 'B'], ArrayHelper::flat(['A', ['A', 'B']], false));
});

Runner::getInstance()->runTest('ArrayHelper - flatJoin', function () {
    Assert::equals('A,B', ArrayHelper::flatJoin(['A', ['B']], ','));
    Assert::equals('', ArrayHelper::flatJoin(null, ','));
});

Runner::getInstance()->runTest('ArrayHelper - assoc', function () {
    Assert::equals(['A' => 'A', 'B' => 'Huruf B'], ArrayHelper::assoc(['A', 'B' => 'Huruf B']));
    Assert::equals(['A' => 'a'], ArrayHelper::assoc(['A'], fn ($v) => strtolower($v)));
});

Runner::getInstance()->runTest('ArrayHelper - mergeRef', function () {
    $a = ['x' => 1, 'deep' => ['a']];
    ArrayHelper::mergeRef($a, ['y' => 2, 'deep' => ['b']]);
    Assert::equals(['x' => 1, 'deep' => ['a', 'b'], 'y' => 2], $a);
});

Runner::getInstance()->runTest('ArrayHelper - removeKey', function () {
    $arr = ['a' => 1, 'password' => 2, 'password_confirmation' => 3, 'b' => 4];
    ArrayHelper::removeKey($arr, ['a', '/^password/']);
    Assert::equals(['b' => 4], $arr);

    $arr2 = ['a' => 1, 'b' => 9];
    ArrayHelper::removeKey($arr2, [fn ($v, $k) => $k === 'b']);
    Assert::equals(['a' => 1], $arr2);
});

Runner::getInstance()->runTest('ArrayHelper - removeVal', function () {
    $arr = ['a' => 'x', 'b' => ['c' => 'x', 'd' => 'y']];
    ArrayHelper::removeVal($arr, ['x']);
    Assert::equals(['b' => ['d' => 'y']], $arr);
});

Runner::getInstance()->runTest('ArrayHelper - valExists', function () {
    Assert::true(ArrayHelper::valExists('/index', ['/index', '/logout']));
    Assert::true(ArrayHelper::valExists('/setting/mailer/index', ['/setting/*']));
    Assert::true(ArrayHelper::valExists('admin', ['/^(admin|member)$/']));
    Assert::false(ArrayHelper::valExists('other', ['/^(admin|member)$/', '/setting/*']));
});

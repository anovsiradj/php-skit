<?php

/**
 * HowTo & Test: anovsiradj\skit\helpers\Tree
 */
use anovsiradj\skit\helpers\Tree;
use anovsiradj\skit\tests\Runner;
use anovsiradj\skit\tests\Assert;

Runner::getInstance()->runTest('Tree - class exists', function () {
    Assert::true(class_exists('anovsiradj\skit\helpers\Tree'));
});

Runner::getInstance()->runTest('Tree - tree', function () {
    $rows = [
        ['id' => 2, 'id_cate' => 1, 'name' => 'child2'],
        ['id' => 1, 'id_cate' => null, 'name' => 'root1'],
        ['id' => 3, 'id_cate' => 2, 'name' => 'child3'],
        ['id' => 4, 'id_cate' => 0, 'name' => 'root4'],
    ];

    $tree = Tree::tree($rows);
    Assert::equals(2, count($tree));
    Assert::equals('root1', $tree[0]['name']);
    Assert::equals(1, $tree[0]['deep']);
    Assert::equals(1, count($tree[0]['tree']));
    Assert::equals('child2', $tree[0]['tree'][0]['name']);
    Assert::equals(2, $tree[0]['tree'][0]['deep']);
    Assert::equals(3, $tree[0]['tree'][0]['tree'][0]['deep']);
});

Runner::getInstance()->runTest('Tree - flat', function () {
    $rows = [
        ['id' => 1, 'id_cate' => null, 'name' => 'root1'],
        ['id' => 2, 'id_cate' => 1, 'name' => 'child2'],
        ['id' => 3, 'id_cate' => 2, 'name' => 'child3'],
        ['id' => 4, 'id_cate' => null, 'name' => 'root4'],
    ];
    $flat = Tree::flat(Tree::tree($rows));
    Assert::equals([1, 2, 3, 4], array_column($flat, 'id'));
});

Runner::getInstance()->runTest('Tree - parents', function () {
    $rows = [
        ['id' => 1, 'id_cate' => null, 'name' => 'root1'],
        ['id' => 2, 'id_cate' => 1, 'name' => 'child2'],
        ['id' => 3, 'id_cate' => 2, 'name' => 'child3'],
    ];
    Assert::equals([1, 2, 3], array_column(Tree::parents($rows, 3), 'id'));
    Assert::equals([1], array_column(Tree::parents($rows, 1), 'id'));
});

Runner::getInstance()->runTest('Tree - sortTree', function () {
    $rows = [
        ['id' => 1, 'id_cate' => null, 'name' => 'b-root'],
        ['id' => 2, 'id_cate' => null, 'name' => 'a-root'],
        ['id' => 3, 'id_cate' => 2, 'name' => 'z-kid'],
        ['id' => 4, 'id_cate' => 2, 'name' => 'y-kid'],
    ];
    $tree = Tree::tree($rows, ['sort' => fn ($a, $b) => strcmp($a['name'], $b['name'])]);
    Assert::equals('a-root', $tree[0]['name']);
    Assert::equals('y-kid', $tree[0]['tree'][0]['name']);
});

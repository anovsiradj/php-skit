<?php

/**
 * HowTo & Test: anovsiradj\skit\helpers\MenuBuilder
 */
use anovsiradj\skit\helpers\MenuBuilder;
use anovsiradj\skit\tests\Runner;
use anovsiradj\skit\tests\Assert;

Runner::getInstance()->runTest('MenuBuilder - class exists', function () {
    Assert::true(class_exists('anovsiradj\skit\helpers\MenuBuilder'));
});

Runner::getInstance()->runTest('MenuBuilder - manual build', function () {
    $builder = new MenuBuilder();
    $builder
        ->addTitle('t1', 'Judul')
        ->beginDropdown('d1', 'Master')
        ->addLink('l1', 'User', '/user')
        ->endDropdown()
        ->addLink('l2', 'Home', '/');

    $menu = $builder->getResult();
    Assert::equals(3, count($menu));
    Assert::equals('title', $menu[0]['slug']);
    Assert::equals('dropdown', $menu[1]['slug']);
    Assert::equals(1, count($menu[1]['elements']));
    Assert::equals('link', $menu[1]['elements'][0]['slug']);
    Assert::equals('/user', $menu[1]['elements'][0]['href']);
    Assert::equals('/', $menu[2]['href']);
});

Runner::getInstance()->runTest('MenuBuilder - nested dropdown', function () {
    $builder = new MenuBuilder();
    $builder
        ->beginDropdown('d1', 'Level1')
        ->beginDropdown('d2', 'Level2')
        ->addLink('l1', 'Daun', '/daun')
        ->endDropdown()
        ->endDropdown();

    $menu = $builder->getResult();
    Assert::equals(1, count($menu));
    Assert::equals(1, count($menu[0]['elements']));
    Assert::equals('Daun', $menu[0]['elements'][0]['elements'][0]['name']);
});

Runner::getInstance()->runTest('MenuBuilder - fromRows', function () {
    $rows = [
        [
            'id' => 1, 'name' => 'Master', 'href' => '#', 'tree' => [
                ['id' => 2, 'name' => 'User', 'href' => '/user', 'tree' => []],
            ],
        ],
    ];

    $menu = MenuBuilder::fromRows($rows)->getResult();
    Assert::equals(1, count($menu));
    Assert::equals('dropdown', $menu[0]['slug']);
    Assert::equals('/user', $menu[0]['elements'][0]['href']);
});

<?php

/**
 * HowTo & Test: src/functs/ico.php
 * 
 * Usage:
 * ```php
 * require_once 'src/functs/ico.php';
 * $im = imagecreate(10, 10);
 * imageico($im, 'out.ico');
 * ```
 */

test_run('functs/ico.php - check dependency and function availability', function () {
    if (!extension_loaded('gd')) {
        test_skip('ext-gd not loaded');
    }

    require_once __DIR__ . '/../../../src/functs/ico.php';
    assert_true(function_exists('imageico'));
    
    // Test small 1x1 image conversion
    $im = imagecreate(1, 1);
    ob_start();
    imageico($im);
    $data = ob_get_clean();
    
    assert_true(strlen($data) > 0);
    assert_equals(pack('v3', 0, 1, 1), substr($data, 0, 6), 'Should have ICO header');
    
    imagedestroy($im);
});

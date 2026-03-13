<?php

/**
 * Output an ICO image to either the standard output or a file.
 *
 * It takes the same arguments as 'imagepng' from the GD library. Works by
 * creating a ICO container with a single PNG image.
 * This type of ICO image is supported since Windows Vista and by all major
 * browsers.
 * 
 * License: I place this code in the public domain.
 *
 * @link https://en.wikipedia.org/wiki/ICO_(file_format)#PNG_format
 * @link https://gist.github.com/goncalomb/6d879df103fda9b63feb
 * @author Gonçalo Baltazar <me@goncalomb.com>
 * @author Mayendra Costanov <anovsiradj@gmail.com>
 * @license Public Domain
 */
function imageico($image, $filename = null, $quality = 9, $filters = PNG_NO_FILTER)
{
	$x = imagesx($image);
	$y = imagesy($image);
	if ($x > 256 || $y > 256) {
		trigger_error('ICO images cannot be larger than 256 pixels wide/tall', E_USER_WARNING);
		return;
	}
	if ($filename) {
		ob_start();
	}
	// Collect PNG data.
	ob_start();
	imagesavealpha($image, true);
	imagepng($image, null, $quality, $filters);
	$png_data = ob_get_clean();
	// Write ICO header, image entry and PNG data.
	echo pack('v3', 0, 1, 1);
	echo pack('C4v2V2', $x, $y, 0, 0, 1, 32, strlen($png_data), 22);
	echo $png_data;
	// Output to file.
	if ($filename) {
		file_put_contents($filename, ob_get_clean());
	}
}

// Logic to use this as CLI script.
// Run 'php img2ico.php {your_image_file}' to output an ICO image.
// This requires the GD library.

if (!defined('STDERR')) {
	define('STDERR', fopen('php://stderr', 'r'));
}

if ($argc >= 2) {
	$input_file = $argv[1];
	$output_file = $input_file . '.ico';
	if (!is_file($input_file)) {
		fwrite(STDERR, "File '{$input_file}' not found.\n");
		exit(2);
	} else if (is_file($output_file)) {
		fwrite(STDERR, "Output file '{$output_file}' exists. Will not overwrite.\n");
		exit(3);
	}
	$im = @imagecreatefromstring(file_get_contents($input_file));
	if (!$im) {
		fwrite(STDERR, "File '{$input_file}' is not a valid image file.\n");
		exit(4);
	}
	imageico($im, $output_file);
	imagedestroy($im);
	exit(0);
} else {
	fwrite(STDERR, "No input file.\n");
	exit(1);
}

<?php

/**
 * Tujuan: Helper terkait internationalization/locale (placeholder/akan dikembangkan).
 * Cara pakai: Panggil IntlHelper::create() atau method lain saat tersedia.
 * Dependency: Opsional ext-intl (tergantung implementasi).
 * Catatan standalone: Pakai via Composer autoload atau require file ini langsung.
 */

namespace anovsiradj\skit\helpers;

abstract class IntlHelper
{
	public static $langs = [
		'id-ID',
		'en-US',
		'id' => 'id-ID',
		'en' => 'en-US',
	];

	public static function create()
	{
		// code...
	}
}

<?php

namespace anovsiradj\skit\helpers;

use Spatie\Html\Elements\File;
use Spatie\Html\Elements\Input;
use Spatie\Html\Elements\Select;
use Spatie\Html\Elements\Textarea;

/**
 * Builder HTML sederhana untuk form di template.
 * Memerlukan package `spatie/laravel-html` (dipakai lewat namespace Spatie\Html saja,
 * tanpa facade/ServiceProvider-nya).
 *
 * origin: C:/works/legacy/riung/riung_medsos_web/vendor/jeemce/laravel/helpers/HtmlHelper.php
 *
 * @author anovsiradj, custom_hcnsec/Qwen3.8-Flash-Next
 * @version 2026-09-23
 */
class HtmlHelper
{
    public static function select(array $options = [])
    {
        $elem = new Select;

        if (isset($options['options'])) {
            if (isset($options['placeholder'])) {
                $options['options'] = ['' => $options['placeholder']] + $options['options'];
                // attr "placeholder" tidak berlaku untuk elemen "select"
                unset($options['placeholder']);
            }
        }

        foreach ($options as $key => $val) {
            if (method_exists($elem, $key)) {
                $elem = $elem->{$key}($val);
            } else {
                $elem = $elem->attribute($key, $val);
            }
        }

        return $elem;
    }

    public static function textInput()
    {
        return new Input;
    }

    public static function fileInput()
    {
        return new File;
    }

    public static function textarea()
    {
        return new Textarea;
    }
}

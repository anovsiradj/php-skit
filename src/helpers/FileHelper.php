<?php

namespace anovsiradj\skit\helpers;

/**
 * Utility filesystem murni (plain PHP); varian Storage/façade Laravel ada di laravel-skit.
 *
 * origin: C:\works\legacy\jogjaprov_historia\app\Helpers\Helper.php (generateFileName), C:\works\legacy\riung\riung_medsos_web\vendor\jeemce\laravel\helpers\FileHelper.php (kerja4)
 * author: anovsiradj, custom_hcnsec/Qwen3.8-Flash-Next
 * version: 2026-09-23
 */
abstract class FileHelper
{
    /**
     * buat direktori (rekursif, silent jika sudah ada).
     */
    public static function createDirectory($path, $mode = 0755)
    {
        return is_dir($path) || mkdir($path, $mode, true);
    }

    /**
     * tulis file baru.
     */
    public static function create($path, $contents)
    {
        if ($contents === null) {
            return false;
        }
        return file_put_contents($path, $contents) !== false;
    }

    /**
     * tulis ulang file.
     */
    public static function update($path, $contents)
    {
        return static::create($path, $contents);
    }

    /**
     * nama file bebas tabrakan: `name.ext` -> `name_2.ext` -> `name_3.ext` ...
     *
     * @param callable|null $existsFn fn(string $absPath): bool ; default file_exists
     * @return string nama file saja (tanpa path), sudah unik
     */
    public static function uniqueName($dir, $fileName, ?callable $existsFn = null)
    {
        $existsFn ??= 'file_exists';
        if (!$existsFn(rtrim((string) $dir, '/\\') . DIRECTORY_SEPARATOR . $fileName)) {
            return $fileName;
        }

        $info = pathinfo($fileName);
        $n = 2;
        do {
            $candidate = ($info['filename'] ?? $fileName) . '_' . $n . (isset($info['extension']) ? '.' . $info['extension'] : '');
            $n++;
        } while ($existsFn(rtrim((string) $dir, '/\\') . DIRECTORY_SEPARATOR . $candidate));

        return $candidate;
    }
}

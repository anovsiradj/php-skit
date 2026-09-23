<?php

namespace anovsiradj\skit\helpers;

/**
 * CSV reader sederhana -> array asosiatif ber-key header baris pertama.
 *
 * origin: C:\works\legacy\jogjaprov_kasil\app\Services\CSVService.php
 * author: anovsiradj, custom_hcnsec/Qwen3.8-Flash-Next
 * version: 2026-09-23
 */
abstract class CsvHelper
{
    /**
     * @return bool|array false bila file tidak bisa dibaca
     */
    public static function toArray($filename, $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }

        $header = null;
        $data = [];
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                if ($row === [null]) {
                    continue; // baris kosong
                }
                if ($header === null) {
                    $header = $row;
                    continue;
                }
                if (count($row) !== count($header)) {
                    $row = array_pad($row, count($header), null);
                    $row = array_slice($row, 0, count($header));
                }
                $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }
}

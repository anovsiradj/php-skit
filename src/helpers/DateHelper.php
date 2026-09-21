<?php

namespace anovsiradj\skit\helpers;

use DateTime;

/**
 * origin: C:\works\legacy\kt_ifm_gtid_web\common\components\DateHelper.php, C:\works\legacy\persada_konstruksi\components\DateHelper.php, C:\works\legacy\jogjaprov_pangripta_app2\common\components\Date.php
 * author: anovsiradj, custom_hcnsec/Qwen3.8-Flash-Next
 * version: 2026-09-21
 */

abstract class DateHelper
{
    public static function format($date, $format = 'd-m-Y')
    {
        if (!$date) return null;
        $dt = $date instanceof DateTime ? $date : new DateTime($date);
        return $dt->format($format);
    }

    public static function startOfDay($date)
    {
        $dt = new DateTime($date);
        $dt->setTime(0, 0, 0);
        return $dt->format('Y-m-d H:i:s');
    }

    public static function endOfDay($date)
    {
        $dt = new DateTime($date);
        $dt->setTime(23, 59, 59);
        return $dt->format('Y-m-d H:i:s');
    }

    public static function namaBulan($bulan)
    {
        $nama = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return $nama[((int)$bulan) - 1] ?? null;
    }

    public static function namaBulanPendek($bulan)
    {
        $nama = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        return $nama[((int)$bulan) - 1] ?? null;
    }

    public static function dayName($day, $lang = 'id')
    {
        $days = [
            'id' => ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
            'en' => ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        ];
        return $days[$lang][(int)$day] ?? null;
    }

    public static function formatTanggal($tanggal, $tipe = 'long', $showDayName = false, $withTime = false)
    {
        if (!$tanggal) {
            return null;
        }

        $parts = explode(' ', trim($tanggal));
        $dateParts = explode('-', $parts[0]);

        if (count($dateParts) != 3) {
            return null;
        }

        $time = isset($parts[1]) ? ' - ' . substr($parts[1], 0, 8) : '';

        $day = (int)$dateParts[2];
        $bulan = $tipe == 'short' ? static::namaBulanPendek($dateParts[1]) : static::namaBulan($dateParts[1]);

        $output = '';
        if ($showDayName) {
            $output .= static::dayName(date('w', strtotime($tanggal)), 'id') . ', ';
        }
        $output .= $day . ' ' . $bulan . ' ' . $dateParts[0];
        if ($withTime) {
            $output .= $time;
        }

        return $output;
    }

    public static function konversiTanggal($tanggal)
    {
        if (!$tanggal) {
            return null;
        }

        $parts = explode('-', $tanggal);
        if (count($parts) == 3) {
            return $parts[2] . '/' . $parts[1] . '/' . $parts[0];
        }

        $parts = explode('/', $tanggal);
        if (count($parts) == 3) {
            return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        }

        return null;
    }

    public static function timeAgo($waktu)
    {
        $diff = time() - strtotime($waktu);

        if ($diff <= 60) {
            return 'Baru saja';
        } elseif ($diff <= 600) {
            return floor($diff / 60) . ' menit yang lalu';
        } elseif ($diff <= 3600) {
            return 'Sekitar ' . floor($diff / 60) . ' menit yang lalu';
        } elseif ($diff <= 36000) {
            return floor($diff / 3600) . ' jam yang lalu';
        } elseif ($diff <= 86400) {
            return 'Sekitar ' . floor($diff / 3600) . ' jam yang lalu';
        } elseif ($diff <= 864000) {
            return floor($diff / 86400) . ' hari yang lalu';
        } elseif ($diff <= 2592000) {
            return 'Sekitar ' . floor($diff / 86400) . ' hari yang lalu';
        } elseif ($diff <= 25920000) {
            return floor($diff / 2592000) . ' bulan yang lalu';
        } elseif ($diff <= 31536000) {
            return 'Sekitar ' . floor($diff / 2592000) . ' bulan yang lalu';
        }

        return floor($diff / 31536000) . ' tahun yang lalu';
    }
}

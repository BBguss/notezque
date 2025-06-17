<?php
// filepath: c:\xampp\htdocs\Kelompok_3\pages\kalender\helper_format.php

// Function untuk format tanggal MM/DD/YY
function formatTanggalMMDDYY($waktu_acara)
{
    try {
        $date = new DateTime($waktu_acara);
        return $date->format('m/d/y');
    } catch (Exception $e) {
        return 'Invalid Date';
    }
}

// Function untuk format tanggal tampilan dengan nama bulan Indonesia
function formatTanggalTampilan($waktu_acara)
{
    try {
        $date = new DateTime($waktu_acara);
        $bulan_indonesia = array(
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        );

        $bulan_num = $date->format('n');
        $bulan_nama = $bulan_indonesia[$bulan_num];

        return $date->format('m/d/y') . ' (' . $bulan_nama . ')';
    } catch (Exception $e) {
        return 'Invalid Date';
    }
}

// Function untuk format waktu
function formatWaktu($waktu_acara)
{
    try {
        $date = new DateTime($waktu_acara);
        $time = $date->format('H:i');
        return $time === '00:00' ? 'Sepanjang hari' : $time;
    } catch (Exception $e) {
        return 'Invalid Time';
    }
}

// Function untuk format tanggal lengkap Indonesia
function formatTanggalLengkap($waktu_acara)
{
    try {
        $date = new DateTime($waktu_acara);
        $hari_indonesia = array(
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        );

        $bulan_indonesia = array(
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        );

        $hari_eng = $date->format('l');
        $hari_idn = $hari_indonesia[$hari_eng];
        $tanggal = $date->format('j');
        $bulan_idn = $bulan_indonesia[$date->format('n')];
        $tahun = $date->format('Y');

        return $hari_idn . ', ' . $tanggal . ' ' . $bulan_idn . ' ' . $tahun;
    } catch (Exception $e) {
        return 'Invalid Date';
    }
}

// Function untuk mendapatkan nama bulan Indonesia
function getNamaBulanIndonesia($bulan_num)
{
    $bulan_indonesia = array(
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    );

    return isset($bulan_indonesia[$bulan_num]) ? $bulan_indonesia[$bulan_num] : 'Unknown';
}

// Function untuk validasi format tanggal
function isValidDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

// Function untuk konversi format tanggal
function konversiFormatTanggal($tanggal, $from_format, $to_format)
{
    try {
        $date = DateTime::createFromFormat($from_format, $tanggal);
        if ($date) {
            return $date->format($to_format);
        }
        return false;
    } catch (Exception $e) {
        return false;
    }
}
?>
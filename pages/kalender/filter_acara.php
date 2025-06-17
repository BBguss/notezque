<?php
// filepath: c:\xampp\htdocs\Kelompok_3\pages\kalender\filter_acara.php

// Function untuk mendapatkan data acara dengan filter
function getAcaraDenganFilter($conn, $id_user, $bulan_filter, $tahun_filter, $sort_filter, $page, $items_per_page)
{
    // Bersihkan input
    $id_user = (int) $id_user;
    $bulan_filter = (int) $bulan_filter;
    $tahun_filter = (int) $tahun_filter;
    $page = max(1, (int) $page);
    $items_per_page = max(1, (int) $items_per_page);
    $offset = ($page - 1) * $items_per_page;

    // Query dasar untuk filter
    $sql_filter = "SELECT id_acara, judul_acara, desc_acara, waktu_acara 
                   FROM kalender_acara 
                   WHERE id_user = '$id_user' 
                   AND MONTH(waktu_acara) = '$bulan_filter' 
                   AND YEAR(waktu_acara) = '$tahun_filter'";

    // Tambah sorting berdasarkan filter
    switch ($sort_filter) {
        case 'tanggal_desc':
            $sql_filter .= " ORDER BY waktu_acara DESC";
            break;
        case 'judul':
            $sql_filter .= " ORDER BY judul_acara ASC";
            break;
        default: // tanggal_asc
            $sql_filter .= " ORDER BY waktu_acara ASC";
            break;
    }

    // Hitung total data untuk pagination
    $sql_count = "SELECT COUNT(*) as total 
                  FROM kalender_acara 
                  WHERE id_user = '$id_user' 
                  AND MONTH(waktu_acara) = '$bulan_filter' 
                  AND YEAR(waktu_acara) = '$tahun_filter'";

    $result_count = mysqli_query($conn, $sql_count);
    $count_data = mysqli_fetch_assoc($result_count);
    $total_data = $count_data['total'];
    $total_pages = ceil($total_data / $items_per_page);

    // Ambil data dengan limit dan offset
    $sql_filter .= " LIMIT $items_per_page OFFSET $offset";
    $result_filter = mysqli_query($conn, $sql_filter);

    $acara_bulan_ini = array();
    while ($row = mysqli_fetch_assoc($result_filter)) {
        $acara_bulan_ini[] = $row;
    }

    return array(
        'data' => $acara_bulan_ini,
        'total_data' => $total_data,
        'total_pages' => $total_pages,
        'current_page' => $page
    );
}

// Function untuk mendapatkan semua data acara user (untuk JavaScript)
function getSemuaAcaraUser($conn, $id_user)
{
    $id_user = (int) $id_user;

    $sql_acara = "SELECT id_acara, judul_acara, desc_acara, waktu_acara 
                  FROM kalender_acara 
                  WHERE id_user = '$id_user' 
                  ORDER BY waktu_acara ASC";

    $result_acara = mysqli_query($conn, $sql_acara);

    $data_acara = array();
    while ($row = mysqli_fetch_assoc($result_acara)) {
        $data_acara[] = $row;
    }

    return $data_acara;
}

// Function untuk validasi parameter filter
function validasiParameterFilter($bulan, $tahun, $sort, $page)
{
    // Validasi bulan (1-12)
    if ($bulan < 1 || $bulan > 12) {
        $bulan = date('n');
    }

    // Validasi tahun (1900-2100)
    if ($tahun < 1900 || $tahun > 2100) {
        $tahun = date('Y');
    }

    // Validasi sort
    $valid_sorts = array('tanggal_asc', 'tanggal_desc', 'judul');
    if (!in_array($sort, $valid_sorts)) {
        $sort = 'tanggal_asc';
    }

    // Validasi page
    if ($page < 1) {
        $page = 1;
    }

    return array(
        'bulan' => $bulan,
        'tahun' => $tahun,
        'sort' => $sort,
        'page' => $page
    );
}

// Function untuk generate URL dengan parameter filter
function generateFilterUrl($base_url, $bulan, $tahun, $sort, $page)
{
    $params = array();

    if ($bulan != date('n')) {
        $params[] = 'bulan=' . $bulan;
    }

    if ($tahun != date('Y')) {
        $params[] = 'tahun=' . $tahun;
    }

    if ($sort != 'tanggal_asc') {
        $params[] = 'sort=' . $sort;
    }

    if ($page > 1) {
        $params[] = 'page=' . $page;
    }

    if (!empty($params)) {
        return $base_url . '?' . implode('&', $params);
    }

    return $base_url;
}
?>
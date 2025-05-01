<?php
include '../../config/koneksi.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');


// Dapatkan parameter bulan dan tahun
$bulan = isset($_GET['bulan']) ? (int) $_GET['bulan'] : date('n');
$tahun = isset($_GET['tahun']) ? (int) $_GET['tahun'] : date('Y');

// Query untuk mengambil acara
$sql = "SELECT DATE(waktu_acara) as tanggal, judul_acara as judul, desc_acara as deskripsi, DATE_FORMAT(waktu_acara, '%H:%i') as jam
        FROM kalender_acara
        WHERE MONTH(waktu_acara) = $bulan AND YEAR(waktu_acara) = $tahun
        ORDER BY waktu_acara";

$result = $conn->query($sql);

// Jika data ditemukan
if ($result->num_rows > 0) {
    $acara = [];
    while ($row = $result->fetch_assoc()) {
        $acara[$row['tanggal']][] = $row;
    }
    echo json_encode($acara);
} else {
    echo json_encode([]);
}

$conn->close();
?>
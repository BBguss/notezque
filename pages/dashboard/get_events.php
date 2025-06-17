<?php
include '../../config/koneksi.php';
include '../../config/session.php';
// Dapatkan parameter bulan dan tahun
$bulan = isset($_GET['bulan']) ? (int) $_GET['bulan'] : date('n');
$tahun = isset($_GET['tahun']) ? (int) $_GET['tahun'] : date('Y');
$id_user = $_SESSION['id_user'];
$admin = $_SESSION['username'];

// Query untuk mengambil acara
if ($admin == 'admin') {
    $sql = "SELECT DATE(waktu_acara) as tanggal, judul_acara as judul, desc_acara as deskripsi, DATE_FORMAT(waktu_acara, '%H:%i') as jam
        FROM kalender_acara
        WHERE MONTH(waktu_acara) = $bulan AND YEAR(waktu_acara) = $tahun
        ORDER BY waktu_acara";
} else {
    $sql = "SELECT id_user, DATE(waktu_acara) as tanggal, judul_acara as judul, desc_acara as deskripsi, DATE_FORMAT(waktu_acara, '%H:%i') as jam
        FROM kalender_acara
        WHERE id_user = $id_user AND MONTH(waktu_acara) = $bulan AND YEAR(waktu_acara) = $tahun
        ORDER BY waktu_acara";
}

$hasil = $conn->query($sql);

// Jika data ditemukan
if ($hasil->num_rows > 0) {
    $acara = [];
    while ($row = $hasil->fetch_assoc()) {
        $acara[$row['tanggal']][] = $row;
    }
    echo json_encode($acara);
} else {
    echo json_encode([]);
}

$conn->close();
?>

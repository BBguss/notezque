<?php
// filepath: c:\xampp\htdocs\Kelompok_3\pages\dashboard\get_events.php
include '../../config/koneksi.php';
include '../../config/session.php';

// Validasi input
$bulan = isset($_GET['bulan']) ? (int) $_GET['bulan'] : date('n');
$tahun = isset($_GET['tahun']) ? (int) $_GET['tahun'] : date('Y');

// Validasi range bulan dan tahun
if ($bulan < 1 || $bulan > 12) {
    $bulan = date('n');
}

if ($tahun < 1900 || $tahun > 2100) {
    $tahun = date('Y');
}

$id_user = $_SESSION['id_user'];
$username = $_SESSION['username'];

// Query untuk mengambil acara berdasarkan role
if ($username == 'admin') {
    $sql = "SELECT DATE(waktu_acara) as tanggal, judul_acara as judul, desc_acara as deskripsi, DATE_FORMAT(waktu_acara, '%H:%i') as jam
            FROM kalender_acara
            WHERE MONTH(waktu_acara) = $bulan AND YEAR(waktu_acara) = $tahun
            ORDER BY waktu_acara";
} else {
    $sql = "SELECT DATE(waktu_acara) as tanggal, judul_acara as judul, desc_acara as deskripsi, DATE_FORMAT(waktu_acara, '%H:%i') as jam
            FROM kalender_acara
            WHERE id_user = $id_user AND MONTH(waktu_acara) = $bulan AND YEAR(waktu_acara) = $tahun
            ORDER BY waktu_acara";
}

$hasil = mysqli_query($conn, $sql);

// Set header untuk JSON
header('Content-Type: application/json');

// Jika query berhasil
if ($hasil) {
    if (mysqli_num_rows($hasil) > 0) {
        $acara = array();
        while ($row = mysqli_fetch_assoc($hasil)) {
            // Sanitize output
            $tanggal = $row['tanggal'];
            $acara[$tanggal][] = array(
                'judul' => htmlspecialchars($row['judul']),
                'deskripsi' => htmlspecialchars($row['deskripsi'] ?: 'Tidak ada deskripsi'),
                'jam' => $row['jam']
            );
        }
        echo json_encode($acara);
    } else {
        echo json_encode(array());
    }
} else {
    // Jika query error
    echo json_encode(array(
        'error' => 'Database error: ' . mysqli_error($conn)
    ));
}

mysqli_close($conn);
?>
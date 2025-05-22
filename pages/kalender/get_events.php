<?php
include '../../config/koneksi.php';
include '../../config/session.php';

// Ambil id_user dari session
$id_user = $_SESSION['id_user'];
$admin = $_SESSION['username']; // Nama pengguna yang login

// Cek jika pengguna adalah admin
if ($admin === 'admin') {
    $sql = "SELECT * FROM kalender_acara ORDER BY waktu_acara ASC";  // Pastikan nama tabel benar
} else {
    $sql = "SELECT id_acara, judul_acara, desc_acara, waktu_acara 
              FROM kalender_acara 
              WHERE id_user = $id_user
              ORDER BY waktu_acara ASC";
}

// Jalankan query
$hasil = mysqli_query($conn, $sql);

$events = [];
while ($row = mysqli_fetch_assoc($hasil)) {
    $events[] = $row;
}

echo json_encode($events);
?>
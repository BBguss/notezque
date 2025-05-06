<?php
include '../../config/koneksi.php';
include '../../config/session.php';

header('Content-Type: application/json');

// Ambil id_user dari session
$id_user = $_SESSION['id_user'];
$admin = $_SESSION['username']; // Nama pengguna yang login

// Cek jika pengguna adalah admin
if ($admin === 'admin') {
    $query = "SELECT * FROM kalender_acara ORDER BY waktu_acara ASC";  // Pastikan nama tabel benar
} else {
    $query = "SELECT id_acara, judul_acara, desc_acara, waktu_acara 
              FROM kalender_acara 
              WHERE id_user = $id_user
              ORDER BY waktu_acara ASC";
}

// Jalankan query
$result = mysqli_query($conn, $query);

$events = [];
while ($row = mysqli_fetch_assoc($result)) {
    $events[] = $row;
}

echo json_encode($events);
?>
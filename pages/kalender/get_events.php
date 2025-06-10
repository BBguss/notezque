<?php

include '../../config/koneksi.php';
include '../../config/session.php';

// Set timezone untuk PHP (sesuaikan dengan timezone Anda)
date_default_timezone_set('Asia/Jakarta');

// Set timezone untuk MySQL connection
mysqli_query($conn, "SET time_zone = '+07:00'");

// Ambil id_user dari session
$id_user = $_SESSION['id_user'];
$admin = $_SESSION['username']; // Nama pengguna yang login

// Cek jika pengguna adalah admin
if ($admin === 'admin') {
    // disini digunakan fungsi untuk mengatur timezone agar semua data memiliki timezone waktu yang sama
    $sql = "SELECT id_acara, judul_acara, desc_acara, 
                   DATE_FORMAT(waktu_acara, '%Y-%m-%d %H:%i:%s') as waktu_acara,
                   reminder_enabled,
                   CASE 
                       WHEN reminder_time IS NOT NULL 
                       THEN DATE_FORMAT(reminder_time, '%Y-%m-%d %H:%i:%s')
                       ELSE NULL 
                   END as reminder_time
            FROM kalender_acara 
            ORDER BY waktu_acara ASC";  
} else {
    $sql = "SELECT id_acara, judul_acara, desc_acara, 
                   DATE_FORMAT(waktu_acara, '%Y-%m-%d %H:%i:%s') as waktu_acara,
                   reminder_enabled,
                   CASE 
                       WHEN reminder_time IS NOT NULL 
                       THEN DATE_FORMAT(reminder_time, '%Y-%m-%d %H:%i:%s')
                       ELSE NULL 
                   END as reminder_time
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

// Set header untuk timezone
header('Content-Type: application/json; charset=utf-8');
echo json_encode($events);
?>

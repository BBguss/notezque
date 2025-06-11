<?php
// filepath: c:\xampp\htdocs\Kelompok_3\pages\kalender\get_events.php
session_start();
include '../../config/koneksi.php';

if (!isset($_SESSION['is_login']) || !$_SESSION['is_login']) {
    echo json_encode([]);
    exit;
}

// Set timezone
date_default_timezone_set('Asia/Jakarta');
mysqli_query($conn, "SET time_zone = '+07:00'");

// Ambil data user dari session
$user_id = $_SESSION['id_user'];
$username = $_SESSION['username'] ?? '';

try {
    // Query berdasarkan role user
    if ($username === 'admin') {
        // Admin dapat melihat semua acara
        $sql = "SELECT id_acara, judul_acara, desc_acara, 
                       DATE_FORMAT(waktu_acara, '%Y-%m-%d %H:%i:%s') as waktu_acara,
                       prioritas,
                       reminder_enabled,
                       CASE 
                           WHEN reminder_time IS NOT NULL 
                           THEN DATE_FORMAT(reminder_time, '%Y-%m-%d %H:%i:%s')
                           ELSE NULL 
                       END as reminder_time
                FROM kalender_acara 
                ORDER BY waktu_acara ASC";
        $result = mysqli_query($conn, $sql);
    } else {
        // User biasa hanya melihat acara miliknya
        $sql = "SELECT id_acara, judul_acara, desc_acara, 
                       DATE_FORMAT(waktu_acara, '%Y-%m-%d %H:%i:%s') as waktu_acara,
                       prioritas,
                       reminder_enabled,
                       CASE 
                           WHEN reminder_time IS NOT NULL 
                           THEN DATE_FORMAT(reminder_time, '%Y-%m-%d %H:%i:%s')
                           ELSE NULL 
                       END as reminder_time
                FROM kalender_acara 
                WHERE id_user = ?
                ORDER BY waktu_acara ASC";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    }

    // Kumpulkan data events
    $events = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $events[] = $row;
    }

    // Set header dan return JSON
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($events);
    
} catch (Exception $e) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'Gagal mengambil data: ' . $e->getMessage()]);
} finally {
    mysqli_close($conn);
}
?>

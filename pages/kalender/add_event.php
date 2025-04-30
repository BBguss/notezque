<?php
session_start();
require_once '../../koneksi.php'; // Sesuaikan dengan lokasi file koneksi Anda

header('Content-Type: application/json');

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

$judul = mysqli_real_escape_string($conn, $data['judul_acara']);
$deskripsi = mysqli_real_escape_string($conn, $data['desc_acara']);
$waktu = mysqli_real_escape_string($conn, $data['waktu_acara']);

$query = "INSERT INTO kalender_acara (judul_acara, desc_acara, waktu_acara) 
          VALUES ('$judul', '$deskripsi', '$waktu')";

if (mysqli_query($conn, $query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
}
?>
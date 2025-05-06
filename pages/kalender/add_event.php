<?php
session_start();
include '../../config/koneksi.php';

header('Content-Type: application/json');

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

$id_user = $_SESSION['id_user'];
$admin = $_SESSION['username'];
$judul = mysqli_real_escape_string($conn, $data['judul_acara']);
$deskripsi = mysqli_real_escape_string($conn, $data['desc_acara']);
$waktu = mysqli_real_escape_string($conn, $data['waktu_acara']);

if ($admin == 'admin') {
    $query = "INSERT INTO kalender_acara (judul_acara, desc_acara, waktu_acara) 
          VALUES ('$judul', '$deskripsi', '$waktu')";
} else {
    $query = "INSERT INTO kalender_acara (id_user, judul_acara, desc_acara, waktu_acara) 
          VALUES ('$id_user', '$judul', '$deskripsi', '$waktu')";
}


if (mysqli_query($conn, $query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
}
?>
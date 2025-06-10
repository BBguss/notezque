<?php
session_start();
include '../../config/koneksi.php';

if (!isset($_SESSION['is_login']) || !$_SESSION['is_login']) {
    echo '{"success":false,"message":"Harus login dulu"}';
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$user_id = $_SESSION['id_user'];
$judul = $data['judul_acara'];
$deskripsi = $data['desc_acara'];
$waktu = $data['waktu_acara'];
$prioritas = isset($data['prioritas']) ? $data['prioritas'] : 'rendah';

$sql = "INSERT INTO kalender_acara (id_user, judul_acara, desc_acara, waktu_acara, prioritas) 
        VALUES ('$user_id', '$judul', '$deskripsi', '$waktu', '$prioritas')";

$hasil = mysqli_query($conn, $sql);

if ($hasil) {
    echo json_encode(["success" => true, "message" => "Acara berhasil ditambahkan!"]);
} else {
    echo json_encode(["success" => false, "message" => "Gagal menambahkan acara."]);
}

mysqli_close($conn);
?>
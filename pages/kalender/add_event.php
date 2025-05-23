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

if ($_SESSION['username'] == 'admin') {
    $sql = "INSERT INTO kalender_acara (judul_acara, desc_acara, waktu_acara) 
              VALUES ('$judul', '$deskripsi', '$waktu')";
} else {
    $sql = "INSERT INTO kalender_acara (id_user, judul_acara, desc_acara, waktu_acara) 
              VALUES ('$user_id', '$judul', '$deskripsi', '$waktu')";
}

$hasil = mysqli_query($conn, $sql);

if ($hasil) {
    echo '{"Sukses"}';
} else {
    echo '{"Gagal menambah acara"}';
}

mysqli_close($conn);
?>
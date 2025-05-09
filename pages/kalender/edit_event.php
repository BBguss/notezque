<?php
session_start();
require '../../config/koneksi.php';

// Cek login
if (!isset($_SESSION['id_user'])) {
    echo '{"success":false,"message":"Harus login dulu"}';
    exit;
}

// Ambil data dari input
$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['id']) || empty($data['judul_acara']) || empty($data['waktu_acara'])) {
    echo '{"Data Tidak lengkap"}';
    exit;
}

// Ambil nilai dari data
$id = $data['id'];
$judul = $data['judul_acara'];
$deskripsi = $data['desc_acara'] ?? ''; 
$waktu = $data['waktu_acara'];
$user_id = $_SESSION['id_user'];

// Update database
$sql = "UPDATE kalender_acara SET 
          judul_acara = '$judul', 
          desc_acara = '$deskripsi', 
          waktu_acara = '$waktu' 
          WHERE id_acara = $id AND id_user = $user_id";

$hasil = mysqli_query($conn, $sql);

// Beri respon
if ($hasil && mysqli_affected_rows($conn) > 0) {
    echo '{"Update berhasil"}';
} else {
    echo '{"Gagal update"}';
}

// Tutup koneksi
mysqli_close($conn);
?>
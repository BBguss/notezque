<?php
session_start();
include '../../config/koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] != true) {
    echo '{"success":false,"message":"Harus login dulu"}';
    exit;
}

// Ambil data dari input
$data = json_decode(file_get_contents('php://input'), true);

// Pastikan ID acara ada
if (empty($data['id'])) {
    echo '{"success":false,"message":"ID acara tidak ada"}';
    exit;
}

// Siapkan query hapus
$id = $data['id'];
$user_id = $_SESSION['id_user'];

if ($_SESSION['username'] == 'admin') {
    $query = "DELETE FROM kalender_acara WHERE id_acara = $id";
}
else {
    $query = "DELETE FROM kalender_acara WHERE id_acara = $id AND id_user = $user_id";
}

$result = mysqli_query($conn, $query);

if ($result) {
    echo '{"success":true}';
} else {
    echo '{"success":false,"message":"Gagal menghapus"}';
}

// Tutup koneksi
mysqli_close($conn);
?>
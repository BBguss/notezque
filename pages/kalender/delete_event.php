<?php
session_start();
include '../../config/koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] != true) {
    echo '{"Harap login terlebih dahulu"}';
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['id'])) {
    echo '{"success":false,"message":"ID acara tidak ada"}';
    exit;
}

$id = $data['id'];
$user_id = $_SESSION['id_user'];

if ($_SESSION['username'] == 'admin') {
    $sql = "DELETE FROM kalender_acara WHERE id_acara = $id";
}
else {
    $sql = "DELETE FROM kalender_acara WHERE id_acara = $id AND id_user = $user_id";
}

$hasil = mysqli_query($conn, $sql);

if ($hasil) {
    echo '{"Sukses"}';
} else {
    echo '{"Gagal menghapus acara"}';
}

// Tutup koneksi
mysqli_close($conn);
?>
<?php
// hapus_user.php
include './config/koneksi.php';

// Ambil ID dari URL
$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM tugas WHERE id_user = $id");
mysqli_query($conn, "DELETE FROM reset_password_requests WHERE id_user = $id");
$hasil = mysqli_query($conn, "DELETE FROM users WHERE id_user = $id");

if ($hasil) {
    header("Location: http://localhost/Kelompok_3/admin_dashboard.php");
} else {
    header("Location: http://localhost/Kelompok_3/admin_dashboard.php");
}
?>
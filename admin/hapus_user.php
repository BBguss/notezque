<?php
// hapus_user.php
include '../config/koneksi.php';

// Ambil ID dari URL
$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM tugas WHERE id_user = $id");
mysqli_query($conn, "DELETE FROM reset_password_requests WHERE id_user = $id");
$hasil = mysqli_query($conn, "DELETE FROM users WHERE id_user = $id");

if ($hasil) {
    echo "<script>alert('Berhasil hapus');window.location.href = 'admin_dashboard.php'</script>";
} else {
    echo "<script>alert('Gagal hapus');window.location.href = 'admin_dashboard.php</script>";
}

?>
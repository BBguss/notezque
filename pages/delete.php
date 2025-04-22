<?php
include 'koneksi.php';
$id = $_GET['id_tugas'];

$sql = "DELETE FROM produk WHERE id_tugas=$id";
if (mysqli_query($conn, $sql)) {
    echo "<script>
            alert('Produk berhasil dihapus.');
            window.location.href = 'index.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal menghapus produk: " . mysqli_error($conn) . "');
            window.location.href = 'index.php';
          </script>";
}
?>
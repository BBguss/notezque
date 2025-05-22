<?php
include '../../config/koneksi.php';

$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'];
$judul = $data['judul_acara'];
$deskripsi = $data['desc_acara'];
$waktu = $data['waktu_acara'];

$query = "UPDATE kalender_acara SET 
          judul_acara = '$judul', 
          desc_acara = '$deskripsi', 
          waktu_acara = '$waktu' 
          WHERE id_acara = $id";

$result = mysqli_query($conn, $query);

if ($result) {
    echo '{"status":"sukses", "pesan":"Acara berhasil diupdate"}';
} else {
    echo '{"status":"gagal", "pesan":"Gagal mengupdate acara"}';
}

mysqli_close($conn);
?>
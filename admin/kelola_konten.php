<?php
include '../config/koneksi.php';

if (isset($_POST['submit'])) {
    $namaFile = $_FILES['logo']['name'];
    $tmpName = $_FILES['logo']['tmp_name'];
    $folder = '../asset/images/'; // folder penyimpanan gambar
    $path = $folder . $namaFile;

    // Simpan file ke folder
    if (move_uploaded_file($tmpName, $path)) {
        // Simpan nama file ke database
        $query = "UPDATE konten SET gambar = '$namaFile' WHERE konten_key = 'logo_header'";
        mysqli_query($conn, $query);
        echo "Logo berhasil diupdate.";
    } else {
        echo "Upload gagal.";
    }
}
?>
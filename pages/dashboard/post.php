<?php
include '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['logo'])) {
    $file_name = $_FILES['logo']['name'];
    $file_tmp = $_FILES['logo']['tmp_name'];
    $folder = '../../asset/images/';

    if ($file_name !== '') {
        move_uploaded_file($file_tmp, $folder . $file_name);

        // Update logo di database
        $update = mysqli_query($conn, "
            UPDATE konten_statis 
            SET gambar = '$file_name', updated_at = NOW() 
            WHERE konten_key = 'logo' AND nama_halaman = 'dashboard'
        ");

        if ($update) {
            header("Location: 5Dashboard.php?pesan=logo_updated");
        } else {
            echo "Gagal update database.";
        }
    } else {
        echo "File tidak valid.";
    }
}
?>
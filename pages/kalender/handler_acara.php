<?php
// filepath: c:\xampp\htdocs\Kelompok_3\pages\kalender\handler_acara.php
session_start();
include '../../config/koneksi.php';

// Cek session login
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header('Location: ../2loginpage.php');
    exit();
}

// Function untuk membersihkan input
function bersihkanInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Handle save event (tambah acara)
if (isset($_POST['save'])) {
    $judul_acara = bersihkanInput($_POST['title']);
    $desc_acara = bersihkanInput($_POST['desk']);
    $tanggal = bersihkanInput($_POST['tanggal']);
    $waktu = bersihkanInput($_POST['waktu']);
    $id_user = $_SESSION['id_user'];

    // Jika waktu kosong, set default 00:00
    if (empty($waktu)) {
        $waktu = '00:00';
    }

    // Gabungkan tanggal dan waktu
    $waktu_acara = $tanggal . ' ' . $waktu . ':00';

    // Validasi input
    if (empty($judul_acara)) {
        echo "<script>alert('Judul acara harus diisi!'); window.history.back();</script>";
        exit();
    }

    if (empty($tanggal)) {
        echo "<script>alert('Tanggal harus dipilih!'); window.history.back();</script>";
        exit();
    }

    // Query insert ke database
    $sql_insert = "INSERT INTO kalender_acara (id_user, judul_acara, desc_acara, waktu_acara) 
                   VALUES ('$id_user', '$judul_acara', '$desc_acara', '$waktu_acara')";

    $result = mysqli_query($conn, $sql_insert);

    if ($result) {
        echo "<script>alert('Acara berhasil ditambahkan!'); window.location.href='6kalender.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan acara: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }
    exit();
}

// Handle edit event (edit acara)
if (isset($_POST['edit'])) {
    $id_acara = bersihkanInput($_POST['id_acara']);
    $judul_acara = bersihkanInput($_POST['title']);
    $desc_acara = bersihkanInput($_POST['desk']);
    $tanggal = bersihkanInput($_POST['tanggal']);
    $waktu = bersihkanInput($_POST['waktu']);
    $id_user = $_SESSION['id_user'];

    // Jika waktu kosong, set default 00:00
    if (empty($waktu)) {
        $waktu = '00:00';
    }

    // Gabungkan tanggal dan waktu
    $waktu_acara = $tanggal . ' ' . $waktu . ':00';

    // Validasi input
    if (empty($id_acara)) {
        echo "<script>alert('ID acara tidak valid!'); window.history.back();</script>";
        exit();
    }

    if (empty($judul_acara)) {
        echo "<script>alert('Judul acara harus diisi!'); window.history.back();</script>";
        exit();
    }

    if (empty($tanggal)) {
        echo "<script>alert('Tanggal harus dipilih!'); window.history.back();</script>";
        exit();
    }

    // Cek apakah acara milik user
    $sql_check = "SELECT id_acara FROM kalender_acara WHERE id_acara = '$id_acara' AND id_user = '$id_user'";
    $result_check = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result_check) == 0) {
        echo "<script>alert('Acara tidak ditemukan atau bukan milik Anda!'); window.location.href='6kalender.php';</script>";
        exit();
    }

    // Query update database
    $sql_update = "UPDATE kalender_acara 
                   SET judul_acara = '$judul_acara', desc_acara = '$desc_acara', waktu_acara = '$waktu_acara' 
                   WHERE id_acara = '$id_acara' AND id_user = '$id_user'";

    $result = mysqli_query($conn, $sql_update);

    if ($result) {
        echo "<script>alert('Acara berhasil diupdate!'); window.location.href='6kalender.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate acara: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }
    exit();
}

// Handle delete event (hapus acara)
if (isset($_POST['hapus'])) {
    $id_acara = bersihkanInput($_POST['id_acara']);
    $id_user = $_SESSION['id_user'];

    // Validasi input
    if (empty($id_acara)) {
        echo "<script>alert('ID acara tidak valid!'); window.history.back();</script>";
        exit();
    }

    // Ambil nama acara untuk pesan konfirmasi
    $sql_get = "SELECT judul_acara FROM kalender_acara WHERE id_acara = '$id_acara' AND id_user = '$id_user'";
    $result_get = mysqli_query($conn, $sql_get);

    if (mysqli_num_rows($result_get) == 0) {
        echo "<script>alert('Acara tidak ditemukan atau bukan milik Anda!'); window.location.href='6kalender.php';</script>";
        exit();
    }

    $event_data = mysqli_fetch_assoc($result_get);

    // Query delete dari database
    $sql_delete = "DELETE FROM kalender_acara WHERE id_acara = '$id_acara' AND id_user = '$id_user'";
    $result = mysqli_query($conn, $sql_delete);

    if ($result) {
        $deleted_title = $event_data['judul_acara'];
        echo "<script>alert('Acara \"$deleted_title\" berhasil dihapus!'); window.location.href='6kalender.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus acara: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }
    exit();
}

// Jika tidak ada action yang valid, redirect ke kalender
header('Location: 6kalender.php');
exit();
?>
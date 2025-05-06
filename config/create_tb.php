<?php
include 'koneksi.php';

// Buat tabel users
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_users) === TRUE) {
    echo "Tabel user berhasil dibuat.<br>";
} else {
    echo "Error membuat tabel user: " . $conn->error . "<br>";
}

// Buat tabel tugas
$sql_tugas = "CREATE TABLE IF NOT EXISTS tugas (
    id_tugas INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL FOREIGN KEY,
    judul_tugas VARCHAR(100) NOT NULL,
    matkul VARCHAR(100) NOT NULL,
    desc_tugas TEXT NOT NULL,
    deadline DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_tugas) === TRUE) {
    echo "Tabel tugas berhasil dibuat.<br>";
} else {
    echo "Error membuat tabel tugas: " . $conn->error . "<br>";
}

$sql_kalender_acara = "CREATE TABLE IF NOT EXISTS kalender_acara (
    id_acara INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL FOREIGN KEY,
    judul_acara VARCHAR(100) NOT NULL,
    desc_acara TEXT NOT NULL,
    waktu_acara DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_kalender_acara) === TRUE) {
    echo "Tabel kalender_acara berhasil dibuat.<br>";
} else {
    echo "Error membuat tabel kalender_acara: " . $conn->error . "<br>";
}

$sql_tambahFolder = "CREATE TABLE IF NOT EXISTS tambahFolder (
    id_folder INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL FOREIGN KEY,
    nama_materi VARCHAR(100) NOT NULL,
    nama_pengajar VARCHAR(100) NOT NULL,
    gambar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_tambahFolder) === TRUE) {
    echo "Tabel tambahFolder berhasil dibuat.<br>";
} else {
    echo "Error membuat tabel tambahFolder: " . $conn->error . "<br>";
}

$sql_tambahFile = "CREATE TABLE IF NOT EXISTS tambahFile (
    id_file INT AUTO_INCREMENT PRIMARY KEY,
    nama_file VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_tambahFile) === TRUE) {
    echo "Tabel tambahFile berhasil dibuat.<br>";
} else {
    echo "Error membuat tabel tambahFile: " . $conn->error . "<br>";
}

$conn->close();
?>

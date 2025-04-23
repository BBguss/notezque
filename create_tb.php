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

$conn->close();
?>

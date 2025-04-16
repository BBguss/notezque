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

$conn->close();
?>
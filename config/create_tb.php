<?php
include 'koneksi.php';

// Tabel users
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
echo $conn->query($sql_users) ? "Tabel users berhasil dibuat.<br>" : "Error: " . $conn->error . "<br>";

// Tabel tugas
$sql_tugas = "CREATE TABLE IF NOT EXISTS tugas (
    id_tugas INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    judul_tugas VARCHAR(100) NOT NULL,
    matkul VARCHAR(100) NOT NULL,
    desc_tugas TEXT NOT NULL,
    deadline1 DATE NOT NULL,
    deadline2 TIME NOT NULL,
    status VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user)
)";
echo $conn->query($sql_tugas) ? "Tabel tugas berhasil dibuat.<br>" : "Error: " . $conn->error . "<br>";

// Tabel kalender_acara
$sql_kalender_acara = "CREATE TABLE IF NOT EXISTS kalender_acara (
    id_acara INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    judul_acara VARCHAR(100) NOT NULL,
    desc_acara TEXT NOT NULL,
    waktu_acara DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user)
)";
echo $conn->query($sql_kalender_acara) ? "Tabel kalender_acara berhasil dibuat.<br>" : "Error: " . $conn->error . "<br>";

// Tabel tambahFolder
$sql_tambahFolder = "CREATE TABLE IF NOT EXISTS tambahFolder (
    id_folder INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    nama_materi VARCHAR(100) NOT NULL,
    nama_pengajar VARCHAR(100) NOT NULL,
    gambar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user)
)";
echo $conn->query($sql_tambahFolder) ? "Tabel tambahFolder berhasil dibuat.<br>" : "Error: " . $conn->error . "<br>";

// Tabel tambahFile
$sql_tambahFile = "CREATE TABLE IF NOT EXISTS tambahFile (
    id_file INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_folder INT NOT NULL,
    nama_file VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_folder) REFERENCES tambahFolder(id_folder)
)";
echo $conn->query($sql_tambahFile) ? "Tabel tambahFile berhasil dibuat.<br>" : "Error: " . $conn->error . "<br>";

// Tabel reset_password_requests
$sql_reset_password_requests = "CREATE TABLE IF NOT EXISTS reset_password_requests (
    id_user INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user)
)";
echo $conn->query($sql_reset_password_requests) ? "Tabel reset_password_requests berhasil dibuat.<br>" : "Error: " . $conn->error . "<br>";

// Tabel kolaborasi
$sql_kolaborasi = "CREATE TABLE IF NOT EXISTS kolaborasi (
    id_collab INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_tugas INT NOT NULL,
    collaborator VARCHAR(225) NOT NULL,
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_tugas) REFERENCES tugas(id_tugas)
)";
echo $conn->query($sql_kolaborasi) ? "Tabel kolaborasi berhasil dibuat.<br>" : "Error: " . $conn->error . "<br>";

// Tabel konten_statis
$sql_konten_statis = "CREATE TABLE IF NOT EXISTS konten_statis (
    id_konten INT AUTO_INCREMENT PRIMARY KEY,
    nama_halaman VARCHAR(100) NOT NULL,
    section VARCHAR(100),
    deskripsi TEXT,
    gambar VARCHAR(255),
    keterangan VARCHAR(225)
)";
echo $conn->query($sql_konten_statis) ? "Tabel konten_statis berhasil dibuat.<br>" : "Error: " . $conn->error . "<br>";

$conn->close();
?>
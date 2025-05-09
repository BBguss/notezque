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

if ($conn->query($sql_tugas) === TRUE) {
    echo "Tabel tugas berhasil dibuat.<br>";
} else {
    echo "Error membuat tabel tugas: " . $conn->error . "<br>";
}

// Buat tabel kalender_acara
$sql_kalender_acara = "CREATE TABLE IF NOT EXISTS kalender_acara (
    id_acara INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    judul_acara VARCHAR(100) NOT NULL,
    desc_acara TEXT NOT NULL,
    waktu_acara DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user)
)";

if ($conn->query($sql_kalender_acara) === TRUE) {
    echo "Tabel kalender_acara berhasil dibuat.<br>";
} else {
    echo "Error membuat tabel kalender_acara: " . $conn->error . "<br>";
}

// Buat tabel tambahFolder
$sql_tambahFolder = "CREATE TABLE IF NOT EXISTS tambahFolder (
    id_folder INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    nama_materi VARCHAR(100) NOT NULL,
    nama_pengajar VARCHAR(100) NOT NULL,
    gambar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user)
)";

if ($conn->query($sql_tambahFolder) === TRUE) {
    echo "Tabel tambahFolder berhasil dibuat.<br>";
} else {
    echo "Error membuat tabel tambahFolder: " . $conn->error . "<br>";
}

// Buat tabel tambahFile
$sql_tambahFile = "CREATE TABLE IF NOT EXISTS tambahFile (
    id_file INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_folder INT NOT NULL,
    nama_file VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_folder) REFERENCES tambahFolder(id_folder)
)";

if ($conn->query($sql_tambahFile) === TRUE) {
    echo "Tabel tambahFile berhasil dibuat.<br>";
} else {
    echo "Error membuat tabel tambahFile: " . $conn->error . "<br>";
}

$sql_reser_password_requests = "CREATE TABLE IF NOT EXISTS `reset_password_requests` (
    `id_user` int(11) NOT NULL,
    `token` int(255) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    FOREIGN KEY (id_user) REFERENCES users(id_user)
  )";

if ($conn->query($sql_reser_password_requests) === TRUE) {
    echo "Tabel reset_password_requests berhasil dibuat.<br>";
} else {
    echo "Error membuat tabel reset_password_requests: " . $conn->error . "<br>";
}
$conn->close();
?>
<?php
include 'koneksi.php';

// Tabel users
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    quiz_question VARCHAR(255) NOT NULL,
    quiz_answer VARCHAR(220) NOT NULL,
    aktivitas_terakhir DATETIME,
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
    reminder_enabled BOOLEAN DEFAULT FALSE,
    reminder_time DATETIME NULL,
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
    reminder_enabled BOOLEAN DEFAULT FALSE,
    reminder_time DATETIME NULL,
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

// Tabel notifications
$sql_notifications = "CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50) NOT NULL,
    reference_id INT NULL,
    scheduled_time DATETIME NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user)
)";
echo $conn->query($sql_notifications) ? "Tabel notifications berhasil dibuat.<br>" : "Error: " . $conn->error . "<br>";

// Tabel reminder_templates
$sql_reminder_templates = "CREATE TABLE IF NOT EXISTS reminder_templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    minutes_before INT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
echo $conn->query($sql_reminder_templates) ? "Tabel reminder_templates berhasil dibuat.<br>" : "Error: " . $conn->error . "<br>";

// Insert data default untuk reminder_templates
$insert_templates = "INSERT IGNORE INTO reminder_templates (id, name, minutes_before, is_active) VALUES 
    (1, '5 menit sebelum', 5, 1),
    (2, '10 menit sebelum', 10, 1),
    (3, '30 menit sebelum', 30, 1),
    (4, '1 jam sebelum', 60, 1),
    (5, '1 hari sebelum', 1440, 1),
    (6, '1 minggu sebelum', 10080, 1)";
echo $conn->query($insert_templates) ? "Data reminder_templates berhasil diinsert.<br>" : "Error: " . $conn->error . "<br>";


$conn->close();
?>
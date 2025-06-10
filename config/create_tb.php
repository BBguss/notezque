<?php 
include 'koneksi.php';

// Buat tabel users (sama seperti sebelumnya)
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

if ($conn->query($sql_users) === TRUE) {
    echo "Tabel user berhasil dibuat.<br>";
} else {
    echo "Error membuat tabel user: " . $conn->error . "<br>";
}

// Modifikasi tabel tugas - tambahkan kolom reminder
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

if ($conn->query($sql_tugas) === TRUE) {
    echo "Tabel tugas berhasil dibuat.<br>";
} else {
    echo "Error membuat tabel tugas: " . $conn->error . "<br>";
}

// Modifikasi tabel kalender_acara - tambahkan kolom reminder
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

if ($conn->query($sql_kalender_acara) === TRUE) {
    echo "Tabel kalender_acara berhasil dibuat.<br>";
} else {
    echo "Error membuat tabel kalender_acara: " . $conn->error . "<br>";
}

// Tabel baru: notifications untuk menyimpan semua notifikasi
$sql_notifications = "CREATE TABLE IF NOT EXISTS notifications (
    id_notification INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('tugas', 'acara') NOT NULL,
    reference_id INT NOT NULL, -- id_tugas atau id_acara
    scheduled_time DATETIME NOT NULL,
    is_sent BOOLEAN DEFAULT FALSE,
    is_read BOOLEAN DEFAULT FALSE,
    sent_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    INDEX idx_scheduled_time (scheduled_time),
    INDEX idx_is_sent (is_sent)
)";

if ($conn->query($sql_notifications) === TRUE) {
    echo "Tabel notifications berhasil dibuat.<br>";
} else {
    echo "Error membuat tabel notifications: " . $conn->error . "<br>";
}



// Tabel baru: reminder_templates untuk template pengingat yang bisa dipilih user
$sql_reminder_templates = "CREATE TABLE IF NOT EXISTS reminder_templates (
    id_template INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    minutes_before INT NOT NULL,
    description VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_reminder_templates) === TRUE) {
    echo "Tabel reminder_templates berhasil dibuat.<br>";
} else {
    echo "Error membuat tabel reminder_templates: " . $conn->error . "<br>";
}

// Insert default reminder templates
$default_templates = [
    ['5 Menit Sebelumnya', 5, 'Pengingat 5 menit sebelum acara'],
    ['15 Menit Sebelumnya', 15, 'Pengingat 15 menit sebelum acara'],
    ['30 Menit Sebelumnya', 30, 'Pengingat 30 menit sebelum acara'],
    ['1 Jam Sebelumnya', 60, 'Pengingat 1 jam sebelum acara'],
    ['2 Jam Sebelumnya', 120, 'Pengingat 2 jam sebelum acara'],
    ['1 Hari Sebelumnya', 1440, 'Pengingat 1 hari sebelum acara'],
    ['1 Minggu Sebelumnya', 10080, 'Pengingat 1 minggu sebelum acara']
];

foreach ($default_templates as $template) {
    $stmt = $conn->prepare("INSERT IGNORE INTO reminder_templates (name, minutes_before, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $template[0], $template[1], $template[2]);
    $stmt->execute();
}

// Tabel yang sudah ada tetap sama
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

$sql_reset_password_requests = "CREATE TABLE IF NOT EXISTS reset_password_requests (
    id_user INT(11) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
)";


if ($conn->query($sql_reset_password_requests) === TRUE) {
    echo "Tabel reset_password_requests berhasil dibuat.<br>";
} else {
    echo "Error membuat tabel reset_password_requests: " . $conn->error . "<br>";
}  

$sql_kolaborasi = "CREATE TABLE IF NOT EXISTS kolaborasi (
    id_collab INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_tugas INT NOT NULL,
    collaborator VARCHAR(225) NOT NULL,
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_tugas) REFERENCES tugas(id_tugas)
  )";

if ($conn->query($sql_kolaborasi) === TRUE) {
    echo "Tabel kolaborasi berhasil dibuat.<br>";
} else {
    echo "Error membuat tabel kolaborasi: " . $conn->error . "<br>";
}

$sql_konten_statis = "CREATE TABLE IF NOT EXISTS konten_statis (
    id_konten INT AUTO_INCREMENT PRIMARY KEY,
    nama_halaman VARCHAR(100) NOT NULL,
    section VARCHAR(100),
    deskripsi TEXT,
    gambar VARCHAR(255),
    keterangan VARCHAR(225)
)";

if ($conn->query($sql_konten_statis) === TRUE) {
    echo "Tabel konten_statis berhasil dibuat.<br>";
} else {
    echo "Error membuat tabel konten_statis: " . $conn->error . "<br>";
}

echo "<br>===== DATABASE SETUP COMPLETED =====<br>";
echo "Fitur notifikasi telah ditambahkan ke database Anda!<br>";

$conn->close();
?>

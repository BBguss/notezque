<?php
session_start();
include '../../config/koneksi.php';

// PERBAIKAN: Set timezone untuk PHP dan MySQL
date_default_timezone_set('Asia/Jakarta');
mysqli_query($conn, "SET time_zone = '+07:00'");

header('Content-Type: application/json');

// Cek login
if (!isset($_SESSION['is_login']) || !$_SESSION['is_login']) {
    echo json_encode(['success' => false, 'message' => 'Harus login terlebih dahulu']);
    exit;
}

// Ambil data JSON
$data = json_decode(file_get_contents('php://input'), true);

// Validasi input dasar
if (
    empty($data['judul_acara']) ||
    empty($data['waktu_acara'])
) {
    echo json_encode(['success' => false, 'message' => 'Judul dan waktu acara wajib diisi']);
    exit;
}

// Ambil data
$user_id = $_SESSION['id_user'];
$judul = mysqli_real_escape_string($conn, $data['judul_acara']);
$deskripsi = mysqli_real_escape_string($conn, $data['desc_acara'] ?? '');
$waktu_acara_input = $data['waktu_acara'];
$reminder_enabled = isset($data['reminder_enabled']) && $data['reminder_enabled'] ? 1 : 0;
$reminder_minutes = isset($data['reminder_minutes']) && is_numeric($data['reminder_minutes']) ? intval($data['reminder_minutes']) : null;
$waktu_acara = date('Y-m-d H:i:s', strtotime($waktu_acara_input));

// Debug: Log waktu yang diterima dan yang akan disimpan
if (defined('DEBUG') && DEBUG) {
    error_log("Waktu diterima: " . $waktu_acara_input);
    error_log("Waktu yang akan disimpan: " . $waktu_acara);
}

// Hitung reminder_time jika reminder diaktifkan
$reminder_time = null;
if ($reminder_enabled && $reminder_minutes > 0) {
    $reminder_timestamp = strtotime($waktu_acara) - ($reminder_minutes * 60);
    $reminder_time = date("Y-m-d H:i:s", $reminder_timestamp);
    if (defined('DEBUG') && DEBUG) {
        error_log("Reminder time: " . $reminder_time);
    }
}

// Buat query berdasarkan role
try {
    if ($_SESSION['username'] == 'admin') {
        $stmt = $conn->prepare("INSERT INTO kalender_acara (id_user, judul_acara, desc_acara, waktu_acara, reminder_enabled, reminder_time) VALUES (NULL, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $judul, $deskripsi, $waktu_acara, $reminder_enabled, $reminder_time);
    } else {
        $stmt = $conn->prepare("INSERT INTO kalender_acara (id_user, judul_acara, desc_acara, waktu_acara, reminder_enabled, reminder_time) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssis", $user_id, $judul, $deskripsi, $waktu_acara, $reminder_enabled, $reminder_time);
    }

    // Eksekusi
    if ($stmt->execute()) {
        if ($reminder_enabled && $reminder_time) {
    $title = "Pengingat Acara";
    $message = "Acara '$judul' akan dimulai dalam $reminder_minutes menit";

    $notif_stmt = $conn->prepare("INSERT INTO notifications (id_user, title, message, type, reference_id, scheduled_time) VALUES (?, ?, ?, 'acara', ?, ?)");
    $inserted_id = $conn->insert_id; // ID dari acara yang baru ditambahkan
    $notif_stmt->bind_param("issis", $user_id, $title, $message, $inserted_id, $reminder_time);
    $notif_stmt->execute();
    $notif_stmt->close();
}

        echo json_encode([
            'success' => true, 
            'message' => 'Acara berhasil ditambahkan',
            'debug' => [
                'input_time' => $waktu_acara_input,
                'saved_time' => $waktu_acara,
                'reminder_time' => $reminder_time
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menambah acara', 'error' => $stmt->error]);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

$conn->close();
?>

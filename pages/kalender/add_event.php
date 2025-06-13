<?php
// filepath: c:\xampp\htdocs\Kelompok_3\pages\kalender\add_event.php
session_start();
require_once '../../config/koneksi.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    $judul_acara = trim($_POST['judul_acara'] ?? $_POST['title'] ?? '');
    $desc_acara = trim($_POST['desc_acara'] ?? $_POST['desk'] ?? '');
    $tanggal = $_POST['tanggal'] ?? '';
    $waktu = $_POST['waktu'] ?? '00:00';

    // Validasi input
    if (empty($judul_acara)) {
        throw new Exception('Judul acara harus diisi');
    }

    if (empty($tanggal)) {
        throw new Exception('Tanggal harus diisi');
    }

    // Konversi format tanggal dari DD-MM-YYYY ke MM-DD-YYYY untuk database
    $tanggal_parts = explode('-', $tanggal);
    if (count($tanggal_parts) !== 3) {
        throw new Exception('Format tanggal tidak valid');
    }

    $hari = $tanggal_parts[0];
    $bulan = $tanggal_parts[1];
    $tahun = $tanggal_parts[2];

    // Format untuk database: MM-DD-YYYY
    $tanggal_db = $bulan . '-' . $hari . '-' . $tahun;

    // Format waktu lengkap
    if (empty($waktu) || $waktu === '00:00') {
        $waktu_acara = $tanggal_db . ' 00:00:00';
    } else {
        $waktu_acara = $tanggal_db . ' ' . $waktu . ':00';
    }

    // Insert ke database
    $stmt = $pdo->prepare("INSERT INTO acara (judul_acara, desc_acara, waktu_acara, created_at) VALUES (?, ?, ?, NOW())");
    $result = $stmt->execute([$judul_acara, $desc_acara, $waktu_acara]);

    if ($result) {
        $id_acara = $pdo->lastInsertId();
        echo json_encode([
            'success' => true,
            'message' => 'Acara berhasil ditambahkan',
            'data' => [
                'id_acara' => $id_acara,
                'judul_acara' => $judul_acara,
                'desc_acara' => $desc_acara,
                'waktu_acara' => $waktu_acara
            ]
        ]);
    } else {
        throw new Exception('Gagal menambahkan acara');
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
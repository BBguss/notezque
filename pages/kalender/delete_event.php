<?php
// filepath: c:\xampp\htdocs\Kelompok_3\pages\kalender\delete_event.php
session_start();
require_once '../../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    $id_acara = $_POST['id_acara'] ?? '';

    // Validasi input
    if (empty($id_acara)) {
        throw new Exception('ID acara tidak valid');
    }

    // Cek apakah acara exists
    $stmt = $pdo->prepare("SELECT id_acara, judul_acara FROM acara WHERE id_acara = ?");
    $stmt->execute([$id_acara]);
    $acara = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$acara) {
        throw new Exception('Acara tidak ditemukan');
    }

    // Hapus dari database
    $stmt = $pdo->prepare("DELETE FROM acara WHERE id_acara = ?");
    $result = $stmt->execute([$id_acara]);

    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Acara "' . $acara['judul_acara'] . '" berhasil dihapus'
        ]);
    } else {
        throw new Exception('Gagal menghapus acara');
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
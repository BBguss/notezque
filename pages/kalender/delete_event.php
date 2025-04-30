<?php
session_start();
require_once '../../koneksi.php'; // Sesuaikan dengan lokasi file koneksi Anda

header('Content-Type: application/json');

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$id = intval($data['id']);

$query = "DELETE FROM kalender_acara WHERE id_acara = $id";

if (mysqli_query($conn, $query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
}
?>
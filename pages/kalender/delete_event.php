<?php
session_start();
include '../../config/koneksi.php';

header('Content-Type: application/json');

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$id_user = $_SESSION['id_user'];
$admin = $_SESSION['username'];
$data = json_decode(file_get_contents('php://input'), true);
$id = intval($data['id']);

if ($admin == 'admin') {
    $query = "DELETE FROM kalender_acara WHERE id_acara = $id";
} else {
    $query = "DELETE FROM kalender_acara WHERE id_acara = $id AND id_user = $id_user";
}


if (mysqli_query($conn, $query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
}
?>
<?php
// filepath: c:\xampp\htdocs\Kelompok_3\pages\kalender\filter_events.php
session_start();
include '../../config/koneksi.php';

header('Content-Type: application/json');

if (!isset($_SESSION['is_login']) || !$_SESSION['is_login']) {
    echo json_encode(['success' => false, 'data' => [], 'total' => 0]);
    exit;
}

$prioritas = $_GET['prioritas'] ?? '';
$sort = $_GET['sort'] ?? 'prioritas'; // Ubah default dari 'tanggal_asc' ke 'prioritas'
$user_id = $_SESSION['id_user'];

// Query dasar
$query = "SELECT * FROM kalender_acara WHERE id_user = $user_id";

// Filter prioritas
if (!empty($prioritas)) {
    $query .= " AND prioritas = '$prioritas'";
}

// Sorting
switch ($sort) {
    case 'tanggal_desc':
        $query .= " ORDER BY waktu_acara DESC";
        break;
    case 'tanggal_asc':
        $query .= " ORDER BY waktu_acara ASC";
        break;
    case 'judul':
        $query .= " ORDER BY judul_acara ASC";
        break;
    default: // prioritas (default)
        $query .= " ORDER BY FIELD(prioritas, 'tinggi', 'sedang', 'rendah'), waktu_acara ASC";
        break;
}

$result = mysqli_query($conn, $query);
$events = [];

while ($row = mysqli_fetch_assoc($result)) {
    $events[] = $row;
}

echo json_encode([
    'success' => true,
    'data' => $events,
    'total' => count($events)
]);

mysqli_close($conn);
?>
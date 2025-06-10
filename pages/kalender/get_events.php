<?php
session_start();
include '../../config/koneksi.php';

if (!isset($_SESSION['is_login']) || !$_SESSION['is_login']) {
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION['id_user'];

if ($_SESSION['username']) {
    $sql = "SELECT id_acara, judul_acara, desc_acara, waktu_acara, prioritas FROM kalender_acara WHERE id_user = $user_id ORDER BY waktu_acara ASC";
}

$result = mysqli_query($conn, $sql);
$events = [];

while ($row = mysqli_fetch_assoc($result)) {
    $events[] = $row;
}

echo json_encode($events);
mysqli_close($conn);
?>
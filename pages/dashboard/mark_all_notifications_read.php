<?php
include '../../config/koneksi.php';
include '../../config/session.php';

$id_user = $_SESSION['id_user'];

$sql = "UPDATE notifications SET is_read = 1 WHERE id_user = ? AND scheduled_time <= NOW()";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();

echo json_encode(['success' => true]);
?>

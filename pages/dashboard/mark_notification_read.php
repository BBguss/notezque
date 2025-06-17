<?php
include '../../config/koneksi.php';
include '../../config/session.php';

$data = json_decode(file_get_contents("php://input"), true);
$notificationId = $data['notification_id'];
$id_user = $_SESSION['id_user'];

$sql = "UPDATE notifications SET is_read = 1 WHERE id_notification = ? AND id_user = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $notificationId, $id_user);
$stmt->execute();

echo json_encode(['success' => $stmt->affected_rows > 0]);
?>

<?php
include '../../config/koneksi.php';
include '../../config/session.php';

$id_user = $_SESSION['id_user'];

$update = $conn->prepare("
    UPDATE notifications 
    SET is_sent = 1 
    WHERE id_user = ? AND scheduled_time <= NOW() AND is_sent = 0
");
$update->bind_param("i", $id_user);
$update->execute();



$sql = "SELECT id_notification, title, message, is_read, created_at 
        FROM notifications 
        WHERE id_user = ? 
          AND scheduled_time <= NOW()
        ORDER BY scheduled_time DESC 
        LIMIT 20";



$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
$unread_count = 0;

while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
    if (!$row['is_read']) {
        $unread_count++;
    }
}

echo json_encode([
    'success' => true,
    'unread_count' => $unread_count,
    'notifications' => $notifications
]);
?>

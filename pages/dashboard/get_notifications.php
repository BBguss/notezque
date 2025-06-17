<?php
// filepath: c:\xampp\htdocs\Kelompok_3\pages\dashboard\get_notifications.php
include '../../config/koneksi.php';
include '../../config/session.php';

$id_user = (int) $_SESSION['id_user'];

// Set header untuk JSON
header('Content-Type: application/json');

try {
    // Update status is_sent untuk notifikasi yang sudah waktunya
    $update_sql = "UPDATE notifications 
                   SET is_sent = 1 
                   WHERE id_user = $id_user AND scheduled_time <= NOW() AND is_sent = 0";
    mysqli_query($conn, $update_sql);

    // Ambil notifikasi
    $sql = "SELECT id_notification, title, message, is_read, created_at 
            FROM notifications 
            WHERE id_user = $id_user 
              AND scheduled_time <= NOW()
            ORDER BY created_at DESC 
            LIMIT 20";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        $notifications = array();
        $unread_count = 0;

        while ($row = mysqli_fetch_assoc($result)) {
            $notifications[] = array(
                'id_notification' => (int) $row['id_notification'],
                'title' => htmlspecialchars($row['title']),
                'message' => htmlspecialchars($row['message']),
                'is_read' => (int) $row['is_read'],
                'created_at' => $row['created_at']
            );

            if (!$row['is_read']) {
                $unread_count++;
            }
        }

        echo json_encode(array(
            'success' => true,
            'unread_count' => $unread_count,
            'notifications' => $notifications
        ));
    } else {
        echo json_encode(array(
            'success' => false,
            'error' => 'Database error: ' . mysqli_error($conn)
        ));
    }
} catch (Exception $e) {
    echo json_encode(array(
        'success' => false,
        'error' => 'Server error: ' . $e->getMessage()
    ));
}

mysqli_close($conn);
?>
<?php
// filepath: c:\xampp\htdocs\Kelompok_3\pages\dashboard\mark_notification_read.php
include '../../config/koneksi.php';
include '../../config/session.php';

// Set header untuk JSON
header('Content-Type: application/json');

try {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    if (!$data || !isset($data['notification_id'])) {
        echo json_encode(array('success' => false, 'error' => 'Invalid input'));
        exit();
    }

    $notificationId = (int) $data['notification_id'];
    $id_user = (int) $_SESSION['id_user'];

    if ($notificationId <= 0) {
        echo json_encode(array('success' => false, 'error' => 'Invalid notification ID'));
        exit();
    }

    $sql = "UPDATE notifications 
            SET is_read = 1 
            WHERE id_notification = $notificationId AND id_user = $id_user";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        $affected_rows = mysqli_affected_rows($conn);
        echo json_encode(array('success' => $affected_rows > 0));
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
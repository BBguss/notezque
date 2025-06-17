<?php
// filepath: c:\xampp\htdocs\Kelompok_3\pages\dashboard\mark_all_notifications_read.php
include '../../config/koneksi.php';
include '../../config/session.php';

// Set header untuk JSON
header('Content-Type: application/json');

try {
    $id_user = (int) $_SESSION['id_user'];

    $sql = "UPDATE notifications 
            SET is_read = 1 
            WHERE id_user = $id_user AND scheduled_time <= NOW()";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo json_encode(array('success' => true));
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
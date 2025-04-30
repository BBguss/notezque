<?php
include '../../koneksi.php';

header('Content-Type: application/json');

$query = "SELECT id_acara, judul_acara, desc_acara, waktu_acara 
          FROM kalender_acara 
          ORDER BY waktu_acara ASC";
$result = mysqli_query($conn, $query);

$events = [];
while ($row = mysqli_fetch_assoc($result)) {
    $events[] = $row;
}

echo json_encode($events);
?>
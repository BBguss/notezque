<?php
// filepath: c:\xampp\htdocs\Kelompok_3\pages\kalender\get_events.php
session_start();
require_once '../../config/database.php';

header('Content-Type: application/json');

try {
    $bulan = $_GET['bulan'] ?? null;
    $tahun = $_GET['tahun'] ?? null;
    $tanggal = $_GET['tanggal'] ?? null;

    $sql = "SELECT id_acara, judul_acara, desc_acara, waktu_acara FROM acara";
    $params = [];

    if ($tanggal) {
        // Filter berdasarkan tanggal spesifik
        $sql .= " WHERE DATE(STR_TO_DATE(waktu_acara, '%m-%d-%Y %H:%i:%s')) = STR_TO_DATE(?, '%d-%m-%Y')";
        $params[] = $tanggal;
    } elseif ($bulan && $tahun) {
        // Filter berdasarkan bulan dan tahun
        $sql .= " WHERE MONTH(STR_TO_DATE(waktu_acara, '%m-%d-%Y %H:%i:%s')) = ? AND YEAR(STR_TO_DATE(waktu_acara, '%m-%d-%Y %H:%i:%s')) = ?";
        $params[] = $bulan;
        $params[] = $tahun;
    }

    $sql .= " ORDER BY STR_TO_DATE(waktu_acara, '%m-%d-%Y %H:%i:%s') ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Konversi format tanggal untuk display (MM-DD-YYYY ke DD-MM-YYYY)
    foreach ($events as &$event) {
        if ($event['waktu_acara']) {
            $parts = explode(' ', $event['waktu_acara']);
            $tanggal_parts = explode('-', $parts[0]);

            if (count($tanggal_parts) === 3) {
                $bulan = $tanggal_parts[0];
                $hari = $tanggal_parts[1];
                $tahun = $tanggal_parts[2];
                $waktu = isset($parts[1]) ? $parts[1] : '00:00:00';

                // Format untuk display: DD-MM-YYYY HH:MM:SS
                $event['waktu_acara'] = $hari . '-' . $bulan . '-' . $tahun . ' ' . $waktu;
            }
        }
    }

    echo json_encode(['success' => true, 'data' => $events]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
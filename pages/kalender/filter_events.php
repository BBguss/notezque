<?php
// filepath: c:\xampp\htdocs\Kelompok_3\pages\kalender\filter_events.php
session_start();
require_once '../../config/database.php';

header('Content-Type: application/json');

try {
    $sort = $_GET['sort'] ?? 'tanggal_asc';
    $search = $_GET['search'] ?? '';
    $bulan = $_GET['bulan'] ?? null;
    $tahun = $_GET['tahun'] ?? null;

    $sql = "SELECT id_acara, judul_acara, desc_acara, waktu_acara FROM acara WHERE 1=1";
    $params = [];

    // Filter pencarian
    if (!empty($search)) {
        $sql .= " AND (judul_acara LIKE ? OR desc_acara LIKE ?)";
        $params[] = '%' . $search . '%';
        $params[] = '%' . $search . '%';
    }

    // Filter bulan dan tahun
    if ($bulan && $tahun) {
        $sql .= " AND MONTH(STR_TO_DATE(waktu_acara, '%m-%d-%Y %H:%i:%s')) = ? AND YEAR(STR_TO_DATE(waktu_acara, '%m-%d-%Y %H:%i:%s')) = ?";
        $params[] = $bulan;
        $params[] = $tahun;
    }

    // Pengurutan
    switch ($sort) {
        case 'tanggal_desc':
            $sql .= " ORDER BY STR_TO_DATE(waktu_acara, '%m-%d-%Y %H:%i:%s') DESC";
            break;
        case 'judul_asc':
            $sql .= " ORDER BY judul_acara ASC";
            break;
        case 'judul_desc':
            $sql .= " ORDER BY judul_acara DESC";
            break;
        default: // tanggal_asc
            $sql .= " ORDER BY STR_TO_DATE(waktu_acara, '%m-%d-%Y %H:%i:%s') ASC";
            break;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Konversi format tanggal untuk display
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

    echo json_encode(['success' => true, 'data' => $events, 'total' => count($events)]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
<?php
include '../../config/koneksi.php';

// Set header untuk JSON response
header('Content-Type: application/json');

try {
    // Ambil data dari request body
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validasi data
    if (!$data || !isset($data['id']) || !isset($data['judul_acara'])) {
        throw new Exception('Data tidak valid');
    }

    $id = mysqli_real_escape_string($conn, $data['id']);
    $judul = mysqli_real_escape_string($conn, $data['judul_acara']);
    $deskripsi = mysqli_real_escape_string($conn, $data['desc_acara']);
    $waktu = mysqli_real_escape_string($conn, $data['waktu_acara']);
    $prioritas = isset($data['prioritas']) ? mysqli_real_escape_string($conn, $data['prioritas']) : 'rendah';

    // Query update
    $query = "UPDATE kalender_acara SET 
              judul_acara = '$judul', 
              desc_acara = '$deskripsi', 
              waktu_acara = '$waktu',
              prioritas = '$prioritas'
              WHERE id_acara = $id";

    $hasil = mysqli_query($conn, $query);

    if ($hasil) {
        echo json_encode([
            'status' => 'sukses',
            'pesan' => 'Acara berhasil diperbarui!'
        ]);
    } else {
        throw new Exception('Gagal mengupdate acara: ' . mysqli_error($conn));
    }

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'pesan' => $e->getMessage()
    ]);
}

mysqli_close($conn);
?>
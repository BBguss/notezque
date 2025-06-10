<?php
include '../config/koneksi.php';

// Ambil data konten statis
$sql = "SELECT * FROM konten_statis";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Konten Statis</title>
    <link rel="stylesheet" href="../asset/css/style.css">
    <style>
        .konten-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
            background: #fff;
        }
        .konten-table th, .konten-table td {
            border: 1px solid #e0e0e0;
            padding: 0.75rem 1rem;
            text-align: left;
        }
        .konten-table th {
            background: #f5f7fb;
        }
        .konten-table tr:hover {
            background: #f0f4fa;
        }
        .btn-edit, .btn-hapus {
            padding: 0.3rem 0.8rem;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
        }
        .btn-edit { background: #4361ee; color: #fff; }
        .btn-hapus { background: #f72585; color: #fff; }
        .btn-tambah {
            background: #4cc9f0;
            color: #fff;
            border: none;
            padding: 0.5rem 1.2rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            cursor: pointer;
            font-size: 1rem;
        }
        .form-konten {
            background: #fff;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            max-width: 500px;
        }
        .form-konten input, .form-konten textarea {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            border: 1px solid #e0e0e0;
        }
        .form-konten label {
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="admin-main">
        <h2>Kelola Konten Statis</h2>

        <!-- Form tambah konten -->
        <form class="form-konten" method="post" action="">
            <label>Nama Halaman</label>
            <input type="text" name="nama_halaman" required>
            <label>Section</label>
            <input type="text" name="section">
            <label>Deskripsi</label>
            <textarea name="deskripsi"></textarea>
            <label>Gambar (URL)</label>
            <input type="text" name="gambar">
            <label>Keterangan</label>
            <input type="text" name="keterangan">
            <button type="submit" name="tambah" class="btn-tambah">Tambah Konten</button>
        </form>

        <?php
        // Proses tambah konten sederhana
        if (isset($_POST['tambah'])) {
            $nama_halaman = $_POST['nama_halaman'];
            $section = $_POST['section'];
            $deskripsi = $_POST['deskripsi'];
            $gambar = $_POST['gambar'];
            $keterangan = $_POST['keterangan'];
            mysqli_query($conn, "INSERT INTO konten_statis (nama_halaman, section, deskripsi, gambar, keterangan) VALUES ('$nama_halaman', '$section', '$deskripsi', '$gambar', '$keterangan')");
            echo "<meta http-equiv='refresh' content='0'>";
        }

        // Proses hapus konten sederhana
        if (isset($_GET['hapus'])) {
            $id = $_GET['hapus'];
            mysqli_query($conn, "DELETE FROM konten_statis WHERE id_konten=$id");
            echo "<meta http-equiv='refresh' content='0'>";
        }
        ?>

        <table class="konten-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Halaman</th>
                    <th>Section</th>
                    <th>Deskripsi</th>
                    <th>Gambar</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['id_konten'] ?></td>
                    <td><?= $row['nama_halaman'] ?></td>
                    <td><?= $row['section'] ?></td>
                    <td><?= $row['deskripsi'] ?></td>
                    <td><?= $row['gambar'] ?></td>
                    <td><?= $row['keterangan'] ?></td>
                    <td>
                        <!-- Edit hanya reload ke halaman ini, tidak ada form edit -->
                        <a href="?hapus=<?= $row['id_konten'] ?>" class="btn-hapus" onclick="return confirm('Hapus konten ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
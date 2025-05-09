<?php
// 5Dashboard.php
session_start();
include './config/koneksi.php';

// Ambil data user (kecuali admin)
$result = mysqli_query($conn, "SELECT * FROM users WHERE username != 'admin'");
$jumlahPengguna = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="./asset/attributes/Atribute1.css">
    <link rel="stylesheet" href="./asset/attributes/Atribute2.css">
    <link rel="stylesheet" href="./asset/attributes/Atribute3.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="dashboard-container">
        <h1>Dashboard Admin</h1>

        <?php if (isset($_GET['status'])): ?>
            <div class="notification <?= $_GET['status'] === 'deleted' ? 'success' : 'error' ?>">
                <?= $_GET['status'] === 'deleted' ? 'User berhasil dihapus' : 'Gagal menghapus user' ?>
            </div>
        <?php endif; ?>

        <div class="cards">
            <div class="card">
                <h2><?= $jumlahPengguna ?></h2>
                <p>Pengguna</p>
            </div>

            <h2 style="margin-top:40px;">Daftar Pengguna</h2>
            <table>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Tanggal Daftar</th>
                    <th>Aksi</th>
                </tr>
                <?php
                $no = 1;
                while ($user = mysqli_fetch_assoc($result)):
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= $user['created_at'] ?></td>
                        <td>
                            <a href="hapus_user.php?id=<?= $user['id_user'] ?>" class="btn-hapus"
                                onclick="return confirm('Yakin hapus user <?= htmlspecialchars($user['username']) ?>?')">
                                Hapus
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</body>

</html>
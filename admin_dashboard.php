<?php
session_start();
include './config/koneksi.php';

// Ambil data user
$result = mysqli_query($conn, "SELECT * FROM users WHERE username != 'admin'");

// Hitung data
$jumlahPengguna = mysqli_num_rows($result);
$jumlahTugas = 25; // Contoh data statis
$jumlahEvent = 8;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="./asset/attributes/Atribute1.css">
    <link rel="stylesheet" href="./asset/attributes/Atribute2.css">
    <link rel="stylesheet" href="./asset/attributes/Atribute3.css">
</head>
<body>
    <header>
        <div class="topNav-db">
            <nav>
                <div class="logo">
                    <a href="http://localhost/kelompok_3/pages/dashboard/5Dashboard.php"><img src="./asset/images/logoNotezQue.svg" alt=""></a>
                </div>
                <div class="dropdown">
                    <i class="dropdown-button" style="color: white;"><iconify-icon icon="iconamoon:profile-light" width="36" height="36"></iconify-icon></i>
                    <div class="dropdown-content">
                        <form action="" method="post">
                            <button type="submit" name="profile">Profile</button>
                            <button type="submit" name="logout">Logout</button>
                        </form>
                    </div>
                </div>
            </nav>
        </div>

    <aside>
        <input type="checkbox" name="" id="check">
                    <label for="check">
                        <i id="tombol" style="color: white;" ><iconify-icon icon="tabler:menu-2" width="32" height="32"></iconify-icon></i>
                        <i id="batal" style="color: white;"><iconify-icon icon="tabler:menu-3" width="32" height="32"></iconify-icon></i>
                    </label>
            <div class="sideNav-db">
                <nav>
                    <h2>Menu</h2>
                    <a href="../pages/dashboard/5Dashboard.php" class="active">
                        <iconify-icon icon="ic:round-space-dashboard" width="38" height="38"></iconify-icon>
                        <span>Dashboard</span>
                    </a>
                    <a href="./kalender/6kalender.php">
                        <iconify-icon icon="uim:schedule" width="38" height="38"></iconify-icon>
                        <span>Kalender</span>
                    </a>
                    <a href="7listTugas.php">
                        <iconify-icon icon="fluent:task-list-square-ltr-24-filled" width="38" height="38"></iconify-icon>
                        <span>Tugas</span>
                    </a>
                    <a href="8tambahMateri.php">
                        <iconify-icon icon="mingcute:book-5-line" width="38" height="38"></iconify-icon>
                        <span>Tambah Materi</span>
                    </a>
                </nav>
            </div>
    </aside>  
    </header>

    <div class="dashboard-container">
        <h1>Dashboard Admin</h1>
        <div class="cards">
            <div class="card">
                <h2><?= $jumlahPengguna ?></h2>
                <p>Pengguna</p>
            </div>

        <h2 style="margin-top:40px;">Daftar Pengguna</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php $no = 1; // Nomor urut dimulai dari 1
            while ($user = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $no ?></td> <!-- Nomor urut -->
            <td><?= $user['username'] ?></td>
            <td><?= $user['email'] ?></td>
            <td><?= $user['created_at'] ?></td>
            <td>
            <a href="hapus_user.php?id=<?= $user['id_user'] ?>"
                onclick="return confirm('Yakin ingin menghapus user ini?')">Hapus</a>
            </td>
            </tr>
            <?php
            $no++; // Tambah nomor urut
            endwhile; ?>
        </table>
    </div
</body>
</html>

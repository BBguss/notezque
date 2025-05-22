<?php
// 5Dashboard.php
include '../config/koneksi.php';
include '../config/session.php';
include 'filter.php';
// At the top of the file after session_start()
if (!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header('location: http://localhost/kelompok_3/pages/2loginpage.php');
    exit();
}

function waktu_lalu($waktu)
{
  if (!$waktu)
    return "Belum aktif";

  $waktu = new DateTime(datetime: $waktu);
  $sekarang = new DateTime();
  $selisih = $sekarang->diff($waktu);

  if ($selisih->y > 0 || $selisih->m > 0) {
    return $waktu->format("d M Y"); 
  } elseif ($selisih->d > 0) {
    return $selisih->d . " hari yang lalu";
  } elseif ($selisih->h > 0) {
    return $selisih->h . " jam yang lalu";
  } elseif ($selisih->i > 0) {
    return $selisih->i . " menit yang lalu";
  } else {
    return "Baru saja";
  }
}

// filter status 
$status = $_GET['status'] ?? '';
$sort_by = $_GET['sort_by'] ?? '';

$where = kondisiFilter($status);
$order = kondisiUrutan($sort_by);

$sql = "SELECT * FROM users WHERE $where ORDER BY $order";
$result = mysqli_query($conn, $sql);

$jumlahPengguna = mysqli_num_rows($result);
$pengguna_aktif = mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE username != 'admin' AND aktivitas_terakhir > DATE_SUB(NOW(), INTERVAL 1 DAY)");
$pengguna_aktif = mysqli_fetch_assoc($pengguna_aktif)['count'];

$pengguna_baru = mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE created_at > DATE_SUB(NOW(), INTERVAL 7 DAY)");
$pengguna_baru = mysqli_fetch_assoc($pengguna_baru)['count'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin NotezQue</title>
  <link rel="icon" type="image/x-icon" href="../Asset/images/Logo NotezQue.svg">
  <link rel="stylesheet" href="../Asset/css/style.css">
  <link rel="stylesheet" href="../Asset/font/Font.css">
  <link rel="stylesheet" href="../Asset/attributes/Atribute3.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap"rel="stylesheet">
</head>

<body>
    <div class="admin-container">
        <!-- Header Section -->
        <header class="admin-header">
            <div class="header-brand">
                <img src="../asset/images/logoNotezQue.svg" alt="Logo NotezQue" class="brand-logo">
                <h1>Admin Dashboard</h1>
            </div>
            <div class="header-actions">
                <form method="post">
                    <button type="submit" name="logout" class="btn btn-danger">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </button>
                </form>
            </div>
        </header>

        <!-- Sidebar Navigation -->
        <aside class="admin-sidebar">
            <nav>
                <ul class="sidebar-menu">
                    <li class="sidebar-item active">
                        <a href="5Dashboard.php" class="sidebar-link">
                            <i class="fas fa-tachometer-alt sidebar-icon"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <i class="fas fa-users sidebar-icon"></i>
                            Pengguna
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <i class="fas fa-sticky-note sidebar-icon"></i>
                            Catatan
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <i class="fas fa-chart-bar sidebar-icon"></i>
                            Analitik
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <i class="fas fa-cog sidebar-icon"></i>
                            Pengaturan
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Dashboard Cards -->
            <div class="dashboard-cards">
                <div class="dashboard-card">
                    <div class="card-header">
                        <span class="card-title">Total Pengguna</span>
                        <div class="card-icon icon-primary">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="card-value"><?= $jumlahPengguna ?></div>
                    <div class="card-footer">Semua pengguna terdaftar</div>
                </div>
                
                <div class="dashboard-card">
                    <div class="card-header">
                        <span class="card-title">Pengguna Aktif</span>
                        <div class="card-icon icon-success">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                    <div class="card-value"><?= $pengguna_aktif ?></div>
                    <div class="card-footer">Aktif dalam 24 jam terakhir</div>
                </div>
                
                <div class="dashboard-card">
                    <div class="card-header">
                        <span class="card-title">Pengguna Baru</span>
                        <div class="card-icon icon-warning">
                            <i class="fas fa-user-plus"></i>
                        </div>
                    </div>
                    <div class="card-value"><?= $pengguna_baru ?></div>
                    <div class="card-footer">Bergabung 7 hari terakhir</div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="data-table-container">
                <div class="table-header">
                    <h2 class="table-title">Manajemen Pengguna</h2>
                    <div class="table-actions">
                    <form method="GET" class="filter-group">
                        <select name="status" class="filter-control" onchange="this.form.submit()">
                            <option value="">Filter Berdasarkan Status</option>
                            <option value="aktif" <?= (isset($_GET['status']) && $_GET['status'] == 'aktif') ? 'selected' : '' ?>>Aktif</option>
                            <option value="tidak_aktif" <?= (isset($_GET['status']) && $_GET['status'] == 'tidak_aktif') ? 'selected' : '' ?>>Tidak Aktif</option>
                        </select>

                        <select name="sort_by" class="filter-control" onchange="this.form.submit()">
                          <option value="">Urut Berdasarkan</option>
                          <option value="created_at_desc" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'created_at_desc') ? 'selected' : '' ?>>Tanggal Daftar Terbaru</option>
                          <option value="created_at_asc" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'created_at_asc') ? 'selected' : '' ?>>Tanggal Daftar Terlama</option>
                      </select>
                    </form>
                        <button class="btn btn-primary btn-sm">
                            <i class="fas fa-download"></i> Ekspor
                        </button>
                    </div>
                </div>
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pengguna</th>
                            <th>Email</th>
                            <th>Tanggal Daftar</th>
                            <th>Aktivitas Terakhir</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($user = mysqli_fetch_assoc($result)):
                            $statusClass = (strtotime($user['aktivitas_terakhir']) > strtotime('-1 day')) 
                                ? 'badge-success' 
                                : 'badge-warning';
                            $statusText = (strtotime($user['aktivitas_terakhir']) > strtotime('-1 day')) 
                                ? 'Aktif' 
                                : 'Tidak Aktif';
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $user['username'] ?></td>
                            <td><?= $user['email'] ?></td>
                            <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                            <td><?= waktu_lalu($user['aktivitas_terakhir']) ?></td>
                            <td><span class="badge <?= $statusClass ?>"><?= $statusText ?></span></td>
                            <td>
                                <a href="hapus_user.php?id=<?= $user['id_user'] ?>" 
                                   class="btn btn-outline btn-sm"
                                   onclick="return confirm('Hapus pengguna ini?')">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <?php include '../pages/footer.php' ?>
</body>
<script src="../asset/attributes/Atribute1.js"></script>
</html>
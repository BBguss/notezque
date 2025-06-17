<?php
// filepath: c:\xampp\htdocs\Kelompok_3\admin\admin_dashboard.php
include '../config/koneksi.php';
include '../config/session.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header('location: http://localhost/kelompok_3/pages/2loginpage.php');
    exit();
}

// Logout functionality
if (isset($_POST['logout'])) {
    session_destroy();
    header('location: ../pages/2loginpage.php');
    exit();
}

// Function untuk cek apakah kolom ada di tabel
function columnExists($conn, $table, $column)
{
    $result = mysqli_query($conn, "SHOW COLUMNS FROM `$table` LIKE '$column'");
    return mysqli_num_rows($result) > 0;
}

// Mengambil statistik untuk dashboard
// Total pengguna
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE username != 'admin'"))['count'];

// Total konten
$total_konten = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM konten_statis"))['count'];

// Total kunjungan - cek dulu apakah tabel ada
$visit_table_exists = mysqli_query($conn, "SHOW TABLES LIKE 'statistik_kunjungan'");
if (mysqli_num_rows($visit_table_exists) > 0) {
    $total_visits = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM statistik_kunjungan"))['count'];
} else {
    $total_visits = 0;
}

// Pengguna aktif hari ini - cek kolom aktivitas_terakhir
$aktivitas_column_exists = columnExists($conn, 'users', 'aktivitas_terakhir');
if ($aktivitas_column_exists) {
    $active_users = mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE username != 'admin' AND aktivitas_terakhir > DATE_SUB(NOW(), INTERVAL 1 DAY)");
    $active_users = mysqli_fetch_assoc($active_users)['count'];
} else {
    // Fallback - hitung user yang ada saja
    $active_users = $total_users;
}

// Cek kolom created_at di tabel users dan konten_statis
$users_has_created_at = columnExists($conn, 'users', 'created_at');
$konten_has_created_at = columnExists($conn, 'konten_statis', 'created_at');

// Aktivitas terbaru dengan fallback jika kolom tidak ada
$recent_activity_queries = [];

// Query untuk user baru
if ($users_has_created_at) {
    $recent_activity_queries[] = "(SELECT 'user_baru' as tipe, username as subjek, created_at as waktu, NULL as detail 
     FROM users 
     WHERE created_at > DATE_SUB(NOW(), INTERVAL 7 DAY) AND username != 'admin')";
} else {
    // Fallback: ambil user terbaru berdasarkan ID
    $recent_activity_queries[] = "(SELECT 'user_baru' as tipe, username as subjek, NOW() as waktu, 'User terdaftar' as detail 
     FROM users 
     WHERE username != 'admin' 
     ORDER BY id_user DESC 
     LIMIT 5)";
}

// Query untuk konten baru
if ($konten_has_created_at) {
    $recent_activity_queries[] = "(SELECT 'konten_baru' as tipe, nama_halaman as subjek, created_at as waktu, section as detail 
     FROM konten_statis 
     WHERE created_at > DATE_SUB(NOW(), INTERVAL 7 DAY))";
} else {
    // Fallback: ambil konten terbaru berdasarkan ID
    $recent_activity_queries[] = "(SELECT 'konten_baru' as tipe, nama_halaman as subjek, NOW() as waktu, section as detail 
     FROM konten_statis 
     ORDER BY id_konten DESC 
     LIMIT 5)";
}

// Gabungkan query
$recent_activity_query = implode(" UNION ", $recent_activity_queries) . " ORDER BY waktu DESC LIMIT 10";

// Eksekusi query dengan error handling
$recent_activity = mysqli_query($conn, $recent_activity_query);

// Jika query gagal, buat query sederhana
if (!$recent_activity) {
    $recent_activity = mysqli_query($conn, "SELECT 'system' as tipe, 'Sistem Aktif' as subjek, NOW() as waktu, 'Dashboard diakses' as detail");
}

// Pagination logic
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; // Jumlah aktivitas per halaman
$offset = ($page - 1) * $limit;

// Hitung total aktivitas untuk pagination
$total_activities = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM (
    SELECT 'user_baru' as tipe, username as subjek, created_at as waktu, NULL as detail 
    FROM users 
    WHERE created_at > DATE_SUB(NOW(), INTERVAL 7 DAY) AND username != 'admin'
    UNION ALL
    SELECT 'konten_baru' as tipe, nama_halaman as subjek, created_at as waktu, section as detail 
    FROM konten_statis 
    WHERE created_at > DATE_SUB(NOW(), INTERVAL 7 DAY)
) as recent_activity"))['count'];

// Hitung total halaman
$total_pages = ceil($total_activities / $limit);

// Ambil aktivitas terbaru dengan pagination
$recent_activity_paginated = mysqli_query($conn, "SELECT * FROM (
    SELECT 'user_baru' as tipe, username as subjek, created_at as waktu, NULL as detail 
    FROM users 
    WHERE created_at > DATE_SUB(NOW(), INTERVAL 7 DAY) AND username != 'admin'
    UNION ALL
    SELECT 'konten_baru' as tipe, nama_halaman as subjek, created_at as waktu, section as detail 
    FROM konten_statis 
    WHERE created_at > DATE_SUB(NOW(), INTERVAL 7 DAY)
) as recent_activity ORDER BY waktu DESC LIMIT $limit OFFSET $offset");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - NotezQue</title>
    <link rel="icon" type="image/x-icon" href="../asset/images/logoNotezQue.svg">

    <!-- CSS Files -->
    <link rel="stylesheet" href="../asset/css/admin_dashboard.css">
    <link rel="stylesheet" href="../asset/font/Font.css">
    <link rel="stylesheet" href="../asset/attributes/Atribute3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
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
                        <a href="admin_dashboard.php" class="sidebar-link">
                            <i class="fas fa-tachometer-alt sidebar-icon"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="pengguna.php" class="sidebar-link">
                            <i class="fas fa-users sidebar-icon"></i>
                            Pengguna
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="kelola_konten.php" class="sidebar-link">
                            <i class="fas fa-file-alt sidebar-icon"></i>
                            Kelola Konten
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="statistik.php" class="sidebar-link">
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
            <!-- Welcome Section -->
            <div class="welcome-section">
                <h2>Selamat Datang, Admin!</h2>
                <p>Berikut adalah ringkasan aktivitas pada website NotezQue.</p>
            </div>  

            <!-- Dashboard Cards -->
            <div class="dashboard-cards">
                <div class="dashboard-card">
                    <div class="card-header">
                        <span class="card-title">Total Pengguna</span>
                        <div class="card-icon icon-primary">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="card-value"><?= $total_users ?></div>
                    <div class="card-footer">
                        <a href="pengguna.php">Lihat detail <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <span class="card-title">Pengguna Aktif</span>
                        <div class="card-icon icon-success">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                    <div class="card-value"><?= $active_users ?></div>
                    <div class="card-footer">
                        <a href="pengguna.php">Lihat detail <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <span class="card-title">Total Konten</span>
                        <div class="card-icon icon-warning">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                    <div class="card-value"><?= $total_konten ?></div>
                    <div class="card-footer">
                        <a href="kelola_konten.php">Lihat detail <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <span class="card-title">Kunjungan</span>
                        <div class="card-icon icon-info">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                    <div class="card-value"><?= $total_visits ?></div>
                    <div class="card-footer">
                        <a href="statistik.php">Lihat detail <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <div class="data-table-container">
                <div class="table-header">
                    <h2 class="table-title">
                        <i class="fas fa-history"></i> Aktivitas Terbaru
                    </h2>
                    <div class="table-info">
                        <span class="badge badge-info">
                            <?= $total_activities ?> Total Aktivitas
                        </span>
                    </div>
                </div>
                <div style="padding: 0;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th width="15%">Waktu</th>
                                <th width="15%">Tipe</th>
                                <th width="40%">Detail</th>
                                <th width="30%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($recent_activity && mysqli_num_rows($recent_activity) > 0):
                                while ($row = mysqli_fetch_assoc($recent_activity)):
                                    // Tampilan aktivitas berdasarkan tipe
                                    $icon = '';
                                    $description = '';
                                    $action_link = '';

                                    if ($row['tipe'] == 'user_baru') {
                                        $icon = '<i class="fas fa-user-plus text-success"></i>';
                                        $description = "Pengguna baru: <strong>{$row['subjek']}</strong> mendaftar";
                                        $action_link = '<a href="pengguna.php" class="btn btn-outline btn-sm">Lihat Pengguna</a>';
                                    } elseif ($row['tipe'] == 'konten_baru') {
                                        $icon = '<i class="fas fa-file-plus text-primary"></i>';
                                        $description = "Konten baru ditambahkan pada halaman <strong>{$row['subjek']}</strong>";
                                        if ($row['detail']) {
                                            $description .= " (Section: {$row['detail']})";
                                        }
                                        $action_link = '<a href="kelola_konten.php" class="btn btn-outline btn-sm">Lihat Konten</a>';
                                    } else {
                                        $icon = '<i class="fas fa-cog text-info"></i>';
                                        $description = $row['detail'];
                                        $action_link = '<span class="text-muted">-</span>';
                                    }
                                    ?>
                                    <tr>
                                        <td><?= date('d/m/Y H:i', strtotime($row['waktu'])) ?></td>
                                        <td>
                                            <?= $icon ?>
                                            <?= ucfirst(str_replace('_', ' ', $row['tipe'])) ?>
                                        </td>
                                        <td><?= $description ?></td>
                                        <td><?= $action_link ?></td>
                                    </tr>
                                    <?php
                                endwhile;
                            else:
                                ?>
                                <tr>
                                    <td colspan="4" class="empty-state">
                                        <i class="fas fa-stream"></i>
                                        <h4>Belum Ada Aktivitas</h4>
                                        <p>Belum ada aktivitas terbaru dalam 7 hari terakhir</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Controls -->
                <?php if ($total_pages > 1): ?>
                <div class="pagination-container">
                    <div class="pagination-info">
                        <span>Halaman <?= $page ?> dari <?= $total_pages ?></span>
                        <span class="text-muted">(<?= $total_activities ?> total aktivitas)</span>
                    </div>
                    <div class="pagination-controls">
                        <!-- Previous Button -->
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?>" class="btn btn-outline btn-sm">
                                <i class="fas fa-chevron-left"></i> Sebelumnya
                            </a>
                        <?php else: ?>
                            <span class="btn btn-outline btn-sm disabled">
                                <i class="fas fa-chevron-left"></i> Sebelumnya
                            </span>
                        <?php endif; ?>

                        <!-- Page Numbers -->
                        <div class="page-numbers">
                            <?php
                            $start_page = max(1, $page - 2);
                            $end_page = min($total_pages, $page + 2);
                            
                            // Tampilkan halaman pertama jika tidak termasuk dalam range
                            if ($start_page > 1): ?>
                                <a href="?page=1" class="page-btn">1</a>
                                <?php if ($start_page > 2): ?>
                                    <span class="page-ellipsis">...</span>
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- Tampilkan range halaman -->
                            <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                                <a href="?page=<?= $i ?>" class="page-btn <?= $i == $page ? 'active' : '' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>

                            <!-- Tampilkan halaman terakhir jika tidak termasuk dalam range -->
                            <?php if ($end_page < $total_pages): ?>
                                <?php if ($end_page < $total_pages - 1): ?>
                                    <span class="page-ellipsis">...</span>
                                <?php endif; ?>
                                <a href="?page=<?= $total_pages ?>" class="page-btn"><?= $total_pages ?></a>
                            <?php endif; ?>
                        </div>

                        <!-- Next Button -->
                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?= $page + 1 ?>" class="btn btn-outline btn-sm">
                                Selanjutnya <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php else: ?>
                            <span class="btn btn-outline btn-sm disabled">
                                Selanjutnya <i class="fas fa-chevron-right"></i>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Quick Access Section -->
            <div class="quick-access">
                <h2><i class="fas fa-bolt"></i> Akses Cepat</h2>
                <div class="quick-access-grid">
                    <a href="pengguna.php" class="quick-access-card">
                        <i class="fas fa-users"></i>
                        <span>Kelola Pengguna</span>
                    </a>
                    <a href="kelola_konten.php" class="quick-access-card">
                        <i class="fas fa-file-alt"></i>
                        <span>Kelola Konten</span>
                    </a>
                    <a href="statistik.php" class="quick-access-card">
                        <i class="fas fa-chart-bar"></i>
                        <span>Lihat Statistik</span>
                    </a>
                    <a href="#" class="quick-access-card">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan</span>
                    </a>
                </div>
            </div>
        </main>
    </div>

    <!-- JavaScript Files -->
    <script src="../asset/attributes/Atribute1.js"></script>
    <script>
        // Auto refresh dashboard setiap 5 menit
        setInterval(function () {
            // Bisa ditambahkan AJAX refresh jika diperlukan
            console.log('Dashboard auto-refresh check');
        }, 300000);
    </script>
</body>

</html>
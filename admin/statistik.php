<?php
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

// Mendapatkan statistik untuk dashboard
// Data kunjungan per hari (untuk 7 hari terakhir)
$daily_visits_query = "SELECT 
                        DATE(waktu_kunjungan) as tanggal,
                        COUNT(*) as jumlah_kunjungan 
                      FROM statistik_kunjungan 
                      WHERE waktu_kunjungan >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                      GROUP BY DATE(waktu_kunjungan)
                      ORDER BY tanggal";
$daily_visits_result = mysqli_query($conn, $daily_visits_query);

// Total kunjungan
$total_visits_query = "SELECT COUNT(*) as total FROM statistik_kunjungan";
$total_visits = mysqli_fetch_assoc(mysqli_query($conn, $total_visits_query))['total'];

// Kunjungan hari ini
$today_visits_query = "SELECT COUNT(*) as today FROM statistik_kunjungan WHERE DATE(waktu_kunjungan) = CURDATE()";
$today_visits = mysqli_fetch_assoc(mysqli_query($conn, $today_visits_query))['today'];

// Pengunjung unik
$unique_visitors_query = "SELECT COUNT(DISTINCT ip_address) as unique_count FROM statistik_kunjungan";
$unique_visitors = mysqli_fetch_assoc(mysqli_query($conn, $unique_visitors_query))['unique_count'];

// Halaman paling populer
$popular_pages_query = "SELECT 
                            halaman_dikunjungi,
                            COUNT(*) as jumlah 
                        FROM statistik_kunjungan 
                        GROUP BY halaman_dikunjungi 
                        ORDER BY jumlah DESC 
                        LIMIT 5";
$popular_pages_result = mysqli_query($conn, $popular_pages_query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Website - Admin NotezQue</title>
    <link rel="icon" type="image/x-icon" href="../asset/images/logoNotezQue.svg">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../asset/css/style.css">
    <link rel="stylesheet" href="../asset/css/konten_statis.css">
    <link rel="stylesheet" href="../asset/font/Font.css">
    <link rel="stylesheet" href="../asset/attributes/Atribute3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    
    <!-- Chart.js untuk visualisasi data -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="admin-container">
        <!-- Header Section -->
        <header class="admin-header">
            <div class="header-brand">
                <img src="../asset/images/logoNotezQue.svg" alt="Logo NotezQue" class="brand-logo">
                <h1>Statistik Website</h1>
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
                    <li class="sidebar-item">
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
                    <li class="sidebar-item active">
                        <a href="statistik.php" class="sidebar-link">
                            <i class="fas fa-chart-bar sidebar-icon"></i>
                            Statistik
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
                        <span class="card-title">Total Kunjungan</span>
                        <div class="card-icon icon-primary">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                    <div class="card-value"><?= $total_visits ?></div>
                    <div class="card-footer">
                        <i class="fas fa-history"></i> Semua waktu
                    </div>
                </div>
                
                <div class="dashboard-card">
                    <div class="card-header">
                        <span class="card-title">Kunjungan Hari Ini</span>
                        <div class="card-icon icon-success">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                    </div>
                    <div class="card-value"><?= $today_visits ?></div>
                    <div class="card-footer">
                        <i class="fas fa-clock"></i> Sejak 00:00 WIB
                    </div>
                </div>
                
                <div class="dashboard-card">
                    <div class="card-header">
                        <span class="card-title">Pengunjung Unik</span>
                        <div class="card-icon icon-warning">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="card-value"><?= $unique_visitors ?></div>
                    <div class="card-footer">
                        <i class="fas fa-fingerprint"></i> Berdasarkan IP Address
                    </div>
                </div>
            </div>

            <!-- Traffic Chart -->
            <div class="data-table-container">
                <div class="table-header">
                    <h2 class="table-title">
                        <i class="fas fa-chart-area"></i> Grafik Kunjungan 7 Hari Terakhir
                    </h2>
                </div>
                <div style="padding: 1.5rem; background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
                    <canvas id="trafficChart" height="300"></canvas>
                </div>
            </div>

            <!-- Popular Pages Table -->
            <div class="data-table-container" style="margin-top: 2rem;">
                <div class="table-header">
                    <h2 class="table-title">
                        <i class="fas fa-star"></i> Halaman Paling Populer
                    </h2>
                </div>
                <div style="overflow-x: auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Halaman</th>
                                <th>Jumlah Kunjungan</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            if (mysqli_num_rows($popular_pages_result) > 0):
                                while ($row = mysqli_fetch_assoc($popular_pages_result)):
                                    $percentage = ($row['jumlah'] / $total_visits) * 100;
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($row['halaman_dikunjungi']) ?></strong>
                                    </td>
                                    <td><?= number_format($row['jumlah']) ?></td>
                                    <td>
                                        <div class="progress-bar-container">
                                            <div class="progress-bar" style="width: <?= $percentage ?>%"></div>
                                            <span><?= number_format($percentage, 1) ?>%</span>
                                        </div>
                                    </td>
                                </tr>
                            <?php 
                                endwhile;
                            else:
                            ?>
                                <tr>
                                    <td colspan="4" class="empty-state">
                                        <i class="fas fa-chart-pie"></i>
                                        <h4>Belum Ada Data</h4>
                                        <p>Belum ada data kunjungan yang tercatat</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- JavaScript -->
    <script src="../asset/attributes/Atribute1.js"></script>
    <script>
        // Chart untuk traffic
        document.addEventListener('DOMContentLoaded', function() {
            const dates = [];
            const visits = [];
            
            <?php 
            mysqli_data_seek($daily_visits_result, 0);
            while ($row = mysqli_fetch_assoc($daily_visits_result)) {
                echo "dates.push('".date('d/m', strtotime($row['tanggal']))."');\n";
                echo "visits.push(".$row['jumlah_kunjungan'].");\n";
            }
            ?>
            
            const ctx = document.getElementById('trafficChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'Jumlah Kunjungan',
                        data: visits,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    }
                }
            });
        });
    </script>
    <?php include '../pages/footer.php' ?>
</body>
</html>
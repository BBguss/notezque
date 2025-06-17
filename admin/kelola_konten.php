<?php
include '../config/koneksi.php';
include '../config/session.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header('location: http://localhost/kelompok_3/pages/2loginpage.php');
    exit();
}

// Proses tambah konten
if (isset($_POST['tambah'])) {
    $nama_halaman = mysqli_real_escape_string($conn, $_POST['nama_halaman']);
    $section = mysqli_real_escape_string($conn, $_POST['section']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $gambar = mysqli_real_escape_string($conn, $_POST['gambar']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    $sql = "INSERT INTO konten_statis (nama_halaman, section, deskripsi, gambar, keterangan) 
            VALUES ('$nama_halaman', '$section', '$deskripsi', '$gambar', '$keterangan')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success_message'] = "Konten berhasil ditambahkan!";
    } else {
        $_SESSION['error_message'] = "Gagal menambahkan konten!";
    }

    header("Location: kelola_konten.php");
    exit();
}

// Proses edit konten
if (isset($_POST['edit'])) {
    $id_konten = $_POST['id_konten'];
    $nama_halaman = mysqli_real_escape_string($conn, $_POST['nama_halaman']);
    $section = mysqli_real_escape_string($conn, $_POST['section']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $gambar = mysqli_real_escape_string($conn, $_POST['gambar']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    $sql = "UPDATE konten_statis SET 
            nama_halaman='$nama_halaman', 
            section='$section', 
            deskripsi='$deskripsi', 
            gambar='$gambar', 
            keterangan='$keterangan' 
            WHERE id_konten=$id_konten";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success_message'] = "Konten berhasil diperbarui!";
    } else {
        $_SESSION['error_message'] = "Gagal memperbarui konten!";
    }

    header("Location: kelola_konten.php");
    exit();
}

// Proses hapus konten
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $sql = "DELETE FROM konten_statis WHERE id_konten=$id";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success_message'] = "Konten berhasil dihapus!";
    } else {
        $_SESSION['error_message'] = "Gagal menghapus konten!";
    }

    header("Location: kelola_konten.php");
    exit();
}

// Logout functionality
if (isset($_POST['logout'])) {
    session_destroy();
    header('location: ../pages/2loginpage.php');
    exit();
}

// Ambil data konten statis dengan filter
$search = $_GET['search'] ?? '';
$filter_halaman = $_GET['filter_halaman'] ?? '';

$where_conditions = [];
if (!empty($search)) {
    $where_conditions[] = "(nama_halaman LIKE '%$search%' OR section LIKE '%$search%' OR deskripsi LIKE '%$search%')";
}
if (!empty($filter_halaman)) {
    $where_conditions[] = "nama_halaman = '$filter_halaman'";
}

$where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';
$sql = "SELECT * FROM konten_statis $where_clause ORDER BY nama_halaman, section";
$result = mysqli_query($conn, $sql);

// Ambil daftar halaman untuk filter
$halaman_sql = "SELECT DISTINCT nama_halaman FROM konten_statis ORDER BY nama_halaman";
$halaman_result = mysqli_query($conn, $halaman_sql);

// Statistik
$total_konten = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM konten_statis"));
$total_halaman = mysqli_num_rows(mysqli_query($conn, "SELECT DISTINCT nama_halaman FROM konten_statis"));
$konten_dengan_gambar = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM konten_statis WHERE gambar != '' AND gambar IS NOT NULL"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Konten Statis - Admin NotezQue</title>
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
</head>

<body>
    <div class="admin-container">
        <!-- Header Section -->
        <header class="admin-header">
            <div class="header-brand">
                <img src="../asset/images/logoNotezQue.svg" alt="Logo NotezQue" class="brand-logo">
                <h1>Kelola Konten Statis</h1>
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
                    <li class="sidebar-item active">
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
            <!-- Success/Error Messages -->
            <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?= $_SESSION['success_message'] ?>
                    </div>
                    <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error_message'] ?>
                    </div>
                    <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <!-- Dashboard Cards -->
            <div class="dashboard-cards">
                <div class="dashboard-card">
                    <div class="card-header">
                        <span class="card-title">Total Konten</span>
                        <div class="card-icon icon-primary">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                    <div class="card-value"><?= $total_konten ?></div>
                    <div class="card-footer">
                        <i class="fas fa-database"></i> Semua konten statis
                    </div>
                </div>
                
                <div class="dashboard-card">
                    <div class="card-header">
                        <span class="card-title">Total Halaman</span>
                        <div class="card-icon icon-success">
                            <i class="fas fa-globe"></i>
                        </div>
                    </div>
                    <div class="card-value"><?= $total_halaman ?></div>
                    <div class="card-footer">
                        <i class="fas fa-sitemap"></i> Halaman berbeda
                    </div>
                </div>
                
                <div class="dashboard-card">
                    <div class="card-header">
                        <span class="card-title">Dengan Gambar</span>
                        <div class="card-icon icon-warning">
                            <i class="fas fa-image"></i>
                        </div>
                    </div>
                    <div class="card-value"><?= $konten_dengan_gambar ?></div>
                    <div class="card-footer">
                        <i class="fas fa-photo-video"></i> Konten bergambar
                    </div>
                </div>
            </div>

            <!-- Content Table -->
            <div class="data-table-container">
                <div class="table-header">
                    <h2 class="table-title">
                        <i class="fas fa-list-alt"></i> Manajemen Konten Statis
                    </h2>
                    <div class="table-actions">
                        <button class="btn btn-primary btn-sm" onclick="openModal('add')">
                            <i class="fas fa-plus"></i> Tambah Konten
                        </button>
                    </div>
                </div>

                <!-- Search and Filter -->
                <div style="padding: 1.5rem; border-bottom: 1px solid var(--light-gray); background: #fafbfc;">
                    <form method="GET" class="search-container">
                        <div class="search-input">
                            <input type="text" name="search" placeholder="Cari berdasarkan nama halaman, section, atau deskripsi..." 
                                   value="<?= htmlspecialchars($search) ?>" class="form-control">
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Filter Halaman</label>
                            <select name="filter_halaman" class="form-control" style="min-width: 180px;">
                                <option value="">Semua Halaman</option>
                                <?php
                                mysqli_data_seek($halaman_result, 0);
                                while ($halaman = mysqli_fetch_assoc($halaman_result)):
                                    ?>
                                        <option value="<?= $halaman['nama_halaman'] ?>" 
                                                <?= ($filter_halaman == $halaman['nama_halaman']) ? 'selected' : '' ?>>
                                            <?= $halaman['nama_halaman'] ?>
                                        </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-outline btn-sm">
                            <i class="fas fa-search"></i> Cari
                        </button>
                        <a href="kelola_konten.php" class="btn btn-outline btn-sm">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    </form>
                </div>
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>Nama Halaman</th>
                            <th>Section</th>
                            <th>Deskripsi</th>
                            <th style="width: 120px;">Gambar</th>
                            <th>Keterangan</th>
                            <th style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if (mysqli_num_rows($result) > 0):
                            while ($row = mysqli_fetch_assoc($result)):
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <strong style="color: var(--primary);">
                                            <?= htmlspecialchars($row['nama_halaman']) ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-light">
                                            <?= htmlspecialchars($row['section']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div style="max-width: 200px; overflow: hidden;">
                                            <?= htmlspecialchars(substr($row['deskripsi'], 0, 100)) ?>
                                            <?= strlen($row['deskripsi']) > 100 ? '...' : '' ?>
                                        </div>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php if (!empty($row['gambar'])): ?>
                                                <img src="../uploads/<?= $row['gambar'] ?>" alt="Preview" class="image-preview" title="<?= $row['gambar'] ?>">
                                        <?php else: ?>
                                                <span class="text-muted">
                                                    <i class="fas fa-image" style="opacity: 0.3;"></i>
                                                    <br><small>Tidak ada</small>
                                                </span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['keterangan']) ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-primary btn-sm btn-icon" 
                                                    onclick="editContent(<?= htmlspecialchars(json_encode($row)) ?>)" 
                                                    title="Edit Konten">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm btn-icon" 
                                                    onclick="deleteContent(<?= $row['id_konten'] ?>, '<?= htmlspecialchars($row['nama_halaman']) ?>')"
                                                    title="Hapus Konten">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            endwhile;
                        else:
                            ?>
                            <tr>
                                <td colspan="7" class="empty-state">
                                    <i class="fas fa-folder-open"></i>
                                    <h4>Tidak Ada Data</h4>
                                    <p>Tidak ada konten yang ditemukan berdasarkan kriteria pencarian</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Modal Add/Edit Content -->
    <div id="contentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">
                    <i class="fas fa-plus modal-icon"></i>Tambah Konten Baru
                </h3>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <form id="contentForm" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="contentId" name="id_konten">
                    
                    <div class="form-group">
                        <label for="namaHalaman">
                            Nama Halaman <span class="required">*</span>
                        </label>
                        <input type="text" id="namaHalaman" name="nama_halaman" class="form-control" required>
                        <div class="form-helper">
                            <i class="fas fa-info-circle"></i>
                            Nama halaman tempat konten ini ditampilkan
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="section">Section</label>
                        <input type="text" id="section" name="section" class="form-control" 
                               placeholder="Contoh: header, footer, sidebar">
                        <div class="form-helper">
                            <i class="fas fa-info-circle"></i>
                            Bagian spesifik dari halaman
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" class="form-control" rows="4" 
                                  placeholder="Deskripsi konten..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="gambar">Nama File Gambar</label>
                        <input type="text" id="gambar" name="gambar" class="form-control" 
                               placeholder="Contoh: logo-notezque.svg">
                        <div class="form-helper">
                            <i class="fas fa-upload"></i>
                            Masukkan nama file gambar yang ada di folder uploads/
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <input type="text" id="keterangan" name="keterangan" class="form-control" 
                               placeholder="Keterangan tambahan...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeModal()">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" id="submitBtn" name="tambah" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript Files -->
    <script src="../asset/attributes/Atribute1.js"></script>
    <script src="../asset/js/konten_statis.js"></script>

    <?php include '../pages/footer.php' ?>
</body>
</html>
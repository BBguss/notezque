<?php
// filepath: c:\xampp\htdocs\Kelompok_3\pages\kalender\6kalender.php
include '../../config/koneksi.php';
include '../../config/session.php';

// Cek session login
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header('Location: ../2loginpage.php');
    exit();
}

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: ../2loginpage.php');
    exit();
}

// Handle save event
if (isset($_POST['save'])) {
    $judul_acara = trim($_POST['judul_acara'] ?? $_POST['title'] ?? '');
    $desc_acara = trim($_POST['desc_acara'] ?? $_POST['desk'] ?? '');
    $tanggal = $_POST['tanggal'] ?? '';
    $waktu = $_POST['waktu'] ?? '00:00';
    $id_user = $_SESSION['id_user'];

    if (!empty($judul_acara) && !empty($tanggal)) {
        // Format waktu lengkap untuk database (YYYY-MM-DD HH:MM:SS)
        if (empty($waktu) || $waktu === '00:00') {
            $waktu_acara = $tanggal . ' 00:00:00';
        } else {
            $waktu_acara = $tanggal . ' ' . $waktu . ':00';
        }

        $sql_insert = "INSERT INTO kalender_acara (id_user, judul_acara, desc_acara, waktu_acara) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql_insert);
        mysqli_stmt_bind_param($stmt, "isss", $id_user, $judul_acara, $desc_acara, $waktu_acara);

        if (mysqli_stmt_execute($stmt)) {
            header('Location: 6kalender.php?sukses=tambah');
            exit();
        } else {
            header('Location: 6kalender.php?error=' . urlencode('Gagal menambahkan acara'));
            exit();
        }
    } else {
        header('Location: 6kalender.php?error=' . urlencode('Data tidak lengkap'));
        exit();
    }
}

// Handle edit event
if (isset($_POST['edit'])) {
    $id_acara = $_POST['id_acara'] ?? '';
    $judul_acara = trim($_POST['judul_acara'] ?? $_POST['title'] ?? '');
    $desc_acara = trim($_POST['desc_acara'] ?? $_POST['desk'] ?? '');
    $tanggal = $_POST['tanggal'] ?? '';
    $waktu = $_POST['waktu'] ?? '00:00';
    $id_user = $_SESSION['id_user'];

    if (!empty($id_acara) && !empty($judul_acara) && !empty($tanggal)) {
        // Format waktu lengkap untuk database (YYYY-MM-DD HH:MM:SS)
        if (empty($waktu) || $waktu === '00:00') {
            $waktu_acara = $tanggal . ' 00:00:00';
        } else {
            $waktu_acara = $tanggal . ' ' . $waktu . ':00';
        }

        $sql_update = "UPDATE kalender_acara SET judul_acara = ?, desc_acara = ?, waktu_acara = ? WHERE id_acara = ? AND id_user = ?";
        $stmt = mysqli_prepare($conn, $sql_update);
        mysqli_stmt_bind_param($stmt, "sssii", $judul_acara, $desc_acara, $waktu_acara, $id_acara, $id_user);

        if (mysqli_stmt_execute($stmt)) {
            header('Location: 6kalender.php?sukses=edit');
            exit();
        } else {
            header('Location: 6kalender.php?error=' . urlencode('Gagal mengupdate acara'));
            exit();
        }
    } else {
        header('Location: 6kalender.php?error=' . urlencode('Data tidak lengkap'));
        exit();
    }
}

// Handle delete event
if (isset($_POST['hapus'])) {
    $id_acara = $_POST['id_acara'] ?? '';
    $id_user = $_SESSION['id_user'];

    if (!empty($id_acara)) {
        // Get event title for success message
        $sql_get = "SELECT judul_acara FROM kalender_acara WHERE id_acara = ? AND id_user = ?";
        $stmt_get = mysqli_prepare($conn, $sql_get);
        mysqli_stmt_bind_param($stmt_get, "ii", $id_acara, $id_user);
        mysqli_stmt_execute($stmt_get);
        $result_get = mysqli_stmt_get_result($stmt_get);
        $event_data = mysqli_fetch_assoc($result_get);

        $sql_delete = "DELETE FROM kalender_acara WHERE id_acara = ? AND id_user = ?";
        $stmt = mysqli_prepare($conn, $sql_delete);
        mysqli_stmt_bind_param($stmt, "ii", $id_acara, $id_user);

        if (mysqli_stmt_execute($stmt)) {
            $deleted_title = $event_data ? urlencode($event_data['judul_acara']) : 'acara';
            header('Location: 6kalender.php?sukses=hapus&deleted=' . $deleted_title);
            exit();
        } else {
            header('Location: 6kalender.php?error=' . urlencode('Gagal menghapus acara'));
            exit();
        }
    }
}

// Ambil logo
$logo_query = "SELECT * FROM konten_statis WHERE gambar = 'logo-notezque.svg'";
$logo_result = mysqli_query($conn, $logo_query);
$logo = mysqli_fetch_assoc($logo_result);

// Ambil data acara user untuk JavaScript
$id_user = $_SESSION['id_user'];
$sql_acara = "SELECT id_acara, judul_acara, desc_acara, waktu_acara FROM kalender_acara WHERE id_user = ? ORDER BY waktu_acara ASC";
$stmt_acara = mysqli_prepare($conn, $sql_acara);
mysqli_stmt_bind_param($stmt_acara, "i", $id_user);
mysqli_stmt_execute($stmt_acara);
$result_acara = mysqli_stmt_get_result($stmt_acara);

$data_acara = [];
while ($row = mysqli_fetch_assoc($result_acara)) {
    $data_acara[] = $row;
}

// Handle pesan sukses dan error
$success_messages = [
    'tambah' => 'Data aktivitas berhasil ditambahkan!',
    'edit' => 'Perubahan data berhasil disimpan!',
    'hapus' => 'Data berhasil dihapus dari sistem!'
];

$success_message = '';
$error_message = '';

if (isset($_GET['sukses']) && isset($success_messages[$_GET['sukses']])) {
    $success_message = $success_messages[$_GET['sukses']];

    if ($_GET['sukses'] === 'hapus' && isset($_GET['deleted'])) {
        $deleted_title = htmlspecialchars(urldecode($_GET['deleted']));
        $success_message = "Acara \"$deleted_title\" berhasil dihapus dari sistem!";
    }
}

if (isset($_GET['error'])) {
    $error_message = htmlspecialchars(urldecode($_GET['error']));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalender - NotezQue</title>
    <link rel="icon" type="image/x-icon" href="../../uploads/<?= $logo['gambar'] ?>">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../../asset/css/6kalender.css">
    <link rel="stylesheet" href="../../asset/css/remainder.css">
    <link rel="stylesheet" href="../../asset/font/Font.css">
    <link rel="stylesheet" href="../../asset/attributes/Atribute1.css">
    <link rel="stylesheet" href="../../asset/attributes/Atribute2.css">
    <link rel="stylesheet" href="../../asset/attributes/Atribute3.css">
    
    <!-- External CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="app-container">
    <!-- Alert Messages -->
    <?php if ($success_message): ?>
            <div class="alert alert-success show" id="alertMessage">
                <i class="fas fa-check-circle"></i>
                <span><?= htmlspecialchars($success_message) ?></span>
                <button class="alert-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
    <?php endif; ?>

    <?php if ($error_message): ?>
            <div class="alert alert-error show" id="alertMessage">
                <i class="fas fa-exclamation-circle"></i>
                <span><?= htmlspecialchars($error_message) ?></span>
                <button class="alert-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
    <?php endif; ?>

    <!-- Header Navigation -->
    <header>
        <div class="topNav-db">
            <nav>
                <a href="../../pages/dashboard/5Dashboard.php">
                    <img src="../../uploads/<?= $logo['gambar'] ?>" alt="Logo NotezQue">
                </a>
                <div class="dropdown">
                    <i class="dropdown-button">
                        <iconify-icon icon="iconamoon:profile-light" width="36" height="36"></iconify-icon>
                    </i>
                    <div class="dropdown-content">
                        <a href="../../pages/12profile.php">
                            <button type="button">Profile</button>
                        </a>
                        <form action="" method="post">
                            <button type="submit" name="logout">Logout</button>
                        </form>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Sidebar Navigation -->
        <aside>
            <input type="checkbox" name="" id="check">
            <label for="check">
                <i id="tombol">
                    <iconify-icon icon="tabler:menu-2" width="32" height="32"></iconify-icon>
                </i>
                <i id="batal">
                    <iconify-icon icon="tabler:menu-3" width="32" height="32"></iconify-icon>
                </i>
            </label>
            <div class="sideNav-db">
                <nav>
                    <h2>Menu</h2>
                    <a href="../dashboard/5Dashboard.php">
                        <iconify-icon icon="ic:round-space-dashboard" width="38" height="38"></iconify-icon>
                        <span>Dashboard</span>
                    </a>
                    <a href="../kalender/6kalender.php" class="active">
                        <iconify-icon icon="uim:schedule" width="38" height="38"></iconify-icon>
                        <span>Kalender</span>
                    </a>
                    <a href="../7listTugas.php">
                        <iconify-icon icon="fluent:task-list-square-ltr-24-filled" width="38" height="38"></iconify-icon>
                        <span>Tugas</span>
                    </a>
                    <a href="../8tambahMateri.php">
                        <iconify-icon icon="mingcute:book-5-line" width="38" height="38"></iconify-icon>
                        <span>Tambah Materi</span>
                    </a>
                </nav>
            </div>
        </aside>
    </header>

    <!-- Main Content -->
    <main id="main">
        <!-- Calendar Container -->
        <div class="main-container">
            <!-- Calendar Header -->
            <div class="jadwal">
                <div class="prev" onclick="prevMonth()">
                    <i>
                        <iconify-icon icon="mingcute:left-fill" width="32" height="32"></iconify-icon>
                    </i>
                </div>
                <div class="bln-thn">Januari 2024</div>
                <div class="next" onclick="nextMonth()">
                    <i>
                        <iconify-icon icon="mingcute:right-fill" width="32" height="32"></iconify-icon>
                    </i>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="weeks">
                <div class="hari">
                    <div style="color: red;">Min</div>
                    <div>Sen</div>
                    <div>Sel</div>
                    <div>Rab</div>
                    <div>Kam</div>
                    <div>Jum</div>
                    <div style="color: red;">Sab</div>
                </div>
                <div class="tglBln"></div>
            </div>
        </div>

        <!-- Events Sidebar -->
        <div class="side-listAcara">
            <div class="header">
                <h3>
                    <iconify-icon icon="mdi:calendar-check" width="24" height="24"></iconify-icon>
                    <span>Daftar Acara</span>
                    <span class="badge" id="eventBadge" style="display: none;">0</span>
                </h3>
                <button id="btnTambahAcara" onclick="openModal()">
                    <iconify-icon icon="mdi:plus" width="20" height="20"></iconify-icon>
                    <span>Tambah</span>
                </button>
            </div>

            <!-- Filter Section -->
            <div class="filter-section">
                <div class="filter-controls">
                    <!-- Sort Filter -->
                    <div class="filter-group">
                        <label for="sortFilter">Urutkan:</label>
                        <select id="sortFilter">
                            <option value="tanggal_asc" selected>Tanggal (Lama ke Baru)</option>
                            <option value="tanggal_desc">Tanggal (Baru ke Lama)</option>
                            <option value="judul">Judul A-Z</option>
                        </select>
                    </div>
                    
                    <!-- Clear Filter -->
                    <button id="clearFilters" class="clear-btn">
                        <iconify-icon icon="mdi:filter-remove" width="16" height="16"></iconify-icon>
                        Reset Filter
                    </button>
                </div>
            </div>

            <!-- Activity List Container -->
            <div class="acara-container">
                <!-- Events akan diisi oleh JavaScript -->
            </div>
        </div>
    </main>

    <!-- Modal Tambah/Edit Acara -->
    <div id="modal" class="modal">
        <div class="form">
            <form action="" method="post" id="eventForm">
                <h1 class="modalTitle">Buat Acara Baru</h1>
                <h1 class="update-text" style="display: none;">Edit Acara</h1>
                
                <div class="inputan">
                    <!-- Hidden field untuk edit -->
                    <input type="hidden" id="id_acara" name="id_acara">
                    
                    <!-- Judul Acara -->
                    <div class="input-grup">
                        <label for="title">Judul Acara *</label>
                        <input type="text" 
                               name="judul_acara" 
                               id="title" 
                               required 
                               placeholder="Contoh: Rapat Tim, Presentasi Project">
                        <small class="form-help">Judul harus diisi dan maksimal 100 karakter</small>
                    </div>
                    
                    <!-- Deskripsi Acara -->
                    <div class="input-grup">
                        <label for="desk">Deskripsi Acara</label>
                        <textarea id="desk" 
                                  name="desc_acara" 
                                  rows="4"
                                  placeholder="Tambahkan deskripsi acara (opsional)"></textarea>
                        <small class="form-help">Deskripsi membantu memberikan detail lebih tentang acara</small>
                    </div>
                    
                    <!-- Pilih Tanggal -->
                    <div class="input-grup">
                        <label for="tanggal">Pilih Tanggal *</label>
                        <input type="date" 
                               name="tanggal" 
                               id="tanggal" 
                               required>
                        <small class="form-help">Pilih tanggal untuk acara Anda</small>
                    </div>
                    
                    <!-- Pilih Waktu -->
                    <div class="input-grup">
                        <label for="waktu">Pilih Waktu</label>
                        <input type="time" 
                               name="waktu" 
                               id="waktu"
                               placeholder="HH:MM">
                        <small class="form-help">Kosongkan untuk acara sepanjang hari</small>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="btn">
                        <button type="button" class="btl" onclick="closeModal()">
                            <iconify-icon icon="mdi:close" width="18" height="18"></iconify-icon>
                            Batal
                        </button>
                        <button type="button" class="save" onclick="saveEvent()">
                            <iconify-icon icon="mdi:content-save" width="18" height="18"></iconify-icon>
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Side Detail Modal -->
    <div id="sideModalDetail" class="side-modal">
        <div class="side-modal-header">
            <h3 id="sideDetailJudul">Detail Acara</h3>
            <button class="side-modal-close" onclick="closeSideModal()">
                <iconify-icon icon="mdi:close" width="24" height="24"></iconify-icon>
            </button>
        </div>
        <div class="side-modal-body">
            <div class="detail-item">
                <strong>
                    <iconify-icon icon="mdi:text" width="16" height="16"></iconify-icon>
                    Deskripsi:
                </strong>
                <p id="sideDetailDeskripsi">Tidak ada deskripsi</p>
            </div>
            <div class="detail-item">
                <strong>
                    <iconify-icon icon="mdi:calendar" width="16" height="16"></iconify-icon>
                    Tanggal:
                </strong>
                <p id="sideDetailTanggalMulai">-</p>
            </div>
            <div class="detail-item">
                <strong>
                    <iconify-icon icon="mdi:clock-outline" width="16" height="16"></iconify-icon>
                    Waktu:
                </strong>
                <p id="sideDetailTanggalBerakhir">-</p>
            </div>
        </div>
        <div class="side-modal-footer">
            <button id="editSideAcaraBtn" class="btn-edit-side" onclick="editFromSide()">
                <iconify-icon icon="mdi:pencil" width="18" height="18"></iconify-icon> 
                Edit
            </button>
            <button id="hapusSideAcaraBtn" class="btn-hapus-side" onclick="deleteFromSide()">
                <iconify-icon icon="mdi:trash-can-outline" width="18" height="18"></iconify-icon> 
                Hapus
            </button>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Data PHP untuk JavaScript
        window.phpEvents = <?= json_encode($data_acara) ?>;
    </script>
    
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <script src="../../asset/attributes/Atribute1.js"></script>
    <script src="../../asset/attributes/Atribute2.js"></script>
    <script src="../../asset/js/6kalender.js"></script>
</body>
</html>
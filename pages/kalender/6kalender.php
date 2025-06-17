<?php
// filepath: c:\xampp\htdocs\Kelompok_3\pages\kalender\6kalender.php
session_start();
include '../../config/koneksi.php';
include 'filter_acara.php';
include 'helper_format.php';

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

// Ambil logo
$logo_query = "SELECT * FROM konten_statis WHERE gambar = 'logo-notezque.svg'";
$logo_result = mysqli_query($conn, $logo_query);
$logo = mysqli_fetch_assoc($logo_result);

// Ambil dan validasi parameter untuk filter dan pagination
$bulan_filter = isset($_GET['bulan']) ? $_GET['bulan'] : date('n');
$tahun_filter = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$sort_filter = isset($_GET['sort']) ? $_GET['sort'] : 'tanggal_asc';
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$items_per_page = 3;

// Validasi parameter
$validated = validasiParameterFilter($bulan_filter, $tahun_filter, $sort_filter, $page);
$bulan_filter = $validated['bulan'];
$tahun_filter = $validated['tahun'];
$sort_filter = $validated['sort'];
$page = $validated['page'];

// Ambil data acara user untuk JavaScript (semua data)
$id_user = $_SESSION['id_user'];
$data_acara = getSemuaAcaraUser($conn, $id_user);

// Ambil data acara dengan filter dan pagination
$result_filter = getAcaraDenganFilter($conn, $id_user, $bulan_filter, $tahun_filter, $sort_filter, $page, $items_per_page);
$acara_bulan_ini = $result_filter['data'];
$total_data = $result_filter['total_data'];
$total_pages = $result_filter['total_pages'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalender - NotezQue</title>
    <link rel="icon" type="image/x-icon" href="../../uploads/<?php echo $logo['gambar']; ?>">

    <!-- CSS Files -->
    <link rel="stylesheet" href="../../asset/css/6kalender.css">
    <link rel="stylesheet" href="../../asset/css/pagination.css">
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
    <!-- Header Navigation -->
    <header>
        <!-- Header content sama seperti sebelumnya -->
        <div class="topNav-db">
            <nav>
                <a href="../../pages/dashboard/5Dashboard.php">
                    <img src="../../uploads/<?php echo $logo['gambar']; ?>" alt="Logo NotezQue">
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
                <div class="prev" onclick="bulanSebelumnya()">
                    <i>
                        <iconify-icon icon="mingcute:left-fill" width="32" height="32"></iconify-icon>
                    </i>
                </div>
                <div class="bln-thn">Januari 2024</div>
                <div class="next" onclick="bulanSelanjutnya()">
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
                    <?php if ($total_data > 0): ?>
                            <span class="badge" id="eventBadge"><?php echo $total_data; ?></span>
                    <?php endif; ?>
                </h3>
                <button id="btnTambahAcara" onclick="bukaModalTambahAcara()">
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
                            <option value="tanggal_asc" <?php echo $sort_filter === 'tanggal_asc' ? 'selected' : ''; ?>>
                                Tanggal (Lama ke Baru)
                            </option>
                            <option value="tanggal_desc" <?php echo $sort_filter === 'tanggal_desc' ? 'selected' : ''; ?>>
                                Tanggal (Baru ke Lama)
                            </option>
                            <option value="judul" <?php echo $sort_filter === 'judul' ? 'selected' : ''; ?>>
                                Judul A-Z
                            </option>
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
                <?php if (empty($acara_bulan_ini)): ?>
                        <!-- Pesan kosong -->
                        <div class="no-events">
                            <iconify-icon icon="mdi:calendar-outline" width="64" height="64"></iconify-icon>
                            <h4>Tidak ada acara</h4>
                            <p>Belum ada acara yang dijadwalkan untuk <?php echo getNamaBulanIndonesia($bulan_filter) . ' ' . $tahun_filter; ?></p>
                        </div>
                <?php else: ?>
                        <!-- Info pagination -->
                        <?php if ($total_data > $items_per_page): ?>
                                <div class="pagination-info">
                                    <iconify-icon icon="mdi:information-outline" width="16" height="16"></iconify-icon>
                                    Menampilkan <?php echo (($page - 1) * $items_per_page) + 1; ?>-<?php echo min($page * $items_per_page, $total_data); ?> dari <?php echo $total_data; ?> acara
                                </div>
                        <?php endif; ?>

                        <!-- Daftar acara -->
                        <?php foreach ($acara_bulan_ini as $acara): ?>
                                <div class="acara">
                                    <div class="acara-actions">
                                        <button class="action-btn detail-btn" 
                                                data-id="<?php echo $acara['id_acara']; ?>" 
                                                title="Lihat Detail"
                                                onclick="bukaDetailAktivitas(<?php echo $acara['id_acara']; ?>)">
                                            <iconify-icon icon="mdi:eye" width="16" height="16"></iconify-icon>
                                        </button>
                                        <button class="action-btn edit-btn" 
                                                data-id="<?php echo $acara['id_acara']; ?>" 
                                                title="Edit"
                                                onclick="editAktivitas(<?php echo $acara['id_acara']; ?>)">
                                            <iconify-icon icon="mdi:pencil" width="16" height="16"></iconify-icon>
                                        </button>
                                        <button class="action-btn hapus-btn" 
                                                data-id="<?php echo $acara['id_acara']; ?>" 
                                                title="Hapus"
                                                onclick="konfirmasiHapusAktivitas(<?php echo $acara['id_acara']; ?>)">
                                            <iconify-icon icon="mdi:trash-can-outline" width="16" height="16"></iconify-icon>
                                        </button>
                                    </div>
                                    <div class="event-date">
                                        <iconify-icon icon="mdi:calendar" width="14" height="14"></iconify-icon>
                                        <?php echo formatTanggalTampilan($acara['waktu_acara']); ?> - <?php echo formatWaktu($acara['waktu_acara']); ?>
                                    </div>
                                    <h4><?php echo htmlspecialchars($acara['judul_acara']); ?></h4>
                                    <div class="event-desc">
                                        <?php echo htmlspecialchars($acara['desc_acara'] ?: 'Tidak ada deskripsi'); ?>
                                    </div>
                                </div>
                        <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_data > $items_per_page): ?>
                    <div class="footer pagination">
                        <button id="prevPage" class="pagination-btn" onclick="changePage(-1)" <?php echo $page <= 1 ? 'disabled' : ''; ?>>
                            <iconify-icon icon="mdi:chevron-left" width="24" height="24"></iconify-icon>
                        </button>
                        <span id="currentPage"><?php echo $page; ?> / <?php echo $total_pages; ?></span>
                        <button id="nextPage" class="pagination-btn" onclick="changePage(1)" <?php echo $page >= $total_pages ? 'disabled' : ''; ?>>
                            <iconify-icon icon="mdi:chevron-right" width="24" height="24"></iconify-icon>
                        </button>
                    </div>
            <?php else: ?>
                    <div class="footer pagination" style="display: none;"></div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Modal Tambah/Edit Acara -->
    <div id="modal" class="modal">
        <div class="form">
            <form action="handler_acara.php" method="post" id="eventForm">
                <h1 class="modalTitle">Buat Acara Baru</h1>
                <h1 class="update-text" style="display: none;">Edit Acara</h1>

                <div class="inputan">
                    <!-- Hidden field untuk edit -->
                    <input type="hidden" id="id_acara" name="id_acara">

                    <!-- Judul Acara -->
                    <div class="input-grup">
                        <label for="title">Judul Acara *</label>
                        <input type="text" name="title" id="title" required placeholder="Contoh: Rapat Tim, Presentasi Project" maxlength="100">
                        <small class="form-help">Judul harus diisi dan maksimal 100 karakter</small>
                    </div>

                    <!-- Deskripsi Acara -->
                    <div class="input-grup">
                        <label for="desk">Deskripsi Acara</label>
                        <textarea id="desk" name="desk" rows="4" placeholder="Tambahkan deskripsi acara (opsional)" maxlength="500"></textarea>
                        <small class="form-help">Deskripsi membantu memberikan detail lebih tentang acara</small>
                    </div>

                    <!-- Pilih Tanggal -->
                    <div class="input-grup">
                        <label for="tanggal">Pilih Tanggal *</label>
                        <input type="date" name="tanggal" id="tanggal" required>
                        <small class="form-help">Pilih tanggal untuk acara Anda</small>
                    </div>

                    <!-- Pilih Waktu -->
                    <div class="input-grup">
                        <label for="waktu">Pilih Waktu</label>
                        <input type="time" name="waktu" id="waktu" placeholder="HH:MM">
                        <small class="form-help">Kosongkan untuk acara sepanjang hari</small>
                    </div>

                    <!-- Action Buttons -->
                    <div class="btn">
                        <button type="button" class="btl" onclick="tutupModal()">
                            <iconify-icon icon="mdi:close" width="18" height="18"></iconify-icon>
                            Batal
                        </button>
                        <button type="button" class="save" onclick="simpanAktivitas()">
                            <iconify-icon icon="mdi:content-save" width="18" height="18"></iconify-icon>
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Side Detail Modal sama seperti sebelumnya -->
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
    <?php include '../footer.php' ?>
    <!-- Scripts -->
    <script>
        // Data PHP untuk JavaScript (untuk kalender saja)
        window.phpEvents = <?php echo json_encode($data_acara); ?>;

        // Set bulan dan tahun dari PHP
        bulanSekarang = <?php echo $bulan_filter - 1; ?>; // JavaScript month 0-11
        tahunSekarang = <?php echo $tahun_filter; ?>;
    </script>
    
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <script src="../../asset/attributes/Atribute1.js"></script>
    <script src="../../asset/attributes/Atribute2.js"></script>
    <script src="../../asset/js/6kalender.js"></script>
</body>
</html>
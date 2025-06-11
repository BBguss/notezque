<?php
include '../../config/koneksi.php';
include '../../config/session.php';
include '../../modal/success_modal.php';

$logo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM konten_statis WHERE gambar = 'logo-notezque.svg'"));
            
$success_messages = [
    'tambah' => 'Data aktivitas berhasil ditambahkan!',
    'edit' => 'Perubahan data berhasil disimpan!',
    'hapus' => 'Data berhasil dihapus dari sistem!'
];

if (isset($_GET['sukses']) && isset($success_messages[$_GET['sukses']])) {
    showSuccessModal(
        "Berhasil!",
        $success_messages[$_GET['sukses']],
        null,
        3000
    );
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
    <link rel="stylesheet" href="../../asset/font/Font.css">
    <link rel="stylesheet" href="../../asset/attributes/Atribute1.css">
    <link rel="stylesheet" href="../../asset/attributes/Atribute2.css">
    <link rel="stylesheet" href="../../asset/attributes/Atribute3.css">
    
    <!-- External CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        /* styling */
        .reminder-section {
            margin: 15px 0;
        }
        
        .reminder-toggle {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .reminder-toggle input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            accent-color: #4285f4;
        }
        
        .reminder-toggle label {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: white;
            cursor: pointer;
            user-select: none;
        }
        
        .reminder-options {
            display: none;
        }
        
        .reminder-options.active {
            display: block;
        }
        
        .reminder-select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-bottom: 10px;
            font-size: 14px;
            background-color: #fff;
            color: #333;
            box-sizing: border-box;
        }
        
        .reminder-select:focus {
            outline: none;
            border-color: #4285f4;
            box-shadow: 0 0 0 2px rgba(66, 133, 244, 0.1);
        }
        
        .custom-reminder {
            display: none;
            margin-top: 10px;
        }
        
        .custom-reminder.active {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .custom-input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            background-color: #fff;
            color: #333;
        }
        
        .custom-input:focus {
            outline: none;
            border-color: #4285f4;
            box-shadow: 0 0 0 2px rgba(66, 133, 244, 0.1);
        }
        
        .reminder-icon {
            color: #4285f4;
            margin-right: 5px;
        }
        
        .custom-reminder span {
            font-size: 14px;
            color: #666;
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <!-- Header Navigation -->
    <header>
        <div class="topNav-db">
            <nav>
                <a href="../../pages/dashboard/5Dashboard.php">
                    <img src="../../uploads/<?= $logo['gambar'] ?>" alt="Logo NotezQue">
                </a>
                <div class="dropdown">
                    <i class="dropdown-button" style="color: white;">
                        <iconify-icon icon="iconamoon:profile-light" width="36" height="36"></iconify-icon>
                    </i>
                <a href="../../pages/dashboard/5Dashboard.php"><img src="../../uploads/<?= $logo['gambar'] ?>" alt="Logo"></a>
                <div class="dropdown">
                    <i class="dropdown-button" style="color: white;"><iconify-icon icon="iconamoon:profile-light" width="36" height="36"></iconify-icon></i>
                    <div class="dropdown-content">
                        <a href="../../pages/12profile.php">
                            <button type="submit" name="profile">Profile</button>
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
                <i id="tombol" style="color: white;">
                    <iconify-icon icon="tabler:menu-2" width="32" height="32"></iconify-icon>
                </i>
                <i id="batal" style="color: white;">
                    <iconify-icon icon="tabler:menu-3" width="32" height="32"></iconify-icon>
                </i>
        <aside>
            <input type="checkbox" name="" id="check">
            <label for="check">
                <i id="tombol" style="color: white;"><iconify-icon icon="tabler:menu-2" width="32" height="32"></iconify-icon></i>
                <i id="batal" style="color: white;"><iconify-icon icon="tabler:menu-3" width="32" height="32"></iconify-icon></i>
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
        </aside>
    </header>

    <main id="main">
        <div class="main-container">
            <!-- Calendar Header -->
            <div class="jadwal">
                <div class="prev">
                    <i>
                        <iconify-icon icon="mingcute:left-fill" width="32" height="32" style="color: #4285f4"></iconify-icon>
                    </i>
                </div>
                <div class="bln-thn"></div>
                <div class="next">
                    <i>
                        <iconify-icon icon="mingcute:right-fill" width="32" height="32" style="color: #4285f4"></iconify-icon>
                    </i>
                    <i><iconify-icon icon="mingcute:left-fill" width="32" height="32" style="color: #4285f4"></iconify-icon></i>
                </div>
                <div class="bln-thn"></div>
                <div class="next">
                    <i><iconify-icon icon="mingcute:right-fill" width="32" height="32" style="color: #4285f4"></iconify-icon></i>
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
                    <span class="badge" id="eventBadge"></span>
                </h3>
                <button id="btnTambahAcara">
                    <iconify-icon icon="mdi:plus" width="20" height="20"></iconify-icon>
                    <span>Tambah</span>
                </button>
            </div>

            <!-- Filter Section - Sederhana -->
            <div class="filter-section">
                <div class="filter-controls">
                    <!-- Priority Filter -->
                    <div class="filter-group">
                        <label for="priorityFilter">Filter Prioritas:</label>
                        <select id="priorityFilter">
                            <option value="">Semua Prioritas</option>
                            <option value="tinggi">Tinggi</option>
                            <option value="sedang">Sedang</option>
                            <option value="rendah">Rendah</option>
                        </select>
        <div class="side-listAcara">
            <h2 class="side-title" style="text-align: center">List acara</h2>
        </div>

        <div class="acara-container"></div>
    </main>

    <aside id="modal" class="modal">
        <div class="form">
            <form action="" method="post">
                <h1 class="modalTitle">Buat Acara Baru</h1>
                <h1 class="update-text"></h1>
                <div class="inputan">
                    <label for="title">
                        <input type="text" name="j.acara" id="title" placeholder="Judul Acara" required>
                    </label>
                    <label for="desk">
                        <textarea id="desk" name="d.acara" placeholder="Deskripsi Acara"></textarea>
                    </label>
                    <label for="tenggat">
                        <input type="time" name="dl.acara" id="tenggat" required>
                    </label>

                    <!-- Fitur Reminder -->
<div class="reminder-section">
    <div class="reminder-toggle">
        <input type="checkbox" id="reminder_enabled" checked>
        <label for="reminder_enabled">
            Aktifkan Pengingat
        </label>
    </div>
                        
    <div class="reminder-options active" id="reminder_options">
        <select class="reminder-select" id="reminder_template">
            <option value="">Pilih waktu pengingat...</option>
                <?php 
                    mysqli_data_seek($reminder_templates, 0);
                    while ($template = mysqli_fetch_assoc($reminder_templates)): ?>
            <option value="<?= $template['minutes_before'] ?>" 
                <?= isset($selected_minutes) && $template['minutes_before'] == $selected_minutes ? 'selected' : '' ?>>
                <?= $template['name'] ?>
            </option>
            <?php endwhile; ?>
        </select>

                        </div>
                    </div>

                    <div class="btn">
                        <button type="button" class="btl" id="closeBtn" name="batal">Batal</button>
                      <button type="button" class="save" id="save" name="save">Simpan</button>

                    </div>
                    
                    <!-- Sort Filter -->
                    <div class="filter-group">
                        <label for="sortFilter">Urutkan:</label>
                        <select id="sortFilter">
                            <option value="prioritas" selected>Prioritas (Tinggi ke Rendah)</option>
                            <option value="tanggal_asc">Tanggal (Lama ke Baru)</option>
                            <option value="tanggal_desc">Tanggal (Baru ke Lama)</option>
                            <option value="judul">Judul A-Z</option>
                        </select>
                    </div>
                    
                    <!-- Clear Filter -->
                    <button id="clearFilters" class="clear-btn">Reset Filter</button>
                </div>
            </div>

            <section class="isi">
                <!-- Activity Actions (dynamically shown/hidden) -->
                <div class="activity-actions">
                    <button class="action-button edit-button">
                        <iconify-icon icon="mdi:pencil" width="18" height="18"></iconify-icon>
                        <span>Edit</span>
                    </button>
                    <button class="action-button delete-button">
                        <iconify-icon icon="mdi:trash-can-outline" width="18" height="18"></iconify-icon>
                        <span>Hapus</span>
                    </button>
                </div>
                
                <div class="acara-container"></div>
            </section>
        </div>

        <!-- Modal Tambah/Edit Acara -->
        <aside id="modal" class="modal">
            <div class="form">
                <form action="" method="post">
                    <h1 class="modalTitle">Buat Acara Baru</h1>
                    <h1 class="update-text" style="display: none;"></h1>
                    
                    <div class="inputan">
                        <!-- Judul Acara -->
                        <div class="input-grup">
                            <label for="title">Masukan Judul Acara</label>
                            <input type="text" name="j.acara" id="title" required>
                        </div>
                        
                        <!-- Deskripsi Acara -->
                        <div class="input-grup">
                            <label for="desk">Deskripsi Acara</label>
                            <textarea id="desk" name="d.acara" placeholder="Tambahkan deskripsi acara (opsional)"></textarea>
                        </div>
                        
                        <!-- Tenggat Waktu -->
                        <div class="input-grup">
                            <label for="tenggat">Tenggat</label>
                            <div class="tombol-grup">
                                <button type="button" id="skrg" class="aktif">Hari ini</button>
                                <button type="button" id="besok">Besok</button>
                                <button type="button" id="kustom">Kustom</button>
                            </div>
                            
                            <!-- Container untuk input custom -->
                            <div class="custom-input-container" style="display: none;">
                                <div class="custom-date-group">
                                    <label for="tanggalCustom">Tanggal</label>
                                    <input type="date" id="tanggalCustom" name="custom_due_date">
                                </div>
                                <div class="custom-time-group">
                                    <label for="waktuCustom">Waktu</label>
                                    <input type="time" id="waktuCustom" name="custom_due_time">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Prioritas -->
                        <div class="input-grup">
                            <label for="prioritas">Prioritas</label>
                            <div class="tombol-grup">
                                <button type="button" class="rendah aktif">Rendah</button>
                                <button type="button" class="sedang">Sedang</button>
                                <button type="button" class="tinggi">Tinggi</button>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="btn">
                            <button type="button" class="btl" id="closeBtn" name="batal">Batal</button>
                            <button type="button" class="save" id="save" name="save">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </aside>

        <!-- Side Detail Modal -->
        <aside id="sideModalDetail" class="side-modal">
            <div class="side-modal-header">
                <h3 id="sideDetailJudul">Detail Acara</h3>
                <button class="side-modal-close" id="closeSideModalBtn" aria-label="Tutup">
                    <iconify-icon icon="mdi:close" width="24" height="24"></iconify-icon>
                </button>
            </div>
            <div class="side-modal-body">
                <div class="detail-item">
                    <strong>Deskripsi:</strong>
                    <p id="sideDetailDeskripsi"></p>
                </div>
                <div class="detail-item">
                    <strong>Mulai:</strong>
                    <p id="sideDetailTanggalMulai"></p>
                </div>
                <div class="detail-item">
                    <strong>Berakhir:</strong>
                    <p id="sideDetailTanggalBerakhir"></p>
                </div>
                <div class="detail-item">
                    <strong>Durasi:</strong>
                    <p id="sideDetailDurasi"></p>
                </div>
                <div class="detail-item">
                    <strong>Prioritas:</strong>
                    <p><span id="sideDetailPrioritas" class="prioritas-badge-side"></span></p>
                </div>
            </div>
            <div class="side-modal-footer">
                <button id="editSideAcaraBtn" class="btn-edit-side">
                    <iconify-icon icon="mdi:pencil" width="18" height="18"></iconify-icon> Edit
                </button>
                <button id="hapusSideAcaraBtn" class="btn-hapus-side">
                    <iconify-icon icon="mdi:trash-can-outline" width="18" height="18"></iconify-icon> Hapus
                </button>
            </div>
        </aside>


    <?php include '../footer.php' ?>
    
    <!-- Scripts -->
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <script src="../../asset/attributes/Atribute1.js"></script>
    <script src="../../asset/attributes/Atribute2.js"></script>


<script>
document.getElementById("reminder_template").addEventListener("change", function () {
    const isCustom = this.value === "custom";
    document.getElementById("custom_reminder").classList.toggle("active", isCustom);
});
</script>
    <script src="../../asset/js/6kalender.js"></script>
</body>
</html>

</html>

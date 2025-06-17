<?php
// filepath: c:\xampp\htdocs\Kelompok_3\pages\9filepage.php
include '../config/koneksi.php';
include '../config/session.php';

// Handle logout dan profile
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: 2loginpage.php');
    exit();
}

if (isset($_POST['profile'])) {
    header('Location: 12profile.php');
    exit();
}

$logo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM konten_statis WHERE gambar = 'logo-notezque.svg'"));
$id_user = $_SESSION['id_user'];
$dir = "../file/";

// Buat folder jika belum ada
if (!is_dir($dir)) {
    mkdir($dir, 0770, true);
}

// Ambil folder_id
$folder_id = $_GET['folder_id'];

// Ambil data folder
$sql_folder = "SELECT * FROM tambahfolder WHERE id_folder = $folder_id AND id_user = $id_user";
$folder_result = mysqli_query($conn, $sql_folder);
$folder = mysqli_fetch_assoc($folder_result);

// Pesan success dan error
$success = '';
$error = '';

// Hapus file
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    $check_sql = "SELECT tf.nama_file FROM tambahfile tf 
                  JOIN tambahfolder tfolder ON tf.id_folder = tfolder.id_folder 
                  WHERE tf.id_file = $delete_id AND tfolder.id_user = $id_user AND tf.id_folder = $folder_id";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        $file_data = mysqli_fetch_assoc($check_result);

        if (file_exists($dir . $file_data['nama_file'])) {
            unlink($dir . $file_data['nama_file']);
        }

        mysqli_query($conn, "DELETE FROM tambahfile WHERE id_file = $delete_id");
        $success = "File berhasil dihapus!";
    }

    header("Location: ?folder_id=$folder_id");
    exit();
}

// Tambah file
if (isset($_POST['simpan'])) {
    if ($_FILES['nama_file']['error'] == 0) {
        $file = $_FILES['nama_file'];
        $nama_file = $file['name'];
        $tmp_nama_file = $file['tmp_name'];
        $ukuran_file = $file['size'];
        $ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

        if ($ukuran_file > 10 * 1024 * 1024) {
            $error = "Ukuran file terlalu besar! Maksimal 10MB.";
        } else {
            $target = $dir . $nama_file;
            $i = 1;
            while (file_exists($target)) {
                $base_name = pathinfo($nama_file, PATHINFO_FILENAME);
                $target = $dir . $base_name . "_$i." . $ext;
                $nama_file = $base_name . "_$i." . $ext;
                $i++;
            }

            if (move_uploaded_file($tmp_nama_file, $target)) {
                $sql = "INSERT INTO tambahfile (id_user, id_folder, nama_file) VALUES ($id_user, $folder_id, '$nama_file')";
                mysqli_query($conn, $sql);
                $success = "File berhasil ditambahkan!";
            } else {
                $error = "Gagal mengupload file!";
            }
        }
    } else {
        $error = "File harus dipilih!";
    }
}

// Ambil daftar file
$sql = "SELECT * FROM tambahfile WHERE id_folder = $folder_id ORDER BY nama_file ASC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $folder['nama_materi'] ?> - NotezQue</title>
    <link rel="icon" type="image/x-icon" href="../uploads/<?= $logo['gambar'] ?>">
    <link rel="stylesheet" href="../Asset/CSS/9file.css">
    <link rel="stylesheet" href="../Asset/font/Font.css">
    <link rel="stylesheet" href="../Asset/attributes/Atribute1.css">
    <link rel="stylesheet" href="../Asset/attributes/Atribute2.css">
    <link rel="stylesheet" href="../Asset/attributes/Atribute3.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
          integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" 
          crossorigin="anonymous" referrerpolicy="no-referrer">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <div class="topNav-db">
            <nav>
                <a href="../pages/dashboard/5Dashboard.php">
                    <img src="../uploads/<?php echo htmlspecialchars($logo['gambar']); ?>" alt="Logo NotezQue">
                    </a>
                    <div class="dropdown">
                        <i class="dropdown-button" style="color: white;">
                            <iconify-icon icon="iconamoon:profile-light" width="36" height="36"></iconify-icon>
                        </i>
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
                    <i id="tombol" style="color: white;">
                        <iconify-icon icon="tabler:menu-2" width="32" height="32"></iconify-icon>
                    </i>
                    <i id="batal" style="color: white;">
                        <iconify-icon icon="tabler:menu-3" width="32" height="32"></iconify-icon>
                    </i>
                </label>
                <div class="sideNav-db">
                    <nav>
                        <h2>Menu</h2>
                        <a href="../pages/dashboard/5Dashboard.php">
                            <iconify-icon icon="ic:round-space-dashboard" width="38" height="38"></iconify-icon>
                            <span>Dashboard</span>
                        </a>
                        <a href="./kalender/6kalender.php">
                            <iconify-icon icon="uim:schedule" width="38" height="38"></iconify-icon>
                            <span>Kalender</span>
                        </a>
                        <a href="7listTugas.php">
                            <iconify-icon icon="fluent:task-list-square-ltr-24-filled" width="38"
                                height="38"></iconify-icon>
                            <span>Tugas</span>
                        </a>
                        <a href="8tambahMateri.php" class="active">
                            <iconify-icon icon="mingcute:book-5-line" width="38" height="38"></iconify-icon>
                            <span>Tambah Materi</span>
                        </a>
                    </nav>
                </div>
            </aside>
        </header>

    <!-- Main Content -->
    <main class="container">
        <!-- Alert Messages -->
        <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= $success ?>
                </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= $error ?>
                </div>
        <?php endif; ?>

        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <div class="hero-header">
                    <div class="hero-info">
                        <h1 class="hero-title"><?= $folder['nama_materi'] ?></h1>
                        <div class="hero-subtitle">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <span>Dosen: <?= $folder['nama_pengajar'] ?></span>
                        </div>
                        <div class="hero-actions">
                            <a href="8tambahMateri.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                                Kembali ke Materi
                            </a>
                            <button class="btn btn-primary" onclick="openModal()">
                                <i class="fas fa-plus-circle"></i>
                                Tambah File Baru
                            </button>
                        </div>
                    </div>
                    <?php if ($folder['gambar']): ?>
                        <img src="../GambarFolder/<?= $folder['gambar'] ?>" alt="<?= $folder['nama_materi'] ?>" class="hero-image">
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- File Content -->
        <section class="content-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-folder-open"></i>
                    File dalam Folder
                </h2>
                <div class="file-count">
                    <?= mysqli_num_rows($result) ?> File
                </div>
            </div>

            <?php if (mysqli_num_rows($result) > 0): ?>
                <div class="file-grid">
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <div class="file-card">
                            <div class="file-icon-container">
                                <?php
                                $ext = strtolower(pathinfo($row['nama_file'], PATHINFO_EXTENSION));
                                $icon = 'fas fa-file';
                                $color = '#6b7280';

                                switch ($ext) {
                                    case 'pdf':
                                        $icon = 'fas fa-file-pdf';
                                        $color = '#ef4444';
                                        break;
                                    case 'doc':
                                    case 'docx':
                                        $icon = 'fas fa-file-word';
                                        $color = '#2563eb';
                                        break;
                                    case 'xls':
                                    case 'xlsx':
                                        $icon = 'fas fa-file-excel';
                                        $color = '#16a34a';
                                        break;
                                    case 'ppt':
                                    case 'pptx':
                                        $icon = 'fas fa-file-powerpoint';
                                        $color = '#ea580c';
                                        break;
                                    case 'jpg':
                                    case 'jpeg':
                                    case 'png':
                                    case 'gif':
                                    case 'webp':
                                        $icon = 'fas fa-file-image';
                                        $color = '#7c3aed';
                                        break;
                                    case 'txt':
                                        $icon = 'fas fa-file-alt';
                                        $color = '#4b5563';
                                        break;
                                    case 'zip':
                                    case 'rar':
                                        $icon = 'fas fa-file-archive';
                                        $color = '#f59e0b';
                                        break;
                                }
                                ?>
                                <i class="<?= $icon ?>" style="color: <?= $color ?>; font-size: 2rem;"></i>
                            </div>
                            
                            <h3 class="file-name"><?= $row['nama_file'] ?></h3>
                            
                            <div class="file-actions">
                                <a href="../file/<?= $row['nama_file'] ?>" target="_blank" class="file-action-btn view">
                                    <i class="fas fa-eye"></i>
                                    Lihat
                                </a>
                                <a href="../file/<?= $row['nama_file'] ?>" download class="file-action-btn download">
                                    <i class="fas fa-download"></i>
                                    Download
                                </a>
                                <button onclick="confirmDelete(<?= $row['id_file'] ?>, '<?= $row['nama_file'] ?>')" class="file-action-btn delete">
                                    <i class="fas fa-trash"></i>
                                    Hapus
                                </button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-file-plus"></i>
                    </div>
                    <h3 class="empty-title">Belum Ada File</h3>
                    <p class="empty-description">
                        Folder "<?= $folder['nama_materi'] ?>" masih kosong. 
                        Mulai dengan menambahkan file pertama Anda.
                    </p>
                    <button class="btn btn-primary" onclick="openModal()">
                        <i class="fas fa-plus"></i>
                        Tambah File Pertama
                    </button>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <!-- Modal Tambah File -->
    <div id="fileModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h2 class="modal-title">
                    <i class="fas fa-file-plus"></i>
                    Tambah File Baru
                </h2>
            </div>
            
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-file"></i>
                        Pilih File
                    </label>
                    <input type="file" name="nama_file" class="form-input" required>
                    <div class="form-help">
                        Maksimal 10MB. Semua format file didukung.
                    </div>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">
                        <i class="fas fa-times"></i>
                        Batal
                    </button>
                    <button type="submit" name="simpan" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Simpan File
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <script src="../asset/attributes/Atribute1.js"></script>
    <script src="../asset/attributes/Atribute2.js"></script>
    <script src="../asset/js/9file.js"></script></script>
</body>
</html>
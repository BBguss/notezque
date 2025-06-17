<?php
include '../../config/koneksi.php';
include '../../config/session.php';


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM tugas WHERE id_tugas = $id");
    header("Location:" . $_SERVER['PHP_SELF']);
    exit();
}

$query = "SELECT * FROM tugas WHERE id_user = $_SESSION[id_user] ORDER BY deadline1 ASC ";
$dataTugas = mysqli_query($conn, $query);
$query1 = "SELECT judul_acara, desc_acara, waktu_acara FROM kalender_acara WHERE id_user = $_SESSION[id_user]";
$dataAcara = mysqli_query($conn, $query1);
$logo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM konten_statis WHERE gambar = 'logo-notezque.svg'"));


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NotezQue</title>
    <link rel="icon" type="image/x-icon" href="../../uploads/<?= $logo['gambar'] ?>">
    <link rel="stylesheet" href="../../Asset/css/5dashboard.css">
    <link rel="stylesheet" href="../../Asset/font/Font.css">
    <link rel="stylesheet" href="../../Asset/attributes/Atribute1.css">
    <link rel="stylesheet" href="../../Asset/attributes/Atribute2.css">
    <link rel="stylesheet" href="../../Asset/attributes/Atribute3.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&display=swap"
        rel="stylesheet">

        <style>
            /* Container untuk icon navbar */
.nav-icons {
    display: flex;
    align-items: center;
    gap: 15px;
}

/* Styling untuk container notifikasi */
.notification-container {
    position: relative;
    display: inline-block;
      z-index: 10000;
}

/* Styling untuk icon lonceng */
.notification-bell {
    position: relative;
    cursor: pointer;
    color: white;
    padding: 8px;
    border-radius: 50%;
    transition: background-color 0.3s ease;
}

.notification-bell:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

/* Badge untuk jumlah notifikasi */
.notification-badge {
    position: absolute;
    top: -2px;
    right: -2px;
    background-color: #ff4444;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: bold;
    min-width: 20px;
}

.notification-badge.hidden {
    display: none;
}

/* Dropdown notifikasi */
.notification-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    width: 350px;
    max-height: 400px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    z-index: 99999 !important;;
    display: none;
    border: 1px solid #e0e0e0;
}

.notification-dropdown.show {
    display: block !important;
}

/* Header dropdown */
.notification-header {
    padding: 15px 20px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.notification-header h3 {
    margin: 0;
    font-size: 16px;
    color: #333;
}

.mark-all-read {
    background: none;
    border: none;
    color: #007bff;
    cursor: pointer;
    font-size: 12px;
    padding: 4px 8px;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.mark-all-read:hover {
    background-color: #f8f9fa;
}

/* List notifikasi */
.notification-list {
    max-height: 300px;
    overflow-y: auto;
}

/* Item notifikasi */
.notification-item {
    padding: 12px 20px;
    border-bottom: 1px solid #f5f5f5;
    cursor: pointer;
    transition: background-color 0.3s ease;
    position: relative;
}

.notification-item:hover {
    background-color: #f8f9fa;
}

.notification-item.unread {
    background-color: #f0f8ff;
    border-left: 3px solid #007bff;
}

.notification-item.unread::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 50%;
    transform: translateY(-50%);
    width: 8px;
    height: 8px;
    background-color: #007bff;
    border-radius: 50%;
}

.notification-title {
    font-weight: bold;
    color: #333;
    font-size: 14px;
    margin-bottom: 4px;
}

.notification-message {
    color: #666;
    font-size: 13px;
    margin-bottom: 4px;
}

.notification-time {
    color: #999;
    font-size: 11px;
}

/* Jika tidak ada notifikasi */
.no-notifications {
    padding: 40px 20px;
    text-align: center;
    color: #999;
}

.no-notifications p {
    margin: 0;
    font-size: 14px;
}

/* Responsive */
@media (max-width: 768px) {
    .notification-dropdown {
        width: 300px;
        right: -50px;
    }
}

/* animasi greeting user */
.toast {
  position: fixed;
  top: 20px;
  right: 20px;
  background-color: blue;
  color: #fff;
  padding: 14px 24px;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
  font-size: 16px;
  z-index: 99999;
  opacity: 0;
  transform: translateY(-20px);
  animation: slideIn 0.5s forwards, fadeOut 0.5s 3.5s forwards;
}

@keyframes slideIn {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeOut {
  to {
    opacity: 0;
    transform: translateY(-20px);
    z-index: -5;
  }
}



    </style>
</head>

<body>
    <header>
        <div class="topNav-db">
             <nav>
        <a href="5Dashboard.php"><img src="../../uploads/<?= $logo['gambar'] ?>" alt="Logo"></a>
        <div class="nav-icons">
            <!-- Icon Notifikasi -->
            <div class="notification-container">
            <div class="notification-bell" id="notificationBell">
    <iconify-icon icon="mdi:bell-outline" width="28" height="28"></iconify-icon>
    <span class="notification-badge" id="notificationBadge">0</span>
</div>
<div class="notification-dropdown" id="notificationDropdown">
    <div class="notification-header">
        <h3>Notifikasi</h3>
        <button class="mark-all-read" id="markAllRead">Tandai Semua Dibaca</button>
    </div>
    <div class="notification-list" id="notificationList">
        <div class="no-notifications">
            <p>Tidak ada notifikasi</p>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['welcome_message'])): ?>
  "<div class="toast id="loginToast">
    <?= $_SESSION['welcome_message']; ?>
  </div>
  <?php unset($_SESSION['welcome_message']); ?>
<?php endif; ?>


            </div>
            
            <!-- Icon Profile -->
            <div class="dropdown">
                <i class="dropdown-button" style="color: white;">
                    <iconify-icon icon="iconamoon:profile-light" width="36" height="36"></iconify-icon>
                </i>
                <div class="dropdown-content">
                    <a href="../../pages/12profile.php"><button type="submit" name="profile">Profile</button></a>
                    <form action="" method="post">
                        <button type="submit" name="logout">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
        </div>

        <aside>
            <input type="checkbox" name="" id="check">
            <label for="check">
                <i id="tombol" style="color: white;"><iconify-icon icon="tabler:menu-2" width="32"
                        height="32"></iconify-icon></i>
                <i id="batal" style="color: white;"><iconify-icon icon="tabler:menu-3" width="32"
                        height="32"></iconify-icon></i>
            </label>
            <div class="sideNav-db">
                <nav>
                    <h2>Menu</h2>
                    <a href="5Dashboard.php" class="active">
                        <iconify-icon icon="ic:round-space-dashboard" width="38" height="38"></iconify-icon>
                        <span>Dashboard</span>
                    </a>
                    <a href="../kalender/6kalender.php">
                        <iconify-icon icon="uim:schedule" width="38" height="38"></iconify-icon>
                        <span>Kalender</span>
                    </a>
                    <a href="../7listTugas.php">
                        <iconify-icon icon="fluent:task-list-square-ltr-24-filled" width="38"
                            height="38"></iconify-icon>
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
    <main id="main">
        <div class="mainContainer-db">
            <h1 class="greeting">Halo <?= $_SESSION['username'] ?> selamat datang</h1>
            <div class="container-atas">
                <div class="jadwalKuliah-db">
                    <h2>Acara hari ini</h2>
                    <?php 
                    if ($dataAcara && mysqli_num_rows($dataAcara) > 0) {
                        while ($row = mysqli_fetch_assoc($dataAcara)) {
                            echo '
                            <div class="nama-matkul">
                            <a href="#" style="text-decoration: none;color: black;">
                            <h2>' . ($row['judul_acara']) . '</h2>
                            </a>
                            <div class="keterangan">
                                <div class="waktu">
                                    <h4>Waktu</h4>
                                    <p>'. ($row['waktu_acara']).'</p>
                                </div>
                                <div class="ruangan">
                                    <h4>Deskripsi</h4>
                                    <p>'.($row['desc_acara']).'</p>
                                </div>
                            </div>
                        </div>
                            ';
                        }
                    }
                    ?>
                    
                </div>

                <div class="kalender-db">
                    <div class="kalender-nav">
                        <div class="kal-nextprev">
                            <button class="prev"><iconify-icon icon="mingcute:left-fill" width="24"
                                    height="24"></iconify-icon></button>
                            <div class="tanggal"></div>
                            <button class="next"><iconify-icon icon="mingcute:right-fill" width="24"
                                    height="24"></iconify-icon></button>
                        </div>
                    </div>
                    <div class="mingguan">
                        <div class="minggu" style="color: red;">Ming</div>
                        <div class="minggu">Sen</div>
                        <div class="minggu">Sel</div>
                        <div class="minggu">Rab</div>
                        <div class="minggu">Kam</div>
                        <div class="minggu">Jum</div>
                        <div class="minggu" style="color: red;">Sab</div>
                    </div>
                    <div class="harian"></div>

                </div>
            </div>


            <div class="aksesCepat">
                <div class="q-pilihanMatkul">
                    <h2 style="font-size: 24px; text-align: center;">Mata Kuliah</h2>
                    <div class="q-matkulContainer">

                        <div class="q-matkul">
                            <div class="matkulImg"><a href="#"><img
                                        src="https://engineering.jhu.edu/ams/wp-content/uploads/2021/06/hero-image-research-500x282.jpeg"
                                        alt="diskrit.jpg"></a></div>
                            <div class="q-namaMatkul">
                                <a href="#">Matematika Diskrit</a>
                                <p>Pak Hanung</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <aside>
            <div class="aside-container">
                <h1 class="task-title" style="text-align: center">List Tugas</h1>
                <div class="listTugas-db">
                    <?php
                    if ($dataTugas && mysqli_num_rows($dataTugas) > 0) {
                        while ($row = mysqli_fetch_assoc($dataTugas)) {
                            echo '
                        <div class="tmatkul">
                            <div class="deadline">
                                <p><strong>Deadline:</strong> ' .($row['deadline1']) . " [" .($row['deadline2']) . "]" . '</p>
                            </div>
                            <h4><strong>Judul tugas:</strong> ' . ($row['judul_tugas']) . '</h4>
                            <p><strong>Mata Kuliah:</strong> ' . ($row['matkul']) . '</p>
                            <a href="?id=' . $row['id_tugas'] . '" onclick="return confirm(\'Yakin hapus?\')" class="hapus">
                                <iconify-icon class="cancel-btn" icon="material-symbols:cancel-outline-rounded" width="32" height="32"></iconify-icon>
                            </a>
                        </div>
                        ';
                        }
                    } else {
                        echo '<p class="no-events" style="text-align: center">yeay tidak ada tugas hari ini</p>';
                    }
                    ?>
                </div>
            </div>
        </aside>
    </main>
    <?php include '../footer.php' ?>

    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <script src="../../asset/attributes/Atribute1.js"></script>
    <script src="../../asset/attributes/Atribute2.js"></script>
    <script src="../../asset/js/5dashboard.js"></script>
</body>

</html>

<?php
include '../koneksi.php';
session_start();

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: 2loginpage.php");
    exit();
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('location: 1homepage.php');
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM tugas WHERE id_tugas = $id");
    header("Location:". $_SERVER['PHP_SELF']);
    exit();
}

$query = "SELECT * FROM tugas ORDER BY deadline ASC";
$dataTugas = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NotezQue</title>
    <link rel="icon" type="image/x-icon" href="../asset/images/logoNotezQue.svg">
    <link rel="stylesheet" href="../asset/css/5dashboard.css">
    <link rel="stylesheet" href="../asset/font/Font.css">
    <link rel="stylesheet" href="../asset/attributes/Atribute1.css">
    <link rel="stylesheet" href="../asset/attributes/Atribute2.css">
    <link rel="stylesheet" href="../asset/attributes/Atribute3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

</head>
<body>
    <header>
        <div class="topNav-db">
            <nav>
                <div class="logo">
                    <a href="5Dashboard.php"><img src="../asset/images/logoNotezQue.svg" alt=""></a>
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
                    <a href="5Dashboard.php" class="active">
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

    <main id="main">
        <div class="mainContainer-db">
            <h1 class="greeting">Halo <?= $_SESSION['username']?> selamat datang kembali</h1>
            <div class="container-atas">
                <div class="jadwalKuliah-db">
                    <div class="nama-matkul">
                        <a href="#" style="text-decoration: none;color: black;"><h3>DESAIN ANTARMUKA PENGGUNA</h3></a>
                        <div class="keterangan">
                            <div class="waktu">
                                <h4>Waktu</h4>
                                <p>07:30 - 10:30</p>
                            </div>
                            <div class="ruangan">
                                <h4>Ruangan</h4>
                                <p>D4-40</p>
                            </div>
                            <div class="dosen">
                                <h4>Dosen</h4>
                                <p>Bu Ais</p>
                            </div>
                        </div>
                    </div>

                    <div class="nama-matkul">
                        <a href="#" style="text-decoration: none;color: black;"><h3>JARINGAN KOMPUTER</h3></a>
                        <div class="keterangan">
                            <div class="waktu">
                                <h4>Waktu</h4>
                                <p>10:30 - 12:30</p>
                            </div>
                            <div class="ruangan">
                                <h4>Ruangan</h4>
                                <p>B3</p>
                            </div>
                            <div class="dosen">
                                <h4>Dosen</h4>
                                <p>Pak Tedi</p>
                            </div>
                        </div>
                    </div>
                   
                    <div class="nama-matkul">
                        <a href="#" style="text-decoration: none;color: black;"><h3>ALGORITMA dan PEMOGRAMAN</h3></a>
                        <div class="keterangan">
                            <div class="waktu">
                                <h4>Waktu</h4>
                                <p>13:30 - 15:30</p>
                            </div>
                            <div class="ruangan">
                                <h4>Ruangan</h4>
                                <p>C3</p>
                            </div>
                            <div class="dosen">
                                <h4>Dosen</h4>
                                <p>Pak Patrick</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="kalender-db">
                    <div class="bln">
                        <a href="/TUBES/Jadwal page/jadwal.php"><div class="tanggal" style="font-weight: bold;"></div></a>
                        <div class="kal-nextprev">
                            <i class="prev"><iconify-icon icon="mingcute:left-fill" width="24" height="24"></iconify-icon></i>
                            <i class="next"><iconify-icon icon="mingcute:right-fill" width="24" height="24"></iconify-icon></i>
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
                    <div class="harian">
                        
                    </div>
                </div>
            </div>

           <div class="aksesCepat">
            <div class="q-pilihanMatkul">
                <h2 style="font-size: 24px; text-align: center;">Mata Kuliah</h2>
                <div class="q-matkulContainer">
                    <div class="q-matkul">
                        <div class="matkulImg"><a href="#"><img src="https://engineering.jhu.edu/ams/wp-content/uploads/2021/06/hero-image-research-500x282.jpeg" alt="diskrit.jpg"></a></div>
                        <div class="q-namaMatkul">
                            <a href="#">Matematika Diskrit</a>
                            <p>Pak Hanung</p>
                        </div>
                    </div>

                    <div class="q-matkul">
                        <div class="matkulImg"><a href="#"><img src="https://blog-static.userpilot.com/blog/wp-content/uploads/2024/07/what-is-a-user-interface-design-key-ui-principles_03a96dbb6560de961f7a43a349ab9814_2000.png" alt="diskrit.jpg"></a></div>
                        <div class="q-namaMatkul">
                            <a href="#">User Interface Design</a>
                            <p>Bu Ais</p>
                        </div>
                    </div>

                    <div class="q-matkul">
                        <div class="matkulImg"><a href="page9_File Mata Kuliah.php"><img src="https://images.shiksha.com/mediadata/ugcDocuments/images/wordpressImages/2021_12_Difference-Between-Algorithm-and-Program_480x360.jpg" alt="diskrit.jpg"></a></div>
                        <div class="q-namaMatkul">
                            <a href="page9_File Mata Kuliah.php">Algoritma dan Pemograman</a>
                            <p>Pak Patrick</p>
                        </div>
                    </div>
                </div>
        
            </div>
            </div>
            
        </div>

        <aside>
            <div class="aside-container">
                <h1 class="task-title" style="text-align: center">List acara</h1>
                <div class="listTugas-db">
                    <?php
                    if ($dataTugas && mysqli_num_rows($dataTugas) > 0 ) {
                        while ($row = mysqli_fetch_assoc( $dataTugas )) {
                        $formatted_deadline = date('d-m-Y H.i', strtotime($row['deadline']));
                        echo '
                        <div class="tmatkul">
                            <div class="deadline">
                                <p><strong>Deadline:</strong> ' . $formatted_deadline . '</p>
                            </div>
                            <h4><strong>Judul tugas:</strong> ' . htmlspecialchars($row['judul_tugas']) . '</h4>
                            <p><strong>Mata Kuliah:</strong> ' . htmlspecialchars($row['matkul']) . '</p>
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
    <?php include 'footer.php'?>

    <script src="../asset/js/5dashboard.js"></script>
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <script src="../asset/attributes/Atribute1.js"></script>
    <script src="../asset/attributes/Atribute2.js"></script>
</body>
</html>
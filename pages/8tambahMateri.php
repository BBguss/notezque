<?php
include '../config/koneksi.php';
include '../config/session.php';

$logo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM konten_statis WHERE gambar = 'logo-notezque.svg'"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NotezQue</title>
    <link rel="icon" type="image/x-icon" href="../uploads/<?= $logo['gambar'] ?>">
    <link rel="stylesheet" href="../asset/css/8tambahMateri.css">
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
            <a href="../pages/dashboard/5Dashboard.php"><img src="../uploads/<?= $logo['gambar'] ?>" alt="Logo"></a>
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
                    <a href="../pages/dashboard/5Dashboard.php" class="active">
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

    <main class="container" id="main">
        <h1>List Mata Kuliah</h1>
        <div class="btn">
            <button class="open-modal-btn" onclick="openModal()">Tambah Mata Kuliah</button>
        </div>
    
        <!-- Modal -->
        <div id="modal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <div class="modal-header">Form Mata Kuliah</div>
                <form id="form" onsubmit="return submitForm(event)">
                    <div class="form-group">
                        <label for="image">Pilih Gambar:</label>
                        <input type="file" id="image" name="image" accept="image/*" required>
                    </div>
                    <div class="form-group">
                        <label for="subject">Mata Kuliah:</label>
                        <input type="text" id="subject" name="subject" placeholder="Masukkan nama mata kuliah" required>
                    </div>
                    <div class="form-group">
                        <label for="dosen">Nama Dosen:</label>
                        <input type="text" id="dosen" name="dosen" placeholder="Masukkan nama dosen" required>
                    </div>
                    <button type="submit" class="submit-btn">Kirim</button>
                </form>
            </div>
        </div>
    
        <!-- Div untuk menampilkan hasil -->
        <div id="result" class="result-container">
            <div class="result-card">
                <img src="https://engineering.jhu.edu/ams/wp-content/uploads/2021/06/hero-image-research-500x282.jpeg" alt="diskrit.jpg">
                <h3>Matematika Diskrit</h3>
                <p>Pak Hanung</p>
            </div>
            <div class="result-card">
                <img src="https://blog-static.userpilot.com/blog/wp-content/uploads/2024/07/what-is-a-user-interface-design-key-ui-principles_03a96dbb6560de961f7a43a349ab9814_2000.png" alt="diskrit.jpg">
                <h3>User Interface Design</h3>
                <p>Bu Ais</p>
            </div>
            <div class="result-card" href="9filepage.php">
                <a href="9filepage.php"><img src="https://images.shiksha.com/mediadata/ugcDocuments/images/wordpressImages/2021_12_Difference-Between-Algorithm-and-Program_480x360.jpg" alt="diskrit.jpg"></a>
                <a href="9filepage.php"><h3>Algoritma dan Pemograman</h3></a>
                <a href="9filepage.php"><p>Pak Patrick</p></a>
            </div>
        </div>
    </main>
    <?php include 'footer.php' ?>
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <script src="../asset/attributes/Atribute1.js"></script>
    <script src="../asset/attributes/Atribute2.js"></script>
    <script src="../asset/JS/8tambahMateri.js"></script>
</body>
</html>
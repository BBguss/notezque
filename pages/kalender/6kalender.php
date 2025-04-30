<?php
session_start();

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: Page2_loginpage.php");
    exit();
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('location: Page1_homepage.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NotezQue</title>
    <link rel="icon" type="image/x-icon" href="../../asset/images/logoNotezQue.svg">
    <link rel="stylesheet" href="../../Asset/css/6kalender.css">
    <link rel="stylesheet" href="../../asset/font/Font.css">
    <link rel="stylesheet" href="../../asset/attributes/Atribute1.css">
    <link rel="stylesheet" href="../../asset/attributes/Atribute2.css">
    <link rel="stylesheet" href="../../asset/attributes/Atribute3.css">
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
                    <a href="../5Dashboard.php"><img src="../../asset/images/logoNotezQue.svg" alt=""></a>
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
                    <a href="../5Dashboard.php" class="active">
                        <iconify-icon icon="ic:round-space-dashboard" width="38" height="38"></iconify-icon>
                        <span>Dashboard</span>
                    </a>
                    <a href="../kalender/6kalender.php">
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

   <main id="main">
        <div class="main-container">
            <div class="jadwal">
                <div class="prev">
                    <i><iconify-icon icon="mingcute:left-fill" width="32" height="32"
                            style="color: #4285f4"></iconify-icon></i>
                </div>
                <div class="bln-thn"></div>
                <div class="next">
                    <i><iconify-icon icon="mingcute:right-fill" width="32" height="32"
                            style="color: #4285f4"></iconify-icon></i>
                </div>
            </div>

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
                <div class="tglBln">

                </div>
            </div>
        </div>


        <div class="side-listAcara">
            <h2 class="side-title" style="text-align: center">List acara</h2>;

        </div>

        <div class="acara-container">

        </div>


    </main>

    <aside id="modal" class="modal" style="">
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
                    <div class="btn">
                        <button type="button" class="btl" id="closeBtn" name="batal">Batal</button>
                        <button type="button" class="save" id="save" name="save">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </aside>

    <?php include '../footer.php' ?>
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <script src="../../asset/attributes/Atribute1.js"></script>
    <script src="../../asset/attributes/Atribute2.js"></script>
    <script src="../../asset/js/6kalender.js"></script>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NotezQue</title>
    <link rel="icon" type="image/x-icon" href="Logo NotezQue.svg">
    <link rel="stylesheet" href="../Asset/css/Page6_Style.css">
    <link rel="stylesheet" href="../asset/font/Font.css">
    <link rel="stylesheet" href="../asset/css/Atribute1_Top Nav.css">
    <link rel="stylesheet" href="../asset/css/Atribute2_Side Nav.css">
    <link rel="stylesheet" href="../asset/css/Atribute3_Footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

</head>

<body>
    <header>
        <div class="topNav-db">
            <nav>
                <div class="logo">
                    <a href="page5_Dasboard.php"><img src="Logo NotezQue.svg" alt=""></a>
                </div>
                <div class="dropdown">
                    <i class="dropdown-button" style="color: white;"><iconify-icon icon="iconamoon:profile-light"
                            width="36" height="36"></iconify-icon></i>
                    <div class="dropdown-content">
                        <a href="#" onclick="">Proflie</a>
                        <a href="page1_homepage.php" onclick="logout()">Logout</a>
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
                    <a href="page5_Dasboard.php" class="active">
                        <iconify-icon icon="ic:round-space-dashboard" width="38" height="38"></iconify-icon>
                        <span>Dashboard</span>
                    </a>
                    <a href="page6_Kalender.php">
                        <iconify-icon icon="uim:schedule" width="38" height="38"></iconify-icon>
                        <span>Kalender</span>
                    </a>
                    <a href="page7_List Tugas.php">
                        <iconify-icon icon="fluent:task-list-square-ltr-24-filled" width="38"
                            height="38"></iconify-icon>
                        <span>Tugas</span>
                    </a>
                    <a href="page8_Mata Kuliah.php">
                        <iconify-icon icon="mingcute:book-5-line" width="38" height="38"></iconify-icon>
                        <span>Mata kuliah</span>
                    </a>
                </nav>
            </div>
        </aside>

    </header>

    <main id="main">
        <div class="main-container">
            <div class="jadwal">
                <div>
                    <i class="prev"><iconify-icon icon="mingcute:left-fill" width="32" height="32"></iconify-icon></i>
                </div>
                <div class="bln-thn"></div>
                <div>
                    <i class="next"><iconify-icon icon="mingcute:right-fill" width="32" height="32"></iconify-icon></i>
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
            <h2 style="text-align: center;">List acara</h2>
        </div>

        <div class="acara-container">

        </div>


    </main>

    <aside id="modal" class="modal">
        <div class="form">
            <form action="/submit">
                <div class="inputan">
                    <label for="title">
                        <input type="text" id="title" placeholder="Masukan Judul" required>
                    </label>
                    <label for="desk">
                        <textarea id="desk" placeholder="Tambah Deskripsi" required></textarea>
                    </label>
                    <label for="tenggat">
                        <input type="time" id="tenggat" required>
                    </label>
                    <div class="btn">
                        <button type="button" class="btl" id="closeBtn">Batal</button>
                        <button type="button" class="save" id="save">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </aside>
    <?php include 'footer.php'?>
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <script src="../asset/js/Atribute 1_Top Nav.js"></script>
    <script src="../asset/js/Atribute 2_Side Nav.js"></script>
    <script src="../asset/js/page 6_Script.js"></script>
</body>

</html>
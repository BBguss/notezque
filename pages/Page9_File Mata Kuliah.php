<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NotezQue</title>
    <link rel="icon" type="image/x-icon" href="../Asset/images/Logo NotezQue.svg">
    <link rel="stylesheet" href="../Asset/css/Page9_Style.css">
    <link rel="stylesheet" href="../Asset/css/../Asset/font/Font.css">
    <link rel="stylesheet" href="../Asset/css/Atribute1_Top Nav.css">
    <link rel="stylesheet" href="../Asset/css/Atribute2_Side Nav.css">
    <link rel="stylesheet" href="../Asset/css/Atribute3_Footer.css">
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
                    <a href="page5_Dasboard.php"><img src="../Asset/images/Logo NotezQue.svg" alt=""></a>
                </div>
                <div class="dropdown">
                    <i  style="color: white;"><iconify-icon icon="iconamoon:profile-light" width="36" height="36"></iconify-icon></i>
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
                        <i id="tombol" style="color: white;" ><iconify-icon icon="tabler:menu-2" width="32" height="32"></iconify-icon></i>
                        <i id="batal" style="color: white;"><iconify-icon icon="tabler:menu-3" width="32" height="32"></iconify-icon></i>
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
                        <iconify-icon icon="fluent:task-list-square-ltr-24-filled" width="38" height="38"></iconify-icon>
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

    <main id="main" class="main">
        <div class="main-container">
            <div class="bannerJudul">
                <img src="https://images.shiksha.com/mediadata/ugcDocuments/images/wordpressImages/2021_12_Difference-Between-Algorithm-and-Program_480x360.jpg" alt="diskrit.jpg">
                <h1>Algoritma dan Pemograman</h1>
                <div class="btn">
                    <button class="cd-btn cd-btn--primary" aria-controls="dialog-1">Tambah Folder</button>
                  </div>
            </div>

            <div class="folderTugas">
                <div class="folder">
                        <a href="page10_File Pelajaran.php"><iconify-icon class="i-folder" icon="fxemoji:folder" width="32" height="32"></iconify-icon></a>
                        <a href="page10_File Pelajaran.php">
                            <h4 class="namfolder">Percabangan</h4></a>
                    <div class="dropdown">
                    <i  style="color: rgb(0, 0, 0);"><iconify-icon icon="proicons:more" width="28" height="28"></iconify-icon></i>
                        <div class="dropdown-content">
                            <a href="#" onclick="hapus()" class="hapus">Hapus</a>
                            <a href="#" onclick="hapus()" class="edit">Edit</a>
                        </div>
                    </div>
                </div>
                <div class="folder">
                    <iconify-icon class="i-folder" icon="fxemoji:folder" width="32" height="32"></iconify-icon>
                    <h4 class="namfolder" >Nested Loop</h4>
                    <div class="dropdown">
                    <i  style="color: rgb(0, 0, 0);"><iconify-icon icon="proicons:more" width="28" height="28"></iconify-icon></i>
                        <div class="dropdown-content">
                            <a href="#" onclick="hapus()" class="hapus">Hapus</a>
                            <a href="#" onclick="hapus()" class="edit">Edit</a>
                        </div>
                    </div>
                    
                </div>
                <div class="folder">
                        <iconify-icon class="i-folder" icon="fxemoji:folder" width="32" height="32"></iconify-icon>
                        <h4 class="namfolder">Modular</h4>
                    <div class="dropdown">
                    <i  style="color: rgb(0, 0, 0);"><iconify-icon icon="proicons:more" width="28" height="28"></iconify-icon></i>
                        <div class="dropdown-content">
                            <a href="#" onclick="hapus()" class="hapus">Hapus</a>
                            <a href="#" onclick="hapus()" class="edit">Edit</a>
                        </div>
                    </div>
                </div>
                <div class="folder">
                        <iconify-icon class="i-folder" icon="fxemoji:folder" width="32" height="32"></iconify-icon>
                        <h4 class="namfolder" >If Else</h4>
                    <div class="dropdown">
                    <i  style="color: rgb(0, 0, 0);"><iconify-icon icon="proicons:more" width="28" height="28"></iconify-icon></i>
                        <div class="dropdown-content">
                            <a href="#" onclick="hapus()" class="hapus">Hapus</a>
                            <a href="#" onclick="hapus()" class="edit">Edit</a>
                        </div>
                    </div>
                </div>
                <div class="folder">
                        <iconify-icon class="i-folder" icon="fxemoji:folder" width="32" height="32"></iconify-icon>
                        <h4 class="namfolder" >Perulangan</h4>
                    <div class="dropdown">
                    <i  style="color: rgb(0, 0, 0);"><iconify-icon icon="proicons:more" width="28" height="28"></iconify-icon></i>
                        <div class="dropdown-content">
                            <a href="#" onclick="hapus()" class="hapus">Hapus</a>
                            <a href="#" onclick="hapus()" class="edit">Edit</a>
                        </div>
                    </div>
                </div>
                <div class="folder">
                        <iconify-icon class="i-folder" icon="fxemoji:folder" width="32" height="32"></iconify-icon>
                        <h4 class="namfolder" >Switch case</h4>
                    <div class="dropdown">
                    <i  style="color: rgb(0, 0, 0);"><iconify-icon icon="proicons:more" width="28" height="28"></iconify-icon></i>
                        <div class="dropdown-content">
                            <a href="#" onclick="hapus()" class="hapus">Hapus</a>
                            <a href="#" onclick="hapus()" class="edit">Edit</a>
                        </div>
                    </div>
                </div>
                <div class="folder">
                    <iconify-icon class="i-folder" icon="fxemoji:folder" width="32" height="32"></iconify-icon>
                    <h4 class="namfolder" >For Loop</h4>
                    <div class="dropdown">
                    <i  style="color: rgb(0, 0, 0);"><iconify-icon icon="proicons:more" width="28" height="28"></iconify-icon></i>
                        <div class="dropdown-content">
                            <a href="#" onclick="hapus()" class="hapus">Hapus</a>
                            <a href="#" onclick="hapus()" class="edit">Edit</a>
                        </div>
                    </div>
                </div>
                <div class="folder">
                    <iconify-icon class="i-folder" icon="fxemoji:folder" width="32" height="32"></iconify-icon>
                    <h4 class="namfolder">Do While</h4>
                    <div class="dropdown">
                    <i  style="color: rgb(0, 0, 0);"><iconify-icon icon="proicons:more" width="28" height="28"></iconify-icon></i>
                        <div class="dropdown-content">
                            <a href="#" onclick="hapus()" class="hapus">Hapus</a>
                            <a href="#" onclick="hapus()" class="edit">Edit</a>
                        </div>
                    </div>
                </div>
                </div>
            </div>
    </main>

    <div class="modal-tbhtgs">
        <div class="modal">
            <form action="/submit">
                <div class="inputan">
                    <input type="text" name="" id="formtbhtgs" placeholder="Buat folder baru" required>
                </div>
                <div class="tombol">
                    <button type="button" class="batal">Batal</button>
                    <button type="button" class="buat">Buat</button>
                </div>
            </form>
        </div>
    </div>
    <?php include 'footer.php' ?>
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <script src="../Asset/JS/Atribute 1_Top Nav.js"></script>
    <script src="../Asset/JS/Atribute 2_Side Nav.js"></script>
    <script src="../Asset/JS/page 9_Script.js"></script>
</body>
</html>
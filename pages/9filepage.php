<?php
include '../config/koneksi.php';
include '../config/session.php';

$logo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM konten_statis WHERE gambar = 'logo-notezque.svg'"));

$id_user = $_SESSION['id_user'];
$dir = "../file/";

// Menampilkan daftar file di folder yang dipilih
$folder_id = $_GET['folder_id'];
$sql = "SELECT * FROM tambahfile WHERE id_folder = $folder_id";
$result = $conn->query($sql);

// Menampilkan nama folder
$sql_folder = "SELECT * FROM tambahfolder WHERE id_folder = $folder_id";
$folder_result = $conn->query($sql_folder);
$folder = $folder_result->fetch_assoc();

// Menangani penghapusan file
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM tambahfile WHERE id_file = $delete_id";
    $conn->query($sql);
}

// Menangani penambahan file
if (isset($_POST['simpan'])) {
    $id_user = $_SESSION['id_user'];
    $id_folder = $folder['id_folder'];
    $file = $_FILES['nama_file'];
    $nama_file = $file['name'];
    $tmp_nama_file = $file['tmp_name'];
    $ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

    $target = $dir . $nama_file;
    $i = 1;
    while (file_exists($target)) {
        $base_name = pathinfo($nama_file, PATHINFO_FILENAME);
        $target = $dir . $base_name . "_$i." . $ext;
        $nama_file = $base_name . "_$i." . $ext;
        $i++;
    }

    if (move_uploaded_file($tmp_nama_file, $target)) {
    $sql = "INSERT INTO tambahfile (id_user, id_folder, nama_file) 
            VALUES ('$id_user', '$id_folder', '$nama_file')";
    $conn->query($sql);
  }


}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NotezQue</title>
    <link rel="icon" type="image/x-icon" href="../uploads/<?= $logo['gambar'] ?>">
    <link rel="stylesheet" href="../Asset/CSS/9file.css">
    <link rel="stylesheet" href="../Asset/CSS/87tambahMateri.css">
    <link rel="stylesheet" href="../Asset/font/Font.css">
    <link rel="stylesheet" href="../Asset/attributes/Atribute1.css">
    <link rel="stylesheet" href="../Asset/attributes/Atribute2.css">
    <link rel="stylesheet" href="../Asset/attributes/Atribute3.css">
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

    <main id="main" class="main">
        <div class="main-container">
            <div class="bannerJudul">
                <?php if (!empty($folder['gambar'])): ?>
                    <img class="banner" src="../GambarFolder/<?php echo $folder['gambar']; ?>" alt="Gambar Folder">
                <?php endif; ?>
                <h1><?php echo $folder['nama_materi']; ?></h1>
                <div class="btn">
                    <button class="cd-btn cd-btn--primary" aria-controls="dialog-1">Tambah Folder</button>
                  </div>
            </div>

      <div id="dialog-1" class="dialog js-dialog modal-tbhtgs" data-animation="on">
        <div class="dialog__content modal" role="alertdialog" aria-labelledby="dialog-title-1"
          aria-describedby="dialog-description-1">
          <h4 id="dialog-title-1" class="dialog__title">Tambah Tugas</h4>
          <form action="" method="post" enctype="multipart/form-data">
            <div id="dialog-description-1 inputMK" class="dialog__description add-task">
              <input type="file" id="nama_file" name="nama_file" required>
            </div>
            <footer class="dialog__footer">
              <button class="cd-btn cd-btn--subtle js-dialog__close batal">Batalkan</button>
              <button type="submit" class="cd-btn cd-btn--accent buat" name="simpan">Simpan</button>
            </footer>
          </form>
        </div>
      </div>

            <div class="folderTugas">
                    <?php
              if ($result && mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="folder">
                        <a href="../file/' . $row['nama_file'] . '">
                            <iconify-icon class="i-folder" icon="fxemoji:folder" width="32" height="32"></iconify-icon>
                        </a>
                        <a href="../file/' . $row['nama_file'] . '">
                            <h4 class="namfolder">' . $row['nama_file'] . '</h4>
                        </a>
                    <div class="dropdown">
                    <i  style="color: rgb(0, 0, 0);"><iconify-icon icon="proicons:more" width="28" height="28"></iconify-icon></i>
                        <div class="dropdown-content">
                            <a href="?delete_id=' . $row['id_file'] . '" onclick="hapus()" class="hapus">Hapus</a>
                            <a href="?edit_id=' . $row['id_file'] . '" onclick="hapus()" class="edit">Edit</a>
                        </div>
                    </div>
                </div>';
              }
            }
            ?>
            </div>
        </div>
    </main>

    <!-- <div class="modal-tbhtgs">
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
    </div> -->
    
    <?php include 'footer.php' ?>
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <script src="../Asset/attributes/Atribute1.js"></script>
    <script src="../Asset/attributes/Atribute2.js"></script>
    <!-- <script src="../Asset/JS/9file.js"></script> -->
    <script src="../Asset/JS/87tambahMateri.js"></script>
</body>
</html>

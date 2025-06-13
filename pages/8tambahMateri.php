<?php
include '../config/koneksi.php';
include '../config/session.php';

$id_user = $_SESSION['id_user'];
$dir = "../GambarFolder/";
if (!is_dir($dir)) {
  mkdir($dir, 0770, true);
}

// --- TAMBAH FOLDER ---
if (isset($_POST['simpan'])) {
  $id_user = $_SESSION['id_user'];
  $gambar = $_FILES['image'];
  $nama_gambar = $gambar['name'];
  $tmp_nama_gambar = $gambar['tmp_name'];
  $ext = strtolower(pathinfo($nama_gambar, PATHINFO_EXTENSION));

  $nama_materi = $_POST['subject'];
  $nama_pengajar = $_POST['dosen'];

  // Pastikan nama file unik
  $target = $dir . $nama_gambar;
  $i = 1;
  while (file_exists($target)) {
    $base_name = pathinfo($nama_gambar, PATHINFO_FILENAME);
    $target = $dir . $base_name . "_$i." . $ext;
    $nama_gambar = $base_name . "_$i." . $ext;
    $i++;
  }

  if (move_uploaded_file($tmp_nama_gambar, $target)) {
    $sql = "INSERT INTO tambahfolder(id_user, nama_materi, nama_pengajar, gambar)
                VALUES ('$id_user','$nama_materi','$nama_pengajar','$nama_gambar')";

    if (!mysqli_query($conn, $sql)) {
      echo "Error: " . mysqli_error($conn);
    }
  }

  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}

// --- UPDATE FOLDER ---
if (isset($_POST['update'])) {
  $id_folder = $_POST['id_folder'];
  $id_user = $_SESSION['id_user'];
  $gambar = $_FILES['image'];
  $nama_gambar = $gambar['name'];
  $tmp_nama_gambar = $gambar['tmp_name'];
  $ext = strtolower(pathinfo($nama_gambar, PATHINFO_EXTENSION));

  $nama_materi = $_POST['subject'];
  $nama_pengajar = $_POST['dosen'];

  // Pastikan nama file unik
  $target = $dir . $nama_gambar;
  $i = 1;
  while (file_exists($target)) {
    $base_name = pathinfo($nama_gambar, PATHINFO_FILENAME);
    $target = $dir . $base_name . "_$i." . $ext;
    $nama_gambar = $base_name . "_$i." . $ext;
    $i++;
  }

  move_uploaded_file($tmp_nama_gambar, $target);

  $sql = "UPDATE tambahfolder 
            SET nama_materi = '$nama_materi', 
                nama_pengajar = '$nama_pengajar', 
                gambar = '$nama_gambar' 
            WHERE id_folder = '$id_folder'";

  if (!mysqli_query($conn, $sql)) {
    echo "Error: " . mysqli_error($conn);
  }

  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}

// --- AMBIL DATA UNTUK EDIT ---
if (isset($_GET['edit'])) {
  $id_edit = $_GET['edit'];
  $result = mysqli_query($conn, "SELECT * FROM tambahfolder WHERE id_folder = $id_edit");
  $editData = mysqli_fetch_assoc($result);
}

// --- HAPUS FOLDER ---
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];

  // Hapus file gambar dari server
  $result = mysqli_query($conn, "SELECT gambar FROM tambahfolder WHERE id_folder = $id");
  $row = mysqli_fetch_assoc($result);
  if ($row && file_exists($dir . $row['gambar'])) {
    unlink($dir . $row['gambar']);
  }

  mysqli_query($conn, "DELETE FROM tambahfolder WHERE id_folder = $id");

  header("Location: " . $_SERVER['PHP_SELF']);
  exit;
}

// --- AMBIL SEMUA FOLDER MILIK USER ---
$id_user = $_SESSION['id_user'];
$query = "SELECT * FROM tambahfolder WHERE id_user = $id_user";
$dataFolder = mysqli_query($conn, $query);
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
  <link rel="stylesheet" href="../Asset/css/87tambahMateri.css">
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

  <main id="main">
    <div class="container">
      <h1>List Mata Kuliah</h1>
      <div class="btn">
        <button class="cd-btn cd-btn--primary" aria-controls="dialog-1">Tambah Folder</button>
      </div>

      <div id="dialog-1" class="dialog js-dialog" data-animation="on">
        <div class="dialog__content" role="alertdialog" aria-labelledby="dialog-title-1"
          aria-describedby="dialog-description-1">
          <h4 id="dialog-title-1" class="dialog__title">Tambah Folder</h4>
          <form action="" method="post" enctype="multipart/form-data">
            <div id="dialog-description-1 inputMK" class="dialog__description add-task">
              <label for="image">Pilih Gambar:</label>
              <input type="file" id="image" name="image" required>
              <label for="subject">Mata Kuliah:</label>
              <input type="text" id="subject" name="subject" placeholder="Masukkan nama mata kuliah" required>
              <label for="dosen">Nama Dosen:</label>
              <input type="text" id="dosen" name="dosen" placeholder="Masukkan nama dosen" required>
            </div>
            <footer class="dialog__footer">
              <button class="cd-btn cd-btn--subtle js-dialog__close">Batalkan</button>
              <button type="submit" class="cd-btn cd-btn--accent" name="simpan">Simpan</button>
            </footer>
          </form>
        </div>
      </div>

      <?php if (isset($editData)): ?>
        <div id="dialog-2" class="dialog js-dialog" data-animation="on">
          <div class="dialog__content" role="alertdialog" aria-labelledby="dialog-title-2"
            aria-describedby="dialog-description-2">
            <h4 id="dialog-title-2" class="dialog__title">Perbarui Tugas</h4>
            <form action="" method="post" enctype="multipart/form-data">
              <div id="dialog-description-2 inputMK" class="dialog__description add-task">
                <input type="hidden" name="id_folder" value="<?php echo $editData['id_folder']; ?>">
                <label for="image">Pilih Gambar:</label>
                <?php if (!empty($editData['gambar'])): ?>
                        <img src="../GambarFolder/<?php echo $editData['gambar']; ?>" width="100" alt="Gambar Folder">
                    <?php endif; ?>
                <input type="file" id="image" name="image" accept="image/*" required>
                <label for="subject">Mata Kuliah:</label>
                <input type="text" id="subject" name="subject" placeholder="Masukkan nama mata kuliah" value="<?php echo $editData['nama_materi']; ?>" required>
                <label for="dosen">Nama Dosen:</label>
                <input type="text" id="dosen" name="dosen" placeholder="Masukkan nama dosen" value="<?php echo $editData['nama_pengajar']; ?>" required>
              </div>
              <footer class="dialog__footer">
                <button class="cd-btn cd-btn--subtle js-dialog__close">Batalkan</button>
                <button type="submit" class="cd-btn cd-btn--accent" name="update">Perbarui</button>
              </footer>
            </form>
          </div>
        </div>
      <?php endif; ?>



      <!-- <div class="">
        <form class="task-category">
          <div id="not-started" class="result-container">
            <?php /*
            if ($dataFolder && mysqli_num_rows($dataFolder) > 0) {
              while ($row = mysqli_fetch_assoc($dataFolder)) {
                echo '
                  <a href="9filepage.php?folder_id=' . $row['id_folder'] . '">
                  <div class="result-card">
                    <div>
                      <img src=" ' . '../GambarFolder/' . $row['gambar'] . '">
                    <div>
                    <div>
                      <div>
                        <h3>' . $row['nama_materi'] . '</h3>
                        <p>' . $row['nama_pengajar'] . '</p>
                      </div>
                      <div>
                        <a href="?delete=' . $row['id_folder'] . '" onclick="return confirm(\'Yakin hapus?\')" class="hapus">
                          <iconify-icon icon="material-symbols:cancel-outline-rounded" width="32" height="32"></iconify-icon>
                        </a>
                      </div>
                      <div>
                        <a href="?edit=' . $row['id_folder'] . '" class="edit" aria-controls="dialog-2">
                          <i class="fas fa-pen edit" style="font-size:24px"></i>
                        </a>
                      </div>
                    <div>
                   </a>';
              }
            } */
            ?>
          </div>
      </div> -->

      <table border="20">
        <thead>
            <tr>
                <th>Nama Materi</th>
                <th>Nama Pengajar</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
              if ($dataFolder && mysqli_num_rows($dataFolder) > 0) {
              while ($row = mysqli_fetch_assoc($dataFolder)) {
            echo '
                <tr>
                    <td><h3>' . $row['nama_materi'] . '</h3></td>
                    <td>' . $row['nama_pengajar'] . '</td>
                    <td><img src=" ' . '../GambarFolder/' . $row['gambar'] . '" width="50"></td>
                    <td>
                        <a href="?edit=' . $row['id_folder'] . '" class="edit" aria-controls="dialog-2">Edit</a> ' . '|' . '
                        <a href="?delete=' . $row['id_folder'] . '" class="hapus" onclick="return confirm(\'Yakin ingin menghapus?\')">Delete</a> ' . '|' . '
                        <a href="9filepage.php?folder_id=' . $row['id_folder'] . '">View Files</a>
                    </td>
                </tr> ';
                 } 
                } 
                ?>
        </tbody>
    </table>
    </div>

  </main>
  <?php include 'footer.php' ?>
  <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
  <script src="../asset/attributes/Atribute1.js"></script>
  <script src="../asset/attributes/Atribute2.js"></script>
  <script src="../asset/JS/8tambahMateri.js"></script>
  <script src="../asset/js/87tambahMateri.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous">
  </script>
  <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
  <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
</body>

</html>

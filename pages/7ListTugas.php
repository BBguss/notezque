<?php
include '../config/koneksi.php';
include '../config/session.php';


if (isset($_POST['simpan'])) {
  $judul = $_POST['tugas'];
  $matkul = $_POST['matkul'];
  $deskripsi = $_POST['deskripsi'];
  $tanggal = $_POST['dl1'];
  $waktu = $_POST['dl2'];
  $hapus = $_GET['delete-task'];
  $id_user = $_SESSION['id_user'];
  $deadline = "$tanggal $waktu:00";

  $sql = "INSERT INTO tugas (id_user, judul_tugas, matkul, desc_tugas, deadline) 
            VALUES ('$id_user', '$judul', '$matkul', '$deskripsi', '$deadline')";

  if (mysqli_query($conn, $sql)) {

  } else {
    echo "Error: " . mysqli_error($conn);
  }
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}

if (isset($_GET['id'])) {
  $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM tugas WHERE id_tugas = $id");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
} 

$query = "SELECT * FROM tugas WHERE id_user = $_SESSION[id_user] ORDER BY deadline ASC ";
$dataTugas = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NotezQue</title>
    <link rel="icon" type="image/x-icon" href="Logo NotezQue.svg">
    <link rel="stylesheet" href="../asset/css/7listTugas.css">
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
                    <a href="../pages/dashboard/5Dashboard.php"><img src="../asset/images/logoNotezQue.svg" alt=""></a>
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
      <h1>List Tugas</h1>
      <div class="btn">
        <button class="cd-btn cd-btn--primary" aria-controls="dialog-1">Tambah Tugas</button>
      </div>

      <div id="dialog-1" class="dialog js-dialog" data-animation="on">
        <div class="dialog__content" role="alertdialog" aria-labelledby="dialog-title-1"
          aria-describedby="dialog-description-1">
          <h4 id="dialog-title-1" class="dialog__title">Tambah Tugas</h4>

          <form action="" method="post">
            <div id="dialog-description-1 inputMK" class="dialog__description add-task">
              <input type="text" id="tugas" name="tugas" placeholder="Judul Tugas" required>
              <input type="text" id="matkul" name="matkul" placeholder="Mata kuliah" required>
              <textarea id="deskripsi" name="deskripsi" placeholder="Deskripsi Tugas" required></textarea>
              <input type="date" id="deadline1" name="dl1" required>
              <input type="time" id="deadline2" name="dl2" required>
            </div>
            <footer class="dialog__footer">
              <button class="cd-btn cd-btn--subtle js-dialog__close">Batalkan</button>
              <button type="submit" class="cd-btn cd-btn--accent" name="simpan">Simpan</button>
            </footer>
          </form>
        </div>
      </div>

      <div class="task-lists">
        <form class="task-category">
          <div id="not-started" class="task-list">
            <?php
            if ($dataTugas && mysqli_num_rows($dataTugas) > 0) {
              while ($row = mysqli_fetch_assoc($dataTugas)) {
                // Format tanggal untuk ditampilkan
                $formatted_deadline = date('d-m-Y H.i', strtotime($row['deadline']));
                echo '
                <div class="task">
                  <div>
                    <div class="task-header">
                      <h3>' . htmlspecialchars($row['judul_tugas']) . '</h3>
                    </div>
                    <p><strong>Mata Kuliah:</strong> ' . htmlspecialchars($row['matkul']) . '</p>
                    <p><strong>Deskripsi Tugas:</strong> ' . htmlspecialchars($row['desc_tugas']) . '</p>
                    <div class="deadline">
                      <p><strong>Deadline:</strong> ' . $formatted_deadline . '</p>
                    </div>
                    </div>
                    <a href="?id='.$row['id_tugas'].'" onclick="return confirm(\'Yakin hapus?\')" class="hapus">
                        <iconify-icon icon="material-symbols:cancel-outline-rounded" width="32" height="32"></iconify-icon>
                    </a>
                    </div>';
                  }
                }
                ?>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php include 'footer.php' ?>
  <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <script src="../asset/attributes/Atribute1.js"></script>
    <script src="../asset/attributes/Atribute2.js"></script>
  <script src="../asset/js/7listTugas.js"></script>
</body>

</html>
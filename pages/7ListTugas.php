<?php
include '../config/koneksi.php';
include '../config/session.php';


if (isset($_POST['simpan'])) {
  $judul = $_POST['tugas'];
  $matkul = $_POST['matkul'];
  $deskripsi = $_POST['deskripsi'];
  $tanggal = $_POST['dl1'];
  $waktu = $_POST['dl2'];
  $status = "";
  $id_user = $_SESSION['id_user'];
  $now = date('Y-m-d');
  
  if (date('Y-m-d', strtotime($tanggal)) < date('Y-m-d', strtotime($now))) {
      $status = "Terlambat";
  } else if (date('Y-m-d', strtotime($tanggal)) == date('Y-m-d', strtotime($now))) {
      $status = "Hari ini";
  } else {
      $status = "Beberapa Hari lagi";
  }
  
  $sql = "INSERT INTO tugas (id_user, judul_tugas, matkul, desc_tugas, deadline1, deadline2, status) 
            VALUES ('$id_user', '$judul', '$matkul', '$deskripsi', '$tanggal', '$waktu', '$status')";

  if (mysqli_query($conn, $sql)) {
    $_SESSION['msg'] = "Tugas berhasil ditambahkan";
    echo $_SESSION['msg'];
  } else {
    echo "Error: " . mysqli_error($conn);
  }
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}

if (isset($_POST['update'])) {
  $id_tugas = $_POST['id_tugas'];
  $judul = $_POST['tugas'];
  $matkul = $_POST['matkul'];
  $deskripsi = $_POST['deskripsi'];
  $tanggal = $_POST['dl1'];
  $waktu = $_POST['dl2'];
  $status = "";
  $id_user = $_SESSION['id_user'];
  $now = date('Y-m-d');
  
  if (date('Y-m-d', strtotime($tanggal)) < date('Y-m-d', strtotime($now))) {
      $status = "Terlambat";
  } else if (date('Y-m-d', strtotime($tanggal)) == date('Y-m-d', strtotime($now))) {
      $status = "Hari ini";
  } else {
      $status = "Beberapa Hari lagi";
  }
  
  $sql = "UPDATE `tugas` SET `judul_tugas`='$judul', `matkul`='$matkul',`desc_tugas`='$deskripsi',
  `deadline1`='$tanggal', `deadline2`='$waktu', `status`='$status' WHERE id_tugas='$id_tugas'";

  if (mysqli_query($conn, $sql)) {
  } else {
    echo "Error: " . mysqli_error($conn);
  }
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}

if (isset($_GET['edit'])) {
  $id_edit = $_GET['edit'];
  $result = mysqli_query($conn, "SELECT * FROM tugas WHERE id_tugas = $id_edit");
  $editData = mysqli_fetch_assoc($result);
}


if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  mysqli_query($conn, "DELETE FROM tugas WHERE id_tugas = $id");
  header("Location: " . $_SERVER['PHP_SELF']);
  exit;
}

$query = "SELECT * FROM tugas WHERE id_user = $_SESSION[id_user] ORDER BY deadline1 ASC ";
$dataTugas = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NotezQue</title>
  <link rel="icon" type="image/x-icon" href="../Asset/images/Logo NotezQue.svg">
  <link rel="stylesheet" href="../Asset/css/7listTugas.css">
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
        <i id="tombol" style="color: white;"><iconify-icon icon="tabler:menu-2" width="32" height="32"></iconify-icon></i>
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

      <?php if (isset($editData)): ?>
      <div id="dialog-2" class="dialog js-dialog" data-animation="on">
        <div class="dialog__content" role="alertdialog" aria-labelledby="dialog-title-2"
          aria-describedby="dialog-description-2">
          <h4 id="dialog-title-2" class="dialog__title">Perbarui Tugas</h4>
          <form action="" method="post">
            <div id="dialog-description-2 inputMK" class="dialog__description add-task">
              <input type="hidden" name="id_tugas" value="<?= $editData['id_tugas'] ?>">
              <input type="text" id="tugas" name="tugas" placeholder="Judul Tugas" required value="<?= $editData['judul_tugas'] ?>">
              <input type="text" id="matkul" name="matkul" placeholder="Mata kuliah" required value="<?= $editData ['matkul']?>">
              <textarea id="deskripsi" name="deskripsi" placeholder="Deskripsi Tugas" required><?= $editData['desc_tugas']?></textarea>
              <input type="date" id="deadline1" name="dl1" required value="<?= $editData['deadline1'] ?>">
              <input type="time" id="deadline2" name="dl2" required value="<?= $editData['deadline2'] ?>">
            </div>
            <footer class="dialog__footer">
              <button class="cd-btn cd-btn--subtle js-dialog__close">Batalkan</button>
              <button type="submit" class="cd-btn cd-btn--accent" name="update">Perbarui</button>
            </footer>
          </form>
        </div>
      </div>
      <?php endif; ?>

      <div class="task-lists">
        <form class="task-category">
          <div id="not-started" class="task-list">
            <?php
            if ($dataTugas && mysqli_num_rows($dataTugas) > 0) {
              while ($row = mysqli_fetch_assoc($dataTugas)) {
                echo '
                <div class="task">
                  <div>
                    <div class="task-header">
                      <h3>' . htmlspecialchars($row['judul_tugas']) . '</h3>
                      <p>' . htmlspecialchars($row['status']) .'</p>
                    </div>
                    <p><strong>Mata Kuliah:</strong> ' . htmlspecialchars($row['matkul']) . '</p>
                    <p><strong>Deskripsi Tugas:</strong> ' . htmlspecialchars($row['desc_tugas']) . '</p>
                    <div class="deadline">
                      <p><strong>Deadline:</strong> ' . htmlspecialchars($row['deadline1']) . htmlspecialchars($row['deadline2']) . '</p>
                    </div>
                  </div>
                  <div>
                    <div>
                      <a href="?delete=' . $row['id_tugas'] . '" onclick="return confirm(\'Yakin hapus?\')" class="hapus">
                      <iconify-icon icon="material-symbols:cancel-outline-rounded" width="32" height="32"></iconify-icon>
                      </a>
                    </div>
                    <div>
                      <a href="?edit=' . $row['id_tugas'] . '" class="edit" aria-controls="dialog-2"">
                      <i class="fas fa-pen edit" style="font-size:24px"></i>
                      </a>
                    </div>
                  </div>
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
  </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous">
    </script>
  <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
  <script src="../asset/attributes/Atribute1.js"></script>
  <script src="../asset/attributes/Atribute2.js"></script>
  <script src="../asset/js/7listTugas.js"></script>
  <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
</body>

</html>

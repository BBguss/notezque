<?php
// filepath: c:\xampp\htdocs\Kelompok_3\pages\8tambahMateri.php
include '../config/koneksi.php';
include '../config/session.php';

// Handle logout dan profile
if (isset($_POST['logout'])) {
  session_destroy();
  header('Location: 2loginpage.php');
  exit();
}

if (isset($_POST['profile'])) {
  header('Location: 12profile.php');
  exit();
}

$id_user = $_SESSION['id_user'];
$dir = "../GambarFolder/";

// Buat folder jika belum ada
if (!is_dir($dir)) {
  mkdir($dir, 0770, true);
}

// Get logo untuk favicon
$logo_query = mysqli_query($conn, "SELECT * FROM konten_statis WHERE gambar LIKE '%logo%' LIMIT 1");
$logo = mysqli_fetch_assoc($logo_query) ?: ['gambar' => 'default-logo.png'];

// Inisialisasi variabel pesan
$success = '';
$error = '';

// --- TAMBAH FOLDER ---
if (isset($_POST['simpan'])) {
  $nama_materi = trim($_POST['subject']);
  $nama_pengajar = trim($_POST['dosen']);

  // Validasi input kosong
  if (empty($nama_materi) || empty($nama_pengajar)) {
    $error = "Semua field harus diisi!";
  } elseif (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    $error = "Gambar harus dipilih!";
  } else {
    $gambar = $_FILES['image'];
    $nama_gambar = $gambar['name'];
    $tmp_nama_gambar = $gambar['tmp_name'];
    $ext = strtolower(pathinfo($nama_gambar, PATHINFO_EXTENSION));

    // Validasi tipe file
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($ext, $allowed_types)) {
      $error = "Format file tidak didukung! Gunakan JPG, JPEG, PNG, atau GIF.";
    } else {
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

        if (mysqli_query($conn, $sql)) {
          $success = "Folder berhasil ditambahkan!";
        } else {
          $error = "Gagal menyimpan data: " . mysqli_error($conn);
          // Hapus file jika database gagal
          if (file_exists($target)) {
            unlink($target);
          }
        }
      } else {
        $error = "Gagal mengupload gambar!";
      }
    }
  }
}

// --- UPDATE FOLDER ---
if (isset($_POST['update'])) {
  $id_folder = (int) $_POST['id_folder'];
  $nama_materi = trim($_POST['subject']);
  $nama_pengajar = trim($_POST['dosen']);

  // Validasi input kosong
  if (empty($nama_materi) || empty($nama_pengajar)) {
    $error = "Nama mata kuliah dan dosen tidak boleh kosong!";
  } else {
    // Cek apakah ada gambar baru
    if (isset($_FILES['image']) && !empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
      $gambar = $_FILES['image'];
      $nama_gambar = $gambar['name'];
      $tmp_nama_gambar = $gambar['tmp_name'];
      $ext = strtolower(pathinfo($nama_gambar, PATHINFO_EXTENSION));

      // Validasi tipe file
      $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
      if (!in_array($ext, $allowed_types)) {
        $error = "Format file tidak didukung! Gunakan JPG, JPEG, PNG, atau GIF.";
      } else {
        // Hapus gambar lama
        $old_image_query = mysqli_query($conn, "SELECT gambar FROM tambahfolder WHERE id_folder = $id_folder AND id_user = $id_user");
        $old_data = mysqli_fetch_assoc($old_image_query);
        if ($old_data && file_exists($dir . $old_data['gambar'])) {
          unlink($dir . $old_data['gambar']);
        }

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
          // Update dengan gambar baru
          $sql = "UPDATE tambahfolder 
                            SET nama_materi = '$nama_materi', 
                                nama_pengajar = '$nama_pengajar', 
                                gambar = '$nama_gambar' 
                            WHERE id_folder = $id_folder AND id_user = $id_user";
        } else {
          $error = "Gagal upload gambar baru!";
        }
      }
    } else {
      // Update tanpa mengubah gambar
      $sql = "UPDATE tambahfolder 
                    SET nama_materi = '$nama_materi', 
                        nama_pengajar = '$nama_pengajar' 
                    WHERE id_folder = $id_folder AND id_user = $id_user";
    }

    if (isset($sql) && !$error) {
      if (mysqli_query($conn, $sql)) {
        $success = "Folder berhasil diperbarui!";
      } else {
        $error = "Gagal memperbarui data: " . mysqli_error($conn);
      }
    }
  }
}

// --- AMBIL DATA UNTUK EDIT ---
$editData = null;
if (isset($_GET['edit'])) {
  $id_edit = (int) $_GET['edit'];
  $result = mysqli_query($conn, "SELECT * FROM tambahfolder WHERE id_folder = $id_edit AND id_user = $id_user");
  $editData = mysqli_fetch_assoc($result);
}

// --- HAPUS FOLDER ---
if (isset($_GET['delete'])) {
  $id = (int) $_GET['delete'];

  // Hapus file gambar dari server
  $result = mysqli_query($conn, "SELECT gambar FROM tambahfolder WHERE id_folder = $id AND id_user = $id_user");
  $row = mysqli_fetch_assoc($result);
  if ($row && file_exists($dir . $row['gambar'])) {
    unlink($dir . $row['gambar']);
  }

  if (mysqli_query($conn, "DELETE FROM tambahfolder WHERE id_folder = $id AND id_user = $id_user")) {
    $success = "Folder berhasil dihapus!";
  } else {
    $error = "Gagal menghapus folder: " . mysqli_error($conn);
  }

  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}

// --- AMBIL SEMUA FOLDER MILIK USER ---
$query = "SELECT * FROM tambahfolder WHERE id_user = $id_user ORDER BY nama_materi ASC";
$dataFolder = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Materi - NotezQue</title>
    <link rel="icon" type="image/x-icon" href="../uploads/<?php echo htmlspecialchars($logo['gambar']); ?>">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../asset/css/87tambahMateri.css">
    <link rel="stylesheet" href="../asset/font/Font.css">
    <link rel="stylesheet" href="../Asset/font/Font.css">
    <link rel="stylesheet" href="../Asset/attributes/Atribute1.css">
    <link rel="stylesheet" href="../Asset/attributes/Atribute2.css">
    <link rel="stylesheet" href="../Asset/attributes/Atribute3.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
          integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" 
          crossorigin="anonymous" referrerpolicy="no-referrer">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <div class="topNav-db">
            <nav>
                <a href="../pages/dashboard/5Dashboard.php">
                    <img src="../uploads/<?php echo htmlspecialchars($logo['gambar']); ?>" alt="Logo NotezQue">
                </a>
                <div class="dropdown">
                    <i class="dropdown-button" style="color: white;">
                        <iconify-icon icon="iconamoon:profile-light" width="36" height="36"></iconify-icon>
                    </i>
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
                <i id="tombol" style="color: white;">
                    <iconify-icon icon="tabler:menu-2" width="32" height="32"></iconify-icon>
                </i>
                <i id="batal" style="color: white;">
                    <iconify-icon icon="tabler:menu-3" width="32" height="32"></iconify-icon>
                </i>
            </label>
            <div class="sideNav-db">
                <nav>
                    <h2>Menu</h2>
                    <a href="../pages/dashboard/5Dashboard.php">
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
                    <a href="8tambahMateri.php" class="active">
                        <iconify-icon icon="mingcute:book-5-line" width="38" height="38"></iconify-icon>
                        <span>Tambah Materi</span>
                    </a>
                </nav>
            </div>
        </aside>
    </header>

    <main id="main">
        <div class="container-main">
            <h1>List Mata Kuliah</h1>
            
            <!-- Alert Messages -->
            <?php if (!empty($success)): ?>
                  <div class="alert alert-success fade-in">
                      <i class="fas fa-check-circle"></i>
                      <?php echo htmlspecialchars($success); ?>
                  </div>
            <?php endif; ?>
            
            <?php if (!empty($error)): ?>
                  <div class="alert alert-error fade-in">
                      <i class="fas fa-exclamation-circle"></i>
                      <?php echo htmlspecialchars($error); ?>
                  </div>
            <?php endif; ?>

            <div class="btn">
                <button class="cd-btn cd-btn--primary" aria-controls="dialog-1">
                    <i class="fas fa-plus-circle"></i>
                    Tambah Folder
                </button>
            </div>

            <!-- Dialog Tambah Folder -->
            <div id="dialog-1" class="dialog js-dialog" data-animation="on">
                <div class="dialog__content" role="alertdialog" aria-labelledby="dialog-title-1" aria-describedby="dialog-description-1">
                    <h4 id="dialog-title-1" class="dialog__title">
                        <i class="fas fa-folder-plus"></i>
                        Tambah Folder Baru
                    </h4>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div id="dialog-description-1" class="dialog__description add-task">
                            <label for="image">
                                <i class="fas fa-image"></i>
                                Pilih Gambar Cover:
                            </label>
                            <input type="file" id="image" name="image" accept="image/*" required>
                            <small style="color: var(--text-secondary); font-size: 0.875rem;">
                                Format: JPG, JPEG, PNG, GIF. Maksimal 5MB.
                            </small>
                            
                            <label for="subject">
                                <i class="fas fa-book"></i>
                                Mata Kuliah:
                            </label>
                            <input type="text" id="subject" name="subject" 
                                   placeholder="Masukkan nama mata kuliah" required maxlength="100">
                            
                            <label for="dosen">
                                <i class="fas fa-user-tie"></i>
                                Nama Dosen:
                            </label>
                            <input type="text" id="dosen" name="dosen" 
                                   placeholder="Masukkan nama dosen" required maxlength="100">
                        </div>
                        <footer class="dialog__footer">
                            <button type="button" class="cd-btn cd-btn--subtle js-dialog__close">
                                <i class="fas fa-times"></i>
                                Batalkan
                            </button>
                            <button type="submit" class="cd-btn cd-btn--accent" name="simpan">
                                <i class="fas fa-save"></i>
                                Simpan Folder
                            </button>
                        </footer>
                    </form>
                </div>
            </div>

            <!-- Dialog Edit Folder -->
            <?php if (isset($editData)): ?>
                  <div id="dialog-2" class="dialog js-dialog dialog--is-visible" data-animation="on">
                      <div class="dialog__content" role="alertdialog" aria-labelledby="dialog-title-2" aria-describedby="dialog-description-2">
                          <h4 id="dialog-title-2" class="dialog__title">
                              <i class="fas fa-edit"></i>
                              Perbarui Folder
                          </h4>
                          <form action="" method="post" enctype="multipart/form-data">
                              <div id="dialog-description-2" class="dialog__description add-task">
                                  <input type="hidden" name="id_folder" value="<?php echo $editData['id_folder']; ?>">
                                
                                  <label for="image_edit">
                                      <i class="fas fa-image"></i>
                                      Pilih Gambar Baru (Opsional):
                                  </label>
                                  <?php if (!empty($editData['gambar'])): ?>
                                        <div class="current-image">
                                            <p>Gambar saat ini:</p>
                                            <img src="../GambarFolder/<?php echo htmlspecialchars($editData['gambar']); ?>" 
                                                 width="100" alt="Gambar Folder" style="border-radius: 8px; margin: 10px 0;">
                                        </div>
                                  <?php endif; ?>
                                  <input type="file" id="image_edit" name="image" accept="image/*">
                                  <small style="color: var(--text-secondary); font-size: 0.875rem;">
                                      Kosongkan jika tidak ingin mengubah gambar.
                                  </small>
                                
                                  <label for="subject_edit">
                                      <i class="fas fa-book"></i>
                                      Mata Kuliah:
                                  </label>
                                  <input type="text" id="subject_edit" name="subject" 
                                         placeholder="Masukkan nama mata kuliah" 
                                         value="<?php echo htmlspecialchars($editData['nama_materi']); ?>" 
                                         required maxlength="100">
                                
                                  <label for="dosen_edit">
                                      <i class="fas fa-user-tie"></i>
                                      Nama Dosen:
                                  </label>
                                  <input type="text" id="dosen_edit" name="dosen" 
                                         placeholder="Masukkan nama dosen" 
                                         value="<?php echo htmlspecialchars($editData['nama_pengajar']); ?>" 
                                         required maxlength="100">
                              </div>
                              <footer class="dialog__footer">
                                  <button type="button" class="cd-btn cd-btn--subtle js-dialog__close">
                                      <i class="fas fa-times"></i>
                                      Batalkan
                                  </button>
                                  <button type="submit" class="cd-btn cd-btn--accent" name="update">
                                      <i class="fas fa-save"></i>
                                      Perbarui Folder
                                  </button>
                              </footer>
                          </form>
                      </div>
                  </div>
            <?php endif; ?>

            <!-- Tabel Data Folder -->
            <div class="table-container">
                <?php if ($dataFolder && mysqli_num_rows($dataFolder) > 0): ?>
                      <div class="table-header" style="padding: var(--space-4); background: var(--bg-primary); border-bottom: 1px solid var(--border-color);">
                          <h2 style="margin: 0; color: var(--text-primary);">
                              <i class="fas fa-folder"></i>
                              Folder Mata Kuliah (<?php echo mysqli_num_rows($dataFolder); ?>)
                          </h2>
                      </div>
                <?php endif; ?>
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Nama Materi</th>
                            <th>Nama Pengajar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($dataFolder && mysqli_num_rows($dataFolder) > 0) {
                          $no = 1;
                          while ($row = mysqli_fetch_assoc($dataFolder)) {
                            echo '
                                <tr class="fade-in">
                                    <td>' . $no++ . '</td>
                                    <td>
                                        <img src="../GambarFolder/' . htmlspecialchars($row['gambar']) . '" 
                                             width="60" height="60" alt="' . htmlspecialchars($row['nama_materi']) . '"
                                             style="border-radius: 8px; object-fit: cover;">
                                    </td>
                                    <td>
                                        <h3>' . htmlspecialchars($row['nama_materi']) . '</h3>
                                    </td>
                                    <td>' . htmlspecialchars($row['nama_pengajar']) . '</td>
                                    <td class="action-buttons">
                                        <a href="?edit=' . $row['id_folder'] . '" class="btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?delete=' . $row['id_folder'] . '" class="btn-delete" 
                                           onclick="return confirm(\'Yakin ingin menghapus folder &quot;' . htmlspecialchars($row['nama_materi']) . '&quot;?\')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <a href="9filepage.php?folder_id=' . $row['id_folder'] . '" class="btn-view" title="Buka Folder">
                                            <i class="fas fa-folder-open"></i>
                                        </a>
                                    </td>
                                </tr>';
                          }
                        } else {
                          echo '
                            <tr>
                                <td colspan="5" class="no-data">
                                    <div class="empty-state">
                                        <i class="fas fa-folder-plus" style="font-size: 4rem; color: var(--gray-300); margin-bottom: 1rem;"></i>
                                        <h3>Belum Ada Folder</h3>
                                        <p>Silakan tambah folder mata kuliah terlebih dahulu untuk mulai mengorganisir materi Anda</p>
                                        <button class="cd-btn cd-btn--primary" aria-controls="dialog-1" style="margin-top: 1rem;" onclick="document.querySelector(\'[aria-controls=dialog-1]\').click()">
                                            <i class="fas fa-plus"></i>
                                            Tambah Folder Pertama
                                        </button>
                                    </div>
                                </td>
                            </tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <!-- JavaScript Files -->
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <script src="../asset/attributes/Atribute1.js"></script>
    <script src="../asset/attributes/Atribute2.js"></script>
    <script src="../asset/js/87tambahMateri.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.remove();
                    }, 300);
                }, 5000);
            });

            // Show edit dialog if edit data exists
            <?php if (isset($editData)): ?>
                  const editDialog = document.getElementById('dialog-2');
                  if (editDialog) {
                      editDialog.classList.add('dialog--is-visible');
                  }
            <?php endif; ?>

            // Image preview function
            function setupImagePreview(inputId, containerId) {
                const input = document.getElementById(inputId);
                if (input) {
                    input.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                // Remove existing preview
                                const existingPreview = input.parentNode.querySelector('.image-preview');
                                if (existingPreview) {
                                    existingPreview.remove();
                                }
                                
                                // Create new preview
                                const preview = document.createElement('div');
                                preview.className = 'image-preview';
                                preview.style.cssText = 'margin-top: 10px; text-align: center;';
                                preview.innerHTML = `<img src="${e.target.result}" alt="Preview" style="max-width: 150px; border-radius: 8px; box-shadow: var(--shadow-md);">`;
                                input.parentNode.appendChild(preview);
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                }
            }

            // Setup image previews
            setupImagePreview('image');
            setupImagePreview('image_edit');
        });
    </script>
</body>
</html>
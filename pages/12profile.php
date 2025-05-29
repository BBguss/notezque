<?php
include '../config/koneksi.php';
include '../config/session.php';

$id_user = $_SESSION['id_user'];
$update_message = '';

// Update profile
if (isset($_POST['update'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_ARGON2ID);
        $sql = "UPDATE users SET username='$username', email='$email', password='$hashed_password' WHERE id_user=$id_user";
    } else {
        $sql = "UPDATE users SET username='$username', email='$email' WHERE id_user=$id_user";
    }

    if ($conn->query($sql)) {
        $update_message = "Profil berhasil diperbarui.";
    } else {
        $update_message = "Gagal memperbarui profil: " . $conn->error;
    }
}

// Ambil data user
$query = mysqli_query($conn, "SELECT username, email FROM users WHERE id_user=$id_user");
$user = mysqli_fetch_assoc($query);

$logo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM konten_statis WHERE gambar = 'logo-notezque.svg'"));
?>

<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - NotezQue</title>
    <link rel="icon" type="image/x-icon" href="../uploads/<?= $logo['gambar'] ?>">
    <link rel="stylesheet" href="../Asset/css/7listTugas.css">
    <link rel="stylesheet" href="../Asset/font/Font.css">
    <link rel="stylesheet" href="../Asset/Attributes/Atribute1.css">
    <link rel="stylesheet" href="../Asset/Attributes/Atribute2.css">
    <link rel="stylesheet" href="../Asset/Attributes/Atribute3.css">
    <link rel="stylesheet" href="../Asset/CSS/profile.css">
</head>

<body>
    <header>
        <div class="topNav-db">
            <nav>
            <a href="../pages/dashboard/5Dashboard.php"><img src="../uploads/<?= $logo['gambar'] ?>" alt="Logo"></a>
                <div class="dropdown">
                    <i class="dropdown-button" style="color: white;"><iconify-icon icon="iconamoon:profile-light"
                            width="36" height="36"></iconify-icon></i>
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
            <input type="checkbox" id="check">
            <label for="check">
                <i id="tombol" style="color: white;"><iconify-icon icon="tabler:menu-2" width="32"
                        height="32"></iconify-icon></i>
                <i id="batal" style="color: white;"><iconify-icon icon="tabler:menu-3" width="32"
                        height="32"></iconify-icon></i>
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
                        <iconify-icon icon="fluent:task-list-square-ltr-24-filled" width="38"
                            height="38"></iconify-icon>
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
    <main>
        <section>
            <div class="container">
                <h2>Profil Saya</h2>
                <form method="POST">
                    <div class="inputbox">
                        <label>Username</label>
                        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>"
                            required />
                    </div>
                    <div class="inputbox">
                        <label>Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required />
                    </div>
                    <div class="inputbox">
                        <label>Password (isi jika ingin ubah)</label>
                        <input type="password" name="password" />
                    </div>
                    <button type="submit" name="update">Simpan Perubahan</button>
                </form>
                <?php if (!empty($update_message)): ?>
                    <p><?= $update_message ?></p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <script src="../Asset/attributes/Atribute1.js"></script>
    <script src="../Asset/attributes/Atribute2.js"></script>

</body>

</html>

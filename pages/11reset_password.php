<?php
include '../config/koneksi.php';

$reset_message = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Ambil ID user dari token
    $stmt = $conn->prepare("SELECT id_user FROM reset_password_requests WHERE token = ?");
    if ($stmt) {
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $id_user = $user['id_user'];

            if (isset($_POST['reset'])) {
                $new_password = $_POST['new_password'];
                $confirm_password = $_POST['confirm_password'];

                if ($new_password !== $confirm_password) {
                    $reset_message = "Kata sandi tidak cocok!";
                } elseif (strlen($new_password) < 6) {
                    $reset_message = "Kata sandi harus minimal 6 karakter!";
                } else {
                    $hashed_password = password_hash($new_password, PASSWORD_ARGON2ID);
                    $update_sql = $conn->prepare("UPDATE users SET password = ? WHERE id_user = ?");
                    if ($update_sql) {
                        $update_sql->bind_param("si", $hashed_password, $id_user);
                        if ($update_sql->execute()) {
                            // Hapus token
                            $delete_stmt = $conn->prepare("DELETE FROM reset_password_requests WHERE token = ?");
                            if ($delete_stmt) {
                                $delete_stmt->bind_param("s", $token);
                                $delete_stmt->execute();
                            }
                            $reset_message = "Kata sandi berhasil diperbarui. <a href='2loginpage.php'>Masuk sekarang</a>";
                        } else {
                            $reset_message = "Gagal memperbarui kata sandi.";
                        }
                    } else {
                        $reset_message = "Gagal menyiapkan query update.";
                    }
                }
            }
        } else {
            $reset_message = "Token tidak valid atau telah kadaluarsa!";
        }
    } else {
        $reset_message = "Gagal memproses token. Error: " . $conn->error;
    }
} else {
    $reset_message = "Token tidak ditemukan!";
}

$logo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM konten_statis WHERE gambar = 'logo-notezque.svg'"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NotezQue - Reset Kata Sandi</title>
    <link rel="icon" type="image/x-icon" href="../uploads/<?= $logo['gambar'] ?>">
    <link rel="stylesheet" href="../asset/css/2login,regist,forgotpass.css">
</head>
<body>
    <section id="reset_password">
      <form method="post">
    <h1>Perbarui Kata Sandi</h1>
    <div class="inputbox">
        <input type="password" name="new_password" required>
        <label>Kata Sandi Baru</label>
    </div>
    <div class="inputbox">
        <input type="password" name="confirm_password" required>
        <label>Konfirmasi Kata Sandi</label>
    </div>
    <button type="submit" name="reset">Perbarui Kata Sandi</button>

    <!-- Pesan pindah ke sini -->
    <?php if ($reset_message): ?>
        <p class="reset_message"><?= $reset_message ?></p>
    <?php endif; ?>

    <div class="login_link">
        <p>Sudah ingat kata sandi? <a href="2loginpage.php">Masuk</a></p>
    </div>
</form>
    </section>
</body>
</html>

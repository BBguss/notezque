<?php
include '../config/koneksi.php';

$reset_message = '';

if (isset($_GET['token'])) {
   ECT id_user FROM reset_password_requests WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result(); $token = $_GET['token'];

    // Cek apakah token valid
    $stmt = $conn->prepare("SEL

    if ($result->num_rows > 0) {
        // Token valid, lanjutkan ke reset password
        if (isset($_POST['reset'])) {
            $email = trim($_POST['email']);
            $quiz_answer = trim($_POST['quiz_answer']); // Jawaban dari quiz
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            // Validasi password baru
            if ($new_password !== $confirm_password) {
                $reset_message = "Kata sandi tidak cocok!";
            } elseif (strlen($new_password) < 6) {
                $reset_message = "Kata sandi harus minimal 6 karakter!";
            } else {
                // Cek apakah email dan jawaban quiz cocok
                $sql = "SELECT * FROM users WHERE email = '$email' AND quiz_answer = '$quiz_answer'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Update password pengguna
                    $hashed_password = password_hash($new_password, PASSWORD_ARGON2ID);
                    $update_sql = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";
                    
                    if ($conn->query($update_sql)) {
                        $reset_message = "Kata sandi berhasil diperbarui.";
                    } else {
                        $reset_message = "Gagal memperbarui kata sandi.";
                    }
                } else {
                    $reset_message = "Email atau jawaban quiz salah!";
                }
            }
        }
    } else {
        $reset_message = "Token tidak valid atau telah kadaluarsa!";
    }
} else {
    $reset_message = "Token tidak ditemukan!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NotezQue - Reset Kata Sandi</title>
    <link rel="stylesheet" href="../asset/css/2login,regist,forgotpass.css">
</head>
<body>
    <section id="reset_password">
        <form method="post">
            <?php if ($reset_message): ?>
            <p class="reset_message"><?= $reset_message ?></p>
            <?php endif; ?>
            <h1>Perbarui Kata Sandi</h1>
            <div class="inputbox">
                <input type="email" name="email" required>
                <label>Masukkan Email Anda</label>
            </div>
            <div class="inputbox">
                <input type="text" name="quiz_answer" required>
                <label>Jawaban Quiz</label>
            </div>
            <div class="inputbox">
                <input type="password" name="new_password" required>
                <label>Kata Sandi Baru</label>
            </div>
            <div class="inputbox">
                <input type="password" name="confirm_password" required>
                <label>Konfirmasi Kata Sandi</label>
            </div>
            <button type="submit" name="reset">Perbarui Kata Sandi</button>
            <div class="login_link">
                <p>Sudah ingat kata sandi? <a href="2loginpage.php">Masuk</a></p>
            </div>
        </form>
    </section>
</body>
</html>

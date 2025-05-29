<?php
include '../config/koneksi.php';
session_start();

$register_message = '';

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $quiz_answer = trim($_POST['quiz_answer']); // Jawaban quiz

    // Validasi password cocok
    if ($password !== $password_confirm) {
        $register_message = "Kata sandi tidak cocok!";
    } elseif (strlen($password) < 6) {
        $register_message = "Kata sandi harus minimal 6 karakter!";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_ARGON2ID);

        try {
            // Buat query
            $sql = "INSERT INTO users (username, email, password, quiz_answer) 
                    VALUES ('$username', '$email', '$hashed_password', '$quiz_answer')";

            if ($conn->query($sql)) {
                $register_message = "Berhasil daftar, silakan login.";
            } else {
                $register_message = "Pendaftaran gagal: " . $conn->error;
            }
        } catch (\Throwable $th) {
            $register_message = $th->getMessage();
        }
    }
}
$logo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM konten_statis WHERE gambar = 'logo-notezque.svg'"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NotezQue</title>
    <link rel="icon" type="image/x-icon" href="../uploads/<?= $logo['gambar'] ?>">
    <link rel="stylesheet" href="../asset/css/2login,regist,forgotpass.css">
</head>
<body>
    <section>
        <form action="3registerpage.php" method="POST">
            <?php if ($register_message): ?>
            <p class="register_message"><?= $register_message ?></p>
            <?php endif; ?>
            <h1>Daftar</h1>
            <div class="inputbox">
                <input type="text" name="username" required>
                <label>Username</label>
            </div>
            <div class="inputbox">
                <input type="email" name="email" required>
                <label>Email</label>
            </div>
            <div class="inputbox">
                <input type="password" name="password" required>
                <label>Buat kata sandi</label>
            </div>
            <div class="inputbox">
                <input type="password" name="password_confirm" required>
                <label>Tulis ulang kata sandi</label>
            </div>
            <div class="inputbox">
                <input type="text" name="quiz_answer" required>
                <label>Siapa nama panggilan masa kecilmu?</label>
            </div>
            <button type="submit" name="register">Daftar</button>
            <div class="register">
                <p>Saya sudah mempunyai akun <a href="2loginpage.php">Masuk</a></p>
            </div>
        </form>
    </section>
</body>
</html>

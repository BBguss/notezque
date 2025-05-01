<?php
include '../config/koneksi.php';
session_start();

$login_message = '';

if (isset($_POST['login'])) {
    $userormail = trim($_POST['userormail']);
    $password = $_POST['password'];

    // Query ambil user berdasarkan username atau email
    $sql = "SELECT * FROM users WHERE username='$userormail' OR email='$userormail'";
    $result = $conn->query($sql);


    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();

        // Cocokkan password
        if (password_verify($password, $data['password'])) {
            $_SESSION ["id_user"] = $data ["id_user"];
            $_SESSION ["username"] = $data["username"];
            $_SESSION["email"] = $data["email"];
            $_SESSION ["is_login"] = true;
            $_SESSION["aktivitas"] = time();

            header("Location: dashboard/5Dashboard.php");
            exit();
        } else {
            $login_message = "Password salah!";
        }
    } else {
        $login_message = "Akun tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NotezQue</title>
    <link rel="icon" type="image/x-icon" href="Logo NotezQue.svg">
    <link rel="stylesheet" href="../asset/css/2login,regist,forgotpass.css">
    <link rel="stylesheet" href="../asset/font/Font.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
</head>

<body>
    <section>
        
        <!-- Form harus mengarah ke file INI SENDIRI (bukan dashboard) -->
        <form action="" method="POST">
            <?php if ($login_message): ?>
                <p class="login_message"><?= $login_message ?></p>
            <?php endif; ?>
            <h1>Masuk</h1>
            <div class="inputbox">
                <input type="text" name="userormail" required>
                <label for="">Masukkan Email atau Username</label>
            </div>
            <div class="inputbox">
                <input type="password" name="password" required>
                <label for="">Sandi</label>
            </div>
            <div class="forget">
                <label><input type="checkbox"> Ingatkan saya</label>
                <span><a href="4forgotpass.php">Lupa password?</a></span>
            </div>
            <button type="submit" name="login">Masuk</button>
            <div class="register">
                <p>Saya tidak mempunyai akun <a href="3registerpage.php">Daftar</a></p>
            </div>
        </form>
    </section>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const loginMessage = document.querySelector('.login_message');
        if (loginMessage) {
            setTimeout(() => {
                loginMessage.style.transition = "opacity 1.5s ease-out";
                loginMessage.style.opacity = 0;
                setTimeout(() => {
                    loginMessage.remove();
                }, 880);
            }, 880);
        }
    });
</script>
</body>

</html>
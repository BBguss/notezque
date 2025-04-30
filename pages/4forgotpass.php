<?php
include '../koneksi.php'; // koneksi ke DB

$forgot_message = "";
$berhasil_message ="";

if (isset($_POST['send'])) {
    $email = $_POST['email'];

    // Cek apakah email ada di database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $berhasil_message = "Berhasil. Silakan periksa email Anda.";
    } else {
        $forgot_message = "Email tidak ditemukan!";
    }
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NotezQue</title>
    <link rel="icon" type="image/x-icon" href="Logo NotezQue.svg">
    <link rel="stylesheet" href="../asset/css/2login,regist,forgotpass.css">
    <link rel="stylesheet" href="/asset/font/Font.css">
</head>

<body>
    <section id="isi">
        <form method="post">
            <?php if ($forgot_message || $berhasil_message): ?>
                <p class="forgot_message"><?= $forgot_message ?></p>
                <p class="berhasil_message"><?= $berhasil_message ?></p>
            <?php endif; ?>
            <h1>Perbarui Kata Sandi</h1>
            <div class="inputbox">
                <input type="email" name="email" required>
                <label>Masukkan Email Anda</label>
            </div>
            <button type="submit" name="send">Kirim</button>

            <div class="register">
                <p>Coba gunakan akun lain <a href="2loginpage.php">Masuk</a></p>
            </div>
        </form>
    </section>
    <script src="4forgotpass.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const forgotmessage = document.querySelector('.forgot_message');
        const berhasilmessage = document.querySelector('.berhasil_message');
        if (forgotmessage || berhasilmessage) {
            setTimeout(() => {
                forgotmessage.style.transition = "opacity 1.5s ease-out";
                forgotmessage.style.opacity = 0;
                berhasilmessage.style.transition = "opacity 1.5s ease-out";
                berhasilmessage.style.opacity = 0;
                setTimeout(() => {
                    forgotmessage.remove();
                    berhasilmessage.remove();
                }, 1000);
            }, 1000);
        }
    });
</script>
</body>

</html>
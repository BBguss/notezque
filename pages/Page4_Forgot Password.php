<?php
include '../koneksi.php'; // koneksi ke DB

$forgot_message = "";

if (isset($_POST['send'])) {
    $email = $_POST['email'];

    // Cek apakah email ada di database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $forgot_message = "Email ditemukan. Silakan periksa email Anda untuk perbarui kata sandi.";
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
    <link rel="stylesheet" href="../Asset/css/Page2,3,&4_Style.css">
    <link rel="stylesheet" href="/Asset/font/Font.css">
</head>

<body>
    <section id="isi">
        <form method="post">
            <h1>Perbarui Kata Sandi</h1>
            
            <div class="inputbox">
                <input type="email" name="email" required>
                <label>Masukkan Email Anda</label>
            </div>

            <button type="submit" name="send">Kirim</button>

            <!-- Tampilkan pesan -->
            <?php if (!empty($forgot_message)) : ?>
                <p style="color:white; margin-top:10px;"><?= $forgot_message ?></p>
            <?php endif; ?>
            
            <div class="register">
                <p>Coba gunakan akun lain <a href="page2_loginpage.php">Masuk</a></p>
            </div>
        </form>
    </section>
    <script src="Page4_Script.js"></script>
</body>
</html>

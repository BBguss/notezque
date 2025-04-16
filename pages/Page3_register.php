<?php
include '../koneksi.php';

$register_message = '';

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Validasi password cocok
    if ($password !== $password_confirm) {
        $register_message = "Kata sandi tidak cocok!";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Buat query
            $sql = "INSERT INTO users (username, email, password) 
                    VALUES ('$username', '$email', '$hashed_password')";
    
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
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NotezQue</title>
    <link rel="icon" type="image/x-icon" href="Logo NotezQue.svg">
    <link rel="stylesheet" href="../Asset/css/Page2,3,&4_Style.css">
    <link rel="stylesheet" href="../Asset/font/Font.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
</head>

<body>
    <section>
        <i><?= $register_message ?></i>
        <form action="page3_register.php" method="POST">
            <h1>Daftar</h1>
            <div class="inputbox">
                <input type="text" name="username" required>
                <label for="">Username</label>
            </div>
            <div class="inputbox">
                <input type="email" name="email" required>
                <label for="">Email</label>
            </div>
            <div class="inputbox">
                <input type="password" name="password" required>
                <label for="">Buat kata sandi</label>
            </div>
            <div class="inputbox">
                <input type="password" name="password_confirm" required>
                <label for="">Tulis ulang kata sandi</label>
            </div>
            <button type="submit" name="register">Daftar</button>
            <div class="register">
                <p>Saya sudah mempunyai akun <a href="page2_loginpage.php">Masuk</a></p>
            </div>
        </form>
    </section>
</body>

</html>
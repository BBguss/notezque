<?php
include '../config/koneksi.php';

$forgot_message = '';
$berhasil_message = '';
$quiz_question = '';
$email = '';
$quiz_answer = '';

if (isset($_POST['check_email'])) {
    $email = trim($_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $forgot_message = "Email tidak valid!";
    } else {
        $stmt = $conn->prepare("SELECT quiz_question FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $quiz_question = $user['quiz_question'];
        } else {
            $forgot_message = "Email tidak ditemukan!";
        }
    }
}

if (isset($_POST['send'])) {
    $email = trim($_POST['email']);
    $quiz_answer = trim($_POST['quiz_answer']);

    $stmt = $conn->prepare("SELECT id_user, quiz_answer FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (strtolower($quiz_answer) === strtolower($user['quiz_answer'])) {
            $token = bin2hex(random_bytes(50));
            $stmt = $conn->prepare("INSERT INTO reset_password_requests (id_user, token) VALUES (?, ?)");
            $stmt->bind_param("is", $user['id_user'], $token);
            $stmt->execute();
            header("Location: 11reset_password.php?token=$token");
            exit;
        } else {
            $forgot_message = "Jawaban quiz salah!";
        }
    } else {
        $forgot_message = "Email tidak ditemukan!";
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
    <link rel="stylesheet" href="/asset/font/Font.css">
</head>
<body>
    <section id="isi">
        <form method="post">
    <?php if ($forgot_message || $berhasil_message): ?>
        <p class="forgot_message"><?= $forgot_message ?></p>
        <p class="berhasil_message"><?= $berhasil_message ?></p>
    <?php endif; ?>

    <h1>Lupa Kata Sandi?</h1>

    <div class="inputbox">
        <input type="email" name="email" required value="<?= htmlspecialchars($email) ?>">
        <label>Masukkan Email Anda</label>
    </div>

    <?php if ($quiz_question): ?>
        <div class="inputbox">
            <p style="color: white; margin-bottom: 5px;"><?= $quiz_question ?></p>
            <input type="text" name="quiz_answer" required>
            <label>Jawaban pertanyaan keamanan Anda</label>
        </div>
    <?php endif; ?>

    <?php if (!$quiz_question): ?>
        <button type="submit" name="check_email">Lanjut</button>
    <?php else: ?>
        <button type="submit" name="send">Kirim</button>
    <?php endif; ?>

    <div class="register">
        <p>Coba gunakan akun lain <a href="2loginpage.php">Masuk</a></p>
    </div>
</form>
    </section>
</body>
</html>

<?php
include '../config/koneksi.php';
session_start();

$login_message = '';

if (isset($_POST['login'])) {
    $userormail = trim($_POST['userormail']);
    $password = $_POST['password'];

    // Validasi input kosong
    if (empty($userormail) || empty($password)) {
        $login_message = "Harap isi semua field!";
    } else {
        // Query ambil user berdasarkan username atau email (gunakan prepared statement untuk keamanan)
        $sql = "SELECT * FROM users WHERE username=? OR email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $userormail, $userormail);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();

            // Cocokkan password
            if (password_verify($password, $data['password'])) {
                // Set session data
                $_SESSION["id_user"] = $data["id_user"];
                $_SESSION["username"] = $data["username"];
                $_SESSION["email"] = $data["email"];
                $_SESSION["is_login"] = true;
                $_SESSION["aktivitas"] = time();

                // Logika redirect berdasarkan role/username
                if ($data['username'] === 'admin' || $data['role'] === 'admin') {
                    // Redirect ke halaman admin
                    header("Location: /Kelompok_3/admin/admin_dashboard.php");
                    exit();
                } else {
                    // Set welcome message untuk user biasa
                    $_SESSION['welcome_message'] = "Selamat datang, " . $data['username'] . "!";
                    // Redirect ke dashboard user
                    header("Location: dashboard/5Dashboard.php");
                    exit();
                }
            } else {
                $login_message = "Password salah!";
            }
        } else {
            $login_message = "Akun tidak ditemukan!";
        }
        
        // Tutup prepared statement
        $stmt->close();
    }
}

// Ambil logo
$logo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM konten_statis WHERE gambar = 'logo-notezque.svg'"));
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - NotezQue</title>
    <link rel="icon" type="image/x-icon" href="../uploads/<?= $logo['gambar'] ?>">
    <link rel="stylesheet" href="../asset/css/2login,regist,forgotpass.css">
    <link rel="stylesheet" href="../asset/font/Font.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
</head>

<body>
    <section>
        <form action="" method="POST">
            <?php if ($login_message): ?>
                <div class="login_message">
                    <p><?= htmlspecialchars($login_message) ?></p>
                </div>
            <?php endif; ?>
            
            <h1>Masuk</h1>
            
            <div class="inputbox">
                <input type="text" name="userormail" value="<?= isset($_POST['userormail']) ? htmlspecialchars($_POST['userormail']) : '' ?>" required>
                <label for="">Masukkan Email atau Username</label>
            </div>
            
            <div class="inputbox">
                <input type="password" name="password" required>
                <label for="">Sandi</label>
            </div>
            
            <div class="forget">
                <label>
                    <input type="checkbox" name="remember_me"> Ingatkan saya
                </label>
                <span>
                    <a href="4forgotpass.php">Lupa password?</a>
                </span>
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
                // Tampilkan pesan selama 3 detik
                setTimeout(() => {
                    loginMessage.style.transition = "opacity 1.5s ease-out";
                    loginMessage.style.opacity = 0;
                    setTimeout(() => {
                        loginMessage.remove();
                    }, 1500);
                }, 3000);
            }

            // Auto-focus pada input pertama
            const firstInput = document.querySelector('input[name="userormail"]');
            if (firstInput) {
                firstInput.focus();
            }

            // Prevent form resubmission on page refresh
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        });

        // Show/hide password functionality
        function togglePassword() {
            const passwordInput = document.querySelector('input[name="password"]');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
        }
    </script>
</body>

</html>

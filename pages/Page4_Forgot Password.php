<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NotezQue</title>
    <link rel="icon" type="image/x-icon" href="Logo NotezQue.svg">
    <link rel="stylesheet" href="../Asset/css/Page2,3,&4_Style.css">
    <link rel="stylesheet" href="/Asset/font/Font.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body>
    <section id="isi">
        <form>
            <h1>Update Password</h1>
            <p>How would you like to reset your password?</p>
            <div class="pilih">
                <label>
                    <input type="radio" name="kontak" value="email" id="emailOption"> Email
                </label>
                <label>
                    <input type="radio" name="kontak" value="sms" id="smsOption"> SMS
                </label>
            </div>
        
                <div id="emailInput" class="mail" style="display: none;">
                    <div class="inputbox">
                        <icon-icon name="mail-outline"></icon-icon>
                        <input type="email" id="email" required>
                        <label for=""User>Email</label>
                    </div>
                    <button onclick="email()">Email Me</button>
                </div>
        
                <div id="smsInput" class="sms" style="display: none;">
                    <div class="inputbox">
                        <icon-icon name="lock-close-outline"></icon-icon>
                        <input type="tel" id="sms" required>
                        <label for="sms">Nomor Telepon:</label>
                    </div>
                    <button>SMS OTP</button>
                </div>
            <div class="register">
                <p>Coba gunakan akun lain <a href="page2_loginpage.php">Masuk</a></p>
            </div>
        </form>
    </section>
    <script src="Page4_Script.js"></script>
</body>
</html>
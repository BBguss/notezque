<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../Asset/css/Page1_Style.css">
    <link rel="stylesheet" href="../Asset/css/Atribute3_Footer.css">
    <link rel="stylesheet" href="../Asset/font/Font.css">
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <div class="top-navbar">
            <nav>
                <div class="logo">
                    <img src="../Asset/images/logo-notezque.svg" alt="404logo" title="NotzQue">
                    <h2 style="color: white;">NotzQue</h2>
                </div>
                <div class="loginRegist">
                    <a href="page2_loginpage.php"><button type="button" class="login">Masuk</button></a>
                    <a href="Page3_register.php"><button type="button" class="login">Daftar</button></a>
                </div>
            </nav>
        </div>
    </header>
    <main>
        <h1 class="apa">Mulai sekarang dan jadikan produktivitas Anda <br> lebih terorganisir bersama NotzQue!</h1>
        <div class="loginRegist2">
            <a href="Page3_register.php"><button type="button" class="button">Bergabung Sekarang</button></a>
        </div>
        <div class="gif-container">
            <div id="videoCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="../Asset/images/gambar (1).png">
                    </div>
                    <div class="carousel-item">
                        <img src="../Asset/images/gambar (2).png">
                    </div>
                    <div class="carousel-item">
                        <img src="../Asset/images/gambar (3).png">
                    </div>
                    <div class="carousel-item">
                        <img src="../Asset/images/gambar (4).png">
                    </div>
                    <div class="carousel-item">
                        <img src="../Asset/images/gambar (5).png">
                    </div>
                    <div class="carousel-item">
                        <img src="../Asset/images/gambar (6).png">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#videoCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#videoCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </main>
    <footer>
        <div class="container">
            <div class="sec aboutus">
                <h2>Tentang Kami</h2>
                <p>NotzQue merupakan sebuah aplikasi notes digital
                    yang bisa digunakan untuk membuat, menyimpan, dan mengelola catatan, serta membuat
                    folder-folder untuk setiap note yang telah dibuat.</p>
                <ul class="satu">
                    <li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                    <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                    <li><a href="#"><i class="fa-brands fa-twitter"></i></a></li>
                    <li><a href="#"><i class="fa-brands fa-youtube"></i></a></li>
                </ul>
            </div>
            <div class="sec contactus">
                <h2>Kontak Kami</h2>
                <ul class="info">
                    <li>
                        <span><i class="fa-solid fa-phone"></i></span>
                        <p><a href="tel: +62 812 3456 7890">+62 812 3456 7890</a></p>
                    </li>
                    <li>
                        <span><i class="fa-solid fa-envelope"></i></span>
                        <p><a href="mailto: notzque@gmail.com">notzque@gmail.com</a></p>
                    </li>
                </ul>
            </div>
        </div>

        <div class="copyrightText">
            <p>&copy; NotzQue 2024 Digital Notes. All Rights Reserved. </p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>

</html>
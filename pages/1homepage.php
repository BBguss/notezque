<?php
include '../config/koneksi.php';
session_start();

function getKonten($conn, $halaman, $section)
{
    $halaman = mysqli_real_escape_string($conn, $halaman);
    $section = mysqli_real_escape_string($conn, $section);

    $query = "SELECT deskripsi FROM konten_statis 
              WHERE nama_halaman = '$halaman' AND section = '$section' 
              LIMIT 1";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        return $row['deskripsi'];
    }
    return '';
}

// Track visitor
if(file_exists("../config/track_visit.php")) {
    include_once '../config/track_visit.php';
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notezque</title>
    <link rel="icon" type="image/x-icon" href="../uploads/<?= getKonten($conn, 'all', 'all') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../asset/css/1homepage.css">
    <link rel="stylesheet" href="../asset/attributes/Atribute3.css">
    <link rel="stylesheet" href="../asset/font/Font.css">
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <div class="top-navbar">
            <nav>
            <img src="../uploads/<?= getKonten($conn, 'all', 'all') ?>" alt="Logo">
                <div class="loginRegist">
                    <?php echo getKonten($conn, 'homepage', 'header') ?> 
                </div>
            </nav>
        </div>
    </header>
    <main>
        <?php echo getKonten($conn, 'homepage', 'text_header') ?>
        <div class="loginRegist2">
            <a href="3registerpage.php"><button type="button" class="button">Bergabung Sekarang</button></a>
        </div>
        <div class="gif-container">
            <div id="videoCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="../asset/images/gambar (1).png">
                    </div>
                    <div class="carousel-item">
                        <img src="../asset/images/gambar (2).png">
                    </div>
                    <div class="carousel-item">
                        <img src="../asset/images/gambar (3).png">
                    </div>
                    <div class="carousel-item">
                        <img src="../asset/images/gambar (4).png">
                    </div>
                    <div class="carousel-item">
                        <img src="../asset/images/gambar (5).png">
                    </div>
                    <div class="carousel-item">
                        <img src="../asset/images/gambar (6).png">
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
                <?php 
                    echo "<h2>". getKonten($conn, 'homepage', 'footer h2'). "</h2>";
                    echo "<p>". getKonten($conn, 'homepage', 'footer'). "</p>";
                 ?>
                <ul class="satu">
                <?php
                    echo getKonten($conn, 'homepage', 'sosmed');
                ?>
                </ul>
            </div>
            <div class="sec contactus">
            <?php
                echo getKonten($conn, 'homepage', 'kontak');
            ?>
            </div>
        </div>

        <div class="copyrightText">
        <?php
            echo getKonten($conn, 'homepage', 'haki');
        ?>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>

</html>
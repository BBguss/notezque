
<?php
// Replace the existing include with:
$base_path = $_SERVER['DOCUMENT_ROOT'] . '/Kelompok_3/config/koneksi.php';
include $base_path;

function getKonten($conn, $halaman, $section) {
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <footer id="footer">
        <div class="container">
            <div class="sec aboutus">
                <?php 
                    echo "<h2>". getKonten($conn, 'homepage', 'footer h2'). "</h2>";
                    echo "<p>". getKonten($conn, 'homepage', 'footer'). "</p>";
                 ?>`
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
</body>
</html>

<?php
session_start();

$timeout = 3600;

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: http://localhost/kelompok_3/pages/2loginpage.php");
    exit();
}

if (isset($_POST['logout']) || (isset($_SESSION['aktivitas']) && (time() - $_SESSION['aktivitas']) > $timeout)){
    session_unset();
    session_destroy();
    header('location: http://localhost/kelompok_3/pages/1homepage.php');
    exit();
}

if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
    $now = date('y-m-d H:i:s');
    mysqli_query($conn, "UPDATE users SET aktivitas_terakhir = '$now' WHERE id_user = $id_user");
}

$_SESSION['aktivitas'] = time();
?>
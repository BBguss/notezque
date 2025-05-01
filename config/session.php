<?php
session_start();

$timeout = 3600;

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: http://localhost/kelompok_3/pages/dashboard/5Dashboard.php");
    exit();
}

if (isset($_POST['logout']) || (isset($_SESSION['aktivitas']) && (time() - $_SESSION['aktivitas']) > $timeout)){
    session_unset();
    session_destroy();
    header('location: http://localhost/kelompok_3/pages/2loginpage.php');
    exit();
}

$_SESSION['aktivitas'] = time();
?>
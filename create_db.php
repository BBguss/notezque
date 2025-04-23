<?php
    $host = "localhost";
    $userdb = "root";
    $passdb = "";
    $namedb = "NotezQue";

    $conndb = mysqli_connect($host, $userdb, $passdb);

    $dbname = "NotezQue";
    $query = mysqli_query($conndb, "CREATE DATABASE $dbname");

    if ($query) {
    echo "<h1>database berhasil di buat</h1>";
    } else {
    echo "Database gagal dibuat";
    }

    $conn = mysqli_connect($host, $userdb, $passdb,$dbname);
?>
<?php
    $hostname = 'localhost';
    $username = 'root';
    $password = 'Supp0rt';
    $dbname   = 'db_aspirasi_siswa';

    $conn = mysqli_connect($hostname, $username, $password, $dbname) or die ('Gagal terhubung ke database');
?>
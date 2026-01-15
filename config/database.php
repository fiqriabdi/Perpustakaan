<?php
// config/database.php

$host     = "localhost";
$username = "root";
$password = "";
$database = "db_perpustakaan";

$conn = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

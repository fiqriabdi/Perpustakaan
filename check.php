<?php
//check.php
require_once 'config/config.php';
session_start();

if (isset($_SESSION['login'])) {

    if ($_SESSION['role'] == 'admin') {
        header("Location: admin/dashboard.php");
    } elseif ($_SESSION['role'] == 'petugas') {
        header("Location: petugas/dashboard.php");
    } else {
        header("Location: mahasiswa/dashboard.php");
    }

} else {
    header("Location: auth/login.php");
}
exit;

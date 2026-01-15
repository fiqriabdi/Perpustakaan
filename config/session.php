<?php
// config/session.php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: " . BASE_URL . "auth/login.php");
    exit;
}

// Fungsi cek role
function cek_role($role) {
    if ($_SESSION['role'] != $role) {
        echo "Akses ditolak!";
        exit;
    }
}

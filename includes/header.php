<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= APP_NAME ?></title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    

    <!-- Custom -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin.css">
</head>
<body>

<!-- TOPBAR -->
<nav class="navbar navbar-dark bg-success px-4">
    <span class="navbar-brand fw-bold">
         <?= APP_NAME ?>
    </span>
    <span class="text-white small">
        Sistem Informasi Perpustakaan Berbasis Web v1.0
    </span>
</nav>

<div class="d-flex">

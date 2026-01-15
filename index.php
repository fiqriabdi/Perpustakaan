<?php
require_once 'config/config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= APP_NAME ?></title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #0a2246, #081a38);
            min-height: 100vh;
        }
        .hero {
            min-height: 85vh;
            display: flex;
            align-items: center;
        }
        .carousel-item img {
            height: 420px;
            object-fit: cover;
        }
        .btn-warning {
            transition: 0.3s;
        }
        .btn-warning:hover {
            transform: translateY(-3px);
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
    <div class="container">
        <!-- GANTI NAMA APLIKASI DI SINI -->
        <a class="navbar-brand fw-bold" href="#">
            📚 Perpustakaan Kampus UTDI
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="#">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Profil</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Layanan</a></li>
                <li class="nav-item">
                    <a href="auth/login.php" class="btn btn-light ms-3">Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero text-white">
    <div class="container">
        <div class="row align-items-center g-4">
            
            <!-- TEXT -->
            <div class="col-md-6">
                <h1 class="fw-bold display-5 mb-3">
                    SELAMAT DATANG
                </h1>
                <p class="lead mb-4">
                    Sistem Informasi Perpustakaan Kampus  
                    untuk mempermudah peminjaman, pengembalian,
                    dan perpanjangan buku secara online.
                </p>
                <!-- <a href="auth/login.php" class="btn btn-warning btn-lg px-4">
                    Masuk ke Sistem
                </a> --><br><br>
            </div>

            <!-- SLIDER -->
            <div class="col-md-6">
                <div id="heroCarousel" class="carousel slide carousel-fade shadow rounded" data-bs-ride="carousel">

                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
                    </div>

                    <div class="carousel-inner rounded">
                        <div class="carousel-item active">
                            <img src="assets/img/utdi.png" class="d-block w-100" alt="Perpustakaan UTDI">
                        </div>
                        <div class="carousel-item">
                            <img src="assets/img/perpus1.jpg" class="d-block w-100" alt="Buku">
                        </div>
                        <div class="carousel-item">
                            <img src="assets/img/perpus2.jpg" class="d-block w-100" alt="Mahasiswa">
                        </div>
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="bg-dark text-white text-center py-3">
    © <?= date('Y') ?> Perpustakaan Kampus UTDI
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

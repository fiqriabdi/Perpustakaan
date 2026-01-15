<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../config/session.php';

cek_role('admin');

require_once '../includes/header.php';
require_once '../includes/sidebar.php';

// ========================
// HITUNG DATA
// ========================

// mahasiswa
$q_mhs = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='mahasiswa'");
$jml_mahasiswa = mysqli_fetch_assoc($q_mhs)['total'];

// petugas
$q_petugas = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='petugas'");
$jml_petugas = mysqli_fetch_assoc($q_petugas)['total'];

// buku
$q_buku = mysqli_query($conn, "SELECT COUNT(*) AS total FROM buku");
$jml_buku = mysqli_fetch_assoc($q_buku)['total'];

// sirkulasi (peminjaman aktif)
$q_sirkulasi = mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM peminjaman 
    WHERE status='dipinjam'
");
$jml_sirkulasi = mysqli_fetch_assoc($q_sirkulasi)['total'];
?>

<div class="container-fluid px-4">
    <h4 class="mt-4 mb-4">Dashboard Administrator</h4>

    <div class="row g-4">

        <!-- BUKU -->
        <div class="col-md-3">
            <div class="card text-white bg-primary shadow">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3><?= $jml_buku ?></h3>
                        <p class="mb-0">Buku</p>
                    </div>
                    <i class="bi bi-book fs-1 opacity-50"></i>
                </div>
                <div class="card-footer text-center">
                    <a href="kelola_buku.php" class="text-white text-decoration-none">
                        More info <i class="bi bi-arrow-right-circle"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- ANGGOTA -->
        <div class="col-md-3">
            <div class="card text-white bg-success shadow">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3><?= $jml_mahasiswa ?></h3>
                        <p class="mb-0">Anggota</p>
                    </div>
                    <i class="bi bi-people fs-1 opacity-50"></i>
                </div>
                <div class="card-footer text-center">
                    <a href="kelola_mahasiswa.php" class="text-white text-decoration-none">
                        More info <i class="bi bi-arrow-right-circle"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- SIRKULASI -->
        <div class="col-md-3">
            <div class="card text-dark bg-warning shadow">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3><?= $jml_sirkulasi ?></h3>
                        <p class="mb-0">Sirkulasi</p>
                    </div>
                    <i class="bi bi-arrow-left-right fs-1 opacity-50"></i>
                </div>
                <div class="card-footer text-center">
                    <a href="../petugas/data_peminjaman.php" class="text-dark text-decoration-none">
                        More info <i class="bi bi-arrow-right-circle"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- LAPORAN -->
        <div class="col-md-3">
            <div class="card text-white bg-danger shadow">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3>📊</h3>
                        <p class="mb-0">Laporan</p>
                    </div>
                    <i class="bi bi-file-earmark-text fs-1 opacity-50"></i>
                </div>
                <div class="card-footer text-center">
                    <a href="laporan.php" class="text-white text-decoration-none">
                        More info <i class="bi bi-arrow-right-circle"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../config/session.php';

cek_role('petugas');

/* ==============================
   UPDATE STATUS TERLAMBAT
   ============================== */
mysqli_query($conn,"
    UPDATE peminjaman
    SET status='terlambat'
    WHERE tanggal_kembali IS NULL
    AND CURDATE() > DATE_ADD(tanggal_pinjam, INTERVAL 7 DAY)
");

/* ==============================
   DATA DASHBOARD
   ============================== */
$peminjaman = mysqli_fetch_assoc(
    mysqli_query($conn,"
        SELECT COUNT(*) total 
        FROM peminjaman 
        WHERE status='dipinjam'
    ")
)['total'];

$perpanjangan = mysqli_fetch_assoc(
    mysqli_query($conn,"
        SELECT COUNT(*) total 
        FROM perpanjangan 
        WHERE status='pending'
    ")
)['total'];

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
?>

<div class="container-fluid px-4">
    <h4 class="mt-4 mb-4">Dashboard Petugas</h4>

    <div class="row g-4">

        <!-- PEMINJAMAN -->
        <div class="col-md-6 col-lg-4">
            <div class="card bg-primary text-white shadow h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3><?= $peminjaman ?></h3>
                        <p class="mb-1">Peminjaman</p>
                        <a href="data_peminjaman.php" class="btn btn-light btn-sm mt-2">
                            Lihat Peminjaman
                        </a>
                    </div>
                    <i class="bi bi-book fs-1 opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- PERPANJANGAN -->
        <div class="col-md-6 col-lg-4">
            <div class="card bg-warning text-white shadow h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3><?= $perpanjangan ?></h3>
                        <p class="mb-1">Perpanjangan Pending</p>
                        <a href="verifikasi_perpanjangan.php" class="btn btn-light btn-sm mt-2">
                            Verifikasi
                        </a>

                        <?php if ($perpanjangan > 0): ?>
                            <div class="mt-2 small text-dark fw-bold">
                                ⚠ Ada <?= $perpanjangan ?> permintaan menunggu
                            </div>
                        <?php endif; ?>
                    </div>
                    <i class="bi bi-arrow-repeat fs-1 opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- INFO -->
        <div class="col-md-6 col-lg-4">
            <div class="card bg-success text-white shadow h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">Status Sistem</h5>
                        <small>Operasional</small>
                    </div>
                    <i class="bi bi-check-circle fs-1 opacity-50"></i>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

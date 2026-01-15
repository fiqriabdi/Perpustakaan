<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../config/session.php';

cek_role('mahasiswa');

require_once '../includes/header.php';
require_once '../includes/sidebar.php';

$uid = $_SESSION['user_id'];

// ambil id_mahasiswa
$mhs = mysqli_fetch_assoc(
    mysqli_query($conn,"
        SELECT id_mahasiswa 
        FROM mahasiswa 
        WHERE id_user = '$uid'
    ")
);

$id_mahasiswa = $mhs['id_mahasiswa'];

// hitung pinjaman aktif
$pinjaman = mysqli_fetch_assoc(
    mysqli_query($conn,"
        SELECT COUNT(*) total
        FROM peminjaman 
        WHERE id_mahasiswa = '$id_mahasiswa'
        AND status = 'dipinjam'
    ")
)['total'];

// hitung perpanjangan pending
$perpanjangan = mysqli_fetch_assoc(
    mysqli_query($conn,"
        SELECT COUNT(*) total
        FROM perpanjangan p
        JOIN peminjaman pj ON p.id_peminjaman = pj.id_peminjaman
        WHERE pj.id_mahasiswa = '$id_mahasiswa'
        AND p.status = 'pending'
    ")
)['total'];
?>

<div class="container-fluid px-4">
    <h4 class="mt-4 mb-4">Dashboard Mahasiswa</h4>

    <div class="row g-4">

        <!-- PINJAMAN AKTIF -->
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3><?= $pinjaman ?></h3>
                        <p class="mb-1">Pinjaman</p>
                        <a href="data_pinjaman.php" class="btn btn-light btn-sm mt-2">
                            Lihat Pinjaman
                        </a>
                    </div>
                    <i class="bi bi-book fs-1 opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- AJUKAN PERPANJANGAN -->
        <div class="col-md-4">
            <div class="card bg-warning text-white shadow h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3><?= $perpanjangan ?></h3>
                        <p class="mb-1">Perpanjangan Pending</p>
                        <a href="perpanjangan.php" class="btn btn-light btn-sm mt-2">
                            Ajukan Perpanjangan
                        </a>
                    </div>
                    <i class="bi bi-arrow-repeat fs-1 opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- NOTIFIKASI -->
        <div class="col-md-4">
            <div class="card bg-success text-white shadow h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">Notifikasi</h5>
                        <small>Cek status perpanjangan</small>
                        <br>
                        <a href="notifikasi.php" class="btn btn-light btn-sm mt-2">
                            Lihat Notifikasi
                        </a>
                    </div>
                    <i class="bi bi-bell fs-1 opacity-50"></i>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

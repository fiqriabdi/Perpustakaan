<?php
// includes/sidebar.php
$role = $_SESSION['role'] ?? '';
?>

<aside class="sidebar bg-dark text-white">
    <!-- USER INFO -->
    <div class="user-panel text-center p-3 border-bottom">
        <img src="<?= BASE_URL ?>assets/img/user.png"
             class="rounded-circle mb-2"
             width="60">
        <h6 class="mb-0"><?= $_SESSION['username'] ?? '' ?></h6>
        <span class="badge bg-warning text-dark mt-1">
            <?= ucfirst($role) ?>
        </span>
    </div>

    <!-- MENU -->
    <ul class="nav flex-column mt-3">

        <?php if ($role == 'admin'): ?>
            <li class="nav-item">
                <a href="<?= BASE_URL ?>admin/dashboard.php" class="nav-link text-white">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>



            <li class="nav-item mt-2 text-uppercase text-secondary small px-3">
                Master Data
            </li>

            <li class="nav-item">
                <a href="<?= BASE_URL ?>admin/kelola_buku.php" class="nav-link text-white">
                    <i class="bi bi-book me-2"></i> Kelola Buku
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= BASE_URL ?>admin/kelola_mahasiswa.php" class="nav-link text-white">
                    <i class="bi bi-people me-2"></i> Kelola Mahasiswa
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= BASE_URL ?>admin/kelola_petugas.php" class="nav-link text-white">
                    <i class="bi bi-person-badge me-2"></i> Sistem Pengguna
                </a>
            </li>

            <li class="nav-item mt-2 text-uppercase text-secondary small px-3">
                Laporan
            </li>

            <li class="nav-item">
                <a href="<?= BASE_URL ?>admin/laporan.php" class="nav-link text-white">
                    <i class="bi bi-file-earmark-text me-2"></i> Laporan
                </a>
            </li>

        <?php elseif ($role == 'petugas'): ?>
            <li class="nav-item">
                <a href="<?= BASE_URL ?>petugas/dashboard.php" class="nav-link text-white">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>

            <li class="nav-item mt-2 text-uppercase text-secondary small px-3">
                Sirkulasi
            </li>

            <li class="nav-item">
                <a href="<?= BASE_URL ?>petugas/input_peminjaman.php" class="nav-link text-white">
                    <i class="bi bi-arrow-right-circle me-2"></i> Peminjaman
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= BASE_URL ?>petugas/pengembalian.php" class="nav-link text-white">
                    <i class="bi bi-arrow-left-circle me-2"></i> Pengembalian
                </a>
            </li>

      <li class="nav-item">
    <a class="nav-link" href="perpanjangan_langsung.php">
        <i class="bi bi-clock-history"></i>
        Perpanjangan
    </a>
</li>

            <li class="nav-item">
                <a href="<?= BASE_URL ?>petugas/verifikasi_perpanjangan.php" class="nav-link text-white">
                    <i class="bi bi-check-circle me-2"></i> Verifikasi
                </a>
            </li>

       <li class="nav-item">
    <a class="nav-link" href="riwayat_perpanjangan.php">
        <i class="bi bi-clock-history"></i>
        Riwayat Perpanjangan
    </a>
</li>


            <li class="nav-item mt-2 text-uppercase text-secondary small px-3">
                Data
            </li>

     
            <li class="nav-item">
                <a href="<?= BASE_URL ?>petugas/registrasi_mahasiswa.php" class="nav-link text-white">
                    <i class="bi bi-person-plus me-2"></i> Registrasi
                </a>
            </li>

        <?php elseif ($role == 'mahasiswa'): ?>
            <li class="nav-item">
                <a href="<?= BASE_URL ?>mahasiswa/dashboard.php" class="nav-link text-white">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>

            <li class="nav-item mt-2 text-uppercase text-secondary small px-3">
                Akademik
            </li>

            <li class="nav-item">
                <a href="<?= BASE_URL ?>mahasiswa/data_pinjaman.php" class="nav-link text-white">
                    <i class="bi bi-bookmark me-2"></i> Data Pinjaman
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= BASE_URL ?>mahasiswa/perpanjangan.php" class="nav-link text-white">
                    <i class="bi bi-arrow-repeat me-2"></i> Perpanjangan
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= BASE_URL ?>mahasiswa/notifikasi.php" class="nav-link text-white">
                    <i class="bi bi-bell me-2"></i> Notifikasi
                </a>
            </li>

            <li class="nav-item mt-2 text-uppercase text-secondary small px-3">
                Akun
            </li>

            <li class="nav-item">
                <a href="<?= BASE_URL ?>mahasiswa/ganti_password.php" class="nav-link text-white">
                    <i class="bi bi-lock me-2"></i> Ganti Password
                </a>
            </li>
        <?php endif; ?>

        <hr class="text-secondary mx-3">

        <li class="nav-item">
            <a href="<?= BASE_URL ?>auth/logout.php" class="nav-link text-danger">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
        </li>
    </ul>
</aside>

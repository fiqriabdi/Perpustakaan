<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../config/session.php';

cek_role('petugas');

/* ===============================
   DATA RIWAYAT PERPANJANGAN
   =============================== */
$data = mysqli_query($conn, "
    SELECT 
        p.id_perpanjangan,
        m.nim,
        m.nama,
        p.kode_buku,
        p.judul_manual,
        p.pengarang_manual,
        p.tanggal_request,
        p.status
    FROM perpanjangan p
    JOIN peminjaman pj ON p.id_peminjaman = pj.id_peminjaman
    JOIN mahasiswa m ON pj.id_mahasiswa = m.id_mahasiswa
    WHERE p.status IN ('accept','reject')
    ORDER BY p.tanggal_request DESC
");

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
?>

<div class="container-fluid px-4">
    <h4 class="mt-4 mb-4">Riwayat Perpanjangan</h4>

    <div class="card shadow">
        <div class="card-body">

            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Barcode</th>
                        <th>Judul Buku</th>
                        <th>Pengarang</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>

                <?php
                $no = 1;
                if (mysqli_num_rows($data) > 0):
                    while ($row = mysqli_fetch_assoc($data)):
                ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nim']) ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td class="text-center">
                            <span class="badge bg-secondary">
                                <?= htmlspecialchars($row['kode_buku']) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($row['judul_manual']) ?></td>
                        <td><?= htmlspecialchars($row['pengarang_manual']) ?></td>
                        <td class="text-center"><?= $row['tanggal_request'] ?></td>
                        <td class="text-center">
                            <?php if ($row['status'] === 'accept'): ?>
                                <span class="badge bg-success">
                                    Disetujui
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger">
                                    Ditolak
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            Belum ada riwayat verifikasi
                        </td>
                    </tr>
                <?php endif; ?>

                </tbody>
            </table>

            <a href="dashboard.php" class="btn btn-secondary mt-3">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>

        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

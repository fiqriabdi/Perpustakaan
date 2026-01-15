<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../config/session.php';

cek_role('petugas');

/* ===============================
   DATA PEMINJAMAN
   =============================== */
$data = mysqli_query($conn, "
    SELECT 
        pj.id_peminjaman,
        m.nim,
        m.nama,
        b.judul,
        b.kode_buku,
        pj.tanggal_pinjam,
        pj.tanggal_jatuh_tempo,
        pj.tanggal_kembali,
        pj.status
    FROM peminjaman pj
    JOIN mahasiswa m ON pj.id_mahasiswa = m.id_mahasiswa
    JOIN buku b ON pj.id_buku = b.id_buku
    ORDER BY pj.tanggal_pinjam DESC
");

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
?>

<div class="container-fluid px-4">
    <h4 class="mt-4 mb-4">Data Peminjaman Buku</h4>

    <div class="card shadow">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama </th>
                            <th>Judul buku</th>
                            <th>Kode Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Tgl Kembali</th>
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
                            <td><?= htmlspecialchars($row['judul']) ?></td>
                            <td><?= htmlspecialchars($row['kode_buku']) ?></td>
                            <td class="text-center"><?= $row['tanggal_pinjam'] ?></td>
                            <td class="text-center"><?= $row['tanggal_jatuh_tempo'] ?></td>
                            <td class="text-center">
                                <?= $row['tanggal_kembali'] ?: '-' ?>
                            </td>
                            <td class="text-center">
                                <?php if ($row['status'] == 'dipinjam'): ?>
                                    <span class="badge bg-success">Dipinjam</span>
                                <?php elseif ($row['status'] == 'terlambat'): ?>
                                    <span class="badge bg-danger">Terlambat</span>
                                <?php else: ?>
                                    <span class="badge bg-primary">Dikembalikan</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                Tidak ada data peminjaman
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <a href="dashboard.php" class="btn btn-secondary mt-3">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>

        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

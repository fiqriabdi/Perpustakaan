<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../config/session.php';

cek_role('petugas');

/* ===============================
   PROSES PENGEMBALIAN
   =============================== */
if (isset($_GET['id'])) {

    $id_peminjaman = (int) $_GET['id'];

    $q = mysqli_query($conn, "
        SELECT id_buku 
        FROM peminjaman 
        WHERE id_peminjaman='$id_peminjaman'
        AND status='dipinjam'
    ");

    if (mysqli_num_rows($q) == 1) {

        $r = mysqli_fetch_assoc($q);
        $id_buku = $r['id_buku'];

        // update peminjaman
        mysqli_query($conn, "
            UPDATE peminjaman
            SET status='dikembalikan', tanggal_kembali=CURDATE()
            WHERE id_peminjaman='$id_peminjaman'
        ");

        // update stok buku
        mysqli_query($conn, "
            UPDATE buku
            SET stok = stok + 1
            WHERE id_buku='$id_buku'
        ");

        // log aktivitas
        $uid = $_SESSION['user_id'];
        mysqli_query($conn, "
            INSERT INTO log_aktivitas (id_user, aktivitas)
            VALUES ('$uid', 'Pengembalian buku ID $id_buku')
        ");

        $success = "Buku berhasil dikembalikan";

    } else {
        $error = "Data peminjaman tidak valid";
    }
}

/* ===============================
   DATA PEMINJAMAN AKTIF
   =============================== */
$data = mysqli_query($conn,"
    SELECT 
        p.id_peminjaman,
        m.nim,
        m.nama,
        b.judul,
        b.kode_buku,
        p.tanggal_pinjam,
        p.tanggal_jatuh_tempo
    FROM peminjaman p
    JOIN mahasiswa m ON p.id_mahasiswa = m.id_mahasiswa
    JOIN buku b ON p.id_buku = b.id_buku
    WHERE p.status='dipinjam'
    ORDER BY p.tanggal_pinjam ASC
");

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
?>

<div class="container-fluid mt-4">

    <h4 class="mb-3">📦 Pengembalian Buku</h4>

    <?php if (isset($success)) : ?>
        <div class="alert alert-success alert-dismissible fade show">
            ✅ <?= $success ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($error)) : ?>
        <div class="alert alert-danger alert-dismissible fade show">
            ❌ <?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Judul Buku</th>
                            <th>Kode Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if (mysqli_num_rows($data) > 0) :
                            while ($row = mysqli_fetch_assoc($data)) :
                        ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nim']) ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['judul']) ?></td>
                            <td><?= htmlspecialchars($row['kode_buku']) ?></td>
                            <td><?= $row['tanggal_pinjam'] ?></td>
                            <td><?= $row['tanggal_jatuh_tempo'] ?></td>
                            <td class="text-center">
                                <a href="?id=<?= $row['id_peminjaman'] ?>"
                                   class="btn btn-sm btn-success"
                                   onclick="return confirm('Yakin buku ini dikembalikan?')">
                                   Kembalikan
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Tidak ada peminjaman aktif
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <a href="dashboard.php" class="btn btn-secondary mt-3">
        ← Kembali ke Dashboard
    </a>

</div>

<?php require_once '../includes/footer.php'; ?>

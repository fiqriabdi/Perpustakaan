<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../config/session.php';

cek_role('petugas');

/* ===============================
   PROSES PERPANJANGAN
   =============================== */
if (isset($_POST['perpanjang'])) {

    $id_peminjaman = (int)$_POST['id_peminjaman'];
    $hari          = (int)$_POST['hari'];

    if ($hari <= 0) $hari = 7;

    $q = mysqli_query($conn,"
        SELECT 
            pj.id_peminjaman,
            pj.tanggal_jatuh_tempo,
            pj.status,
            b.judul,
            u.id_user
        FROM peminjaman pj
        JOIN buku b ON pj.id_buku = b.id_buku
        JOIN mahasiswa m ON pj.id_mahasiswa = m.id_mahasiswa
        JOIN users u ON m.id_user = u.id_user
        WHERE pj.id_peminjaman = '$id_peminjaman'
        AND pj.status = 'dipinjam'
    ");

    if (mysqli_num_rows($q) == 0) {
        $type = "danger";
        $msg  = "❌ Data peminjaman tidak valid";
    } else {

        $d = mysqli_fetch_assoc($q);

        // simpan ke tabel perpanjangan (langsung accept)
        mysqli_query($conn,"
            INSERT INTO perpanjangan
            (id_peminjaman, tanggal_request, jenis_pengajuan, status, keterangan)
            VALUES
            ('$id_peminjaman', CURDATE(), 'petugas', 'accept',
             'Perpanjangan langsung oleh petugas')
        ");

        // update jatuh tempo
        mysqli_query($conn,"
            UPDATE peminjaman
            SET tanggal_jatuh_tempo =
                DATE_ADD(tanggal_jatuh_tempo, INTERVAL $hari DAY)
            WHERE id_peminjaman='$id_peminjaman'
        ");

        // notifikasi mahasiswa
        mysqli_query($conn,"
            INSERT INTO notifikasi (id_user, pesan)
            VALUES (
                '{$d['id_user']}',
                '📘 Buku \"{$d['judul']}\" diperpanjang oleh petugas (+$hari hari)'
            )
        ");

        // log aktivitas
        $uid = $_SESSION['user_id'];
        mysqli_query($conn,"
            INSERT INTO log_aktivitas (id_user, aktivitas)
            VALUES (
                '$uid',
                'Perpanjangan manual petugas ID $id_peminjaman (+$hari hari)'
            )
        ");

        $type = "success";
        $msg  = "✅ Perpanjangan berhasil dilakukan";
    }
}

/* ===============================
   DATA PEMINJAMAN AKTIF
   =============================== */
$data = mysqli_query($conn,"
    SELECT 
        pj.id_peminjaman,
        m.nim,
        m.nama,
        b.judul,
        pj.tanggal_jatuh_tempo
    FROM peminjaman pj
    JOIN mahasiswa m ON pj.id_mahasiswa = m.id_mahasiswa
    JOIN buku b ON pj.id_buku = b.id_buku
    WHERE pj.status = 'dipinjam'
    ORDER BY pj.tanggal_jatuh_tempo ASC
");

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
?>

<div class="container mt-4">
    <h4 class="mb-3">Perpanjangan Buku (Manual oleh Petugas)</h4>

    <?php if (isset($msg)) : ?>
        <div class="alert alert-<?= $type ?> alert-dismissible fade show">
            <?= $msg ?>
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="post">

                <div class="mb-3">
                    <label class="form-label">Pilih Peminjaman</label>
                    <select name="id_peminjaman" class="form-select" required>
                        <option value="">-- Pilih Mahasiswa & Buku --</option>
                        <?php while ($r = mysqli_fetch_assoc($data)) : ?>
                            <option value="<?= $r['id_peminjaman'] ?>">
                                <?= $r['nim'] ?> | <?= $r['nama'] ?> |
                                <?= $r['judul'] ?> |
                                JT: <?= $r['tanggal_jatuh_tempo'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tambah Hari Perpanjangan</label>
                    <input type="number"
                           name="hari"
                           class="form-control"
                           value="7"
                           min="1"
                           required>
                </div>

                <button type="submit"
                        name="perpanjang"
                        class="btn btn-success"
                        onclick="return confirm('Yakin melakukan perpanjangan?')">
                    <i class="bi bi-arrow-repeat"></i> Perpanjang Buku
                </button>

                <a href="dashboard.php" class="btn btn-secondary">
                    Kembali
                </a>

            </form>

        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

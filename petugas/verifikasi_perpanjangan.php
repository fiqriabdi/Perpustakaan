<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../config/session.php';

cek_role('petugas');

/* ===============================
   PROSES ACCEPT / REJECT
   =============================== */
if (isset($_GET['action'], $_GET['id'])) {

    $id = (int) $_GET['id'];
    $action = $_GET['action'];

    // Ambil data perpanjangan manual
    $q = mysqli_query($conn, "
        SELECT 
            p.id_perpanjangan,
            p.id_peminjaman,
            p.judul_manual,
            pj.id_mahasiswa,
            u.id_user
        FROM perpanjangan p
        JOIN peminjaman pj ON p.id_peminjaman = pj.id_peminjaman
        JOIN mahasiswa m ON pj.id_mahasiswa = m.id_mahasiswa
        JOIN users u ON m.id_user = u.id_user
        WHERE p.id_perpanjangan = '$id'
    ");

    if ($d = mysqli_fetch_assoc($q)) {

        if ($action === 'accept') {

            // Update status perpanjangan
            mysqli_query($conn, "
                UPDATE perpanjangan 
                SET status='accept'
                WHERE id_perpanjangan='$id'
            ");

            // Tambah jatuh tempo 7 hari
            mysqli_query($conn, "
                UPDATE peminjaman
                SET tanggal_jatuh_tempo = DATE_ADD(tanggal_jatuh_tempo, INTERVAL 7 DAY)
                WHERE id_peminjaman='{$d['id_peminjaman']}'
            ");

            // Notifikasi mahasiswa
            mysqli_query($conn, "
                INSERT INTO notifikasi (id_user, pesan)
                VALUES (
                    '{$d['id_user']}',
                    '✅ Perpanjangan buku \"{$d['judul_manual']}\" disetujui'
                )
            ");
        }

        if ($action === 'reject') {

            mysqli_query($conn, "
                UPDATE perpanjangan 
                SET status='reject'
                WHERE id_perpanjangan='$id'
            ");

            mysqli_query($conn, "
                INSERT INTO notifikasi (id_user, pesan)
                VALUES (
                    '{$d['id_user']}',
                    '❌ Perpanjangan buku \"{$d['judul_manual']}\" ditolak'
                )
            ");
        }
    }

    header("Location: verifikasi_perpanjangan.php");
    exit;
}

/* ===============================
   DATA PERPANJANGAN MANUAL
   =============================== */
$data = mysqli_query($conn, "
    SELECT 
        p.id_perpanjangan,
        m.nim,
        m.nama,
        p.kode_buku,
        p.judul_manual,
        p.pengarang_manual,
        p.tanggal_request
    FROM perpanjangan p
    JOIN peminjaman pj ON p.id_peminjaman = pj.id_peminjaman
    JOIN mahasiswa m ON pj.id_mahasiswa = m.id_mahasiswa
    WHERE p.status='pending'
    ORDER BY p.tanggal_request DESC
");

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
?>

<div class="container-fluid px-4">
    <h4 class="mt-4 mb-4">Verifikasi Perpanjangan Manual</h4>

    <div class="card shadow">
        <div class="card-body">

            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Kode Buku (Barcode)</th>
                        <th>Judul Buku</th>
                        <th>Pengarang</th>
                        <th>Tanggal Request</th>
                        <th>Aksi</th>
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

                            <a href="?action=accept&id=<?= $row['id_perpanjangan'] ?>"
                               class="btn btn-success btn-sm">
                                <i class="bi bi-check-circle"></i>
                            </a>

                            <a href="?action=reject&id=<?= $row['id_perpanjangan'] ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin menolak perpanjangan ini?')">
                                <i class="bi bi-x-circle"></i>
                            </a>

                        </td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            Tidak ada pengajuan perpanjangan manual
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

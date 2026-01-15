<?php
// mahasiswa/data_pinjaman.php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../config/session.php';

cek_role('mahasiswa');

$uid = $_SESSION['user_id'];

// ambil id_mahasiswa
$mhs = mysqli_query($conn,"
    SELECT id_mahasiswa 
    FROM mahasiswa 
    WHERE id_user = '$uid'
");

$m = mysqli_fetch_assoc($mhs);
$id_mahasiswa = $m['id_mahasiswa'];

// ambil data pinjaman
$data = mysqli_query($conn,"
    SELECT 
        p.id_peminjaman,
        b.judul,
        p.status
    FROM peminjaman p
    JOIN buku b ON p.id_buku = b.id_buku
    WHERE p.id_mahasiswa = '$id_mahasiswa'
    ORDER BY p.id_peminjaman DESC
");

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
?>

<div class="container-fluid mt-4">

    <h4 class="mb-3">Data Pinjaman Buku</h4>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="60">No</th>
                            <th>Judul Buku</th>
                            <th width="150">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 1;
                    if (mysqli_num_rows($data) > 0) {
                        while ($r = mysqli_fetch_assoc($data)) {
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($r['judul']) ?></td>
                            <td class="text-center">
                                <?php if ($r['status'] == 'dipinjam') { ?>
                                    <span class="badge bg-primary">Dipinjam</span>
                                <?php } elseif ($r['status'] == 'terlambat') { ?>
                                    <span class="badge bg-danger">Terlambat</span>
                                <?php } else { ?>
                                    <span class="badge bg-success">Dikembalikan</span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php 
                        }
                    } else {
                    ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                Belum ada data pinjaman
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

            <a href="dashboard.php" class="btn btn-secondary mt-3">
                Kembali ke Dashboard
            </a>

        </div>
    </div>

</div>

<?php require_once '../includes/footer.php'; ?>

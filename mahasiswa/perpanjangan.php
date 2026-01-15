<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../config/session.php';

cek_role('mahasiswa');

$uid = $_SESSION['user_id'];

// ambil id_mahasiswa
$q = mysqli_query($conn,"
    SELECT id_mahasiswa 
    FROM mahasiswa 
    WHERE id_user='$uid'
");
$m = mysqli_fetch_assoc($q);
$id_mahasiswa = $m['id_mahasiswa'];

// ==========================
// PROSES AJUKAN PERPANJANGAN
// ==========================
if (isset($_POST['ajukan'])) {

    $kode_buku = mysqli_real_escape_string($conn, $_POST['kode_buku']);
    $judul     = mysqli_real_escape_string($conn, $_POST['judul_manual']);
    $pengarang = mysqli_real_escape_string($conn, $_POST['pengarang_manual']);
    $ket       = mysqli_real_escape_string($conn, $_POST['keterangan']);

    if ($kode_buku == '' || $judul == '' || $pengarang == '') {
        $msg = "❌ Semua field wajib diisi";
    } else {

        // ambil 1 peminjaman aktif terakhir (untuk FK)
        $p = mysqli_query($conn,"
            SELECT id_peminjaman 
            FROM peminjaman 
            WHERE id_mahasiswa='$id_mahasiswa'
            AND status='dipinjam'
            ORDER BY tanggal_pinjam DESC
            LIMIT 1
        ");

        if (mysqli_num_rows($p) == 0) {
            $msg = "❌ Tidak ada peminjaman aktif";
        } else {

            $pinjam = mysqli_fetch_assoc($p);

            mysqli_query($conn,"
                INSERT INTO perpanjangan
                (
                    id_peminjaman,
                    tanggal_request,
                    jenis_pengajuan,
                    kode_buku,
                    judul_manual,
                    pengarang_manual,
                    status,
                    keterangan
                ) VALUES (
                    '{$pinjam['id_peminjaman']}',
                    CURDATE(),
                    'manual',
                    '$kode_buku',
                    '$judul',
                    '$pengarang',
                    'pending',
                    '$ket'
                )
            ");

            $msg = "✅ Perpanjangan manual berhasil diajukan";
        }
    }
}
?>

<?php require_once '../includes/header.php'; ?>
<?php require_once '../includes/sidebar.php'; ?>

<div class="container mt-4">
    <h4 class="mb-3">Ajukan Perpanjangan (Manual)</h4>

    <?php if (isset($msg)) : ?>
        <div class="alert alert-info"><?= $msg ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="post">

                <div class="mb-3">
                    <label class="form-label">Barcode / Kode Buku</label>
                    <input type="text" name="kode_buku" class="form-control"
                           placeholder="Scan atau ketik kode buku" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Judul Buku</label>
                    <input type="text" name="judul_manual" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Pengarang</label>
                    <input type="text" name="pengarang_manual" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alasan Perpanjangan</label>
                    <textarea name="keterangan" class="form-control" rows="3"
                        placeholder="Contoh: buku masih digunakan untuk tugas"></textarea>
                </div>

                <button type="submit" name="ajukan" class="btn btn-primary">
                    Ajukan Perpanjangan
                </button>

                <a href="dashboard.php" class="btn btn-secondary">
                    Kembali
                </a>

            </form>

        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

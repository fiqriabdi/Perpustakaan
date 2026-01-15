<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../config/session.php';

cek_role('petugas');

if (isset($_POST['simpan'])) {

    $nim     = mysqli_real_escape_string($conn, $_POST['nim']);
    $id_buku = mysqli_real_escape_string($conn, $_POST['id_buku']);

    // ===============================
    // CARI MAHASISWA
    // ===============================
    $mhs = mysqli_query($conn, "
        SELECT id_mahasiswa 
        FROM mahasiswa 
        WHERE nim='$nim'
    ");

    if (mysqli_num_rows($mhs) == 0) {
        $error = "Mahasiswa tidak ditemukan";
    } else {

        $m = mysqli_fetch_assoc($mhs);
        $id_mahasiswa = $m['id_mahasiswa'];

        // ===============================
        // CEK BUKU & STOK
        // ===============================
        $cekBuku = mysqli_query($conn, "
            SELECT stok 
            FROM buku 
            WHERE id_buku='$id_buku'
        ");

        if (mysqli_num_rows($cekBuku) == 0) {
            $error = "Buku tidak ditemukan";
        } else {

            $buku = mysqli_fetch_assoc($cekBuku);

            if ($buku['stok'] <= 0) {
                $error = "Stok buku habis";
            } else {

                // ===============================
                // INSERT PEMINJAMAN
                // ===============================
                $tgl_pinjam  = date('Y-m-d');
                $jatuh_tempo = date('Y-m-d', strtotime('+14 days'));

                mysqli_query($conn, "
                    INSERT INTO peminjaman
                    (id_mahasiswa, id_buku, tanggal_pinjam, tanggal_jatuh_tempo, status)
                    VALUES
                    ('$id_mahasiswa', '$id_buku', '$tgl_pinjam', '$jatuh_tempo', 'dipinjam')
                ");

                // ===============================
                // UPDATE STOK
                // ===============================
                mysqli_query($conn, "
                    UPDATE buku 
                    SET stok = stok - 1 
                    WHERE id_buku='$id_buku'
                ");

                // ===============================
                // LOG AKTIVITAS
                // ===============================
                $uid = $_SESSION['user_id'];
                mysqli_query($conn, "
                    INSERT INTO log_aktivitas (id_user, aktivitas)
                    VALUES ('$uid', 'Input peminjaman buku ID $id_buku untuk NIM $nim')
                ");

                $success = "Peminjaman berhasil dicatat";
            }
        }
    }
}

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
?>

<div class="container-fluid mt-4">

    <h4 class="mb-3">Tambah Peminjaman</h4>

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

            <form method="post">

                <div class="mb-3">
                    <label class="form-label">NIM Mahasiswa</label>
                    <input type="text"
                           name="nim"
                           class="form-control"
                           placeholder="Masukkan NIM"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Pilih Buku</label>
                    <select name="id_buku" class="form-select" required>
                        <option value="">-- Pilih Buku --</option>
                        <?php
                        $buku = mysqli_query($conn,"
                            SELECT id_buku, judul, stok 
                            FROM buku 
                            WHERE stok > 0
                            ORDER BY judul
                        ");
                        while ($b = mysqli_fetch_assoc($buku)) {
                            echo "<option value='{$b['id_buku']}'>
                                    " . htmlspecialchars($b['judul']) . " (stok: {$b['stok']})
                                  </option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" name="simpan" class="btn btn-primary">
                        Simpan
                    </button>
                    <a href="dashboard.php" class="btn btn-secondary">
                        Kembali
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>

<?php require_once '../includes/footer.php'; ?>

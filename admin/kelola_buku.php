<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../config/session.php';
cek_role('admin');

require_once '../modules/buku/buku_controller.php';
require_once '../modules/buku/buku_model.php';
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

$data = getAllBuku($conn);

$edit = null;
if (isset($_GET['edit'])) {
    $q = getBukuById($conn, $_GET['edit']);
    $edit = mysqli_fetch_assoc($q);
}
?>

<div class="container-fluid px-4">
    <h4 class="mt-4 mb-4">📚 Kelola Data Buku</h4>

    <!-- FORM -->
    <div class="card mb-4 shadow">
        <div class="card-header bg-primary text-white">
            <?= $edit ? '✏️ Edit Buku' : '➕ Tambah Buku' ?>
        </div>
        <div class="card-body">
            <form method="post">
                <input type="hidden" name="id_buku" value="<?= $edit['id_buku'] ?? '' ?>">

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Kode Buku</label>
                        <input type="text" name="kode_buku" class="form-control" required
                            value="<?= $edit['kode_buku'] ?? '' ?>">
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Judul Buku</label>
                        <input type="text" name="judul" class="form-control" required
                            value="<?= $edit['judul'] ?? '' ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Pengarang</label>
                        <input type="text" name="pengarang" class="form-control"
                            value="<?= $edit['pengarang'] ?? '' ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Penerbit</label>
                        <input type="text" name="penerbit" class="form-control"
                            value="<?= $edit['penerbit'] ?? '' ?>">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Tahun</label>
                        <input type="number" name="tahun" class="form-control"
                            value="<?= $edit['tahun'] ?? '' ?>">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" class="form-control" required
                            value="<?= $edit['stok'] ?? '' ?>">
                    </div>
                </div>

                <div class="mt-4">
                    <?php if ($edit): ?>
                        <button type="submit" name="update" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Update
                        </button>
                        <a href="kelola_buku.php" class="btn btn-secondary">Batal</a>
                    <?php else: ?>
                        <button type="submit" name="tambah" class="btn btn-success">
                            <i class="bi bi-plus-circle"></i> Tambah
                        </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- TABEL -->
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            📖 Daftar Buku
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Judul Buku</th>
                        <th>Pengarang</th>
                        <th>Penerbit</th>
                        <th>Tahun</th>
                        <th>Stok</th>
                        <th width="130">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php $no=1; while($b = mysqli_fetch_assoc($data)): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= $b['kode_buku'] ?></td>
                        <td><?= $b['judul'] ?></td>
                        <td><?= $b['pengarang'] ?></td>
                        <td><?= $b['penerbit'] ?></td>
                        <td class="text-center"><?= $b['tahun'] ?></td>
                        <td class="text-center"><?= $b['stok'] ?></td>
                        <td class="text-center">
                            <a href="?edit=<?= $b['id_buku'] ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="?hapus=<?= $b['id_buku'] ?>" 
                               onclick="return confirm('Hapus buku ini?')" 
                               class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php require_once '../includes/footer.php'; ?>

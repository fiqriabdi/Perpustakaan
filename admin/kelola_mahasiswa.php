<?php
// admin/kelola_mahasiswa.php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../config/session.php';

cek_role('admin');

$success = $error = '';

// ===========================
// Handle Delete
if (isset($_GET['hapus'])) {
    $id_user = (int)$_GET['hapus'];

    if (mysqli_query($conn, "DELETE FROM mahasiswa WHERE id_user=$id_user") &&
        mysqli_query($conn, "DELETE FROM users WHERE id_user=$id_user")) {
        $success = "Mahasiswa berhasil dihapus!";
    } else {
        $error = "Gagal menghapus mahasiswa!";
    }
}

// ===========================
// Handle Update
if (isset($_POST['update'])) {
    $id_user = (int)($_POST['id_user'] ?? 0);
    $username = trim($_POST['username'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $nim = trim($_POST['nim'] ?? '');
    $prodi = trim($_POST['prodi'] ?? '');
    $kontak = trim($_POST['kontak'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // cek username & nim unik
    $cek = mysqli_query($conn, "SELECT * FROM users u
        JOIN mahasiswa m ON u.id_user = m.id_user
        WHERE (u.username='$username' OR m.nim='$nim') AND u.id_user<>$id_user");

    if (mysqli_num_rows($cek) > 0) {
        $error = "Username atau NIM sudah digunakan oleh mahasiswa lain!";
    } else {
        $update_user = "UPDATE users SET username='$username', nama='$nama'";
        if (!empty($password)) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $update_user .= ", password='$hashed'";
        }
        $update_user .= " WHERE id_user=$id_user";

        $update_mahasiswa = "UPDATE mahasiswa SET nim='$nim', nama='$nama', prodi='$prodi', kontak='$kontak'
                             WHERE id_user=$id_user";

        if (mysqli_query($conn, $update_user) && mysqli_query($conn, $update_mahasiswa)) {
            $success = "Data mahasiswa berhasil diperbarui!";
        } else {
            $error = "Gagal memperbarui data mahasiswa!";
        }
    }
}

// ===========================
// Ambil data mahasiswa
$query = mysqli_query($conn, "
    SELECT 
        u.id_user,
        u.username,
        m.nim,
        m.nama,
        m.prodi,
        m.kontak
    FROM mahasiswa m
    JOIN users u ON m.id_user = u.id_user
    WHERE u.role = 'mahasiswa'
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Mahasiswa</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background: #eee; }
        a { text-decoration: none; margin: 0 5px; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>

<h2>Kelola Mahasiswa</h2>

<!-- PESAN SUKSES / ERROR -->
<?php 
if (!empty($success)) echo "<p class='success'>$success</p>";
if (!empty($error)) echo "<p class='error'>$error</p>";
?>

<table>
<tr>
    <th>No</th>
    <th>Username</th>
    <th>NIM</th>
    <th>Nama</th>
    <th>Prodi</th>
    <th>Kontak</th>
    <th>Aksi</th>
</tr>

<?php $no = 1; while ($row = mysqli_fetch_assoc($query)) { ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= htmlspecialchars($row['username']) ?></td>
    <td><?= htmlspecialchars($row['nim']) ?></td>
    <td><?= htmlspecialchars($row['nama']) ?></td>
    <td><?= htmlspecialchars($row['prodi']) ?></td>
    <td><?= htmlspecialchars($row['kontak']) ?></td>
    <td>
        <a href="?edit=<?= $row['id_user'] ?>">Edit</a> |
        <a href="?hapus=<?= $row['id_user'] ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
    </td>
</tr>
<?php } ?>
</table>

<?php
// ===========================
// Form edit
if (isset($_GET['edit'])) {
    $id_user = (int)$_GET['edit'];
    $res = mysqli_query($conn, "SELECT u.id_user, u.username, m.nim, m.nama, m.prodi, m.kontak 
                                FROM mahasiswa m 
                                JOIN users u ON m.id_user=u.id_user
                                WHERE u.id_user=$id_user");
    $edit = mysqli_fetch_assoc($res);
?>

<h3>Edit Mahasiswa</h3>
<form method="post">
    <input type="hidden" name="id_user" value="<?= $edit['id_user'] ?>">

    <label>Username:</label><br>
    <input type="text" name="username" value="<?= htmlspecialchars($edit['username']) ?>" required><br><br>

    <label>NIM:</label><br>
    <input type="text" name="nim" value="<?= htmlspecialchars($edit['nim']) ?>" required><br><br>

    <label>Nama:</label><br>
    <input type="text" name="nama" value="<?= htmlspecialchars($edit['nama']) ?>" required><br><br>

    <label>Prodi:</label><br>
    <input type="text" name="prodi" value="<?= htmlspecialchars($edit['prodi']) ?>"><br><br>

    <label>Kontak:</label><br>
    <input type="text" name="kontak" value="<?= htmlspecialchars($edit['kontak']) ?>"><br><br>

    <label>Password Baru:</label><br>
    <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengganti"><br><br>

    <button type="submit" name="update">Update</button>
</form>

<?php } ?>

</body>
</html>

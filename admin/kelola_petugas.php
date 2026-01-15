<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../config/session.php';

cek_role('admin');

$nama = $username = ''; // default value

// =======================
// TAMBAH PETUGAS
// =======================
if (isset($_POST['simpan'])) {
    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Cek username sudah ada
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "Username sudah terdaftar!";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // insert ke users
        mysqli_query($conn, "
            INSERT INTO users (nama, username, password, role, status)
            VALUES ('$nama', '$username', '$hashed', 'petugas', 'aktif')
        ");

        $id_user = mysqli_insert_id($conn);

        // insert ke petugas
        mysqli_query($conn, "
            INSERT INTO petugas (id_user, nama)
            VALUES ($id_user, '$nama')
        ");

        $success = "Petugas '$nama' berhasil ditambahkan!";
        $nama = $username = '';
    }
}

// =======================
// EDIT PETUGAS
// =======================
if (isset($_POST['update'])) {
    $id_user = (int)$_POST['id_user'];
    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);

    // cek username unik
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND id_user<>$id_user");
    if (mysqli_num_rows($cek) > 0) {
        $error = "Username sudah digunakan!";
    } else {
        mysqli_query($conn, "UPDATE users SET nama='$nama', username='$username' WHERE id_user=$id_user");
        mysqli_query($conn, "UPDATE petugas SET nama='$nama' WHERE id_user=$id_user");
        $success = "Data petugas berhasil diperbarui!";
        $nama = $username = '';
    }
}

// =======================
// HAPUS PETUGAS
// =======================
if (isset($_GET['hapus'])) {
    $id_user = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM petugas WHERE id_user=$id_user");
    mysqli_query($conn, "DELETE FROM users WHERE id_user=$id_user");
    $success = "Petugas berhasil dihapus!";
}

// =======================
// AMBIL DATA PETUGAS
// =======================
$data = mysqli_query($conn, "
    SELECT u.id_user, u.nama, u.username
    FROM petugas p
    JOIN users u ON p.id_user = u.id_user
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Petugas</title>
</head>
<body>

<h2>Kelola Petugas</h2>

<!-- PESAN SUKSES / ERROR -->
<?php 
if (isset($success)) echo "<p style='color:green;'>$success</p>";
if (isset($error)) echo "<p style='color:red;'>$error</p>";
?>

<!-- FORM TAMBAH PETUGAS -->
<form method="post">
    <input type="text" name="nama" placeholder="Nama Lengkap" value="<?= htmlspecialchars($nama) ?>" required><br><br>
    <input type="text" name="username" placeholder="Username Petugas" value="<?= htmlspecialchars($username) ?>" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit" name="simpan">Simpan</button>
</form>

<hr>

<!-- TABEL PETUGAS -->
<table border="1" cellpadding="5">
<tr>
    <th>No</th>
    <th>Nama Lengkap</th>
    <th>Username</th>
    <th>Aksi</th>
</tr>

<?php
$no = 1;
while ($row = mysqli_fetch_assoc($data)) {
?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= htmlspecialchars($row['nama']) ?></td>
    <td><?= htmlspecialchars($row['username']) ?></td>
    <td>
        <a href="?edit=<?= $row['id_user'] ?>">Edit</a> | 
        <a href="?hapus=<?= $row['id_user'] ?>" onclick="return confirm('Yakin ingin menghapus petugas?')">Hapus</a>
    </td>
</tr>
<?php } ?>
</table>

<?php
// =======================
// FORM EDIT PETUGAS
// =======================
if (isset($_GET['edit'])) {
    $id_user = (int)$_GET['edit'];
    $res = mysqli_query($conn, "SELECT * FROM users WHERE id_user=$id_user");
    $edit = mysqli_fetch_assoc($res);
?>
<hr>
<h3>Edit Petugas</h3>
<form method="post">
    <input type="hidden" name="id_user" value="<?= $edit['id_user'] ?>">
    <input type="text" name="nama" placeholder="Nama Lengkap" value="<?= htmlspecialchars($edit['nama']) ?>" required><br><br>
    <input type="text" name="username" placeholder="Username Petugas" value="<?= htmlspecialchars($edit['username']) ?>" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit" name="update">Update</button>
</form>
<?php } ?>

</body>
</html>

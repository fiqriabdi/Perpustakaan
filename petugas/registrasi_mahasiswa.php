<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../config/session.php';

cek_role('petugas');

// default value untuk form
$nim = '';
$nama = '';
$kontak = '';
$username = '';
$prodi = '';
$info = '';

if (isset($_POST['daftar'])) {
    $nim = trim($_POST['nim'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $kontak = trim($_POST['kontak'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $prodi = trim($_POST['prodi'] ?? '');

    // password default: NIM
    $password_plain = $nim;
    $password_hash = password_hash($password_plain, PASSWORD_DEFAULT);

    // cek apakah NIM atau username sudah ada
    $cek = mysqli_query($conn, "SELECT * FROM mahasiswa m
        JOIN users u ON m.id_user = u.id_user
        WHERE m.nim='$nim' OR u.username='$username'");
    
    if (mysqli_num_rows($cek) > 0) {
        $error = "NIM atau Username sudah terdaftar!";
    } else {
        // Insert ke users
        mysqli_query($conn,"INSERT INTO users (nama, username, password, role, status) VALUES (
            '$nama', '$username', '$password_hash', 'mahasiswa', 'aktif'
        )");
    
        $uid = mysqli_insert_id($conn);
    
        // Insert ke mahasiswa
        mysqli_query($conn,"INSERT INTO mahasiswa (nim, nama, prodi, kontak, id_user) VALUES (
            '$nim', '$nama', '$prodi', '$kontak', '$uid'
        )");
    
        $success = "Akun mahasiswa berhasil dibuat!";
        $info = "Username: <b>$username</b> | Password default: <b>$password_plain</b>";
        // reset form
        $nim = $nama = $kontak = $username = $prodi = '';
    }
}
?>

<h2>Registrasi Mahasiswa</h2>

<?php 
if (isset($success)) echo "<p style='color:green;'>$success</p>";
if (isset($error)) echo "<p style='color:red;'>$error</p>";
if ($info) echo "<p style='color:blue;'>$info</p>";
?>

<form method="post">
  <label>NIM:</label><br>
  <input type="text" name="nim" value="<?= htmlspecialchars($nim) ?>" required><br><br>

  <label>Username:</label><br>
  <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" required><br><br>

  <label>Nama Lengkap:</label><br>
  <input type="text" name="nama" value="<?= htmlspecialchars($nama) ?>" required><br><br>

  <label>Prodi:</label><br>
  <input type="text" name="prodi" value="<?= htmlspecialchars($prodi) ?>"><br><br>

  <label>Kontak:</label><br>
  <input type="text" name="kontak" value="<?= htmlspecialchars($kontak) ?>" required><br><br>

  <button name="daftar">Daftar</button>
</form>

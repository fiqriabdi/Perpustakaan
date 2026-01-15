<?php
// mahasiswa/ganti_password.php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../config/session.php';
cek_role('mahasiswa');

if (isset($_POST['simpan'])) {

    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $id = $_SESSION['user_id'];

    mysqli_query($conn,"UPDATE users SET password='$password' WHERE id='$id'");
    $success = "Password berhasil diubah";
}
?>

<h2>Ganti Password</h2>

<?= isset($success)?$success:'' ?>

<form method="post">
  <input type="password" name="password" placeholder="Password Baru" required>
  <button name="simpan">Simpan</button>
</form>

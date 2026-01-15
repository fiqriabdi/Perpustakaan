<?php
// auth/register.php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../config/session.php';

cek_role('petugas');

if (isset($_POST['register'])) {

    $nim      = $_POST['nim'];
    $kontak   = $_POST['kontak'];
    $username = strtolower($nim);
    $password = password_hash($nim, PASSWORD_DEFAULT);

    // simpan user
    mysqli_query($conn, "INSERT INTO users VALUES(
        NULL,'$username','$password','mahasiswa',1
    )");

    $user_id = mysqli_insert_id($conn);

    // simpan data mahasiswa
    mysqli_query($conn, "INSERT INTO mahasiswa VALUES(
        NULL,'$user_id','$nim','$kontak'
    )");

    $success = "Akun berhasil dibuat. Username & password = NIM";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrasi Mahasiswa</title>
</head>
<body>
<h2>Registrasi Akun Mahasiswa</h2>

<?php if(isset($success)) echo "<p style='color:green'>$success</p>"; ?>

<form method="post">
    <input type="text" name="nim" placeholder="NIM" required><br><br>
    <input type="text" name="kontak" placeholder="Kontak (Email/HP)" required><br><br>
    <button type="submit" name="register">Daftarkan</button>
</form>
</body>
</html>

<?php
// auth/reset_password.php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../config/session.php';

if (isset($_POST['reset'])) {

    $password_baru = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $id = $_SESSION['user_id'];

    mysqli_query($conn,
        "UPDATE users SET password='$password_baru' WHERE id='$id'"
    );

    $success = "Password berhasil diperbarui";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ganti Password</title>
</head>
<body>
<h2>Ganti Password</h2>

<?php if(isset($success)) echo "<p style='color:green'>$success</p>"; ?>

<form method="post">
    <input type="password" name="password" placeholder="Password Baru" required><br><br>
    <button type="submit" name="reset">Simpan</button>
</form>
</body>
</html>

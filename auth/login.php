<?php
require_once '../config/config.php';
require_once '../config/database.php';
session_start();

if (isset($_POST['login'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "
        SELECT * FROM users
        
        WHERE username='$username'
        AND status='aktif'
        LIMIT 1
    ");

    if (mysqli_num_rows($query) === 1) {
        $user = mysqli_fetch_assoc($query);

        $dbPassword = $user['password'];
        $loginValid = false;

        // CEK FORMAT PASSWORD (MD5 / password_hash)
        if (strlen($dbPassword) === 32) {
            if (md5($password) === $dbPassword) {
                $loginValid = true;

                // Upgrade otomatis ke password_hash
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                mysqli_query($conn, "
                    UPDATE users SET password='$newHash'
                    WHERE id_user={$user['id_user']}
                ");
            }
        } else {
            if (password_verify($password, $dbPassword)) {
                $loginValid = true;
            }
        }

        if ($loginValid) {
            $_SESSION['login']    = true;
            $_SESSION['user_id']  = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];
            

            if ($user['role'] === 'admin') {
                header("Location: ../admin/dashboard.php");
            } elseif ($user['role'] === 'petugas') {
                header("Location: ../petugas/dashboard.php");
            } else {
                header("Location: ../mahasiswa/dashboard.php");
            }
            exit;
        }
    }

    $error = "Username atau password salah!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | <?= APP_NAME ?></title>

    <!-- BOOTSTRAP 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #0d6efd, #0a2246);
            min-height: 100vh;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">

<div class="card shadow-lg" style="max-width: 420px; width:100%;">
    <div class="card-body p-4">

        <h4 class="text-center fw-bold mb-3">
            Login Perpustakaan
        </h4>

        <!-- INFORMASI LOGIN -->
        <div class="alert alert-primary small">
            <strong>Informasi Login:</strong>
            <br>
            • Masukkan <b>username</b> dan <b>kata sandi</b> yang diberikan oleh Administrator Perpustakaan.
            <br>
            • Mahasiswa UTDI yang belum memiliki akun, silakan menghubungi <b>staf perpustakaan</b>.
        </div>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger text-center">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" name="login" class="btn btn-primary w-100">
                Login
            </button>
        </form>

        <div class="text-center mt-3 small text-muted">
            © <?= date('Y') ?> <?= APP_NAME ?>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

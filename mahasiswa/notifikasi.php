<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../config/session.php';

cek_role('mahasiswa');

$uid = $_SESSION['user_id'];

$data = mysqli_query($conn,"
    SELECT pesan, status, created_at
    FROM notifikasi
    WHERE id_user='$uid'
    ORDER BY created_at DESC
");

// tandai dibaca
mysqli_query($conn,"
    UPDATE notifikasi SET status='dibaca'
    WHERE id_user='$uid'
");
?>

<h2>Notifikasi Perpanjangan</h2>

<?php if (mysqli_num_rows($data) == 0) { ?>
    <div style="
        padding:15px;
        background:#f8f9fa;
        border-radius:8px;
        color:#555;
    ">
        📭 Belum ada notifikasi
    </div>
<?php } else { ?>

<div style="margin-top:15px;">
    <?php while($r = mysqli_fetch_assoc($data)) { ?>
        <div style="
            background:#ffffff;
            border-left:5px solid <?= ($r['status']=='dibaca') ? '#2ecc71' : '#e74c3c' ?>;
            padding:15px;
            margin-bottom:10px;
            border-radius:6px;
            box-shadow:0 2px 5px rgba(0,0,0,0.05);
        ">
            <p style="margin:0 0 5px 0;">
                <?= $r['pesan'] ?>
            </p>
            <small style="color:#777;">
                🕒 <?= $r['created_at'] ?>
            </small>
        </div>
    <?php } ?>
</div>

<?php 
} 
?>

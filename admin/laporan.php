<?php
// laporan.php - Laporan Peminjaman Buku
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../config/session.php';

cek_role('admin');

$data = mysqli_query($conn,"
    SELECT 
        m.nim,
        b.judul,
        p.status
    FROM peminjaman p
    JOIN mahasiswa m ON p.id_mahasiswa = m.id_mahasiswa
    JOIN buku b ON p.id_buku = b.id_buku
");
?>

<!DOCTYPE html>
<html>
<head>
<head>
    <title>Laporan Peminjaman</title>
</head>
<body>

<h2>Laporan Peminjaman Buku</h2>

<table border="1" cellpadding="5">
<tr>
    <th>No</th>
    <th>NIM</th>
    <th>Judul Buku</th>
    <th>Status</th>
</tr>

<?php
$no = 1;
while ($row = mysqli_fetch_assoc($data)) {
?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= $row['nim'] ?></td>
    <td><?= $row['judul'] ?></td>
    <td><?= $row['status'] ?></td>
</tr>
<?php } ?>

</table>

</body>
</html>

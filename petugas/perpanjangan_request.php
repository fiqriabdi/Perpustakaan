<?php
// petugas/perpanjangan_request.php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../config/session.php';
cek_role('petugas');

$data = mysqli_query($conn,"
  SELECT p.id, m.nim, p.status
  FROM perpanjangan p
  JOIN peminjaman pj ON p.peminjaman_id = pj.id
  JOIN mahasiswa m ON pj.mahasiswa_id = m.id
");
?>

<h2>Permintaan Perpanjangan</h2>

<table border="1">
<tr><th>NIM</th><th>Status</th></tr>
<?php while($r=mysqli_fetch_assoc($data)){ ?>
<tr>
  <td><?= $r['nim'] ?></td>
  <td><?= $r['status'] ?></td>
</tr>
<?php } ?>
</table>

<?php
// modules/buku/buku_controller.php
require_once 'buku_model.php';

if (isset($_POST['tambah'])) {
    tambahBuku($conn, $_POST);
    header("Location: kelola_buku.php");
}

if (isset($_POST['update'])) {
    updateBuku($conn, $_POST['id_buku'], $_POST);
    header("Location: kelola_buku.php");
}

if (isset($_GET['hapus'])) {
    hapusBuku($conn, $_GET['hapus']);
    header("Location: kelola_buku.php");
}

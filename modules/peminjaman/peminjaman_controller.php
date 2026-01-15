<?php
// modules/peminjaman/peminjaman_controller.php

require_once 'peminjaman_model.php';

function prosesPeminjaman($conn, $mahasiswa_id, $buku_id) {
    return tambahPeminjaman($conn, $mahasiswa_id, $buku_id);
}

function prosesPengembalian($conn, $peminjaman_id) {
    return pengembalianBuku($conn, $peminjaman_id);
}

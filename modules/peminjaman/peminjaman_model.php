<?php
// modules/peminjaman/peminjaman_model.php

function tambahPeminjaman($conn, $mahasiswa_id, $buku_id) {
    return mysqli_query($conn,"
        INSERT INTO peminjaman
        VALUES(NULL,'$mahasiswa_id','$buku_id',NOW(),'dipinjam')
    ");
}

function getPeminjamanAktif($conn) {
    return mysqli_query($conn,
        "SELECT * FROM peminjaman WHERE status='dipinjam'"
    );
}

function pengembalianBuku($conn, $id) {
    return mysqli_query($conn,
        "UPDATE peminjaman SET status='kembali' WHERE id='$id'"
    );
}

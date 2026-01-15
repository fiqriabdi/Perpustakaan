<?php
// modules/buku/buku_model.php

function getAllBuku($conn) {
    return mysqli_query($conn, "SELECT * FROM buku ORDER BY id_buku DESC");
}

function getBukuById($conn, $id) {
    return mysqli_query($conn, "SELECT * FROM buku WHERE id_buku='$id'");
}

function tambahBuku($conn, $data) {
    return mysqli_query($conn, "
        INSERT INTO buku (kode_buku, judul, pengarang, penerbit, tahun, stok)
        VALUES (
            '$data[kode_buku]',
            '$data[judul]',
            '$data[pengarang]',
            '$data[penerbit]',
            '$data[tahun]',
            '$data[stok]'
        )
    ");
}

function updateBuku($conn, $id, $data) {
    return mysqli_query($conn, "
        UPDATE buku SET
            kode_buku='$data[kode_buku]',
            judul='$data[judul]',
            pengarang='$data[pengarang]',
            penerbit='$data[penerbit]',
            tahun='$data[tahun]',
            stok='$data[stok]'
        WHERE id_buku='$id'
    ");
}

function hapusBuku($conn, $id) {
    return mysqli_query($conn, "DELETE FROM buku WHERE id_buku='$id'");
}

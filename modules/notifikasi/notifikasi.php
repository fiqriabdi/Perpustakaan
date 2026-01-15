<?php
// modules/notifikasi/notifikasi.php

function kirimNotifikasi($conn, $user_id, $pesan) {
    return mysqli_query($conn,"
        INSERT INTO notifikasi
        VALUES(NULL,'$user_id','$pesan','belum',NOW())
    ");
}

function getNotifikasiUser($conn, $user_id) {
    return mysqli_query($conn,"
        SELECT * FROM notifikasi
        WHERE user_id='$user_id'
        ORDER BY id DESC
    ");
}

function bacaNotifikasi($conn, $id) {
    return mysqli_query($conn,"
        UPDATE notifikasi SET status='dibaca' WHERE id='$id'
    ");
}

<?php
// Isi konfigurasi database di server lokal Anda.
// File ini sengaja dikosongkan agar kredensial tidak tersimpan di GitHub.

$DB_HOST = '';
$DB_USER = '';
$DB_PASS = '';
$DB_NAME = '';

$koneksi = null;

if ($DB_HOST !== '' && $DB_USER !== '' && $DB_NAME !== '') {
    $koneksi = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    if (!$koneksi) {
        die("Database gagal konek");
    }
}
?>
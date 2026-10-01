<?php
// Konfigurasi database lokal/server.
// Jangan simpan password database asli di repository publik.
$DB_HOST = getenv('HOTLINE_DB_HOST') ?: 'localhost';
$DB_USER = getenv('HOTLINE_DB_USER') ?: 'root';
$DB_PASS = getenv('HOTLINE_DB_PASS') ?: '';
$DB_NAME = getenv('HOTLINE_DB_NAME') ?: 'sik2';

$koneksi = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if (!$koneksi) {
    die("Database gagal konek");
}
?>
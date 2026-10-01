<?php
/*
 * KONFIGURASI SERVER WAOCA
 * Sengaja dikosongkan sebelum masuk GitHub.
 */

$serverSIMRS   = '';
$userSIMRS     = '';
$passSIMRS     = '';
$databaseSIMRS = '';

$serverSIMIFM   = '';
$userSIMIFM     = '';
$passSIMIFM     = '';
$databaseSIMIFM = '';

$connSIMRS = null;
$connSIMIFM = null;
$conn = null;

// Isi nilai di server lokal/production.
// Jangan menyimpan password database di repository.
if ($serverSIMRS !== '' && $userSIMRS !== '' && $databaseSIMRS !== '') {
    $connSIMRS = mysqli_connect($serverSIMRS, $userSIMRS, $passSIMRS, $databaseSIMRS);
    if (!$connSIMRS) die('Koneksi ke Database SIMRS gagal');
    mysqli_set_charset($connSIMRS, 'utf8');
}

if ($serverSIMIFM !== '' && $userSIMIFM !== '' && $databaseSIMIFM !== '') {
    $connSIMIFM = mysqli_connect($serverSIMIFM, $userSIMIFM, $passSIMIFM, $databaseSIMIFM);
    if (!$connSIMIFM) die('Koneksi ke Database SIMIFM gagal');
    mysqli_set_charset($connSIMIFM, 'utf8');
    $conn = $connSIMIFM;
}
?>
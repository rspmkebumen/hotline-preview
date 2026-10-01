<?php
include "config.php";
if (!$koneksi) {
    die("Koneksi gagal : ".mysqli_connect_error());
}
echo "Koneksi database SIK2 berhasil";
?>
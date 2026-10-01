<?php
include "../config.php";
header('Content-Type: application/json');

$hari=[1=>"Senin",2=>"Selasa",3=>"Rabu",4=>"Kamis",5=>"Jumat",6=>"Sabtu",7=>"Minggu"];
$hari_ini=$hari[date('N')];

$query=mysqli_query($koneksi,"
SELECT d.id,d.nama_dokter,d.spesialis,d.foto,
       j.hari,j.jam_mulai,j.jam_selesai
FROM hotline_dokter d
JOIN hotline_jadwal_dokter j ON d.id=j.dokter_id
WHERE d.status='aktif' AND j.status='aktif' AND j.hari='$hari_ini'
ORDER BY d.spesialis,d.nama_dokter
");

$data=[];
while($row=mysqli_fetch_assoc($query)){
    $data[]=[
        "nama_dokter"=>$row['nama_dokter'],
        "spesialis"=>$row['spesialis'],
        "foto_url"=>"foto/dokter/".$row['foto'],
        "hari"=>$row['hari'],
        "jam"=>date("H:i",strtotime($row['jam_mulai']))." - ".date("H:i",strtotime($row['jam_selesai']))." WIB"
    ];
}
echo json_encode(["hari_ini"=>$hari_ini,"jumlah"=>count($data),"data"=>$data],JSON_PRETTY_PRINT);
?>
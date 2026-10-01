<?php
include "../config.php";
header('Content-Type: application/json');

$query = mysqli_query($koneksi,"
SELECT d.id,d.nama_dokter,d.spesialis,d.foto,
       j.hari,j.jam_mulai,j.jam_selesai
FROM hotline_dokter d
JOIN hotline_jadwal_dokter j ON d.id=j.dokter_id
WHERE d.status='aktif' AND j.status='aktif'
ORDER BY d.nama_dokter,j.id
");

$data=[];
while($row=mysqli_fetch_assoc($query)){
    $id=$row['id'];
    if(!isset($data[$id])){
        $data[$id]=[
            "nama_dokter"=>$row['nama_dokter'],
            "spesialis"=>$row['spesialis'],
            "foto_url"=>"foto/dokter/".$row['foto'],
            "jadwal"=>[]
        ];
    }
    $data[$id]["jadwal"][]=[
        "hari"=>$row['hari'],
        "jam"=>date("H:i",strtotime($row['jam_mulai']))." - ".date("H:i",strtotime($row['jam_selesai']))." WIB"
    ];
}
echo json_encode(array_values($data));
?>
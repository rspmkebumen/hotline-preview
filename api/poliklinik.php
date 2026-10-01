<?php
include "../config.php";
header('Content-Type: application/json');

$query=mysqli_query($koneksi,"
SELECT p.id,p.nama_poli,p.foto,
       j.nama_dokter,j.hari,j.jam_mulai,j.jam_selesai
FROM hotline_poli p
LEFT JOIN hotline_jadwal_poli j ON p.id=j.poli_id
WHERE p.status='aktif' AND (j.status='aktif' OR j.status IS NULL)
ORDER BY p.id,j.id
");

$data=[];
while($row=mysqli_fetch_assoc($query)){
    $id=$row['id'];
    if(!isset($data[$id])){
        $data[$id]=["nama_poli"=>$row['nama_poli'],"foto"=>"foto/poli/".$row['foto'],"jadwal"=>[]];
    }
    if($row['nama_dokter']){
        $data[$id]['jadwal'][]=[
            "dokter"=>$row['nama_dokter'],
            "hari"=>$row['hari'],
            "jam"=>date("H:i",strtotime($row['jam_mulai']))." - ".date("H:i",strtotime($row['jam_selesai']))." WIB"
        ];
    }
}
echo json_encode(array_values($data));
?>
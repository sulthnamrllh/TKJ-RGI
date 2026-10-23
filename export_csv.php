<?php
require 'koneksi.php';
$kamarDipilih = trim($_GET['kamar'] ?? '');
$sql = 'SELECT id, nama, angkatan, jenis_kelamin, tanggal_lahir, asal_daerah, kamar, no_hp_wali FROM santri';
$params = [];
if ($kamarDipilih !== '') { $sql .= ' WHERE kamar = :kamar'; $params[':kamar'] = $kamarDipilih; }
$sql .= ' ORDER BY id DESC';
$stmt = $pdo->prepare($sql); $stmt->execute($params);
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="daftar-santri.csv"');
header('Pragma: no-cache'); header('Expires: 0');
$out = fopen('php://output','w'); fwrite($out, "\xEF\xBB\xBF");
fputcsv($out, ['ID','Nama','Angkatan','Jenis Kelamin','Tanggal Lahir','Asal Daerah','Kamar','No. HP Wali']);
while ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
  fputcsv($out, [$row['id'],$row['nama'],$row['angkatan'],$row['jenis_kelamin']==='L'?'Laki-laki':($row['jenis_kelamin']==='P'?'Perempuan':''),$row['tanggal_lahir'],$row['asal_daerah'],$row['kamar'],$row['no_hp_wali']]);
}
fclose($out); exit;

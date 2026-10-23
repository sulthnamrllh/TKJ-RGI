<?php
require 'koneksi.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$stmt = $pdo->prepare('SELECT * FROM santri WHERE id = ?');
$stmt->execute([$id]);
$data = $stmt->fetch();
if (!$data) {
  $_SESSION['pesan'] = 'Data santri tidak ditemukan.';
  $_SESSION['tipe'] = 'danger';
  header('Location: index.php');
  exit;
}
$errors = [];
$kolomForm = ['nama', 'angkatan', 'jenis_kelamin', 'tanggal_lahir', 'asal_daerah', 'kamar', 'no_hp_wali'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  foreach ($kolomForm as $kolom)
    $data[$kolom] = trim($_POST[$kolom] ?? '');
  if ($data['nama'] === '')
    $errors[] = 'Nama wajib diisi.';
  if (!in_array($data['jenis_kelamin'], ['L', 'P'], true))
    $errors[] = 'Jenis kelamin wajib dipilih.';
  if ($data['angkatan'] !== '' && filter_var($data['angkatan'], FILTER_VALIDATE_INT) === false)
    $errors[] = 'Angkatan harus berupa bilangan bulat.';
  if (!$errors) {
    $stmt = $pdo->prepare('UPDATE santri SET nama=:nama, angkatan=:angkatan, jenis_kelamin=:jk, tanggal_lahir=:tgl, asal_daerah=:asal, kamar=:kamar, no_hp_wali=:hp WHERE id=:id');
    $stmt->execute([':nama' => $data['nama'], ':angkatan' => $data['angkatan'] !== '' ? (int) $data['angkatan'] : null, ':jk' => $data['jenis_kelamin'], ':tgl' => $data['tanggal_lahir'] ?: null, ':asal' => $data['asal_daerah'], ':kamar' => $data['kamar'], ':hp' => $data['no_hp_wali'], ':id' => $id]);
    $_SESSION['pesan'] = 'Data santri berhasil diperbarui.';
    $_SESSION['tipe'] = 'success';
    header('Location: index.php');
    exit;
  }
}
$mode = 'edit';
include 'header.php';
include 'form.php';
include 'footer.php';

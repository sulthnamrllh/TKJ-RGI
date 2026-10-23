<?php
require 'koneksi.php';
$errors = [];
$data = ['nama' => '', 'angkatan' => '', 'jenis_kelamin' => '', 'tanggal_lahir' => '', 'asal_daerah' => '', 'kamar' => '', 'no_hp_wali' => ''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  foreach ($data as $kolom => $nilai)
    $data[$kolom] = trim($_POST[$kolom] ?? '');
  if ($data['nama'] === '')
    $errors[] = 'Nama wajib diisi.';
  if (!in_array($data['jenis_kelamin'], ['L', 'P'], true))
    $errors[] = 'Jenis kelamin wajib dipilih.';
  if ($data['angkatan'] !== '' && filter_var($data['angkatan'], FILTER_VALIDATE_INT) === false)
    $errors[] = 'Angkatan harus berupa bilangan bulat.';
  if (!$errors) {
    $stmt = $pdo->prepare('INSERT INTO santri (nama, angkatan, jenis_kelamin, tanggal_lahir, asal_daerah, kamar, no_hp_wali) VALUES (:nama, :angkatan, :jk, :tgl, :asal, :kamar, :hp)');
    $stmt->execute([':nama' => $data['nama'], ':angkatan' => $data['angkatan'] !== '' ? (int) $data['angkatan'] : null, ':jk' => $data['jenis_kelamin'], ':tgl' => $data['tanggal_lahir'] ?: null, ':asal' => $data['asal_daerah'], ':kamar' => $data['kamar'], ':hp' => $data['no_hp_wali']]);
    $_SESSION['pesan'] = 'Data santri berhasil ditambahkan.';
    $_SESSION['tipe'] = 'success';
    header('Location: index.php');
    exit;
  }
}
$mode = 'tambah';
include 'header.php';
include 'form.php';
include 'footer.php';

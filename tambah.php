<?php
require 'koneksi.php';

$errors = [];
$data = [
  'nama' => '',
  'angkatan' => '',
  'jenis_kelamin' => '',
  'tanggal_lahir' => '',
  'asal_daerah' => '',
  'kamar' => '',
  'no_hp_wali' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  foreach ($data as $kolom => $nilai) {
    $data[$kolom] = trim($_POST[$kolom] ?? '');
  }

  if ($data['nama'] === '') {
    $errors[] = 'Nama wajib diisi.';
  }
  if (!in_array($data['jenis_kelamin'], ['L', 'P'])) {
    $errors[] = 'Jenis kelamin wajib dipilih.';
  }

  if (count($errors) === 0) {
    $sql = 'INSERT INTO santri
                  (nama, angkatan, jenis_kelamin, tanggal_lahir, asal_daerah, kamar, no_hp_wali)
                VALUES
                  (:nama, :angkatan, :jk, :tgl, :asal, :kamar, :hp)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':nama' => $data['nama'],
      ':angkatan' => $data['angkatan'] !== '' ? (int) $data['angkatan'] : null,
      ':jk' => $data['jenis_kelamin'],
      ':tgl' => $data['tanggal_lahir'] ?: null,
      ':asal' => $data['asal_daerah'],
      ':kamar' => $data['kamar'],
      ':hp' => $data['no_hp_wali'],
    ]);

    $_SESSION['pesan'] = 'Data santri berhasil ditambahkan.';
    header('Location: index.php');
    exit;
  }
}

include 'header.php';
?>

<h4 class="mb-3">Tambah Santri</h4>

<?php if ($errors): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach ($errors as $pesan): ?>
        <li><?= e($pesan) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="post" class="card card-body shadow-sm">
  <div class="mb-3">
    <label class="form-label">Nama Lengkap</label>
    <input type="text" name="nama" class="form-control" value="<?= e($data['nama']) ?>" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Angkatan</label>
    <input type="number" name="angkatan" class="form-control" min="1900" max="2100" value="<?= e($data['angkatan']) ?>" placeholder="Contoh: 2026">
  </div>

  <div class="mb-3">
    <label class="form-label">Jenis Kelamin</label>
    <select name="jenis_kelamin" class="form-select" required>
      <option value="">-- Pilih --</option>
      <option value="L" <?= $data['jenis_kelamin'] === 'L' ? 'selected' : '' ?>>Laki-laki</option>
      <option value="P" <?= $data['jenis_kelamin'] === 'P' ? 'selected' : '' ?>>Perempuan</option>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Tanggal Lahir</label>
    <input type="date" name="tanggal_lahir" class="form-control" value="<?= e($data['tanggal_lahir']) ?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Asal Daerah</label>
    <input type="text" name="asal_daerah" class="form-control" value="<?= e($data['asal_daerah']) ?>">
  </div>

  <div class="row">
    <div class="col-md-6 mb-3">
      <label class="form-label">Kamar</label>
      <input type="text" name="kamar" class="form-control" value="<?= e($data['kamar']) ?>">
    </div>
    <div class="col-md-6 mb-3">
      <label class="form-label">No. HP Wali</label>
      <input type="text" name="no_hp_wali" class="form-control" value="<?= e($data['no_hp_wali']) ?>">
    </div>
  </div>

  <div>
    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="index.php" class="btn btn-secondary">Batal</a>
  </div>
</form>

<?php include 'footer.php'; ?>
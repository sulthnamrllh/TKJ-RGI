<?php // Partial form bersama untuk tambah.php dan edit.php. ?>
<h4 class="mb-3"><?= $mode === 'edit' ? 'Edit Santri' : 'Tambah Santri' ?></h4>
<?php if (!empty($errors)): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach ($errors as $pesan): ?>
        <li><?= e($pesan) ?></li><?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>
<form method="post" class="card card-body shadow-sm">
  <div class="mb-3"><label for="nama" class="form-label">Nama Lengkap</label>
    <input id="nama" type="text" name="nama" class="form-control" value="<?= e($data['nama'] ?? '') ?>" required>
  </div>
  <div class="mb-3"><label for="angkatan" class="form-label">Angkatan</label>
    <input id="angkatan" type="number" name="angkatan" class="form-control" min="1900" max="2100"
      value="<?= e($data['angkatan'] ?? '') ?>" placeholder="Contoh: 2026">
  </div>
  <div class="mb-3"><label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
    <select id="jenis_kelamin" name="jenis_kelamin" class="form-select" required>
      <option value="">-- Pilih --</option>
      <option value="L" <?= ($data['jenis_kelamin'] ?? '') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
      <option value="P" <?= ($data['jenis_kelamin'] ?? '') === 'P' ? 'selected' : '' ?>>Perempuan</option>
    </select>
  </div>
  <div class="mb-3"><label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
    <input id="tanggal_lahir" type="date" name="tanggal_lahir" class="form-control"
      value="<?= e($data['tanggal_lahir'] ?? '') ?>">
  </div>
  <div class="mb-3"><label for="asal_daerah" class="form-label">Asal Daerah</label>
    <input id="asal_daerah" type="text" name="asal_daerah" class="form-control"
      value="<?= e($data['asal_daerah'] ?? '') ?>">
  </div>
  <div class="row">
    <div class="col-md-6 mb-3"><label for="kamar" class="form-label">Kamar</label>
      <input id="kamar" type="text" name="kamar" class="form-control" value="<?= e($data['kamar'] ?? '') ?>">
    </div>
    <div class="col-md-6 mb-3"><label for="no_hp_wali" class="form-label">No. HP Wali</label>
      <input id="no_hp_wali" type="text" name="no_hp_wali" class="form-control"
        value="<?= e($data['no_hp_wali'] ?? '') ?>">
    </div>
  </div>
  <div><button type="submit" class="btn btn-success"><?= $mode === 'edit' ? 'Simpan Perubahan' : 'Simpan' ?></button>
    <a href="index.php" class="btn btn-secondary">Batal</a>
  </div>
</form>
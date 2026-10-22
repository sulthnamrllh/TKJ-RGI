<?php
require 'koneksi.php';

$stmt = $pdo->query('SELECT * FROM santri ORDER BY id DESC');
$daftar = $stmt->fetchAll();

include 'header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Daftar Santri</h4>
  <a href="tambah.php" class="btn btn-success">+ Tambah Santri</a>
</div>

<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table table-striped table-hover mb-0">
      <thead class="table-success">
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Angkatan</th>
          <th>Laki-laki/Perempuan</th>
          <th>Tanggal Lahir</th>
          <th>Asal Daerah</th>
          <th>Kamar</th>
          <th>No. HP Wali</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($daftar) === 0): ?>
          <tr>
            <td colspan="9" class="text-center text-muted">Belum ada data santri.</td>
          </tr>
        <?php endif; ?>

        <?php foreach ($daftar as $no => $s): ?>
          <tr>
            <td><?= $no + 1 ?></td>
            <td><?= e($s['nama']) ?></td>
            <td><?= e($s['angkatan']) ?></td>
            <td><?php if ($s['jenis_kelamin'] == 'L') {
              echo 'Laki-laki';
            } else {
              echo 'Perempuan';
            } ?></td>
            <td><?= e($s['tanggal_lahir']) ?></td>
            <td><?= e($s['asal_daerah']) ?></td>
            <td><?= e($s['kamar']) ?></td>
            <td><?= e($s['no_hp_wali']) ?></td>
            <td>
              <a href="edit.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-warning">Edit</a>


              <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalHapus"
                data-id="<?= $s['id'] ?>" data-nama="<?= e($s['nama']) ?>">Hapus</button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<div class="modal fade" id="modalHapus" tabindex="-1">
  <div class="modal-dialog">
    <form method="post" action="hapus.php" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Yakin ingin menghapus data <strong id="namaHapus"></strong>?
        <input type="hidden" name="id" id="idHapus">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
      </div>
    </form>
  </div>
</div>

<script>
  document.getElementById('modalHapus').addEventListener('show.bs.modal', function (event) {
    var tombol = event.relatedTarget;
    document.getElementById('idHapus').value = tombol.getAttribute('data-id');
    document.getElementById('namaHapus').textContent = tombol.getAttribute('data-nama');
  });
</script>

<?php include 'footer.php'; ?>
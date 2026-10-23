<?php
require 'koneksi.php';
$perHalaman = 10;
$halaman = filter_input(INPUT_GET, 'halaman', FILTER_VALIDATE_INT);
$halaman = ($halaman && $halaman > 0) ? $halaman : 1;
$kamarDipilih = trim($_GET['kamar'] ?? '');
$stmtKamar = $pdo->query("SELECT DISTINCT kamar FROM santri WHERE kamar IS NOT NULL AND TRIM(kamar) <> '' ORDER BY kamar");
$daftarKamar = $stmtKamar->fetchAll(PDO::FETCH_COLUMN);
$where = '';
$params = [];
if ($kamarDipilih !== '') {
  $where = ' WHERE kamar = :kamar';
  $params[':kamar'] = $kamarDipilih;
}
$stmtJumlah = $pdo->prepare('SELECT COUNT(*) FROM santri' . $where);
$stmtJumlah->execute($params);
$totalData = (int) $stmtJumlah->fetchColumn();
$totalHalaman = max(1, (int) ceil($totalData / $perHalaman));
if ($halaman > $totalHalaman)
  $halaman = $totalHalaman;
$offset = ($halaman - 1) * $perHalaman;
$stmt = $pdo->prepare('SELECT * FROM santri' . $where . ' ORDER BY id DESC LIMIT :limit OFFSET :offset');
foreach ($params as $key => $value)
  $stmt->bindValue($key, $value, PDO::PARAM_STR);
$stmt->bindValue(':limit', $perHalaman, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$daftar = $stmt->fetchAll();
include 'header.php';
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
  <h4 class="mb-0">Daftar Santri</h4>
  <div class="d-flex gap-2 flex-wrap">
    <a href="export_csv.php<?= $kamarDipilih !== '' ? '?kamar=' . rawurlencode($kamarDipilih) : '' ?>"
      class="btn btn-outline-success">Unduh CSV</a>
    <a href="tambah.php" class="btn btn-success">+ Tambah Santri</a>
  </div>
</div>
<form method="get" class="card card-body mb-3">
  <div class="row align-items-end g-2">
    <div class="col-md-5"><label for="filterKamar" class="form-label">Filter berdasarkan kamar</label>
      <select id="filterKamar" name="kamar" class="form-select">
        <option value="">Semua kamar</option>
        <?php foreach ($daftarKamar as $namaKamar): ?>
          <option value="<?= e($namaKamar) ?>" <?= $kamarDipilih === $namaKamar ? 'selected' : '' ?>><?= e($namaKamar) ?>
          </option><?php endforeach; ?>
      </select>
    </div>
    <div class="col-auto"><button class="btn btn-primary" type="submit">Filter</button> <a href="index.php"
        class="btn btn-outline-secondary">Reset</a></div>
  </div>
</form>
<div class="d-flex justify-content-between mb-2"><small class="text-muted">Menampilkan
    <?= $totalData ? $offset + 1 : 0 ?>–<?= min($offset + count($daftar), $totalData) ?> dari <?= $totalData ?>
    santri</small><small class="text-muted">Halaman <?= $halaman ?> dari <?= $totalHalaman ?></small></div>
<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table table-striped table-hover mb-0">
      <thead class="table-success">
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Angkatan</th>
          <th>Jenis Kelamin</th>
          <th>Tanggal Lahir</th>
          <th>Umur</th>
          <th>Asal Daerah</th>
          <th>Kamar</th>
          <th>No. HP Wali</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!$daftar): ?>
          <tr>
            <td colspan="10" class="text-center text-muted">
              <?= $kamarDipilih !== '' ? 'Tidak ada santri di kamar ini.' : 'Belum ada data santri.' ?></td>
          </tr><?php endif; ?>
        <?php foreach ($daftar as $no => $s): ?>
          <?php
          $umur = '—';
          if (!empty($s['tanggal_lahir'])) {
            try {
              $lahir = new DateTime($s['tanggal_lahir']);
              $hariIni = new DateTime('today');
              if ($lahir <= $hariIni)
                $umur = $lahir->diff($hariIni)->y . ' tahun';
            } catch (Exception $e) {
              $umur = '—';
            }
          }
          ?>
          <tr>
            <td><?= $offset + $no + 1 ?></td>
            <td><?= e($s['nama']) ?></td>
            <td><?= e($s['angkatan']) ?></td>
            <td><?= $s['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
            <td><?= e($s['tanggal_lahir']) ?></td>
            <td><?= e($umur) ?></td>
            <td><?= e($s['asal_daerah']) ?></td>
            <td><?= e($s['kamar']) ?></td>
            <td><?= e($s['no_hp_wali']) ?></td>
            <td class="text-nowrap"><a href="edit.php?id=<?= (int) $s['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
              <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalHapus"
                data-id="<?= (int) $s['id'] ?>" data-nama="<?= e($s['nama']) ?>">Hapus</button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php if ($totalHalaman > 1): ?>
  <nav aria-label="Navigasi halaman daftar santri" class="mt-3">
    <ul class="pagination justify-content-center flex-wrap">
      <li class="page-item <?= $halaman <= 1 ? 'disabled' : '' ?>"><a class="page-link"
          href="?<?= http_build_query(['halaman' => max(1, $halaman - 1), 'kamar' => $kamarDipilih]) ?>">Sebelumnya</a></li>
      <?php for ($i = 1; $i <= $totalHalaman; $i++): ?>
        <li class="page-item <?= $i === $halaman ? 'active' : '' ?>"><a class="page-link"
            href="?<?= http_build_query(['halaman' => $i, 'kamar' => $kamarDipilih]) ?>"><?= $i ?></a></li><?php endfor; ?>
      <li class="page-item <?= $halaman >= $totalHalaman ? 'disabled' : '' ?>"><a class="page-link"
          href="?<?= http_build_query(['halaman' => min($totalHalaman, $halaman + 1), 'kamar' => $kamarDipilih]) ?>">Berikutnya</a>
      </li>
    </ul>
  </nav><?php endif; ?>
<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" action="hapus.php" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Hapus</h5><button type="button" class="btn-close" data-bs-dismiss="modal"
          aria-label="Tutup"></button>
      </div>
      <div class="modal-body">Yakin ingin menghapus data <strong id="namaHapus"></strong>?<input type="hidden" name="id"
          id="idHapus"></div>
      <div class="modal-footer"><button type="button" class="btn btn-secondary"
          data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-danger">Ya, Hapus</button></div>
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
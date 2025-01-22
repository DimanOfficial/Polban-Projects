<?= $this->extend('template/template'); ?>

<?= $this->section('content'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-blue">Halaman Prodi</h1>

    <!-- Form Search -->
    <form action="/dashboard/prodi" method="get" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control bg-white shadow-sm border-0 small" placeholder="Cari nama/kode prodi atau jurusan" value="<?= $keyword ?? ''; ?>">
            <button class="btn btn-primary" type="submit">Cari</button>
            <a href="/dashboard/prodi" class="btn btn-secondary">Reset</a>
        </div>
    </form>
</div>
<div class="container-fluid">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success mt-3">
            <?= session()->getFlashdata('success'); ?>
        </div>
    <?php endif; ?>
    <div class="table-responsive">
        <table class="table shadow-sm">
            <thead style="background-color: #07294d; color: white; font-size: 14px; text-align:center;">
                <tr>
                    <th>No</th>
                    <th>Kode Prodi</th>
                    <th>Nama Prodi</th>
                    <th>Nama Jurusan</th>
                    <th>Jenjang</th>
                    <th>Akreditasi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody style=" background-color: white; color:black; font-size: 14px;">
                <?php $no = 1; ?>
                <?php foreach ($prodi as $key => $p): ?>
                    <tr>
                        <td><?= ($key + 1) + (5 * ($pager->getCurrentPage('prodi') - 1)); ?></td>
                        <td><?= $p['kode_prodi']; ?></td>
                        <td><?= $p['nama_prodi']; ?></td>
                        <td><?= $p['nama_jurusan'] ?: 'Tidak Ada'; ?></td> <!-- Menampilkan nama jurusan -->
                        <td><?= $p['jenjang']; ?></td>
                        <td><?= $p['akreditasi']; ?></td>
                        <td><?= $p['status']; ?></td>
                        <td>
                            <!-- Tambahkan tombol aksi seperti edit/hapus -->
                            <a href="/dashboard/prodi/edit/<?= $p['id_prodi']; ?>" class="btn btn-success btn-sm me-2">Edit</a>
                            <a href="/dashboard/prodi/delete/<?= $p['id_prodi']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
    <div class="d-flex justify-content-between align-items-center mt-3">
        <a href="/dashboard/prodi/create" class="btn btn-primary btn-sm">Tambah Data</a>
        <div class="d-flex justify-content-between align-items-center">
            <a href="<?= base_url('/dashboardprodi/download-pdf') ?>" class="btn btn-danger btn-sm m-2">Download PDF</a>
            <a href="<?= base_url('/dashboardprodi/download-excel') ?>" class="btn btn-success btn-sm">Download Excel</a>
        </div>
        <div>
            <?= $pager->links('prodi', 'pagination_temp') ?>
        </div>


    </div>
</div>
</div>
<?= $this->endSection(); ?>
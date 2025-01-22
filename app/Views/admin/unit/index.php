<?= $this->extend('template/template'); ?>

<?= $this->section('content'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-blue">Halaman Unit</h1>

</div>
<div class="container-fluid">
    <?php if (session()->getFlashdata('success', 'Data kegiatan berhasil diperbarui')): ?>
        <div class="alert alert-success mt-3">
            <?= session()->getFlashdata('success', 'Data berhasil dihapus.'); ?>
        </div>
    <?php endif; ?>

    <!-- Form Pencarian -->
    <form action="/dashboard/unit" method="get" class="mb-3">
        <div class="input-group">
            <input type="text" name="keyword" class="form-control bg-white shadow-sm border-0 small" placeholder="Cari data unit..." value="<?= $keyword ?? ''; ?>">
            <button type="submit" class="btn btn-primary">Cari</button>
            <a href="/dashboard/unit" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table shadow-sm">
            <thead style="background-color: #07294d; color: white; font-size: 14px; text-align:center;">
                <tr>
                    <th>No</th>
                    <th>Nama Unit</th>
                    <th>Kode Unit</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody style="background-color: white; color:black; font-size: 14px;">
                <?php if (count($unit) > 0): ?>
                    <?php $no = 1 + (5 * ($pager->getCurrentPage('unit') - 1)); ?>
                    <?php foreach ($unit as $u): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $u['nama_unit']; ?></td>
                            <td><?= $u['kode_unit']; ?></td>
                            <td><?= $u['deskripsi']; ?></td>
                            <td>
                                <div class="d-flex justify-content-around">
                                    <a href="/dashboard/unit/edit/<?= $u['id_unit']; ?>" class="btn btn-success btn-sm me-2">Edit</a>
                                    <a href="/dashboard/unit/delete/<?= $u['id_unit']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Data tidak ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center mt-3">
        <a href="/dashboard/unit/create" class="btn btn-primary btn-sm">Tambah Data</a>
        <div class="d-flex justify-content-between align-items-center">
            <a href="<?= base_url('/dashboard/unit/download-pdf') ?>" class="btn btn-danger btn-sm m-2">Download PDF</a>
            <a href="<?= base_url('/dashboard/unit/download-excel') ?>" class="btn btn-success btn-sm">Download Excel</a>
        </div>
        <div>
            <?= $pager->links('unit', 'pagination_temp') ?>
        </div>
    </div>
</div>

<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
<?= $this->endSection(''); ?>
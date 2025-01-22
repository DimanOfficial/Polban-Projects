<?= $this->extend('template/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-blue">Halaman Jurusan</h1>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success mt-3">
            <?= session()->getFlashdata('success'); ?>
        </div>
    <?php endif; ?>

    <!-- Form Pencarian -->
    <form action="/dashboard/jurusan" method="get" class="mb-3">
        <div class="input-group">
            <input type="text" name="keyword" class="form-control bg-white shadow-sm border-0 small" placeholder="Cari data jurusan..." value="<?= $keyword ?? ''; ?>">
            <button type="submit" class="btn btn-primary">Cari</button>
            <a href="/dashboard/jurusan" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table shadow-sm">
            <thead style="background-color: #07294d; color: white; font-size: 12px; text-align:center;">
                <tr>
                    <th>No</th>
                    <th>Nama Jurusan</th>
                    <th>Kode Jurusan</th>
                    <th>Deskripsi</th>
                    <th>Akreditasi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody style="background-color: white; color:black; font-size: 14px;">
                <?php if (count($jurusan) > 0): ?>
                    <?php $no = 1 + (5 * ($pager->getCurrentPage('jurusan') - 1)); ?>
                    <?php foreach ($jurusan as $j): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $j['nama_jurusan']; ?></td>
                            <td><?= $j['kode_jurusan']; ?></td>
                            <td><?= $j['deskripsi']; ?></td>
                            <td><?= $j['akreditasi']; ?></td>
                            <td><?= $j['status']; ?></td>
                            <td>
                                <div class="d-flex justify-content-around">
                                    <a href="/dashboard/jurusan/edit/<?= $j['id_jurusan']; ?>" class="btn btn-success btn-sm me-2">Edit</a>
                                    <a href="/dashboard/jurusan/delete/<?= $j['id_jurusan']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
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
        <a href="/dashboard/jurusan/create" class="btn btn-primary btn-sm">Tambah Data</a>
        <div class="d-flex justify-content-between">
            <a href="<?= base_url('/dashboard/jurusan/download-pdf') ?>" class="btn btn-danger btn-sm">Download PDF</a>
            <a href="<?= base_url('/dashboard/jurusan/download-excel') ?>" class="btn btn-success btn-sm">Download Excel</a>

        </div>
        <div>
            <?= $pager->links('jurusan', 'pagination_temp') ?>
        </div>



    </div>
</div>

<?= $this->endSection(); ?>
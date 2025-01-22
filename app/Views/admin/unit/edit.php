<?= $this->extend('template/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid m-5">
    <h1 class="h3 mb-4 text-gray-800">Edit Unit</h1>
    <form action="/dashboard/unit/update/<?= $unit['id_unit'] ?>" method="post">
        <?= csrf_field(); ?>

        <!-- Menampilkan pesan error jika ada -->
        <?php if (isset($errors)): ?>
            <div class="alert alert-danger">
                <?= implode('<br>', $errors); ?>
            </div>
        <?php endif; ?>

        <div class="row bg-white shadow-sm p-4 rounded mb-3">
            <div class="col-md-6">
            <div class="mb-3">
            <label for="nama_unit" class="form-label">Nama Unit</label>
            <input type="text" class="form-control" id="nama_unit" name="nama_unit" value="<?= $unit['nama_unit'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="kode_unit" class="form-label">Kode Unit</label>
            <input type="text" class="form-control" id="kode_unit" name="kode_unit" value="<?= $unit['kode_unit'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <input type="text" class="form-control" id="deskripsi" name="deskripsi" value="<?= $unit['deskripsi'] ?>" required>
        </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="/dashboard/unit" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</div>
<?= $this->endSection(); ?>
<?= $this->extend('template/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid p-5">
    <h1 class="h3 mb-4 text-gray-800">Tambah Unit</h1>

    <!-- Menampilkan error jika ada -->
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="/dashboard/unit/store" method="post">
        <?= csrf_field(); ?>

        <div class="row bg-white shadow-sm p-4 rounded mb-3">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nama_unit" class="form-label">Nama Unit</label>
                    <input type="text" class="form-control" id="nama_unit" name="nama_unit" required>
                </div>
                
                <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control costum-area" id="deskripsi" name="deskripsi" rows="3"><?= old('deskripsi'); ?></textarea>
                    </div>
            </div>
            <div class="col-md-6">
            <div class="mb-3">
                    <label for="kode_unit" class="form-label">Kode Unit</label>
                    <input type="text" class="form-control" id="kode_unit" name="kode_unit" required>
                </div>
            </div>
        </div>
        

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="/dashboard/unit" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</div>


<?= $this->endSection(); ?>
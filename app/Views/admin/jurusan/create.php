<?= $this->extend('template/template'); ?>

<?= $this->section('content'); ?>
<div class="container mt-5 p-5">
    <div class="content">
        <h1 class="h3 mb-4 text-gray-800">Tambah Jurusan</h1>

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

        <!-- Form untuk menambah kegiatan -->
        <form action="/dashboard/jurusan/store" method="POST">
            <?= csrf_field(); ?>
            <div class="row bg-white shadow-sm p-4 rounded">
                <!-- Kolom Kiri -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nama_jurusan" class="form-label">Nama Jurusan</label>
                        <input type="text" class="form-control" id="nama_jurusan" name="nama_jurusan" value="<?= old('nama_jurusan'); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="kode_jurusan" class="form-label">Kode Jurusan</label>
                        <input type="text" class="form-control" id="kode_jurusan" name="kode_jurusan" value="<?= old('kode_jurusan'); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control costum-area" id="deskripsi" name="deskripsi" rows="3"><?= old('deskripsi'); ?></textarea>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="akreditasi" class="form-label">Akreditasi</label>
                        <select name="akreditasi" class="form-control styled-dropdown">
                            <option value="A" <?= old('akreditasi') == 'A' ? 'selected' : '' ?>>A</option>
                            <option value="B" <?= old('akreditasi') == 'B' ? 'selected' : '' ?>>B</option>
                            <option value="C" <?= old('akreditasi') == 'C' ? 'selected' : '' ?>>C</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-control styled-dropdown">
                            <option value="aktif" <?= old('status') == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="tidak aktif" <?= old('status') == 'tidak aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                        </select>
                    </div>
                </div>
            </div>
            </div>

            <!-- Button Submit -->
            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
            <a href="/dashboard/jurusan" class="btn btn-secondary mt-3">Kembali</a>
        </form>
    </div>
</div>

<style>
    .styled-dropdown {
        border: 1px solid #ced4da;
        border-radius: 5px;
        padding: 10px;
        background-color: #f8f9fa;
        transition: all 0.3s;
    }

    .styled-dropdown:focus {
        border-color: #80bdff;
        background-color: #ffffff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    .costum-area {
        width: 100%;
        resize: none;
    }
</style>
<?= $this->endSection(); ?>
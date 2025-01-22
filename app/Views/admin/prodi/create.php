<?= $this->extend('template/template'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <h1 class="h3 mb-4 text-gray-800">Tambah Prodi</h1>

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

    <form action="/dashboard/prodi/store" method="post">
        <?= csrf_field(); ?>

        <div class="row bg-white shadow-sm p-4 rounded mb-3">
            <!-- Kolom Kiri -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nama_prodi" class="form-label">Nama Prodi</label>
                    <input type="text" class="form-control" id="nama_prodi" name="nama_prodi" value="<?= old('nama_prodi'); ?>">
                </div>

                <div class="mb-3">
                    <label for="kode_prodi" class="form-label">Kode Prodi</label>
                    <input type="text" class="form-control" id="kode_prodi" name="kode_prodi" value="<?= old('kode_prodi'); ?>">
                </div>

                <div class="mb-3">
                    <label for="jenjang" class="form-label">Jenjang</label>
                    <input type="text" class="form-control" id="jenjang" name="jenjang" value="<?= old('jenjang'); ?>">
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control costum-area" id="deskripsi" name="deskripsi" rows="3"><?= old('deskripsi'); ?></textarea>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="id_jurusan" class="form-label">Nama Jurusan</label>
                    <select class="form-control" id="id_jurusan" name="id_jurusan">
                        <option value="">-- Pilih Jurusan --</option>
                        <?php foreach ($jurusan as $j): ?>
                            <option value="<?= $j['id_jurusan']; ?>" <?= old('id_jurusan') == $j['id_jurusan'] ? 'selected' : '' ?>>
                                <?= $j['nama_jurusan']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="akreditasi" class="form-label">Akreditasi</label>
                    <select name="akreditasi" class="form-control">
                        <option value="A" <?= old('akreditasi') == 'A' ? 'selected' : '' ?>>A</option>
                        <option value="B" <?= old('akreditasi') == 'B' ? 'selected' : '' ?>>B</option>
                        <option value="C" <?= old('akreditasi') == 'C' ? 'selected' : '' ?>>C</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="aktif" <?= old('status') == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="tidak aktif" <?= old('status') == 'tidak aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="/dashboard/prodi" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</div>

<style>
    .styled-dropdown {
        border: 1px solid #ced4da;
        border-radius: 5px;
        padding: 10px;
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
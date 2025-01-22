<?= $this->extend('template/template'); ?>

<?= $this->section('content'); ?>
<!-- Begin Page Content -->
<div class="container mt-5 p-5">
    <h1 class="h3 mb-4 text-gray-800">Halaman Edit Jurusan</h1>

    <form action="/dashboard/jurusan/update/<?= $jurusan['id_jurusan'] ?>" method="post">
        <?= csrf_field(); ?>

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

        <div class="row bg-white shadow-sm p-4 rounded">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nama_jurusan" class="form-label">Nama Jurusan</label>
                    <input type="text" class="form-control" id="nama_jurusan" name="nama_jurusan" value="<?= $jurusan['nama_jurusan'] ?>">
                </div>

                <div class="mb-3">
                    <label for="kode_jurusan" class="form-label">Kode Jurusan</label>
                    <input type="text" class="form-control" id="kode_jurusan" name="kode_jurusan" value="<?= $jurusan['kode_jurusan'] ?>">
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control costum-area" id="deskripsi" name="deskripsi" rows="3"><?= $jurusan['deskripsi'] ?></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="akreditasi" class="form-label">Status</label>
                    <select name="akreditasi" class="form-select">
                        <option value="A" <?= $jurusan['akreditasi'] == 'A' ? 'selected' : '' ?>>A</option>
                        <option value="B" <?= $jurusan['akreditasi'] == 'B' ? 'selected' : '' ?>>B</option>
                        <option value="C" <?= $jurusan['akreditasi'] == 'C' ? 'selected' : '' ?>>C</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="aktif" <?= $jurusan['status'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="tidak aktif" <?= $jurusan['status'] == 'tidak aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
        <a href="/dashboard/jurusan" class="btn btn-secondary mt-3">Kembali</a>
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
<!-- End of Page Content -->

<?= $this->endSection(); ?>
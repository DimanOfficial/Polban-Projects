<?= $this->extend('template/template'); ?>

<?= $this->section('content'); ?>
<div class="container mt-5">
    <div class="content">
        <h1 class="h3 mb-4 font-weight-bold text-gray-800">Tambah Kegiatan</h1>

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
        <form action="/dashboard/kegiatan/store" method="POST" enctype="multipart/form-data">
            <?= csrf_field(); ?>
            <div class="row bg-white shadow-sm p-4 rounded">
                <!-- Kolom Kiri -->
                <div class="col-md-6">
                    <!-- Nama Kegiatan -->
                    <div class="mb-3">
                        <label for="nama_kegiatan" class="form-label">Nama Kegiatan</label>
                        <input type="text" class="form-control" id="nama_kegiatan" name="nama_kegiatan" value="<?= old('nama_kegiatan'); ?>" placeholder="Masukkan Nama Kegiatan">
                    </div>

                    <!-- Deskripsi Kegiatan -->
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi Kegiatan</label>
                        <textarea class="form-control costum-area" id="deskripsi" name="deskripsi" rows="3" placeholder="Masukkan Deskripsi Kegiatan"><?= old('deskripsi'); ?></textarea>
                    </div>

                    <!-- Tanggal Mulai -->
                    <div class="mb-3">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="<?= old('tanggal_mulai'); ?>">
                    </div>

                    <!-- Tanggal Selesai -->
                    <div class="mb-3">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="<?= old('tanggal_selesai'); ?>">
                    </div>

                    <!-- Lokasi -->
                    <div class="mb-3">
                        <label for="lokasi" class="form-label">Lokasi</label>
                        <input type="text" class="form-control" id="lokasi" name="lokasi" value="<?= old('lokasi'); ?>" placeholder="Masukkan Lokasi">
                    </div>

                    <!-- Poster -->
                    <div class="mb-3">
                        <label for="poster" class="form-label">Unggah Poster</label>
                        <input type="file" class="form-control" id="poster" name="poster" accept="image/*">
                    </div>

                    <!-- Video -->
                    <div class="mb-3">
                        <label for="video" class="form-label">Unggah Video</label>
                        <input type="file" class="form-control" id="video" name="video" accept="video/*">
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-md-6">
                    <!-- Waktu Kegiatan -->
                    <div class="mb-3">
                        <label for="waktu_kegiatan" class="form-label">Waktu Kegiatan</label>
                        <input type="text" class="form-control" id="waktu_kegiatan" name="waktu_kegiatan" value="<?= old('waktu_kegiatan'); ?>" placeholder="Waktu mulai - selesai kegiatan">
                    </div>

                    <!-- Nara Hubung -->
                    <div class="mb-3">
                        <label for="nara_hubung" class="form-label">Nara Hubung</label>
                        <input type="text" class="form-control" id="nara_hubung" name="nara_hubung" value="<?= old('nara_hubung'); ?>" placeholder="Masukkan Nara Hubung">
                    </div>

                    <!-- Penanggung Jawab -->
                    <div class="mb-3">
                        <label for="penanggung_jawab" class="form-label">Penanggung Jawab</label>
                        <input type="text" class="form-control" id="penanggung_jawab" name="penanggung_jawab" value="<?= old('penanggung_jawab'); ?>" placeholder="Masukkan Penanggung Jawab">
                    </div>

                    <!-- Input Penyelenggara -->
                    <div class="mb-3">
                        <label for="penyelenggara" class="form-label">Penyelenggara</label>
                        <input type="text" class="form-control" id="penyelenggara" name="penyelenggara" 
                            value="<?= $penyelenggara; ?>" readonly>
                    </div>

                    <?php if ($penyelenggara === 'Mahasiswa' && $user['jenis_users'] === 'Mahasiswa') : ?>
                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                value="<?= $user['nama_lengkap']; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="jurusan" class="form-label">Jurusan</label>
                            <input type="text" class="form-control" id="jurusan" name="jurusan" 
                                value="<?= $user['nama_jurusan']; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="prodi" class="form-label">Prodi</label>
                            <input type="text" class="form-control" id="prodi" name="prodi" 
                                value="<?= $user['nama_prodi']; ?>" readonly>
                        </div>
                    <?php elseif ($penyelenggara === 'Karyawan' && $user['jenis_users'] === 'Karyawan') : ?>
                        <div class="mb-3">
                            <label for="jenis_karyawan" class="form-label">Jenis Karyawan</label>
                            <input type="text" class="form-control" id="jenis_karyawan" name="jenis_karyawan" 
                                value="<?= ucfirst($user['jenis_karyawan']); ?>" readonly>
                        </div>
                        <?php if ($user['jenis_karyawan'] === 'jurusan') : ?>
                            <div class="mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                    value="<?= $user['nama_lengkap']; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="jurusan" class="form-label">Jurusan</label>
                                <input type="text" class="form-control" id="jurusan" name="jurusan" 
                                    value="<?= $user['nama_jurusan']; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="prodi" class="form-label">Prodi</label>
                                <input type="text" class="form-control" id="prodi" name="prodi" 
                                    value="<?= $user['nama_prodi']; ?>" readonly>
                            </div>
                        <?php elseif ($user['jenis_karyawan'] === 'unit') : ?>
                            <div class="mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                    value="<?= $user['nama_lengkap']; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="unit" class="form-label">Unit</label>
                                <input type="text" class="form-control" id="unit" name="unit" 
                                    value="<?= $user['nama_unit']; ?>" readonly>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>



                    <!-- Peserta -->
                    <div class="mb-3">
                        <label for="peserta" class="form-label">Peserta</label>
                        <select class="form-control" id="peserta" name="peserta">
                            <option value="">-- Pilih Peserta --</option>
                            <option value="mahasiswa" <?= old('peserta') === 'mahasiswa' ? 'selected' : ''; ?>>Mahasiswa</option>
                            <option value="karyawan" <?= old('peserta') === 'karyawan' ? 'selected' : ''; ?>>Karyawan</option>
                            <option value="umum" <?= old('peserta') === 'umum' ? 'selected' : ''; ?>>Umum</option>
                            <option value="pejabat" <?= old('peserta') === 'pejabat' ? 'selected' : ''; ?>>Pejabat</option>
                        </select>
                    </div>

                    <!-- Jenis Kegiatan -->
                    <div class="mb-3">
                        <label for="jenis_kegiatan" class="form-label">Jenis Kegiatan</label>
                        <select class="form-control" id="jenis_kegiatan" name="jenis_kegiatan">
                            <option value="">-- Pilih Jenis Kegiatan --</option>
                            <option value="Akademik" <?= old('jenis_kegiatan') === 'Akademik' ? 'selected' : ''; ?>>Akademik</option>
                            <option value="Non Akademik" <?= old('jenis_kegiatan') === 'Non Akademik' ? 'selected' : ''; ?>>Non Akademik</option>
                            <option value="Umum" <?= old('jenis_kegiatan') === 'Umum' ? 'selected' : ''; ?>>Umum</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Button Submit -->
            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="/dashboard/pembuat/kegiatan" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11">
  <div id="errorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header bg-danger text-white">
      <i class="bi bi-exclamation-lg me-1 fs-3"></i>
      <strong class="me-auto">Pemberitahuan</strong>
      <small class="fw-normal">Baru Saja</small>
    </div>
    <div class="toast-body">
        Anda harus memilih sesuai dengan jenis users Anda.
    </div>
  </div>
</div>

<style>
    .costum-area {
        width: 100%;
        resize: none;
    }
</style>

<?= $this->endSection(); ?>

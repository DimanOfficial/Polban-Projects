<?= $this->extend('template/templatePembuat'); ?>

<?= $this->section('content'); ?>
<!-- Begin Page Content -->
<div class="container">
    <h1 class="h3 mb-4 font-weight-bold text-gray-800">Halaman Edit Kegiatan</h1>

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

    <form action="/dashboard/kegiatan/update/<?= $kegiatan['id_kegiatan'] ?>" method="post" enctype="multipart/form-data"> <!-- Tambahkan enctype -->
        <?= csrf_field(); ?>

        <div class="row bg-white shadow-sm p-4 rounded">
            <!-- Kolom Kiri -->
            <div class="col-md-6">
                <!-- Nama Kegiatan -->
                <div class="form-group mb-3">
                    <label for="nama_kegiatan" class="form-label">Nama Kegiatan</label>
                    <input type="text" class="form-control" id="nama_kegiatan" name="nama_kegiatan" value="<?= $kegiatan['nama_kegiatan'] ?>" placeholder="Masukkan Nama Kegiatan">
                </div>

                <!-- Deskripsi Kegiatan -->
                <div class="form-group mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi Kegiatan</label>
                    <textarea class="form-control costum-area" id="deskripsi" name="deskripsi" rows="3" placeholder="Masukkan Deskripsi Kegiatan"><?= $kegiatan['deskripsi'] ?></textarea>
                </div>

                <!-- Tanggal Mulai -->
                <div class="form-group mb-3">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="<?= $kegiatan['tanggal_mulai'] ?>">
                </div>

                <!-- Tanggal Selesai -->
                <div class="form-group mb-3">
                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                    <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="<?= $kegiatan['tanggal_selesai'] ?>">
                </div>

                <!-- Lokasi -->
                <div class="form-group mb-3">
                    <label for="lokasi" class="form-label">Lokasi</label>
                    <input type="text" class="form-control" id="lokasi" name="lokasi" value="<?= $kegiatan['lokasi'] ?>" placeholder="Masukkan Lokasi">
                </div>

                <!-- Waktu Kegiatan -->
                <div class="form-group mb-3">
                    <label for="waktu_kegiatan" class="form-label">Waktu Kegiatan</label>
                    <input type="text" class="form-control" id="waktu_kegiatan" name="waktu_kegiatan" value="<?= $kegiatan['waktu_kegiatan'] ?>" placeholder="Waktu mulai - selesai kegiatan">
                </div>

                <!-- Nara Hubung -->
                <div class="form-group mb-3">
                    <label for="nara_hubung" class="form-label">Nara Hubung</label>
                    <input type="text" class="form-control" id="nara_hubung" name="nara_hubung" value="<?= $kegiatan['nara_hubung'] ?>" placeholder="Masukkan Nara Hubung">
                </div>

                <!-- Penanggung Jawab -->
                <div class="form-group mb-3">
                    <label for="penanggung_jawab" class="form-label">Penanggung Jawab</label>
                    <input type="text" class="form-control" id="penanggung_jawab" name="penanggung_jawab" value="<?= $kegiatan['penanggung_jawab'] ?>" placeholder="Masukkan Penanggung Jawab">
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-6">
                <!-- Penyelenggara (Readonly) -->
                <div class="form-group mb-3">
                    <label for="penyelenggara" class="form-label">Penyelenggara</label>
                    <input type="text" class="form-control" id="penyelenggara" name="penyelenggara"
                        value="<?= $kegiatan['penyelenggara'] ?>" readonly>
                </div>

                <!-- Kolom Dinamis -->
                <?php if ($kegiatan['penyelenggara'] === 'Mahasiswa'): ?>
                    <div class="form-group mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                            value="<?= $kegiatan['nama_lengkap'] ?>" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="id_jurusan" class="form-label">Jurusan</label>
                        <input type="text" class="form-control" id="id_jurusan" name="id_jurusan"
                            value="<?= $kegiatan['nama_jurusan'] ?>" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="id_prodi" class="form-label">Prodi</label>
                        <input type="text" class="form-control" id="id_prodi" name="id_prodi"
                            value="<?= $kegiatan['nama_prodi'] ?>" readonly>
                    </div>
                <?php elseif ($kegiatan['penyelenggara'] === 'Karyawan'): ?>
                    <div class="form-group mb-3">
                        <label for="jenis_karyawan" class="form-label">Jenis Karyawan</label>
                        <input type="text" class="form-control" id="jenis_karyawan" name="jenis_karyawan"
                            value="<?= ucfirst($kegiatan['jenis_karyawan']) ?>" readonly>
                    </div>
                    <?php if ($kegiatan['jenis_karyawan'] === 'jurusan'): ?>
                        <div class="form-group mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                                value="<?= $kegiatan['nama_lengkap'] ?>" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label for="id_jurusan" class="form-label">Jurusan</label>
                            <input type="text" class="form-control" id="id_jurusan" name="id_jurusan"
                                value="<?= $kegiatan['nama_jurusan'] ?>" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label for="id_prodi" class="form-label">Prodi</label>
                            <input type="text" class="form-control" id="id_prodi" name="id_prodi"
                                value="<?= $kegiatan['nama_prodi'] ?>" readonly>
                        </div>
                    <?php elseif ($kegiatan['jenis_karyawan'] === 'unit'): ?>
                        <div class="form-group mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                                value="<?= $kegiatan['nama_lengkap'] ?>" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label for="id_unit" class="form-label">Unit</label>
                            <input type="text" class="form-control" id="id_unit" name="id_unit"
                                value="<?= $kegiatan['nama_unit'] ?>" readonly>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <!-- Peserta -->
                <div class="mb-3">
                    <label for="peserta" class="form-label">Peserta</label>
                    <select name="peserta" id="peserta" class="form-select">
                        <option value="mahasiswa" <?= $kegiatan['peserta'] == 'mahasiswa' ? 'selected' : '' ?>>Mahasiswa</option>
                        <option value="karyawan" <?= $kegiatan['peserta'] == 'karyawan' ? 'selected' : '' ?>>Karyawan</option>
                        <option value="umum" <?= $kegiatan['peserta'] == 'umum' ? 'selected' : '' ?>>Umum</option>
                        <option value="pejabat" <?= $kegiatan['peserta'] == 'pejabat' ? 'selected' : '' ?>>Pejabat</option>
                    </select>
                </div>

                <!-- Jenis Kegiatan -->
                <div class="mb-3">
                    <label for="jenis_kegiatan" class="form-label">Jenis Kegiatan</label>
                    <select name="jenis_kegiatan" id="jenis_kegiatan" class="form-select">
                        <option value="Akademik" <?= $kegiatan['jenis_kegiatan'] == 'Akademik' ? 'selected' : '' ?>>Akademik</option>
                        <option value="Non Akademik" <?= $kegiatan['jenis_kegiatan'] == 'Non Akademik' ? 'selected' : '' ?>>Non Akademik</option>
                        <option value="Umum" <?= $kegiatan['jenis_kegiatan'] == 'Umum' ? 'selected' : '' ?>>Umum</option>
                    </select>
                </div>


                <!-- Poster Kegiatan -->
                <div class="form-group mb-3">
                    <label for="poster" class="form-label">Poster Kegiatan</label>
                    <input type="file" class="form-control" id="poster" name="poster" accept="image/*">
                    <?php if (!empty($kegiatan['poster'])): ?>
                        <div class="mt-2">
                            <label class="form-label">Poster Saat Ini:</label><br>
                            <img src="<?= base_url('assets/images/' . $kegiatan['poster']) ?>" alt="Poster Kegiatan" class="img-thumbnail" width="200">
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Video Kegiatan -->
                <div class="form-group mb-3">
                    <label for="video" class="form-label">Video Kegiatan</label>
                    <input type="file" class="form-control" id="video" name="video" accept="video/*">
                </div>

                <!-- Menampilkan video Lama -->
                <?php if (!empty($kegiatan['video'])): ?>
                    <div class="mb-3">
                        <label class="form-label">Video Saat Ini</label><br>
                        <video controls width="200">
                            <source src="<?= base_url('assets/videos/' . $kegiatan['video']) ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
        <a href="/dashboard/kegiatan" class="btn btn-secondary mt-3">Kembali</a>
    </form>
</div>
</div>
<!-- End of Page Content -->

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
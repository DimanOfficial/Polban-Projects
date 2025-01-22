<?= $this->extend('template/templatePengunjung'); ?>

<?= $this->section('conn'); ?>
<div class="container mt-5">
    <h1 class="fs-4 text-blue mb-4">
        <?= $kegiatan['nama_kegiatan']; ?>
    </h1>
    <hr>

    <!-- Gambar memenuhi lebar layar -->
    <div class="text-center mb-4">
        <img src="<?= base_url('assets/images/' . $kegiatan['poster']); ?>" 
             alt="<?= $kegiatan['nama_kegiatan']; ?>" 
             class="img-fluid rounded" 
             style="width: 100%; max-height: 100%; object-fit: cover;">
    </div>

    <!-- Deskripsi dengan jarak dari gambar -->
    <div class="m-4">
        <p class="lead" style="text-align: justify;">
            <strong>Deskripsi:</strong> <?= $kegiatan['deskripsi']; ?>
        </p>
        <p style="text-align: justify;">
            Kegiatan <strong><?= $kegiatan['jenis_kegiatan']; ?></strong> akan dilaksanakan di <strong><?= $kegiatan['lokasi']; ?></strong>
            mulai <strong><?= $kegiatan['tanggal_mulai']; ?></strong> hingga <strong><?= $kegiatan['tanggal_selesai']; ?></strong>, 
            dengan waktu pelaksanaan pukul <strong><?= $kegiatan['waktu_kegiatan']; ?></strong> hingga selesai. 
            Peserta yang terlibat adalah <strong><?= $kegiatan['peserta']; ?></strong>, dan penyelenggaraan kegiatan ini 
            berada di bawah tanggung jawab <strong><?= $kegiatan['penyelenggara']; ?></strong>, 
            khususnya oleh <strong><?= $kegiatan['detail_penyelenggara']; ?></strong> (<?= $kegiatan['jenis_penyelenggara']; ?>).
        </p>
        <p style="text-align: justify;">
            Penanggung jawab kegiatan belum ditentukan, namun narahubung dapat dihubungi melalui email 
            <strong><?= $kegiatan['nara_hubung']; ?></strong>.
        </p>

        <div class="text-center mb-4">
    <?php if (!empty($kegiatan['video'])): ?>
        <video controls class="img-fluid rounded" style="width: 100%; max-height: 100%; object-fit: cover;">
            <source src="<?= base_url('assets/videos/' . $kegiatan['video']); ?>" type="video/mp4">
            Browser anda tidak mendukung penggunaan tag video ini.
        </video>
    <?php else: ?>
        <div class="container mt-5">
            <p class="lead py-4 fw-bold text-secondary" style="background-color:#d6f1f1;"><i class="bi bi-exclamation-circle"></i> Video tidak tersedia untuk kegiatan ini.</p>
        </div>
    <?php endif; ?>
</div>



        <!-- Tombol Kembali sejajar -->
        <div class="text-start mt-4">
            <a href="/pengunjung" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
        crossorigin="anonymous"></script>
<script src="/js/script.js"></script>
<?= $this->endSection(); ?>

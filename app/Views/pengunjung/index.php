<?= $this->extend('template/templatePengunjung'); ?>

<?= $this->section('conn'); ?>
<div class="container mt-5">
    <h1 class="fs-2 fw-bold">Kegiatan Hari Ini</h1>
    <hr>

    <!-- Kegiatan Hari Ini -->
    <div class="row mb-5">
        <?php 
        $today = date('Y-m-d');
        $kegiatanHariIni = array_filter($kegiatan, function($p) use ($today) {
            return $p['tanggal_mulai'] === $today;
        });
        ?>

        <?php if (count($kegiatanHariIni) > 0): ?>
            <?php foreach ($kegiatanHariIni as $p): ?>
                <div class="col-md-4 p-2">
                    <div class="card border-0 shadow position-relative" style="height: 100%;">

                        <!-- Penanda Label -->
                        <div class="position-absolute top-0 start-0 bg-info px-3 py-1 rounded-end" style="z-index: 10; color:white; font-weight:700;">
                            <small><?= date('d M Y', strtotime($p['tanggal_mulai'])); ?></small>
                        </div>

                        <div>
                        <?php 
                        if ($p['status'] == 'belum dimulai') {
                            $badgeClass = 'bg-warning p-1 text-white';
                        } elseif ($p['status'] == 'sedang dilaksanakan') {
                            $badgeClass = 'bg-primary p-1 text-white';
                        } elseif ($p['status'] == 'sudah selesai') {
                            $badgeClass = 'bg-success p-1 text-white';
                        } else {
                            $badgeClass = 'bg-light';
                        }
                        ?>
                        <small class="<?= $badgeClass ?> position-absolute top-0 end-0 px-3 py-1 rounded-start" style="z-index: 10; font-weight:700;"><?= esc($p['status']) ?></small>
                        </div>

                        <!-- Gambar -->
                        <img src="<?= base_url('assets/images/' . $p['poster']); ?>" 
                             class="card-img-top object-fit-cover" 
                             alt="<?= $p['nama_kegiatan']; ?>" 
                             style="height: 200px; object-fit: cover;">

                        <!-- Isi Card -->
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h5 class="card-title text-truncate"><?= $p['nama_kegiatan']; ?></h5>
                            <p class="card-text text-muted small">
                                <?= substr($p['deskripsi'] ?? '', 0, 100); ?>...
                            </p>
                            <div class="mt-auto">
                                <a href="/pengunjung/detail/<?= $p['id_kegiatan']; ?>" class="btn btn-sm btn1 w-100">Lihat Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-5 text-center">
                <p class="lead py-2 fw-semibold text-white" style="background-color:rgba(226, 10, 85, 0.66);">
                    <i class="bi bi-exclamation-circle"></i> Tidak ada kegiatan yang dibuat pada hari ini.
                </p>
            </div>
        <?php endif; ?>
    </div>

    <div class="container-fluid mb-4">
    <h1 class="fs-2 fw-bold">Semua Kegiatan</h1>
    <hr>

    </div>

    <div class="container">
        <!-- Dropdown untuk memilih jumlah data -->
        <div class="row mb-3">
            <div class="col-md-2">
                <form action="/" method="get" id="limitForm">
                    <select name="limit" id="limitDropdown" class="form-select" onchange="document.getElementById('limitForm').submit();">
                        <option value="6" <?= ($limit == 6) ? 'selected' : ''; ?>>6</option>
                        <option value="10" <?= ($limit == 10) ? 'selected' : ''; ?>>10</option>
                        <option value="15" <?= ($limit == 15) ? 'selected' : ''; ?>>15</option>
                        <option value="20" <?= ($limit == 20) ? 'selected' : ''; ?>>20</option>
                    </select>
                    <input type="hidden" name="keyword" value="<?= $keyword ?? ''; ?>">
                </form>
            </div>
        </div>
    </div>

    <!-- Form Pencarian -->
    <form action="/" method="get" class="mb-3">
        <div class="input-group">
            <input type="text" name="keyword" class="form-control bg-white shadow-sm border-0 small" placeholder="Cari data unit..." value="<?= $keyword ?? ''; ?>">
            <input type="hidden" name="limit" value="<?= $limit; ?>">
            <button type="submit" class="btn btn-primary">Cari</button>
            <a href="/" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <!-- Semua Kegiatan -->
    <div class="row">
        <?php if (count($kegiatan) > 0): ?>
            <?php foreach ($kegiatan as $p): ?>
                <div class="col-md-4 p-2">
                    <div class="card border-0 shadow position-relative" style="height: 100%;">

                        <!-- Penanda Label -->
                        <div class="position-absolute top-0 start-0 bg-info px-3 py-1 rounded-end" style="z-index: 10; color:white; font-weight:700;">
                            <small><?= date('d M Y', strtotime($p['tanggal_mulai'])); ?></small>
                        </div>

                        <div>
                        <?php 
                        if ($p['status'] == 'belum dimulai') {
                            $badgeClass = 'bg-warning p-1 text-white';
                        } elseif ($p['status'] == 'sedang dilaksanakan') {
                            $badgeClass = 'bg-primary p-1 text-white';
                        } elseif ($p['status'] == 'sudah selesai') {
                            $badgeClass = 'bg-success p-1 text-white';
                        } else {
                            $badgeClass = 'bg-light';
                        }
                        ?>
                        <small class="<?= $badgeClass ?> position-absolute top-0 end-0 px-3 py-1 rounded-start" style="z-index: 10; font-weight:700;"><?= esc($p['status']) ?></small>
                        </div>

                        <!-- Gambar -->
                        <img src="<?= base_url('assets/images/' . $p['poster']); ?>" 
                             class="card-img-top object-fit-cover" 
                             alt="<?= $p['nama_kegiatan']; ?>" 
                             style="height: 200px; object-fit: cover;">

                        <!-- Isi Card -->
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h5 class="card-title text-truncate"><?= $p['nama_kegiatan']; ?></h5>
                            <p class="card-text text-muted small">
                                <?= substr($p['deskripsi'] ?? '', 0, 100); ?>...
                            </p>
                            <div class="mt-auto">
                                <a href="/pengunjung/detail/<?= $p['id_kegiatan']; ?>" class="btn btn-sm btn1 w-100">Lihat Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <p class="lead py-4 fw-bold text-secondary" style="background-color:#d6f1f1;">
                    <i class="bi bi-exclamation-circle"></i> Tidak ada kegiatan yang tersedia.
                </p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <div class="container d-flex justify-content-center">
        <div>
            <?= $pager->links('kegiatan', 'pagination_temp') ?>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

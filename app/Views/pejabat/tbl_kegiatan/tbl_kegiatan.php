<?= $this->extend('template/templatePejabat'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <h1 class="fs-4 text-dark">Rincian Kegiatan</h1>
    <hr>


    <form action="/pejabat/tbl_kegiatan" method="get" class="mb-3">
        <div class="input-group">
            <input type="text" name="keyword" class="form-control bg-white shadow-sm border-0 small" placeholder="Cari data kegiatan..." value="<?= $keyword ?? ''; ?>">
            <button type="submit" class="btn btn-primary">Cari</button>
            <a href="/pejabat/tbl_kegiatan" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table shadow-sm" id="mydatatable">
            <thead style="background-color: #07294d; color: white; font-size: 14px; text-align:center;">
                <tr>
                    <th>No</th>
                    <th>Nama Kegiatan</th>
                    <th>Poster</th>
                    <th colspan="2">Deskripsi Kegiatan</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Lokasi</th>
                    <th>Jenis Kegiatan</th>
                    <th>Penanggung Jawab</th>
                    <th>Peserta</th>
                    <th>Nara Hubung</th>
                    <th>Penyelenggara</th>
                    <th>Jenis Karyawan</th>
                    <th>Jurusan</th>
                    <th>Prodi</th>
                    <th>Unit</th>
                    <th>Waktu Kegiatan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody style="background-color: white; font-size: 14px; color:black;">
                <?php if (count($kegiatan) > 0): ?>
                    <?php $no = 1 + (5 * ($pager->getCurrentPage('pejabat/tbl_kegiatan') - 1)); ?>
                    <?php foreach ($kegiatan as $index => $k): ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 100px;"><?= esc($k['nama_kegiatan']) ?></td>
                            <td class="text-center">
                                <?php if (!empty($k['poster'])): ?>
                                    <img src="<?= base_url('assets/images/' . $k['poster']) ?>" alt="Poster" style="width: 100px; height: auto;">
                                <?php else: ?>
                                    <span class="text-muted">Tidak ada poster</span>
                                <?php endif; ?>
                            </td>
                            <td colspan="2" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 500px;">
                                <?= esc($k['deskripsi']) ?>
                            </td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px;"><?= esc($k['tanggal_mulai']) ?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px;"><?= esc($k['tanggal_selesai']) ?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px;"><?= esc($k['lokasi']) ?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px;"><?= esc($k['jenis_kegiatan']) ?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 250px;"><?= esc($k['penanggung_jawab']) ?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px;"><?= esc($k['peserta']) ?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px;"><?= esc($k['nara_hubung']) ?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px;"><?= esc($k['penyelenggara']) ?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px;"><?= esc($k['jenis_karyawan']) ?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px;"><?= esc($k['nama_jurusan'] ?? 'Tidak Ada') ?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px;"><?= esc($k['nama_prodi'] ?? 'Tidak Ada') ?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px;"><?= esc($k['nama_unit'] ?? 'Tidak Ada') ?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px;"><?= esc($k['waktu_kegiatan']) ?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px;">
    <?php 
        // Kondisi untuk menentukan kelas badge
        if ($k['status'] == 'pending') {
            $badgeClass = 'bg-warning p-1'; // Kuning
        } elseif ($k['status'] == 'sudah disetujui') {
            $badgeClass = 'bg-success p-1'; // Hijau
        } elseif ($k['status'] == 'sedang dilaksanakan') {
            $badgeClass = 'bg-primary p-1'; // Biru
        } elseif ($k['status'] == 'sudah selesai') {
            $badgeClass = 'bg-dark text-white p-1'; // Abu-abu
        } else {
            $badgeClass = 'bg-dark text-white'; // Default
        }
    ?>
    <span class="badge <?= $badgeClass ?>"><?= esc($k['status']) ?></span>
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
        <div>
            <?= $pager->links('kegiatan', 'pagination_temp') ?>
        </div>
        <a href="<?= base_url('/dashboard/pejabat/download-pdf') ?>" class="btn btn-danger">Download PDF</a>
        <a href="<?= base_url('/dashboard/pejabat/download-excel') ?>" class="btn btn-success btn-sm">Download Excel</a>
    </div>
</div>
</div>
<?= $this->endSection(); ?>
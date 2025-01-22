<?= $this->extend('template/templatePembuat'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 font-weight-bold text-blue">Halaman Kegiatan</h1>
</div>

<div class="container-fluid">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success mt-3">
            <?= session()->getFlashdata('success'); ?>
        </div>
    <?php endif; ?>

    <!-- Form Pencarian -->
    <form action="/dashboard/pembuat/kegiatan" method="get" class="mb-3">
        <div class="input-group">
            <input type="text" name="keyword" class="form-control bg-white shadow-sm border-0 small" placeholder="Cari data kegiatan..." value="<?= $keyword ?? ''; ?>">
            <button type="submit" class="btn btn-primary">Cari</button>
            <a href="/dashboard/pembuat/kegiatan" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table shadow-sm">
            <thead style="background-color: #07294d; color: white; font-size: 14px; text-align:center;">
                <tr>
                    <th>No</th>
                    <th>Nama Kegiatan</th>
                    <th>Poster</th>
                    <th>Video</th>
                    <th colspan="2">Deskripsi Kegiatan</th> <!-- Mengambil 2 kolom -->
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Lokasi</th>
                    <th>Jenis Kegiatan</th>
                    <th>Penanggung Jawab</th>
                    <th>Peserta</th>
                    <th>Nara Hubung</th>
                    <th>Penyelenggara</th>
                    <th>Nama Pembuat</th>
                    <th>Jenis Karyawan</th>
                    <th>Jurusan</th>
                    <th>Prodi</th>
                    <th>Unit</th>
                    <th>Waktu Kegiatan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                    <th>S/T</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody style="background-color: white; font-size: 14px; color:black;">
                <?php if (count($kegiatan) > 0): ?>
                    <?php $no = 1 + (5 * ($pager->getCurrentPage('kegiatan') - 1)); ?>
                    <?php foreach ($kegiatan as $index => $k): ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px;"><?= esc($k['nama_kegiatan']) ?></td>
                            <td class="text-center">
                                <?php if (!empty($k['poster'])): ?>
                                    <img src="<?= base_url('assets/images/' . $k['poster']) ?>" alt="Poster" style="width: 200px; height: auto;">
                                <?php else: ?>
                                    <span class="text-muted">Tidak ada poster</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
    <?php if (!empty($k['video'])): ?>
        <video width="200" height="auto" controls>
            <source src="<?= base_url('assets/videos/' . $k['video']) ?>" type="video/mp4">
        </video>
    <?php else: ?>
        <p>Video tidak tersedia.</p>
    <?php endif; ?>
</td>

                            <td colspan="2" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 500px;">
                                <?= esc($k['deskripsi']) ?>
                            </td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px; text-align:center;"><?= esc($k['tanggal_mulai']) ?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px; text-align:center;"><?= esc($k['tanggal_selesai']) ?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px; text-align:center;"><?= esc($k['lokasi']) ?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px; text-align:center;"><?= esc($k['jenis_kegiatan']) ?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 250px; text-align:center;"><?= esc($k['penanggung_jawab']) ?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px; text-align:center;"><?= esc($k['peserta']) ?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px; text-align:center;"><?= esc($k['nara_hubung']) ?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px; text-align:center;"><?= esc($k['penyelenggara']) ?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px; text-align:center;"><?= esc($k['nama_lengkap']) ?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px; text-align:center;"><?= esc($k['jenis_karyawan']) ?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px; text-align:center;"><?= esc($k['nama_jurusan']) ?? 'Tidak Ada'?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px; text-align:center;"><?= esc($k['nama_prodi'])  ?? 'Tidak Ada'?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px; text-align:center;"><?= esc($k['nama_unit'])  ?? 'Tidak Ada'?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px; text-align:center;"><?= esc($k['waktu_kegiatan']) ?></td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px;">
    <?php 
        // Kondisi untuk menentukan kelas badge
        if ($k['status'] == 'belum dimulai') {
            $badgeClass = 'bg-warning p-1 text-dark'; // Kuning
        } elseif ($k['status'] == 'sedang dilaksanakan') {
            $badgeClass = 'bg-primary p-1'; // Hijau
        } elseif ($k['status'] == 'sudah selesai') {
            $badgeClass = 'bg-success p-1'; // Biru
        } else {
            $badgeClass = 'bg-danger text-white'; // Default
        }
    ?>
    <span class="badge <?= $badgeClass ?>"><?= esc($k['status']) ?></span>
</td>

                            <td class="text-center">
                                <div class="d-flex justify-content-around">
                                    <a href="/dashboard/pembuat/edit/<?= esc($k['id_kegiatan']) ?>" class="btn btn-success btn-sm me-2">Edit</a>
                                    <a href="/dashboard/pembuat/delete/<?= esc($k['id_kegiatan']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin Ingin Menghapus data ini?')">Hapus</a>
                                </div>
                            </td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 150px;">
    <?php 
        // Kondisi untuk menentukan kelas badge
        if ($k['disetujui'] == 'pending') {
            $badgeClass = 'bg-warning p-1 text-dark'; // Kuning
        } elseif ($k['disetujui'] == 'disetujui') {
            $badgeClass = 'bg-success p-1'; // Hijau
        } elseif ($k['disetujui'] == 'ditolak') {
            $badgeClass = 'bg-danger p-1'; // Biru
        } else {
            $badgeClass = 'bg-danger text-white'; // Default
        }
    ?>
    <span class="badge <?= $badgeClass ?>"><?= esc($k['disetujui']) ?></span>
</td>
                            <td>
                <?php if ($k['disetujui'] === 'ditolak'): ?>
                    <button class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#keteranganModal<?= $k['id_kegiatan'] ?>">Lihat Alasan</button>

                    <!-- Modal -->
                    <div class="modal fade" id="keteranganModal<?= $k['id_kegiatan'] ?>" tabindex="-1" aria-labelledby="keteranganModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="keteranganModalLabel">Alasan Penolakan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <?= $k['keterangan'] ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    Tidak ada keterangan
                <?php endif; ?>
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
        <div class="d-flex justify-content-between align-items-center">
            <a href="<?= base_url('kegiatan/download-pdf') ?>" class="btn btn-danger btn-sm m-2">Download PDF</a>
            <a href="<?= base_url('kegiatan/download-excel') ?>" class="btn btn-success btn-sm">Download Excel</a>
        </div>
        <div>
            <?= $pager->links('kegiatan', 'pagination_temp') ?>
        </div>


    </div>

</div>
</div>
<?= $this->endSection(); ?>
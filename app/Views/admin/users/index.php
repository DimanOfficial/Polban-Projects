<?= $this->extend('template/template'); ?>

<?= $this->section('content'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-blue">Data Users</h1>
</div>
<div class="container-fluid">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success mt-3">
            <?= session()->getFlashdata('success'); ?>
        </div>
    <?php endif; ?>
    <div class="container-fluid mb-4">
        <form action="/dashboard/users" method="get">
            <div class="input-group">
                <input type="text" name="keyword" class="form-control bg-white shadow-sm border-0 small" placeholder="Cari berdasarkan Username atau Email" value="<?= $keyword ?? ''; ?>">
                <button class="btn btn-primary" type="submit">Cari</button>
                <a href="/dashboard/users" class="btn btn-secondary">Reset</a>
            </div>
        </form>
        </div>
    <div class="table-responsive">
    <table class="table shadow-sm">
    <thead style="background-color: #07294d; color: white; font-size: 12px; text-align: center;">
        <tr>
            <th>No</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>S/T</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody style="background-color: white; color: black; font-size: 14px;">
        <?php if (count($users) > 0): ?>
            <?php $no = 1 + (5 * ($pager->getCurrentPage('users') - 1)); ?>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $u['username']; ?></td>
                    <td><?= $u['email']; ?></td>
                    <td><?= $u['role']; ?></td>
                    <td><?= $u['status']; ?></td>
                    <td>
                            <?php if ($u['status'] === 'Menunggu Persetujuan'): ?>
                                <button class="btn btn-success approve-btn" data-id="<?= $u['id_users'] ?>">Setujui</button>
                                <button class="btn btn-danger reject-btn" data-id="<?= $u['id_users'] ?>">Tolak</button>
                            <?php elseif ($u['status'] === 'Aktif'): ?>
                                <button class="btn btn-warning toggle-status-btn btn-sm" data-id="<?= $u['id_users'] ?>" data-status="Non Aktif">Non Aktifkan</button>
                            <?php else: ?>
                                <button class="btn btn-success toggle-status-btn btn-sm" data-id="<?= $u['id_users'] ?>" data-status="Aktif">Aktifkan</button>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="/dashboard/users/delete/<?= $u['id_users']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                        </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">Data tidak ditemukan.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</div>
<div>
    <?= $pager->links('users', 'pagination_temp') ?>
</div>
</div>
</div>

<!-- Loading Overlay -->
<div id="loading-overlay" style="display: none;">
    <div id="loading">
        <div class="loading-box">
            <img src="<?= base_url('assets/images/Animation.gif') ?>" alt="loading..." width="100" />
            <p class="loading-text">Sedang Diproses...</p>
        </div>
    </div>
</div>




<div class="modal" id="rejectModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="rejectForm" method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Pendaftaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <textarea name="reason" class="form-control" placeholder="Alasan penolakan" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $('.approve-btn').on('click', function () {
    const userId = $(this).data('id');
    if (confirm('Apakah Anda yakin ingin menyetujui akun ini?')) {
        // Tampilkan Overlay dan GIF
        $('#loading-overlay').show();

        $.ajax({
            url: '/dashboard/admin/users/approve',
            type: 'POST',
            data: { id_users: userId },
            success: function (response) {
                alert(response.message);
                // Sembunyikan Overlay dan GIF setelah proses selesai
                $('#loading-overlay').hide();
                location.reload();
            },
            error: function () {
                alert('Terjadi kesalahan saat memproses permintaan.');
                // Sembunyikan Overlay dan GIF jika terjadi error
                $('#loading-overlay').hide();
            }
        });
    }
});



    // Tombol Tolak
    $('.reject-btn').on('click', function () {
        const userId = $(this).data('id');
        const reason = prompt('Tulis alasan penolakan:');
        if (reason) {
            $.ajax({
                url: '/dashboard/admin/users/reject',
                type: 'POST',
                data: { id_users: userId, reason: reason },
                success: function (response) {
                    alert(response.message);
                    location.reload();
                },
                error: function () {
                    alert('Terjadi kesalahan saat memproses permintaan.');
                }
            });
        }
    });

    // Tombol Non Aktifkan/Aktifkan
    $('.toggle-status-btn').on('click', function () {
        const userId = $(this).data('id');
        const newStatus = $(this).data('status');
        if (confirm(`Apakah Anda yakin ingin mengubah status menjadi ${newStatus}?`)) {
            $.ajax({
                url: '/dashboard/admin/users/toggleStatus',
                type: 'POST',
                data: { id_users: userId, status: newStatus },
                success: function (response) {
                    alert(response.message);
                    location.reload();
                },
                error: function () {
                    alert('Terjadi kesalahan saat memproses permintaan.');
                }
            });
        }
    });
</script>




<?= $this->endSection(); ?>
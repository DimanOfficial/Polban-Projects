<?= $this->extend('template/templatePembuat'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <h1>Dashboard Pembuat</h1>
    <!-- Pesan Flash Selamat Datang -->
<?php if (isset($success)): ?>
            <div class="alert alert-success">
                <?= $success ?>
            </div>
        <?php endif; ?>
        <p style="font-size: 24px; font-weight: bold; color: #333;">
    Selamat datang di halaman dashboard  admin,<?= esc($username); ?>
</p>
</div>
</div>
<?= $this->endSection(''); ?>
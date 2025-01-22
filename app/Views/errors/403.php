<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-danger text-center">
            <h1>403 - Unauthorized</h1>
            <p>Anda tidak memiliki akses untuk mengakses halaman ini.</p>
            <a href="<?= base_url(getRedirectUrl()) ?>" class="btn btn-primary">Kembali ke Halaman Utama</a>
        </div>
    </div>
</body>
</html>

<?php
// Fungsi untuk menentukan URL redirect berdasarkan role
function getRedirectUrl()
{
    $role = session()->get('role');
    if ($role === 'Admin') {
        return 'dashboard/admin';
    } elseif ($role === 'Pembuat') {
        return 'dashboard/pembuat';
    } elseif ($role === 'Pejabat') {
        return 'dashboard/pejabat';
    }

    // Default ke halaman login jika role tidak ditemukan
    return '/login';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        .reset-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .reset-card {
            background-color: #fff;
            border: 1px solid #dadce0;
            border-radius: 12px;
            box-shadow: 0px 1px 3px rgba(60, 64, 67, 0.3);
            padding: 30px 40px;
            width: 100%;
            max-width: 400px;
        }

        .reset-card h3 {
            font-size: 24px;
            color: #202124;
            font-weight: 400;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-label {
            font-size: 14px;
            color: #5f6368;
            font-weight: 500;
        }

                .form-control {
            border: 1px solid #dadce0;
            border-radius: 4px;
            height: 40px; /* Ukuran yang lebih pas */
            font-size: 14px; /* Ukuran font yang lebih kecil */
            padding: 8px; /* Padding lebih kecil */
            margin-bottom: 20px;
            width: 100%; /* Menjamin input sejajar */
        }


        .btn-primary {
            background-color: #1a73e8;
            border-color: #1a73e8;
            color: #fff;
            border-radius: 4px;
            height: 48px;
            font-size: 14px;
            font-weight: 500;
            display: block;
            width: 50%; /* Menjadikan tombol lebih kecil */
            margin: 0 auto; /* Memastikan tombol berada di tengah */
        }

        .btn-primary:hover {
            background-color: #1666d0;
            border-color: #1666d0;
        }

        .alert {
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="reset-card">
            <h3>Reset Password</h3>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php elseif (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('/forgot-password/process-reset-password') ?>" method="post">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="password" class="form-label">Password Baru</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password baru" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Konfirmasi password baru" required>
                </div>
                <button type="submit" class="btn btn-primary">Reset Password</button>
            </form>
        </div>
    </div>

    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>

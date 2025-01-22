<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Arial', sans-serif;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-card {
            background-color: #fff;
            border: 1px solid #dadce0;
            border-radius: 12px;
            box-shadow: 0px 1px 3px rgba(60, 64, 67, 0.3);
            padding: 30px 40px;
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        .login-card h3 {
            font-size: 24px;
            color: #202124;
            font-weight: 400;
            margin-bottom: 10px;
        }

        .login-card p {
            font-size: 14px;
            color: #5f6368;
            margin-bottom: 30px;
        }

        .form-control {
            border: 1px solid #dadce0;
            border-radius: 4px;
            height: 48px;
            font-size: 16px;
            padding: 10px;
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #1a73e8;
            border-color: #1a73e8;
            color: #fff;
            border-radius: 4px;
            height: 48px;
            font-size: 14px;
            font-weight: 500;
            padding: 10px 16px;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #1666d0;
            border-color: #1666d0;
        }

        .text-link {
            color: #1a73e8;
            text-decoration: none;
            font-size: 14px;
        }

        .text-link:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 4px;
            font-size: 14px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <h3>Verifikasi OTP</h3>
            <p>Gunakan kode OTP yang telah dikirim ke email Anda.</p>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php elseif (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('/forgot-password/process-otp') ?>" method="post">
                <?= csrf_field() ?>
                <input type="text" class="form-control" id="otp" name="otp" placeholder="Masukkan kode OTP" required>
                <button type="submit" class="btn btn-primary">Selanjutnya</button>
            </form>

            <div class="mt-3">
                <a href="#" class="text-link">Lupa kode OTP?</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

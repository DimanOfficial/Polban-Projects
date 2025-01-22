<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Polban</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            width: 400px;
            padding: 40px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        .logo img {
            height: 40px;
            margin-right: 10px;
        }
        .logo span {
            font-size: 24px;
            font-weight: 500;
            color: #202124;
        }
        h3 {
            font-size: 24px;
            font-weight: 400;
            margin-bottom: 10px;
        }
        p {
            font-size: 14px;
            color: #5f6368;
            margin-bottom: 20px;
        }
        .form-control {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            border: 1px solid #dadce0;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .btn {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            background-color: #1a73e8;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #155bb5;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #5f6368;
        }
        .footer a {
            color: #1a73e8;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9f/Logo_Politeknik_Negeri_Bandung.svg/2048px-Logo_Politeknik_Negeri_Bandung.svg.png" alt="Polban Logo">
            <span>Polban</span>
        </div>
        <h3>Login</h3>
        <p>Gunakan Akun Polban Anda</p>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php elseif (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('/forgot-password/send-reset-link') ?>" method="post">
            <?= csrf_field() ?>
            <input type="email" class="form-control" id="email" name="email" placeholder="Email atau nomor telepon" required>
            <button type="submit" class="btn">Selanjutnya</button>
        </form>

        <div class="footer">
            <a href="#">Lupa email?</a><br>
            <span>Bukan komputer Anda? Gunakan mode Tamu untuk login secara pribadi. <a href="#">Pelajari lebih lanjut</a></span>
            <br><br>
            <a href="#">Buat akun</a>
        </div>
    </div>
</body>
</html>

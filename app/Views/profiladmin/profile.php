<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Manajemen Kampus - Profil</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #1E3A8A, #4B8B9C);
            /* Blue gradient background */
            font-family: 'Roboto', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .container {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.15);
            padding: 40px;
            max-width: 800px;
            width: 100%;
            transition: all 0.4s ease;
            overflow: hidden;
            position: relative;
        }

        .container:hover {
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            color: #333;
            font-weight: 600;
            margin-bottom: 40px;
            font-size: 36px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .profile-header {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .profile-header img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid #1E3A8A;
            /* Blue border */
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .profile-header img:hover {
            transform: scale(1.1);
            /* Zoom effect on hover */
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .profile-info {
            padding: 20px;
            margin-bottom: 20px;
            background: #f8f9fa;
            border-radius: 15px;
            box-shadow: 0 3px 20px rgba(0, 0, 0, 0.1);
        }

        .profile-info p {
            font-size: 18px;
            margin: 10px 0;
            color: #555;
        }

        .profile-info strong {
            color: #1E3A8A;
            font-weight: 600;
        }

        .btn-primary {
            background-color: #1E3A8A;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #264E88;
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-secondary {
            background-color: transparent;
            color: #1E3A8A;
            border: 2px solid #1E3A8A;
            padding: 12px 30px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #1E3A8A;
            color: white;
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .alert-success {
            background-color: #28a745;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 10px 0;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-wrapper {
            text-align: center;
            margin-top: 30px;
        }

        .btn-wrapper .btn {
            width: 200px;
        }

        /* Animation for profile loading */
        .fadeIn {
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .profile-info .fa {
            margin-right: 8px;
        }

        .profile-info .info-item {
            margin-bottom: 15px;
        }

        .img-thumbnail {
            width: 300px;
            height: auto;
            border-radius:50%;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <!-- Tombol Kembali ke Dashboard -->
        <a href="admin" class="btn btn-secondary btn-back">&larr; Kembali</a>

        <!-- Header Profil -->
        <h2>Profil Saya</h2>

        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success text-center"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <!-- Foto Profil -->
        <div class="text-center">
            <img src="<?= base_url($user['profile_pic'] ?? 'uploads/default.png') ?>" alt="Profile Picture" class="img-thumbnail">
        </div>

        <!-- Informasi Akun -->
        <div class="mt-3">
            <p><strong>Username:</strong> <?= $user['username'] ?></p>
            <p><strong>Tanggal Akun Dibuat:</strong> <?= date('d M Y', strtotime($user['created_at'])) ?></p>
            <p><strong>Alamat:</strong> <?= $user['address'] ?? 'Belum diisi' ?></p>
        </div>

        <!-- Tombol Edit -->
        <div class="text-center mt-4">
            <a href="/dashboard/profiladmin/edit" class="btn btn-primary">Edit Profil</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Inisialisasi Tooltip
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Inisialisasi Popover
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    </script>
</body>

</html>
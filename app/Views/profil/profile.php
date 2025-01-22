<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Halaman Profil Pembuat</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
         .image-box {
            width: 200px;
            height: auto;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <!-- Tombol Kembali ke Dashboard -->
        <a href="pembuat" class="btn btn-secondary btn-back">&larr; Kembali</a>

        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success text-center"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="box mt-5">
            <h2>Profil Pengguna</h2>
            <table class="table">
                <!-- Foto Profil -->
                <div class="text-center">
                <img src="<?= base_url($profile['profile_pic'] ?? 'assets/images/default.png') ?>" alt="Profile Picture" class="image-box">
                 </div>
                <tbody>
                    <tr>
                        <th>Username</th>
                        <td><?= esc($profile['username']) ?></td>
                    </tr>
                    <tr>
                        <th>Nama Lengkap</th>
                        <td><?= esc($profile['nama_lengkap']) ?></td>
                    </tr>
                    <?php if (isset($profile['nim'])): ?>
                        <tr>
                            <th>NIM</th>
                            <td><?= esc($profile['nim']) ?></td>
                        </tr>
                        <tr>
                            <th>Jurusan</th>
                            <td><?= esc($profile['jurusan']) ?></td>
                        </tr>
                        <tr>
                            <th>Prodi</th>
                            <td><?= esc($profile['prodi']) ?></td>
                        </tr>
                    <?php endif; ?>

                    <?php if (isset($profile['nip'])): ?>
                        <tr>
                            <th>NIP</th>
                            <td><?= esc($profile['nip']) ?></td>
                        </tr>
                        <?php if (isset($profile['jurusan'])): ?>
                            <tr>
                                <th>Jurusan</th>
                                <td><?= esc($profile['jurusan']) ?></td>
                            </tr>
                            <tr>
                                <th>Prodi</th>
                                <td><?= esc($profile['prodi']) ?></td>
                            </tr>
                        <?php elseif (isset($profile['unit'])): ?>
                            <tr>
                                <th>Unit</th>
                                <td><?= esc($profile['unit']) ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>



        <!-- Tombol Edit -->
        <div class="text-center mt-4">
            <a href="/dashboard/profil/edit" class="btn btn-primary">Edit Profil</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
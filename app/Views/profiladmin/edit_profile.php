<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Profil</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f8ff;
            font-family: 'Poppins', Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            position: relative;
            animation: fadeIn 1.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://img.icons8.com/ios/452/abstract-shapes.png') no-repeat center center;
            background-size: 50%;
            opacity: 0.1;
            z-index: -1;
            animation: backgroundMove 5s infinite linear;
        }

        /* Animasi pergerakan background */
        @keyframes backgroundMove {
            0% {
                background-position: 0 0;
            }
            100% {
                background-position: 100% 100%;
            }
        }

        .card {
            border: none;
            border-radius: 15px;
            background: #ffffff;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 650px;
            overflow: hidden;
            transform: scale(0.8);
            animation: cardScale 0.8s ease-out forwards;
        }

        @keyframes cardScale {
            from {
                transform: scale(0.8);
            }
            to {
                transform: scale(1);
            }
        }

        .card-header {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            padding: 40px 20px;
            text-align: center;
            border-radius: 15px 15px 0 0;
            animation: headerBounce 1s ease-in-out;
        }

        @keyframes headerBounce {
            0%, 100% {
                transform: translateY(-10px);
            }
            50% {
                transform: translateY(0);
            }
        }

        .card-header h2 {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            letter-spacing: 1px;
            animation: textFadeIn 1s ease-out;
        }

        /* Animasi untuk teks judul */
        @keyframes textFadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-label {
            font-size: 16px;
            font-weight: 600;
            color: #495057;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 18px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
            margin-bottom: 18px;
            animation: inputFocus 0.5s ease-out forwards;
        }

        @keyframes inputFocus {
            from {
                border-color: #ddd;
            }
            to {
                border-color: #2575fc;
            }
        }

        .form-control:focus {
            border-color: #2575fc;
            box-shadow: 0 0 10px rgba(37, 117, 252, 0.4);
            animation: pulse 0.5s ease-out infinite;
        }

        /* Animasi pulse saat input difokuskan */
        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 5px rgba(37, 117, 252, 0.5);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 10px rgba(37, 117, 252, 0.7);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 0 5px rgba(37, 117, 252, 0.5);
            }
        }

        .btn-primary {
            background-color: #2575fc;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 30px;
            font-weight: 600;
            text-transform: uppercase;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(37, 117, 252, 0.2);
            animation: buttonHover 0.4s ease-out forwards;
        }

        @keyframes buttonHover {
            0% {
                transform: scale(1);
                box-shadow: 0 4px 8px rgba(37, 117, 252, 0.2);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 6px 12px rgba(37, 117, 252, 0.3);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 4px 8px rgba(37, 117, 252, 0.2);
            }
        }

        .btn-primary:hover {
            background-color: #6a11cb;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(37, 117, 252, 0.3);
        }

        .btn-danger {
            background-color: #e63946;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 30px;
            font-weight: 600;
            text-transform: uppercase;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(230, 57, 70, 0.2);
        }

        .btn-danger:hover {
            background-color: #d32f2f;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(211, 47, 47, 0.3);
        }

        .icon {
            margin-right: 10px;
            font-size: 18px;
            color: #6c757d;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            animation: buttonGroupAnim 0.5s ease-out forwards;
        }

        @keyframes buttonGroupAnim {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-body {
            padding: 30px;
        }

        .card-footer {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: center;
            font-size: 14px;
            color: #888;
            border-radius: 0 0 15px 15px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">

        <h2>Edit Profil</h2>
        <form action="/dashboard/profiladmin/update" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="profile_pic" class="form-label">Foto Profil <i class="bi bi-info-circle text-danger" 
           data-bs-toggle="tooltip" 
           data-bs-placement="top" 
           title="Ukuran gambar wajib 1:1 atau persegi"></i>
</label>
                <input type="file" name="profile_pic" class="form-control" id="profile_pic">
            </div>

            <div class="form-group">
                <label for="address" class="form-label">Alamat</label>
                <textarea name="address" id="address" class="form-control" placeholder="Masukkan alamat lengkap"><?= old('address', $user['address']) ?></textarea>
                <small class="text-danger"><?= session('validation.address') ?></small>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password Baru</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Isi hanya jika ingin mengganti password">
                <small class="text-danger"><?= session('validation.password') ?></small>
            </div>

            <!-- Tombol Simpan Perubahan dan Batal -->
            <div class="text-center button-group mt-4">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="/dashboard/profiladmin" class="btn btn-danger">Batal</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Inisialisasi popover
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    </script>
</body>
</html>

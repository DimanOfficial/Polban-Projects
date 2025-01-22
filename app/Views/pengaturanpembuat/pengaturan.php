<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Global Styles */
        body {
            background: linear-gradient(135deg, #6c63ff, #3b3f99);
            color: #fff;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            min-height: 100vh;
            padding: 30px 15px;
        }
        body.dark {
            background: #121212;
            color: #ddd;
        }

        h2 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 30px;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
        }

        .card {
            border: none;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .card.dark {
            background: rgba(30, 30, 30, 0.9);
            color: #fff;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.25);
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .btn {
            border-radius: 30px;
            padding: 12px 30px;
        }

        /* Dark Mode Switch */
        .switch-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }
        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 25px;
        }
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 25px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 19px;
            width: 19px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .slider {
            background-color: #6c63ff;
        }
        input:checked + .slider:before {
            transform: translateX(24px);
        }

        /* Progress Bar */
        .progress-container {
            margin: 20px 0;
        }
        .progress {
            height: 20px;
            border-radius: 10px;
            overflow: hidden;
            background-color: #e9ecef;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .progress-bar {
            background: linear-gradient(135deg, #6c63ff, #3b3f99);
            transition: width 0.3s ease;
        }

        /* Footer */
        footer {
            text-align: center;
            margin-top: 30px;
            color: #ddd;
            font-size: 0.9rem;
        }
        footer a {
            color: #6c63ff;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        footer a:hover {
            color: #fff;
        }

        /* Button Kembali */
        .btn-back {
            border-radius: 50%;
            padding: 15px;
            font-size: 1.5rem;
            background-color: #6c63ff;
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .btn-back:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        /* Position the back button */
        .btn-back-container {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 999;
        }

        /* Log Out Button - Enhanced Styles */
        .btn-logout {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50px;
            padding: 12px 30px;
            font-size: 1.4rem;
            background: linear-gradient(145deg, #f44336, #d32f2f);
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
            margin-top: 20px;
        }
        .btn-logout:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            background: linear-gradient(145deg, #d32f2f, #f44336);
        }
        .btn-logout i {
            margin-right: 10px;
        }

    </style>
</head>
<body>
    <!-- Dark Mode Switch -->
    <div class="switch-container">
        <label class="switch">
            <input type="checkbox" id="darkModeToggle">
            <span class="slider"></span>
        </label>
        <span class="ms-3">Mode Gelap</span>
    </div>

    <!-- Main Content -->
    <div class="container">
        <h2 class="text-center">
            <i class="fa-solid fa-cogs text-primary"></i> Pengaturan
        </h2>

        <!-- Progress Bar -->
        <div class="progress-container text-center">
            <span>Profil Anda 70% Lengkap</span>
            <div class="progress mt-2">
                <div class="progress-bar" role="progressbar" style="width: 70%;"></div>
            </div>
        </div>

        <!-- Card Profile -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fa-solid fa-user-circle text-primary"></i> Profil</h5>
                <p class="card-text">Atur informasi pribadi dan foto profil Anda.</p>
                <a href="profil" class="btn btn-primary">Kelola Profil</a>
            </div>
        </div>

        <!-- Card Notifications -->
        <!-- <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fa-solid fa-bell text-warning"></i> Notifikasi</h5>
                <p class="card-text">Kelola pengaturan notifikasi Anda.</p>
                <a href="/pengaturan/notifikasi" class="btn btn-warning">Kelola Notifikasi</a>
            </div>
        </div> -->

        <!-- Card Security -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fa-solid fa-lock text-danger"></i> Keamanan</h5>
                <p class="card-text">Atur kata sandi dan autentikasi keamanan Anda.</p>
                <a href="/forgot-password" class="btn btn-danger">Kelola Keamanan</a>
            </div>
        </div>

        <!-- Card Log Aktivitas -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fa-solid fa-clock text-info"></i> Log Aktivitas</h5>
                <p class="card-text">Lihat dan kelola catatan aktivitas pengguna di sistem.</p>
                <a href="/logs" class="btn btn-info">Lihat Log Aktivitas</a>
            </div>
        </div>

        <!-- Log Out Button -->
        <a href="/logout" class="btn-logout"><i class="fa-solid fa-power-off"></i> Log Out</a>
    </div>

    <!-- Tombol Kembali (Posisi Fixed) -->
    <div class="btn-back-container">
        <button onclick="window.history.back();" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i>
        </button>
    </div>

    <!-- Footer -->
    <footer>
        <p>© 2025 Manajemen Kampus | Ikuti kami di 
            <a href="#" target="_blank"><i class="fab fa-facebook"></i> Facebook</a> |
            <a href="#" target="_blank"><i class="fab fa-instagram"></i> Instagram</a>
        </p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        darkModeToggle.addEventListener('change', function () {
            document.body.classList.toggle('dark', this.checked);
            document.querySelectorAll('.card').forEach(card => card.classList.toggle('dark', this.checked));
        });
    </script>
</body>
</html>

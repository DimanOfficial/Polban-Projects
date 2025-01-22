<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<style>
     body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .logo-orange {
    background-color: #F39200; /* Hex */
    color: #fff; /* Warna teks putih */
}

        .content {
            display: flex;
            width: 100%;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .text-section {
            flex: 1;
            padding: 40px;
        }

        .text-section h1 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #333;
        }

        .text-section p {
            font-size: 1rem;
            line-height: 1.6;
            color: #666;
            margin-bottom: 20px;
        }

        .text-section .btn {
            padding: 10px 20px;
            background-color: #0066ff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
        }

        .text-section .btn:hover {
            background-color: #004ecc;
        }

        .image-section {
            flex: 1;
            background: url('https://upload.wikimedia.org/wikipedia/commons/3/30/Pantai_Kuta_Bali.jpg') no-repeat center center/cover;
            clip-path: polygon(20% 0, 100% 0, 100% 100%, 0 100%);
        }
    * {
        font-family: Roboto;
    }

    .blue-text {
        color: #07294d;
    }

    .bg-blue{
        background:#07294d;
    }

    .blue-text:hover {
        color: #07294d;
    }

    .btn1 {
        background-color: #07294d;
        font-weight: 500;
        color: white;
        transition: .3s;
    }

    .btn1:hover {
        background-color: white;
        font-weight: 700;
        color: #07294d;
        transition: .3s;
        box-shadow: rgba(0, 0, 0, 0.15) 0px 2px 8px;
        border: 1px solid #07294d;
    }

    p {
        font-size: 20px;
    }

    .card {
        height: 350px;
    }

    thead{
        background:red;
    }

    .container hr {
        max-width: 100px;
        margin-bottom: 50px;
        height: 5px;
        background-color: #07294d;
        opacity: 100%;
    }

    .modal-body ul li {
        font-size: 12px;
    }

    .modal-lg {
        max-width: 80%;
        /* Perlebar modal */
    }

    .row,
    .text {
        display: flex;
        flex-wrap: wrap;
    }

    #kegiatanImage {
        max-width: 100%;
        /* Pastikan gambar responsif */
        height: auto;
        /* Sesuaikan tinggi otomatis */
    }

    .list-group-item {
        font-size: 0.9rem;
        /* Atur ukuran font agar lebih rapi */
    }

    
</style>

<body>

    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg bg-white shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand fw-bolder blue-text" href="/">
                    <img src="/image/logo polban.png" alt="logo" width="50px" height="50px">
                    Polban Event II
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 justify-content-center">
                        <li class="nav-item">
                            <a class="nav-link fw-bold blue-text" href="/"><i class="bi bi-houses-fill"></i> Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold blue-text" href="/pengunjung/rincian"><i class="bi bi-journal-bookmark-fill"></i></i> Rincian Kegiatan</a>
                        </li>
                    </ul>
                    <a href="/login" class="btn btn1">Buat Kegiatan</a>
                </div>
            </div>
        </nav>

        <?= $this->renderSection('conn'); ?>

        <footer class="bg-blue text-white mt-5">
    <div class="container py-4">
        <div class="row">
            <!-- Kolom 1 -->
            <div class="col-md-4">
                <h5>Tentang Kami</h5>
                <p>
                    Platform ini bertujuan untuk menyediakan informasi terkini tentang kegiatan dan acara yang berlangsung. 
                    Kami selalu berusaha memberikan informasi yang akurat dan up-to-date.
                </p>
            </div>
            <!-- Kolom 2 -->
            <div class="col-md-4">
                <h5>Kontak</h5>
                <ul class="list-unstyled">
                    <li>Email: polbanofficials@gmail.com</li>
                    <li>Telepon: +62 812 3456 7890</li>
                    <li>Alamat: Jl. Gegerkalong, Desa Ciwaruga. Kec. Parongpong</li>
                </ul>
            </div>
            <!-- Kolom 3 -->
            <div class="col-md-4">
                <h5>Ikuti Kami</h5>
                <div class="d-flex">
                    <a href="#" class="text-white me-3" style="font-size: 1.5rem;"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white me-3" style="font-size: 1.5rem;"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white me-3" style="font-size: 1.5rem;"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="text-white" style="font-size: 1.5rem;"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
        </div>
        <hr class="my-3">
        <div class="text-center">
            <p class="mb-0">&copy; 2024 Polban Event II. All Rights Reserved.</p>
        </div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('limitDropdown').addEventListener('change', function () {
        const limit = this.value;
        fetch(`/activities/getData?limit=${limit}`)
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('cardContainer');
                container.innerHTML = '';
                data.activities.forEach(activity => {
                    container.innerHTML += `
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <img src="${activity.image}" class="card-img-top" alt="${activity.title}">
                                <div class="card-body">
                                    <h5 class="card-title">${activity.title}</h5>
                                    <p class="card-text">${activity.description}</p>
                                    <a href="${activity.link}" class="btn btn-primary">Lihat Selengkapnya</a>
                                </div>
                            </div>
                        </div>`;
                });
            });
    });
</script>

</body>

</html>
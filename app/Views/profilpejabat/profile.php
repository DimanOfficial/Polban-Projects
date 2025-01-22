<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Halaman Profil Pejabat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1E3A8A, #4B8B9C);
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
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
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
        }
        .profile-info p {
            font-size: 18px;
            margin: 10px 0;
        }
        .profile-info strong {
            color: #1E3A8A;
        }
        .btn-primary {
            margin-top: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Profil Saya</h2>
        <div class="profile-header text-center">
        <img src="<?= base_url($user['profile_pic'] ?? 'assets/images/default.png') ?>" alt="Profile Picture" class="img-thumbnail" style="object-fit: cover; border-radius: 50%;">
        </div>
        <div class="profile-info">
        <p><strong>Username:</strong> <?= $user['username'] ?? 'Belum diisi' ?></p>
            <p><strong>Nama Lengkap:</strong> <?= $user['nama_lengkap'] ?? 'Belum diisi' ?></p>
            <p><strong>Jabatan:</strong> <?= $user['jabatan'] ?? 'Belum diisi' ?></p>
            <p><strong>Alamat:</strong> <?= $user['address'] ?? 'Belum diisi' ?></p>
            <p><strong>Tanggal Akun Dibuat:</strong> <?= date('d M Y', strtotime($user['created_at'])) ?></p>
            <p><strong>Role:</strong> <?= ucfirst($user['role']) ?></p>
        </div>
        <a href="/dashboard/profilpejabat/edit" class="btn btn-primary">Edit Profil</a>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Edit Profil</h2>
        <form action="/dashboard/profilpejabat/update" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="profile_pic">Foto Profil</label>
                <input type="file" name="profile_pic" class="form-control" id="profile_pic">
            </div>
            <div class="form-group">
                 <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" 
                        value="<?= isset($user['username']) ? $user['username'] : '' ?>" readonly>
            </div>


            <div class="form-group">
                <label for="jabatan">Jabatan</label>
                <input type="text" name="jabatan" id="jabatan" class="form-control" 
                    value="<?= old('jabatan', $user['jabatan']) ?>" required>
            </div>

            <div class="form-group">
                <label for="address">Alamat</label>
                <input type="text" name="address" id="address" class="form-control" 
                    value="<?= old('address', $user['address']) ?>" required>
            </div>


            
            <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
            <a href="/dashboard/profilpejabat" class="btn btn-secondary mt-3">Batal</a>
        </form>
    </div>
</body>
</html>

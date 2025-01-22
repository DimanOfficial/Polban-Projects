<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #f4f6fc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Arial', sans-serif;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .card-header {
            background-color: #07294d;
            color: white;
            font-weight: bold;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .btn-success {
            background: #07294d;
            border: 2px solid transparent;
            transition: all .5s;
        }
        .btn-success:hover {
            background: white;
            color: #07294d;
            border-color: #07294d;
            transition: all .5s;
        }
        .card-footer {
            background: #f8f9fa;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #077aff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center py-3">
                        <h2 class="fw-bold">Create Your Account</h2>
                        <p class="mb-0">Fill in the form below to register</p>
                    </div>
                    <div class="card-body">
                        <!-- Flash Messages -->
                        <?php if (session()->getFlashdata('error')) : ?>
                            <div class="alert alert-danger">
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <!-- Register Form -->
                        <form action="/process-register" method="post">
                            <?= csrf_field() ?>

                            <!-- Username -->
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username" required>
                            </div>

                            <!-- Nama Lengkap -->
                            <div class="mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" placeholder="Masukkan Nama Lengkap" required>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="example@domain.com" required>
                            </div>
                            
                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Create a strong password" required>
                            </div>

                            <!-- Alamat -->
                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat</label>
                                <input type="text" name="address" id="address" class="form-control" placeholder="Masukkan Alamat" required>
                            </div>
                            
                            <!-- Role -->
                            <div class="mb-3">
                                <label for="jenis_users" class="form-label">Jenis User</label>
                                <select name="jenis_users" id="jenis_users" class="form-control" required>
                                    <option value="">-- Pilih jenis_users --</option>
                                    <option value="Mahasiswa">Mahasiswa</option>
                                    <option value="Karyawan">Karyawan</option>
                                </select>
                            </div>

                            <!-- Dynamic Fields -->
                            <div id="dynamic-fields"></div>

                            <!-- Submit -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg">Register</button>
                            </div>
                        </form>

<script>
document.getElementById('jenis_users').addEventListener('change', function () {
    const jenis_users = this.value;
    const dynamicFields = document.getElementById('dynamic-fields');

    dynamicFields.innerHTML = ''; // Clear previous fields

    if (jenis_users === 'Mahasiswa') {
        dynamicFields.innerHTML = `
            <div class="mb-3">
                <label for="nim" class="form-label">NIM</label>
                <input type="text" name="nim" id="nim" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="jurusan" class="form-label">Jurusan</label>
                <select name="jurusan" id="jurusan" class="form-control" required>
                    <option value="">-- Pilih Jurusan --</option>
                    <?php foreach ($jurusan as $j): ?>
                        <option value="<?= $j['id_jurusan'] ?>"><?= $j['nama_jurusan'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="prodi" class="form-label">Prodi</label>
                <select name="prodi" id="prodi" class="form-control" required>
                    <option value="">-- Pilih Prodi --</option>
                </select>
            </div>
        `;

        document.getElementById('jurusan').addEventListener('change', function () {
            const idJurusan = this.value;
            fetch(`/get-prodi/${idJurusan}`).then(res => res.json()).then(data => {
                const prodiDropdown = document.getElementById('prodi');
                prodiDropdown.innerHTML = '<option value="">-- Pilih Prodi --</option>';
                data.forEach(prodi => {
                    prodiDropdown.innerHTML += `<option value="${prodi.id_prodi}">${prodi.nama_prodi}</option>`;
                });
            });
        });
    } else if (jenis_users === 'Karyawan') {
        dynamicFields.innerHTML = `
            <div class="mb-3">
                <label for="nip" class="form-label">NIP</label>
                <input type="text" name="nip" id="nip" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="jenis_karyawan" class="form-label">Jenis Karyawan</label>
                <select name="jenis_karyawan" id="jenis_karyawan" class="form-control" required>
                    <option value="">-- Pilih Jenis Karyawan --</option>
                    <option value="Jurusan">Jurusan</option>
                    <option value="Unit">Unit</option>
                </select>
            </div>
            <div id="additional-fields"></div>
        `;

        document.getElementById('jenis_karyawan').addEventListener('change', function () {
            const jenis = this.value;
            const additionalFields = document.getElementById('additional-fields');
            additionalFields.innerHTML = ''; // Clear previous fields

            if (jenis === 'Jurusan') {
                additionalFields.innerHTML = `
                    <div class="mb-3">
                        <label for="jurusan" class="form-label">Jurusan</label>
                        <select name="jurusan" id="jurusan" class="form-control" required>
                            <option value="">-- Pilih Jurusan --</option>
                            <?php foreach ($jurusan as $j): ?>
                                <option value="<?= $j['id_jurusan'] ?>"><?= $j['nama_jurusan'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="prodi" class="form-label">Prodi</label>
                        <select name="prodi" id="prodi" class="form-control">
                            <option value="">-- Pilih Prodi --</option>
                        </select>
                    </div>
                `;
                document.getElementById('jurusan').addEventListener('change', function () {
                    const idJurusan = this.value;
                    fetch(`/get-prodi/${idJurusan}`).then(res => res.json()).then(data => {
                        const prodiDropdown = document.getElementById('prodi');
                        prodiDropdown.innerHTML = '<option value="">-- Pilih Prodi --</option>';
                        data.forEach(prodi => {
                            prodiDropdown.innerHTML += `<option value="${prodi.id_prodi}">${prodi.nama_prodi}</option>`;
                        });
                    });
                });
            } else if (jenis === 'Unit') {
                additionalFields.innerHTML = `
                    <div class="mb-3">
                        <label for="unit" class="form-label">Unit</label>
                        <select name="unit" id="unit" class="form-control" required>
                            <option value="">-- Pilih Unit --</option>
                            <?php foreach ($unit as $u): ?>
                                <option value="<?= $u['id_unit'] ?>"><?= $u['nama_unit'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                `;
            }
        });
    }
});
</script>

                    </div>
                    <div class="card-footer text-center py-3">
                        <p>Already have an account? <a href="/login">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"></script>
</body>
</html>

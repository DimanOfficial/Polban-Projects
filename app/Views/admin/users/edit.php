<?= $this->extend('template/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid p-5">

 <!-- Menampilkan error jika ada -->
 <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>


    <h1 class="h3 mb-4 text-gray-800">Edit Data Pengguna</h1>
    <form action="/dashboard/users/update/<?= $pengguna['id_users'] ?>" method="post">
        <?= csrf_field(); ?>

        <div class="row bg-white shadow-sm p-4 rounded mb-4">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= $pengguna['username'] ?>">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= $pengguna['email'] ?>">
                </div>

            </div>
                <div class="mb-3">
                    <label for="role" class="form-label">role</label>
                    <select name="role" class="form-control styled-dropdown">
                        <option value="">-- Pilih role --</option>
                        <option value="admin" <?= $pengguna['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="pejabat" <?= $pengguna['role'] == 'pejabat' ? 'selected' : '' ?>>Pejabat</option>
                        <option value="pembuat" <?= $pengguna['role'] == 'pembuat' ? 'selected' : '' ?>>Pembuat</option>
                    </select>
                </div>

            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="/dashboard/users" class="btn btn-secondary">Kembali</a>
        </div>

        
    </form>
</div>

<style>
    .styled-dropdown {
        border: 1px solid #ced4da;
        border-radius: 5px;
        padding: 10px;
        transition: all 0.3s;
    }

    .styled-dropdown:focus {
        border-color: #80bdff;
        background-color: #ffffff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    .costum-area {
        width: 100%;
        resize: none;
    }
</style>
<?= $this->endSection(); ?>
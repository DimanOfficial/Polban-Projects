<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .posisi{
            position: fixed;
            bottom: 150px;
            left: 150px;
        }
        html {
            scroll-behavior: smooth;
        }
        .scroll-buttons {
            position: fixed;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 1000;
        }
        .scroll-buttons .btn {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background:#07294d;
        }
        .scroll-buttons .btn i{
            color:white;
        }
        /* Button Kembali */
        .btn-back {
            font-size: 18px;
            font-weight: 700;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .btn-back:hover {
            
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>

<div class="scroll-buttons">
    <button id="scrollTop" class="btn mb-2">
        <i class="bi bi-arrow-up"></i>
    </button>
    <button id="scrollBottom" class="btn">
        <i class="bi bi-arrow-down"></i>
    </button>
</div>

<div class="container mt-5">
    <!-- Tombol Kembali (Posisi Fixed) -->
    <div class="btn-back-container">
        <button onclick="window.history.back();" class="btn-back btn btn-primary">
            <i class="bi bi-arrow-left"></i> Kembali
        </button>
    </div>
</div>
<div class="container mt-5">
    <h3>Log Aktivitas</h3>

    

    <!-- Filter hanya untuk Admin -->
    <?php if ($isAdmin): ?>
        <form method="get" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <select name="role" class="form-select">
                        <option value="">Semua Role</option>
                        <option value="Admin" <?= ($filterRole === 'Admin') ? 'selected' : '' ?>>Admin</option>
                        <option value="Pembuat" <?= ($filterRole === 'Pembuat') ? 'selected' : '' ?>>Pembuat</option>
                        <option value="Pejabat" <?= ($filterRole === 'Pejabat') ? 'selected' : '' ?>>Pejabat</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="week" name="week" class="form-control" value="<?= esc($filterWeek) ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>
    <?php endif; ?>

    <!-- Tombol Export (Admin, Pembuat, Pejabat) -->
    <?php if ($isAdmin || $isPembuat || $isPejabat): ?>
        <div class="mt-3">
            <a href="/log/export/pdf" class="btn btn-danger">Download PDF</a>
            <a href="/log/export/excel" class="btn btn-success">Download Excel</a>
        </div>
        <br>
    <?php endif; ?>

    <!-- Tabel log aktivitas -->
    <table class="table table-striped">
        <thead class="text-white" style="  background-color: #07294d;">
            <tr>
                <th>Username</th>
                <th>Role</th>
                <th>Aktivitas</th>
                <th>Tanggal</th>
            </tr>
        </thead>
       <tbody>
    <?php if (!empty($logs)): ?>
        <?php foreach ($logs as $log): ?>
            <!-- Tampilkan log untuk Admin atau user yang sesuai -->
            <?php if ($isAdmin || $log['username'] === session()->get('username')): ?>
                <tr>
                    <td><?= esc($log['username']) ?></td>
                    <td><?= esc($log['role']) ?></td>
                    <td><?= esc($log['aktivitas']) ?></td>
                    <td><?= esc($log['waktu']) ?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4" class="text-center">Tidak ada data log.</td>
        </tr>
    <?php endif; ?>
</tbody> 
    </table>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.getElementById("scrollTop").addEventListener("click", function () {
        window.scrollTo({ top: 0, behavior: "smooth" });
    });

    document.getElementById("scrollBottom").addEventListener("click", function () {
        window.scrollTo({ top: document.body.scrollHeight, behavior: "smooth" });
    });
</script>

</body>
</html>

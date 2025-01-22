<!DOCTYPE html>
<html>

<head>
    <title>PDF Data jurusan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2>Data Jurusan</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Jurusan</th>
                <th>Kode Jurusan</th>
                <th>Deskripsi</th>
                <th>Akreditasi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($jurusan) > 0): ?>
                <?php $no = 1; ?>
                <?php foreach ($jurusan as $j): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $j['nama_jurusan']; ?></td>
                        <td><?= $j['kode_jurusan']; ?></td>
                        <td><?= $j['deskripsi']; ?></td>
                        <td><?= $j['akreditasi']; ?></td>
                        <td><?= $j['status']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Data tidak ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>
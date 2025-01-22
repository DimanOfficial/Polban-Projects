<!DOCTYPE html>
<html>
<head>
    <title>Data Unit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Data Unit</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Unit</th>
                <th>Kode Unit</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($units as $index => $unit): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $unit['nama_unit'] ?></td>
                    <td><?= $unit['kode_unit'] ?></td>
                    <td><?= $unit['deskripsi'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

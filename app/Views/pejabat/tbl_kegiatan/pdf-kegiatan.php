<!DOCTYPE html>
<html>

<head>
    <title>Data kegiatan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 50%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2>Data Kegiatan</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kegiatan</th>
                <th>Deskripsi Kegiatan</th> <!-- Mengambil 2 kolom -->
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Lokasi</th>
                <th>Jenis Kegiatan</th>
                <th>Penanggung Jawab</th>
                <th>Peserta</th>
                <th>Nara Hubung</th>
                <th>Penyelenggara</th>
                <th>Jenis Penyelenggara</th>
                <th>Detail Penyelenggara</th>
                <th>Waktu Kegiatan</th>
                <th>Status</th>
                <th>Disetujui</th>

            </tr>
        </thead>
        <tbody>
            <?php foreach ($kegiatan as $index => $k): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $k['nama_kegiatan'] ?></td>
                    <td><?= $k['deskripsi'] ?></td>
                    <td><?= $k['tanggal_mulai'] ?></td>
                    <td><?= $k['tanggal_selesai'] ?></td>
                    <td><?= $k['lokasi'] ?></td>
                    <td><?= $k['jenis_kegiatan'] ?></td>
                    <td><?= $k['penanggung_jawab'] ?></td>
                    <td><?= $k['peserta'] ?></td>
                    <td><?= $k['nara_hubung'] ?></td>
                    <td><?= $k['penyelenggara'] ?></td>
                    <td><?= $k['jenis_penyelenggara'] ?></td>
                    <td><?= $k['detail_penyelenggara'] ?></td>
                    <td><?= $k['waktu_kegiatan'] ?></td>
                    <td><?= $k['status'] ?></td>
                    <td><?= $k['disetujui'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>
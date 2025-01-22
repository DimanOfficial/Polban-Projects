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
            <?php foreach ($kegiatan as $index => $kegiatan): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $kegiatan['nama_kegiatan'] ?></td>
                    <td><?= $kegiatan['deskripsi'] ?></td>
                    <td><?= $kegiatan['tanggal_mulai'] ?></td>
                    <td><?= $kegiatan['tanggal_selesai'] ?></td>
                    <td><?= $kegiatan['lokasi'] ?></td>
                    <td><?= $kegiatan['jenis_kegiatan'] ?></td>
                    <td><?= $kegiatan['penanggung_jawab'] ?></td>
                    <td><?= $kegiatan['peserta'] ?></td>
                    <td><?= $kegiatan['nara_hubung'] ?></td>
                    <td><?= $kegiatan['penyelenggara'] ?></td>
                    <td><?= $kegiatan['jenis_penyelenggara'] ?></td>
                    <td><?= $kegiatan['detail_penyelenggara'] ?></td>
                    <td><?= $kegiatan['waktu_kegiatan'] ?></td>
                    <td><?= $kegiatan['status'] ?></td>
                    <td><?= $kegiatan['disetujui'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>
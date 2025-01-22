<!DOCTYPE html>
<html>
<head>
    <title>Log Aktivitas</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .container {
            justify: center;
            align-items: center;
        }
    </style>
</head>
<body>
    <h3>Log Aktivitas</h3>
   <div class="container">
   <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Role</th>
                <th>Aktivitas</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= esc($log['username']) ?></td>
                    <td><?= esc($log['role']) ?></td>
                    <td><?= esc($log['aktivitas']) ?></td>
                    <td><?= esc($log['waktu']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
   </div>
</body>
</html>

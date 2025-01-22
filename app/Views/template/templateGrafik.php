<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard Pejabat</title>

    <!-- Custom fonts for this template-->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link href="<?= base_url('templateDash/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="<?= base_url('templateDash/css/sb-admin-2.css') ?>" rel="stylesheet">

    <!-- link icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- link style css manual -->
    <style>
        .box {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            border-radius:10px;
        }
        .chart-container {
            width: 80%;
            margin: auto;
            background-color:white;
            padding:10px;
        }
        .chartPeserta canvas{
        max-width: 500px; /* Sesuaikan lebar maksimum */
        height: auto;    /* Tinggi otomatis sesuai proporsi */
    }


       


    </style>

</head>

<body id="page-top">

<!-- Filter Form -->
<form method="GET" action="">
        <label for="tahun">Tahun:</label>
        <select name="tahun" id="tahun">
            <option value="">Semua</option>
            <?php foreach ($filterOptions['tahun'] as $tahun): ?>
                <option value="<?= $tahun['tahun']; ?>" <?= ($tahun['tahun'] == $_GET['tahun']) ? 'selected' : ''; ?>>
                    <?= $tahun['tahun']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="bulan">Bulan:</label>
        <select name="bulan" id="bulan">
            <option value="">Semua</option>
            <?php for ($i = 1; $i <= 12; $i++): ?>
                <option value="<?= $i; ?>" <?= ($i == $_GET['bulan']) ? 'selected' : ''; ?>>
                    <?= date('F', mktime(0, 0, 0, $i, 1)); ?>
                </option>
            <?php endfor; ?>
        </select>

        <label for="jurusan">Jurusan:</label>
        <select name="jurusan" id="jurusan">
            <option value="">Semua</option>
            <?php foreach ($filterOptions['jurusan'] as $jurusan): ?>
                <option value="<?= $jurusan['id_jurusan']; ?>" <?= ($jurusan['id_jurusan'] == $_GET['jurusan']) ? 'selected' : ''; ?>>
                    <?= $jurusan['nama_jurusan']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="prodi">Prodi:</label>
        <select name="prodi" id="prodi">
            <option value="">Semua</option>
            <?php foreach ($filterOptions['prodi'] as $prodi): ?>
                <option value="<?= $prodi['id_prodi']; ?>" <?= ($prodi['id_prodi'] == $_GET['prodi']) ? 'selected' : ''; ?>>
                    <?= $prodi['nama_prodi']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Filter</button>
    </form>



    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-putih sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
                <div class="sidebar-brand-icon">
                    <img src="/image/logo polban.png" alt="logo" width="50px" height="50px">
                </div>
                <div class="sidebar-brand-text mx-3 text-blue">Manajemen <sup>Polban</sup></div>
            </a>

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link text-blue" href="/pejabat">
                    <i class="bi bi-house-door"></i>
                    <span>Dashboard</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-calendar3-event"></i>
                    <span>Kegiatan</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/pejabat/chart">
                    <i class="bi bi-calendar3-event"></i>
                    <span>Grafik</span></a>
            </li>

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column bg-gradient-semiPutih">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow-sm">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Pejabat</span>
                                <img class="img-profile rounded-circle"
                                    src="/image/image 1.jpg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profil
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Pengaturan
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Keluar
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>

                
                <!-- End of Topbar -->

                <?= $this->renderSection('content'); ?>

                <!-- Footer -->
                <footer class="sticky-footer bg-white mt-5">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; Manajemen Polban @024</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-primary" href="<?= base_url('/') ?>">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap core JavaScript-->
        <script src=" <?= base_url('templateDash/vendor/jquery/jquery.min.js') ?>"></script>
        <script src=" <?= base_url('templateDash/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

        <!-- Core plugin JavaScript-->
        <script src=" <?= base_url('templateDash/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>

        <!-- Custom scripts for all pages-->
        <script src=" <?= base_url('templateDash/js/sb-admin-2.min.js') ?>"></script>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


        <script>
        const ctx = document.getElementById('grafikKegiatan').getContext('2d');
        const data = <?= json_encode($kegiatan); ?>;

        const labels = data.map(item => item.nama_kegiatan);
        const durasi = data.map(item => {
            const tanggalMulai = new Date(item.tanggal_mulai).getTime();
            const tanggalSelesai = new Date(item.tanggal_selesai).getTime();
            return (tanggalSelesai - tanggalMulai) / (1000 * 60 * 60 * 24);
        });

        const myChart = new Chart(ctx, {
            type: line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Durasi (hari)',
                    data: durasi,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</body>

</html>
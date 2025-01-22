<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">


    <title><?= $title; ?></title>

    <!-- Custom fonts for this template-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link href="<?= base_url('templateDash/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="<?= base_url('templateDash/css/sb-admin-2.css') ?>" rel="stylesheet">

    <!-- link icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- link bootstrap via cdn -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- link style css manual -->
    <style>
        * {
            font-family: "Roboto", serif;
        }

        .box {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            border-radius: 10px;
        }

        .text-blue {
            color: #07294d;
            font-weight: 700;
        }
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-putih sidebar sidebar-dark accordion shadow-sm" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
                <div class="sidebar-brand-icon">
                    <img src="/image/logo polban.png" alt="logo" width="50px" height="50px">
                </div>
                <div class="sidebar-brand-text mx-3 text-blue">Manajemen <sup>Polban</sup></div>
            </a>

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link text-blue" href="/dashboard/pembuat">
                    <i class="bi bi-house-door"></i>
                    <span>Dashboard</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/dashboard/pembuat/kegiatan">
                    <i class="bi bi-calendar3-event"></i>
                    <span>Kegiatan</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/dashboard/pembuat/tambah">
                    <i class="bi bi-calendar2-plus-fill"></i>
                    <span>Tambah Kegiatan</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/logs">
                    <i class="bi bi-clock-history"></i>
                    <span>Log Activity</span></a>
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
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <!-- Menampilkan Nama Lengkap -->
                                <span class="mr-3 text-gray-600">
                                    <?= $user['username'] ?>
                                </span>
                                <div class="dropdown-divider mx-2" style="height: 20px; border-left: 1px solid #ddd;"></div>
                                <img src="<?= base_url($user['profile_pic'] ?? 'assets/images/default.png') ?>" alt="Profile Picture" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="/dashboard/profil">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profil
                                </a>
                                <a class="dropdown-item" href="<?= base_url('/dashboard/pengaturanpembuat') ?>">
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
                        <h5 class="modal-title" id="exampleModalLabel">Yakin Mau Keluar?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Pilih "Logout" untuk melakukan Logout</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-primary" href="<?= base_url('/logout') ?>">Logout</a>
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

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- script disini dipindahkan ke public/text -->
</body>

</html>
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Auth Routes
$routes->get('/login', 'AuthController::login');
$routes->post('/process-login', 'AuthController::processLogin');
$routes->get('/register', 'AuthController::register');
$routes->get('/register/success', 'AuthController::registerSuccess');
$routes->post('/process-register', 'AuthController::processRegister');
$routes->get('/logout', 'AuthController::logout');
$routes->get('/get-prodi/(:num)', 'AuthController::getProdi/$1');

$routes->post('/forgot-password/process', 'ForgotPasswordController::process');
$routes->post('/forgot-password/check-otp', 'ForgotPasswordController::checkOtp');
$routes->post('/forgot-password/update-password', 'ForgotPasswordController::updatePassword');
$routes->get('/forgot-password', 'ForgotPasswordController::forgotPassword');
$routes->post('/forgot-password/send-reset-link', 'ForgotPasswordController::sendResetLink');
$routes->get('/forgot-password/verify-otp', 'ForgotPasswordController::verifyOtp');
$routes->post('/forgot-password/process-otp', 'ForgotPasswordController::processOtp');
$routes->get('/forgot-password/reset-password', 'ForgotPasswordController::resetPassword');
$routes->post('/forgot-password/process-reset-password', 'ForgotPasswordController::processResetPassword');


//Pembatasan Hak Akses
$routes->group('dashboard', ['filter' => 'role:Admin, LogActivityFilter'], function ($routes) {
    $routes->get('admin', 'DashboardController::admin');
    $routes->post('admin/users/approve', 'KegiatanController::approveUsers');
    $routes->post('admin/users/reject', 'KegiatanController::rejectUsers');
    $routes->post('admin/users/toggleStatus', 'KegiatanController::toggleStatus');
    $routes->get('kegiatan', 'KegiatanController::index');
    $routes->get('kegiatan/create', 'KegiatanController::create');
    $routes->post('kegiatan/store', 'KegiatanController::store');
    $routes->get('kegiatan/delete/(:num)', 'KegiatanController::delete/$1');
    $routes->get('kegiatan/edit/(:num)', 'KegiatanController::edit/$1');
    $routes->post('kegiatan/update/(:num)', 'KegiatanController::update/$1');
    $routes->get('kegiatan/download-pdf', 'KegiatanController::downloadPdf');
    $routes->get('kegiatan/download-excel', 'KegiatanController::downloadExcel');
    $routes->get('kegiatan/approve/(:num)', 'KegiatanController::approve/$1');
    $routes->post('kegiatan/reject/(:num)', 'KegiatanController::reject/$1');
    $routes->get('jurusan', 'JurusanController::index');
    $routes->get('jurusan/create', 'JurusanController::create');
    $routes->post('jurusan/store', 'JurusanController::store');
    $routes->get('jurusan/edit/(:num)', 'JurusanController::edit/$1');
    $routes->post('jurusan/update/(:num)', 'JurusanController::update/$1');
    $routes->get('jurusan/delete/(:num)', 'JurusanController::delete/$1');
    $routes->get('jurusan/download-pdf', 'JurusanController::downloadPdf');
    $routes->get('jurusan/download-excel', 'JurusanController::downloadExcel');
    $routes->get('prodi', 'ProdiController::index');
    $routes->get('prodi/create', 'ProdiController::create');
    $routes->post('prodi/store', 'ProdiController::store');
    $routes->get('prodi/edit/(:num)', 'prodiController::edit/$1');
    $routes->post('prodi/update/(:num)', 'prodiController::update/$1');
    $routes->get('prodi/delete/(:num)', 'prodiController::delete/$1');
    $routes->get('prodi/download-pdf', 'prodiController::downloadPdf');
    $routes->get('prodi/download-excel', 'prodiController::downloadExcel');
    $routes->get('unit', 'UnitController::index');
    $routes->get('unit/create', 'UnitController::create');
    $routes->post('unit/store', 'UnitController::store');
    $routes->get('unit/edit/(:num)', 'unitController::edit/$1');
    $routes->post('unit/update/(:num)', 'unitController::update/$1');
    $routes->get('unit/delete/(:num)', 'unitController::delete/$1');
    $routes->get('unit/download-pdf', 'unitController::downloadPdf');
    $routes->get('unit/download-excel', 'UnitController::downloadExcel');
    $routes->get('users', 'UserController::index');
    $routes->get('users/delete/(:num)', 'UserController::delete/$1');
    $routes->get('users/edit/(:num)', 'UserController::edit/$1');
    $routes->post('users/update/(:num)', 'UserController::update/$1');
    $routes->get('profiladmin', 'ProfilAdminController::index');
    $routes->post('profiladmin/update', 'ProfilAdminController::update');
    $routes->get('profiladmin/edit', 'ProfilAdminController::edit');
    $routes->get('pengaturan', 'PengaturanController::index');
    $routes->get('kegiatan/getProdi', 'KegiatanController::getProdi');
    $routes->post('/forgot-password/process', 'ForgotPasswordPengaturanAdminController::process');
    $routes->post('/forgot-password/check-otp', 'ForgotPasswordPengaturanAdminController::checkOtp');
    $routes->post('/forgot-password/update-password', 'ForgotPasswordPengaturanAdminController::updatePassword');
    $routes->get('/forgot-password', 'ForgotPasswordPengaturanAdminController::forgotPassword');
    $routes->post('/forgot-password/send-reset-link', 'ForgotPasswordPengaturanAdminController::sendResetLink');
    $routes->get('/forgot-password/verify-otp', 'ForgotPasswordPengaturanAdminController::verifyOtp');
    $routes->post('/forgot-password/process-otp', 'ForgotPasswordPengaturanAdminController::processOtp');
    $routes->get('/forgot-password/reset-password', 'ForgotPasswordPengaturanAdminController::resetPassword');
    $routes->post('/forgot-password/process-reset-password', 'ForgotPasswordPengaturanAdminController::processResetPassword');
});

$routes->group('dashboard', ['filter' => 'role:Pembuat, LogActivityFilter'], function ($routes) {
    $routes->get('pembuat', 'DashboardController::pembuat');
    $routes->get('pembuat/kegiatan', 'PembuatController::kegiatan');
    $routes->get('pembuat/tambah', 'PembuatController::tambah');
    $routes->post('pembuat/simpan', 'PembuatController::simpan');
    $routes->get('pembuat/edit/(:num)', 'PembuatController::edit/$1');
    $routes->post('pembuat/update/(:num)', 'PembuatController::update/$1');
    $routes->get('profil', 'ProfilController::index');
    $routes->post('profil/update', 'ProfilController::update');
    $routes->get('profil/edit', 'ProfilController::edit');
    $routes->get('pengaturanpembuat', 'PengaturanPembuatController::index');
});

$routes->group('dashboard', ['filter' => 'role:Pejabat, LogActivityFilter'], function ($routes) {
    $routes->get('pejabat', 'DashboardController::pejabat');
    $routes->get('pejabat/grafik', 'PejabatController::index4');
    $routes->get('pejabat/tbl_kegiatan', 'PejabatController::tbl_kegiatan');
    $routes->post('getChartData', 'PejabatController::getChartData'); // Mengambil data untuk grafik
    $routes->post('pejabat/getChartData', 'PejabatController::getChartData');
    $routes->get('profilpejabat', 'ProfilPejabatController::index');
    $routes->post('profilpejabat/update', 'ProfilPejabatController::update');
    $routes->get('profilpejabat/edit', 'ProfilPejabatController::edit');
    $routes->get('pengaturanpejabat', 'PengaturanPejabatController::index');
    $routes->post('pejabat/getChartData', 'PejabatController::getChartData');
    $routes->get('get-prodi/(:num)', 'PejabatController::getProdi/$1');
    $routes->get('pejabat/download-pdf', 'PejabatController::downloadPdf');
    $routes->get('pejabat/download-excel', 'PejabatController::downloadExcel');
    $routes->post('/pejabat/getChartData', 'PejabatController::getChartData');
    $routes->post('/dashboard/getChartDataPenyelenggara', 'PejabatController::getChartDataPenyelenggara');
});

//halaman error hak akses dibatasi
$routes->get('/unauthorized', function () {
    return view('errors/403');
});


//pengunjung
$routes->get('/', 'PengunjungController::index');
$routes->get('/pengunjung/rincian', 'PengunjungController::rincian');
$routes->get('/pengunjung/detail/(:num)', 'PengunjungController::detail/$1');

// LogActivity
$routes->get('/logs', 'LogController::index', ['filter' => 'auth']);
$routes->get('log/export/pdf', 'LogController::export/pdf', ['filter' => 'auth']);
$routes->get('log/export/excel', 'LogController::export/excel', ['filter' => 'auth']);

// // Routes untuk Log Pejabat
// $routes->get('/logs/pejabat', 'LogController::indexLogPejabat', ['filter' => 'role:pejabat']);

// // Routes untuk Log Pembuat
// $routes->get('/logs/pembuat', 'LogController::indexLogPembuat', ['filter' => 'role:pembuat']);


//pengaturan

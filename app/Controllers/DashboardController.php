<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Models\LogAktivitasModel;
use App\Models\ProfilAdminModel;
use App\Models\ProfilModel;
use App\Models\ProfilPejabatModel;

class DashboardController extends Controller
{

    protected $profilAdminModel;
    protected $profilModel;
    protected $profilPejabatModel;

    public function __construct()
    {
        $this->profilAdminModel = new ProfilAdminModel();
        $this->profilModel = new ProfilModel();
        $this->profilPejabatModel = new ProfilPejabatModel();
    }

    public function createActivity()
    {
        // Log aktivitas pembuatan
        $logModel = new LogAktivitasModel();
        $logModel->insert([
            'id_users' => session()->get('id_users'),
            'username' => session()->get('username'),
            'role'     => session()->get('role'),
            'aktivitas'=> 'Membuat kegiatan baru',
        ]);

        // Logika untuk membuat kegiatan
        return redirect()->to('/dashboard')->with('success', 'Kegiatan berhasil dibuat');
    }

    public function deleteActivity($id)
    {
        // Log aktivitas penghapusan
        $logModel = new LogAktivitasModel();
        $logModel->insert([
            'id_users' => session()->get('id_users'),
            'username' => session()->get('username'),
            'role'     => session()->get('role'),
            'aktivitas'=> "Menghapus kegiatan dengan ID $id",
        ]);

        // Logika untuk menghapus kegiatan
        return redirect()->to('/dashboard')->with('success', 'Kegiatan berhasil dihapus');
    }

    public function admin()
    {
        $userId = session()->get('id_users'); // Ambil ID user dari session
        $user = $this->profilAdminModel->getUserById($userId); // Ambil data user dari database

        return view('dashboard/admin', [
            'title' => 'Dashboard Admin',
            'username' => session()->get('username'),
            'user' => $user,
        ]);
    }

    public function pembuat()
    {
        $userId = session()->get('id_users'); // Ambil ID user dari session
        $user = $this->profilModel->getUserById($userId); // Ambil data user dari database

        return view('dashboard/pembuat', [
            'title' => 'Dashboard Pembuat',
            'username' => session()->get('username'),
            'user' => $user,
        ]);
    }

    public function pejabat()
    {
        $userId = session()->get('id_users'); // Ambil ID user dari session
        $user = $this->profilAdminModel->getUserById($userId); // Ambil data user dari database

        return view('dashboard/pejabat', [
            'title' => 'Dashboard Pejabat',
            'username' => session()->get('username'),
            'user' => $user,
        ]);
    }

    public function userLogin()
    {
        // Log aktivitas login
        $this->ActivityLogModel->save([
            'user_id'  => session()->get('user_id'),
            'activity' => 'User logged in',
            'role'     => session()->get('role'),
        ]);

        return redirect()->to('/dashboard');
    }

    public function testModel()
    {
        $model = new ActivityLogModel();
        return $model->findAll(); // Cek apakah data dari tabel activity_logs muncul
    }

}

<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\ProfilAdminModel;

class UserController extends BaseController
{
    protected $UserModel;
     protected $profilAdminModel;

    public function __construct()
    {
        $this->UserModel = new UserModel();
        $this->profilAdminModel = new ProfilAdminModel();
    }

    public function index()
    {
        // Hanya Admin yang dapat mengakses halaman ini
        if (session()->get('role') !== 'Admin') {
            return view('errors/403'); // Tampilkan halaman Unauthorized
        }

        $perPage = 5; // Jumlah data per halaman
        $keyword = $this->request->getGet('keyword'); // Ambil keyword dari query string

        // Jika ada pencarian
        if ($keyword) {
            $users = $this->UserModel->search($keyword, $perPage); // Data berdasarkan pencarian
        } else {
            $users = $this->UserModel->getPaginatedUsers($perPage); // Data normal tanpa pencarian
        }

        $userId = session()->get('id_users'); // Ambil ID user dari session
        $user = $this->profilAdminModel->getUserById($userId); // Ambil data user dari database

        $data = [
            'title' => 'Halaman Users',
            'users' => $users,
            'pager' => $this->UserModel->pager, // Objek pager untuk pagination
            'keyword' => $keyword, // Simpan keyword untuk dioper ke view
            'user' => $user,
        ];

        return view('admin/users/index', $data);
    }



    public function edit($id)
    {
        if (session()->get('role') !== 'Admin') {
            return view('errors/403'); // Tampilkan halaman Unauthorized
        }

        $userId = session()->get('id_users'); // Ambil ID user dari session
        $user = $this->UserModel->getUserById($userId);

        $data = [
            'pengguna' => $this->UserModel->find($id),
            'title' => 'Edit Data Pengguna',
            'user' => $user,
        ];
        
        return view('admin/users/edit', $data);
    }

    public function update($id)
    {
        if (session()->get('role') !== 'Admin') {
            return view('errors/403'); // Tampilkan halaman Unauthorized
        }

        // Menyimpan perubahan data
        $data = [
            'role' => $this->request->getPost('role'),
        ];

        // Update data prodi
        $this->UserModel->update($id, $data);

        return redirect()->to('/dashboard/users')->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function delete($id)
    {
        if (session()->get('role') !== 'Admin') {
            return view('errors/403'); // Tampilkan halaman Unauthorized
        }

        $this->UserModel->delete($id);
        return redirect()->to('/dashboard/users')->with('success', 'Data berhasil dihapus.');
    }
}

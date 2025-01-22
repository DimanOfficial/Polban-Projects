<?php

namespace App\Controllers;

use App\Models\ProfilModel;
use App\Models\UserModel;

class ProfilController extends BaseController
{
    protected $profilModel;
    protected $userModel;

    public function __construct()
    {
        $this->profilModel = new ProfilModel();
        $this->userModel = new UserModel(); // Inisialisasi model
        helper(['form', 'url']);
    }

    public function index()
    {
        $session = session(); // Ambil data session pengguna
        $id_users = $session->get('id_users'); // ID user dari session

        $userModel = new UserModel();
        $user = $userModel->find($id_users);// Cari data user berdasarkan ID
        

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Pengguna tidak ditemukan');
        }

        // Filter data profil berdasarkan jenis_users dan jenis_karyawan
        $profileData = [
            'username' => $user['username'],
            'nama_lengkap' => $user['nama_lengkap'],
            'profile_pic' => $user['profile_pic'],
        ];

        if ($user['jenis_users'] == 'Mahasiswa') {
            $profileData['nim'] = $user['nim'];
            $profileData['jurusan'] = $this->getJurusanName($user['id_jurusan']);
            $profileData['prodi'] = $this->getProdiName($user['id_prodi']);
        } elseif ($user['jenis_users'] == 'Karyawan' && $user['jenis_karyawan'] == 'jurusan') {
            $profileData['nip'] = $user['nip'];
            $profileData['jurusan'] = $this->getJurusanName($user['id_jurusan']);
            $profileData['prodi'] = $this->getProdiName($user['id_prodi']);
        } elseif ($user['jenis_users'] == 'Karyawan' && $user['jenis_karyawan'] == 'unit') {
            $profileData['nip'] = $user['nip'];
            $profileData['unit'] = $this->getUnitName($user['id_unit']);
        }


        // if ($user['jenis_users'] == 'Mahasiswa') {
        //     $profileData['nim'] = $user['nim'];
        //     $profileData['jurusan'] = $this->getJurusanName($user['id_jurusan']);
        //     $profileData['prodi'] = $this->getProdiName($user['id_prodi']);
        // } elseif ($user['jenis_users'] == 'Karyawan') {
        //     $profileData['nip'] = $user['nip'];

        //     if ($user['jenis_karyawan'] == 'Jurusan') {
        //         $profileData['jurusan'] = $this->getJurusanName($user['id_jurusan']);
        //         $profileData['prodi'] = $this->getProdiName($user['id_prodi']);
        //     } elseif ($user['jenis_karyawan'] == 'Unit') {
        //         $profileData['unit'] = $this->getUnitName($user['id_unit']);
        //     }
        // }

        return view('profil/profile', ['profile' => $profileData]);
    }

    private function getJurusanName($id_jurusan)
    {
        // Query ke tabel jurusan untuk mendapatkan nama jurusan
        $db = \Config\Database::connect();
        return $db->table('jurusan')->where('id_jurusan', $id_jurusan)->get()->getRow('nama_jurusan');
    }

    private function getProdiName($id_prodi)
    {
        // Query ke tabel prodi untuk mendapatkan nama prodi
        $db = \Config\Database::connect();
        return $db->table('prodi')->where('id_prodi', $id_prodi)->get()->getRow('nama_prodi');
    }

    private function getUnitName($id_unit)
    {
        // Query ke tabel unit untuk mendapatkan nama unit
        $db = \Config\Database::connect();
        return $db->table('unit')->where('id_unit', $id_unit)->get()->getRow('nama_unit');
    }



    /**
     * Display edit profile page
     */
    public function edit()
    {
        $userId = session()->get('id_users'); // Ambil ID user dari session
        $user = $this->userModel->getUserById($userId);

        if (!$user) {
            return redirect()->to('/login'); // Redirect jika data user tidak ditemukan
        }

        return view('profil/edit_profile', ['user' => $user]);
    }


    /**
     * Update profile
     */
    public function update()
    {
        $userId = session()->get('id_users');
        $user = $this->profilModel->getUserById($userId);

        // Validasi input
        $validation = $this->validate([
            'address' => 'required|max_length[255]',
            'password' => 'permit_empty|min_length[8]',
            'profile_pic' => 'permit_empty|is_image[profile_pic]|max_size[profile_pic,2048]'
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Prepare data
        $data = ['address' => $this->request->getPost('address')];

        // Jika password diisi, update password
        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        // Jika ada upload foto profil
        $file = $this->request->getFile('profile_pic');
        if ($file && $file->isValid()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/assets/images', $newName);
            $data['profile_pic'] = 'assets/images/' . $newName;

            // Hapus foto lama jika ada
            if (!empty($user['profile_pic']) && file_exists(ROOTPATH . 'public/' . $user['profile_pic'])) {
                unlink(ROOTPATH . 'public/' . $user['profile_pic']);
            }
        }

        // Update data
        $this->profilModel->update($userId, $data);

        return redirect()->to('dashboard/profil')->with('success', 'Profil berhasil diperbarui.');
    }
}

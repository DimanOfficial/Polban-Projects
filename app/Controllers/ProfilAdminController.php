<?php

namespace App\Controllers;

use App\Models\ProfilAdminModel;

class ProfilAdminController extends BaseController
{
    protected $profilModel;

    public function __construct()
    {
        $this->profilModel = new ProfilAdminModel();
        helper(['form', 'url']);
    }

    /**
     * Display profile page
     */
    public function index()
    {
        $userId = session()->get('id_users'); // Ambil ID user dari session
        $user = $this->profilModel->getUserById($userId); // Ambil data user dari database

        // Pastikan model mengambil field `created_at`
        return view('profiladmin/profile', ['user' => $user]);
    }

    /**
     * Display edit profile page
     */
    public function edit()
    {
        $userId = session()->get('id_users'); // Ambil ID user dari session
        $user = $this->profilModel->getUserById($userId);

        return view('profiladmin/edit_profile', ['user' => $user]);
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
        $this->profilModel->updateProfile($userId, $data);

        return redirect()->to('dashboard/profiladmin')->with('success', 'Profil berhasil diperbarui.');
    }
}

<?php

namespace App\Controllers;

use App\Models\ProfilPejabatModel;

class ProfilPejabatController extends BaseController
{
    protected $profilModel;

    public function __construct()
    {
        $this->profilModel = new ProfilPejabatModel();
        helper(['form', 'url']);
    }

    public function index()
    {
       $userId = session()->get('id_users'); // Ambil ID pengguna dari sesi
    $user = $this->profilModel->getUserById($userId); // Ambil data pengguna dari database

    if (!$user) {
        return redirect()->back()->with('error', 'Data pengguna tidak ditemukan.');
    }

    return view('profilpejabat/profile', [
        'user' => $user, // Data user harus mencakup 'created_at'
    ]);
    }

    public function edit()
{
    $userId = session()->get('id_users'); // Ambil ID pengguna dari sesi
    $user = $this->profilModel->getUserById($userId); // Ambil data pengguna

    if (!$user) {
        return redirect()->back()->with('error', 'Data pengguna tidak ditemukan.');
    }

     
    // Kirim data pengguna ke view
    return view('profilpejabat/edit_profile', [
        'user' => $user, // Pastikan data lengkap pengguna termasuk 'username'
    ]);
}



public function update()
{
    $userId = session()->get('id_users'); // Ambil ID pengguna dari sesi
    $user = $this->profilModel->getUserById($userId); // Ambil data pengguna

    $validation = $this->validate([
        'jabatan' => 'required|max_length[255]', // Validasi untuk jabatan
        'address' => 'required|max_length[255]',
        'password' => 'permit_empty|min_length[8]',
        'profile_pic' => 'permit_empty|is_image[profile_pic]|max_size[profile_pic,2048]'
    ]);

    if (!$validation) {
        return redirect()->back()->withInput()->with('validation', $this->validator);
    }

    $data = [
        'jabatan' => $this->request->getPost('jabatan'),
        'address' => $this->request->getPost('address'),
    ];

    if ($this->request->getPost('password')) {
        $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
    }

    $file = $this->request->getFile('profile_pic');
    if ($file && $file->isValid()) {
        $newName = $file->getRandomName();
        $file->move(ROOTPATH . 'public/uploads', $newName);
        $data['profile_pic'] = 'uploads/' . $newName;

        if (!empty($user['profile_pic']) && file_exists(ROOTPATH . 'public/' . $user['profile_pic'])) {
            unlink(ROOTPATH . 'public/' . $user['profile_pic']);
        }
    }

    $this->profilModel->updateProfile($userId, $data);

    return redirect()->to('/dashboard/profilpejabat')->with('success', 'Profil berhasil diperbarui.');
}

}

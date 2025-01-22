<?php

namespace App\Controllers;

use App\Models\UserModel;
//nabil
use App\Models\JurusanModel; // Tambahkan ini
use App\Models\ProdiModel;  // Tambahkan ini
use App\Models\UnitModel;  // Tambahkan ini
use App\Models\LogAktivitasModel;   // Tambahkan ini
use CodeIgniter\Controller;

class AuthController extends Controller
{
    public function register()
    {
        // Load data jurusan dan prodi dari database
        $jurusanModel = new JurusanModel();
        $prodiModel = new ProdiModel();
        $unitModel = new UnitModel();

        $data['jurusan'] = $jurusanModel->findAll();
        $data['prodi'] = $prodiModel->findAll();
        $data['unit'] = $unitModel->findAll();

        return view('auth/register', $data);
    }

   public function processRegister()
{
    $userModel = new UserModel();

    $validation = $this->validate([
        'username'      => 'required|min_length[3]|max_length[20]',
        'email'         => 'required|valid_email|is_unique[users.email]',
        'password'      => 'required|min_length[8]',
        'jenis_users'   => 'required|in_list[Mahasiswa,Karyawan]',
        'nama_lengkap'  => 'required',
        'nim'           => 'permit_empty|numeric|min_length[8]|max_length[20]',
        'nip'           => 'permit_empty|numeric|min_length[8]|max_length[20]',
        'id_jurusan'    => 'permit_empty|is_natural',
        'id_prodi'      => 'permit_empty|is_natural',
        'id_unit'       => 'permit_empty|is_natural',
    ]);
    

    if (!$validation) {
        return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
    }

    $data = [
        'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
        'username'      => $this->request->getPost('username'),
        'email'         => $this->request->getPost('email'),
        'jenis_users'   => $this->request->getPost('jenis_users'),
        'password'      => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
        'role'          => 'Pembuat',
        'jenis_karyawan'=> $this->request->getPost('jenis_karyawan'),
        'status'   => 'Menunggu Persetujuan', // Default status
    ];
    
    if ($data['jenis_users'] === 'Mahasiswa') {
        $data['nim'] = $this->request->getPost('nim');
        $data['id_jurusan'] = $this->request->getPost('jurusan');
        $data['id_prodi'] = $this->request->getPost('prodi');
    }
    
    if ($data['jenis_users'] === 'Karyawan') {
        $data['nip'] = $this->request->getPost('nip');
        $jenisKaryawan = $this->request->getPost('jenis_karyawan');
    
        if ($jenisKaryawan === 'Jurusan') {
            $data['id_jurusan'] = $this->request->getPost('jurusan');
            $data['id_prodi'] = $this->request->getPost('prodi');
        } elseif ($jenisKaryawan === 'Unit') {
            $data['id_unit'] = $this->request->getPost('unit');
        }
    }
    
    $userModel->insert($data);
    

    return redirect()->to('/register/success')->with('success', 'Registrasi berhasil, silakan login!');
}

public function registerSuccess()
{
    return view('auth/register_success'); // Tampilkan halaman sukses
}


   public function getProdi($id_jurusan) {
    $prodiModel = new \App\Models\ProdiModel();

    // Pastikan menggunakan where() untuk mendapatkan Prodi berdasarkan jurusan
    $prodi = $prodiModel->where('id_jurusan', $id_jurusan)->findAll();

    if (count($prodi) > 0) {
        return $this->response->setJSON($prodi);
    } else {
        return $this->response->setJSON([]);
    }
}

public function login()
{
    return view('auth/login');
}

public function processLogin()
{
    $userModel = new UserModel();
    $logModel = new LogAktivitasModel();
    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');

    // Cek user berdasarkan email
    $user = $userModel->where('email', $email)->first();

    if ($user && password_verify($password, $user['password'])) {
        // Cek status pengguna
        if ($user['status'] == 'Menunggu Persetujuan') {
            return redirect()->back()->with('error', 'Akun belum disetujui oleh admin.');
        }

        if ($user['status'] == 'Non Aktif') {
            return redirect()->back()->with('error', 'Akun Anda tidak aktif.');
        }

        // Set session jika akun aktif
        session()->set([
            'id_users' => $user['id_users'],
            'username' => $user['username'],
            'role'     => $user['role'],
            'isLoggedIn' => true,
        ]);

        // Catat log aktivitas login
        $logModel->insert([
            'id_users' => $user['id_users'],
            'username' => $user['username'],
            'role'     => $user['role'],
            'aktivitas'=> 'Login ke sistem',
        ]);

        // Redirect ke dashboard berdasarkan role
        return redirect()->to('/dashboard/' . strtolower($user['role']));
    }

    // Login gagal jika email atau password salah
    return redirect()->back()->with('error', 'Login gagal!, silahkan cek Email atau Password barang kali ada yang salah');
}


public function logout()
{
    $session = session();
    $logModel = new LogAktivitasModel();

    // Catat log aktivitas logout
    if ($session->has('id_users')) {
        $logModel->insert([
            'id_users' => $session->get('id_users'),
            'username' => $session->get('username'),
            'role'     => $session->get('role'),
            'aktivitas'=> 'Logout dari sistem',
        ]);
    }

    // Hapus session
    $session->destroy();
    return redirect()->to('/login');
}
}

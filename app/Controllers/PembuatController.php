<?php

namespace App\Controllers;

use App\Models\PembuatModel;
use App\Models\JurusanModel;
use App\Models\ProdiModel;
use App\Models\UnitModel;
use App\Models\UserModel;
use App\Models\LogAktivitasModel;
use App\Models\ProfilAdminModel;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PembuatController extends BaseController
{
    protected $PembuatModel;
    protected $JurusanModel;
    protected $ProdiModel;
    protected $UnitModel;
    protected $LogAktivitasModel;
    protected $UserModel;
    protected $profilAdminModel;

    public function __construct()
    {
        $this->PembuatModel = new PembuatModel();
        $this->JurusanModel = new JurusanModel();  // Perbaiki inisialisasi
        $this->ProdiModel = new ProdiModel();      // Perbaiki inisialisasi
        $this->UnitModel = new UnitModel();        // Perbaiki inisialisasi
        $this->LogAktivitasModel = new LogAktivitasModel();
        $this->UserModel = new UserModel();
        $this->profilAdminModel = new profilAdminModel();
    }

   

    public function kegiatan()
    {
        // Cek role user (hanya untuk 'Pembuat')
        if (session()->get('role') !== 'Pembuat') {
            return view('errors/403'); // Halaman akses ditolak
        }

        $userId = session()->get('id_users'); // Ambil ID user dari session
        $user = $this->profilAdminModel->getUserById($userId); // Ambil data user dari database

        $userId = session()->get('id_users'); // ID user yang login
        $perPage = 5; // Jumlah data per halaman
        $keyword = $this->request->getGet('keyword'); // Keyword pencarian

        // Ambil data kegiatan dengan pagination
        $kegiatan = $this->PembuatModel->getPaginatedKegiatan($perPage, $userId, $keyword);

        // Tentukan status kegiatan berdasarkan tanggal
        $today = date('Y-m-d');
        foreach ($kegiatan as &$item) {
            if ($item['disetujui'] === 'disetujui') {
                if ($today < $item['tanggal_mulai']) {
                    $item['status'] = 'belum dimulai';
                } elseif ($today >= $item['tanggal_mulai'] && $today <= $item['tanggal_selesai']) {
                    $item['status'] = 'sedang dilaksanakan';
                } else {
                    $item['status'] = 'sudah selesai';
                }
            } else {
                $item['status'] = 'belum disetujui';
            }
        }

        $data = [
            'title' => 'Daftar Kegiatan Saya',
            'kegiatan' => $kegiatan,
            'pager' => $this->PembuatModel->pager,
            'keyword' => $keyword,
            'username' => session()->get('username'),
            'user' => $user,
        ];

        return view('/pembuat/kegiatan', $data);
    }




public function tambah()
{
    // Mendapatkan data user yang login
    $id_users = session()->get('id_users');
    $user = $this->UserModel->getUserById($id_users);

    // Tentukan penyelenggara berdasarkan jenis_users
    $penyelenggara = $user['jenis_users'] === 'Mahasiswa' ? 'Mahasiswa' : 'Karyawan';

    // Ambil data untuk form
    $data = [
        'title' => 'Halaman Tambah Data',
        'user' => $user,
        'penyelenggara' => $penyelenggara, // Kirim data penyelenggara otomatis
        'jurusan' => $this->JurusanModel->findAll(),
        'prodi' => $this->ProdiModel->findAll(),
        'unit' => $this->UnitModel->findAll(),
    ];

    return view('pembuat/tambah', $data);
}




    public function simpan()
    {

        $id_users = session()->get('id_users');
        $user = $this->UserModel->getUserById($id_users);

        // Log aktivitas
    $logModel = new LogAktivitasModel();
    $session = session();
    
    $logModel->insert([
        'id_users' => $session->get('id_users'),
        'username' => $session->get('username'),
        'role'     => $session->get('role'),
        'aktivitas'=> 'Membuat kegiatan baru',
    ]);


        if (session()->get('role') !== 'Pembuat') {
            return view('errors/403'); // Tampilkan halaman Unauthorized
        }  
              
         $validation = \Config\Services::validation();

    $validation->setRules([
        'nama_kegiatan' => [
            'rules' => 'required|min_length[3]',
            'errors' => [
                'required' => 'Nama kegiatan tidak boleh kosong.',
                'min_length' => 'Nama kegiatan terlalu pendek, minimal 3 karakter.'
            ]
        ],
        'poster' => [
            'rules' => 'is_image[poster]|mime_in[poster,image/jpg,image/jpeg,image/png]',
            'errors' => [
                'is_image' => 'File yang diunggah bukan poster.',
                'mime_in' => 'Hanya poster dengan format JPG, JPEG, atau PNG yang diperbolehkan.'
            ]
        ],
        'video' => [
    'rules' => 'uploaded[video]|mime_in[video,video/mp4,video/x-msvideo]|max_size[video,10240]',
    'errors' => [
        'uploaded' => 'File video harus diunggah.',
        'mime_in' => 'Hanya video dengan format MP4 atau AVI yang diperbolehkan.',
        'max_size' => 'Ukuran video tidak boleh lebih dari 10MB.'
    ]
],

        'deskripsi' => [
            'rules' => 'required|min_length[10]',
            'errors' => [
                'required' => 'Deskripsi kegiatan harus diisi.',
                'min_length' => 'Deskripsi kegiatan terlalu pendek, minimal 10 karakter.'
            ]
        ],
        'tanggal_mulai' => [
            'rules' => 'required|valid_date',
            'errors' => [
                'required' => 'Tanggal mulai harus diisi.',
                'valid_date' => 'Tanggal mulai tidak valid.'
            ]
        ],
        'tanggal_selesai' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Tanggal selesai kegiatan harus diisi.'
            ]
        ],
        'lokasi' => [
            'rules' => 'required|min_length[10]',
            'errors' => [
                'required' => 'Lokasi wajib diisi ya!',
                'min_length' => 'Lokasi minimal harus 10 karakter.'
            ]
        ],
        'jenis_kegiatan' => [
            'rules' => 'required|in_list[Akademik, Non Akademik, Umum]',
            'errors' => [
                'required' => 'Jenis kegiatan wajib diisi!',
                'in_list' => 'Jenis kegiatan harus berisi Akademik, Non Akademik, atau Umum.'
            ]
        ],
        'penanggung_jawab' => [
            'rules' => 'required|min_length[5]',
            'errors' => [
                'required' => 'Penanggung jawab wajib diisi!',
                'min_length' => 'Penanggung jawab minimal harus 5 karakter.'
            ]
        ],
        'peserta' => [
            'rules' => 'required|in_list[mahasiswa, karyawan, umum, pejabat]',
            'errors' => [
                'required' => 'Peserta wajib diisi!',
                'in_list' => 'Peserta harus berisi mahasiswa, karyawan, umum, atau pejabat.'
            ]
        ],
        'nara_hubung' => [
            'rules' => 'required|min_length[10]',
            'errors' => [
                'required' => 'Nara hubung wajib diisi!',
                'min_length' => 'Nara hubung harus berisi minimal 10 karakter.'
            ]
        ],
        'penyelenggara' => [
            'rules' => 'required|in_list[Mahasiswa, Karyawan]',
            'errors' => [
                'required' => 'Penyelenggara wajib diisi!',
                'in_list' => 'Penyelenggara harus berisi Mahasiswa, Karyawan.'
            ]
        ],
        'waktu_kegiatan' => [
            'rules' => 'required|min_length[10]',
            'errors' => [
                'required' => 'Waktu kegiatan wajib diisi!',
                'min_length' => 'Waktu kegiatan harus berisi minimal 10 karakter.'
            ]
        ]
    ]);
    

    $tanggalMulai = $this->request->getPost('tanggal_mulai');
    $existingKegiatan = $this->PembuatModel
    ->where('tanggal_mulai', $tanggalMulai)
    ->first();

if ($existingKegiatan) {
    $validation->setError('tanggal_mulai', 'Kegiatan pada tanggal ' . $tanggalMulai . ' sudah ada. Silakan pilih tanggal lain.');
}

if (!$validation->withRequest($this->request)->run()) {
    return redirect()->back()->withInput()->with('errors', $validation->getErrors());
}

    // Proses upload gambar
    $poster = $this->request->getFile('poster');

    if ($poster->isValid() && !$poster->hasMoved()) {
        // Generate nama file unik
        $newName = $poster->getRandomName();
        $poster->move(ROOTPATH . 'public/assets/images', $newName);
    } else {
        return redirect()->back()->withInput()->with('error', 'Gagal mengunggah poster.');
    }

     // Proses upload video
     $video = $this->request->getFile('video');
     $videoName = null;
     if ($video && $video->isValid() && !$video->hasMoved()) {
         $videoName = $video->getRandomName();
         $video->move(ROOTPATH . 'public/assets/videos', $videoName);
     }

   
 
     // Simpan data ke database
    //  $this->pembuatModel->save([
    //      'nama_kegiatan' => $this->request->getPost('nama_kegiatan'),
    //      'poster' => $newName, // Nama file poster
    //      'video' => $videoName,   // Nama file video (null jika tidak diupload)
    //      'deskripsi' => $this->request->getPost('deskripsi'),
    //      'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
    //      'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
    //      'lokasi' => $this->request->getPost('lokasi'),
    //      'jenis_kegiatan' => $this->request->getPost('jenis_kegiatan'),
    //      'penanggung_jawab' => $this->request->getPost('penanggung_jawab'),
    //      'peserta' => $this->request->getPost('peserta'),
    //      'nara_hubung' => $this->request->getPost('nara_hubung'),
    //      'waktu_kegiatan' => $this->request->getPost('waktu_kegiatan'),
    //      'status' => 'Belum dimulai',
    //      'disetujui' => 'pending',
    //      'keterangan' => $this->request->getPost('keterangan')
    //      'penyelenggara' => $this->request->getPost('penyelenggara'),
    //  ]);

    // Logika penyelenggara
    // Ambil nilai penyelenggara dari form
    $penyelenggara = $this->request->getPost('penyelenggara');
    $jenis_karyawan = $user['jenis_karyawan'] ?? null;

    // Logika penyelenggara = Mahasiswa
    if ($penyelenggara === 'Mahasiswa' && $user['jenis_users'] === 'Mahasiswa') {
        $this->PembuatModel->save([
            'nama_kegiatan' => $this->request->getPost('nama_kegiatan'),
            'poster' => $newName, // Nama file poster
            'video' => $videoName, // Nama file video (null jika tidak diupload)
            'deskripsi' => $this->request->getPost('deskripsi'),
            'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
            'lokasi' => $this->request->getPost('lokasi'),
            'jenis_kegiatan' => $this->request->getPost('jenis_kegiatan'),
            'penanggung_jawab' => $this->request->getPost('penanggung_jawab'),
            'peserta' => $this->request->getPost('peserta'),
            'nara_hubung' => $this->request->getPost('nara_hubung'),
            'waktu_kegiatan' => $this->request->getPost('waktu_kegiatan'),
            'status' => 'Belum dimulai',
            'disetujui' => 'pending',
            'keterangan' => $this->request->getPost('keterangan'),
            'penyelenggara' => $penyelenggara,
            'id_users' => $id_users,
            'nama_lengkap' => $user['nama_lengkap'],
            'id_jurusan' => $user['id_jurusan'],
            'id_prodi' => $user['id_prodi'],
        ]);
    }

    // Logika penyelenggara = Karyawan
    elseif ($penyelenggara === 'Karyawan' && $user['jenis_users'] === 'Karyawan') {
        if ($jenis_karyawan === 'jurusan') {
            $this->PembuatModel->save([
                'nama_kegiatan' => $this->request->getPost('nama_kegiatan'),
                'poster' => $newName,
                'video' => $videoName,
                'deskripsi' => $this->request->getPost('deskripsi'),
                'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
                'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
                'lokasi' => $this->request->getPost('lokasi'),
                'jenis_kegiatan' => $this->request->getPost('jenis_kegiatan'),
                'penanggung_jawab' => $this->request->getPost('penanggung_jawab'),
                'peserta' => $this->request->getPost('peserta'),
                'nara_hubung' => $this->request->getPost('nara_hubung'),
                'waktu_kegiatan' => $this->request->getPost('waktu_kegiatan'),
                'status' => 'Belum dimulai',
                'disetujui' => 'pending',
                'keterangan' => $this->request->getPost('keterangan'),
                'penyelenggara' => $penyelenggara,
                'id_users' => $id_users,
                'jenis_karyawan' => 'jurusan',
                'nama_lengkap' => $user['nama_lengkap'],
                'id_jurusan' => $user['id_jurusan'],
                'id_prodi' => $user['id_prodi'],
            ]);
        } elseif ($jenis_karyawan === 'unit') {
            $this->PembuatModel->save([
                'nama_kegiatan' => $this->request->getPost('nama_kegiatan'),
                'poster' => $newName,
                'video' => $videoName,
                'deskripsi' => $this->request->getPost('deskripsi'),
                'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
                'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
                'lokasi' => $this->request->getPost('lokasi'),
                'jenis_kegiatan' => $this->request->getPost('jenis_kegiatan'),
                'penanggung_jawab' => $this->request->getPost('penanggung_jawab'),
                'peserta' => $this->request->getPost('peserta'),
                'nara_hubung' => $this->request->getPost('nara_hubung'),
                'waktu_kegiatan' => $this->request->getPost('waktu_kegiatan'),
                'status' => 'Belum dimulai',
                'disetujui' => 'pending',
                'keterangan' => $this->request->getPost('keterangan'),
                'penyelenggara' => $penyelenggara,
                'id_users' => $id_users,
                'jenis_karyawan' => 'unit',
                'nama_lengkap' => $user['nama_lengkap'],
                'id_unit' => $user['id_unit'],
            ]);
        } else {
            return redirect()->back()->with('error', 'Jenis karyawan tidak valid.');
        }
    }

    return redirect()->to('/dashboard/pembuat/kegiatan')->with('success', 'Data berhasil ditambahkan.');
}

    public function edit($id)
{
    if (session()->get('role') !== 'Pembuat') {
        return view('errors/403'); // Tampilkan halaman Unauthorized
    }

    // Ambil data kegiatan dengan join (termasuk nama jurusan, prodi, dan unit)
    $kegiatan = $this->PembuatModel->select('kegiatan.*, jurusan.nama_jurusan, prodi.nama_prodi, unit.nama_unit')
        ->join('jurusan', 'kegiatan.id_jurusan = jurusan.id_jurusan', 'left')
        ->join('prodi', 'kegiatan.id_prodi = prodi.id_prodi', 'left')
        ->join('unit', 'kegiatan.id_unit = unit.id_unit', 'left')
        ->where('kegiatan.id_kegiatan', $id)
        ->first();

    if (!$kegiatan) {
        return redirect()->to('/kegiatan')->with('error', 'Kegiatan tidak ditemukan.');
    }

    // Ambil data user yang login
    $user = $this->UserModel->find(session()->get('id_users'));

    $data = [
        'title' => 'Edit Kegiatan',
        'kegiatan' => $kegiatan, // Data kegiatan hasil join
        'jurusan' => $this->JurusanModel->findAll(), // Data jurusan untuk dropdown
        'prodi' => $this->ProdiModel->findAll(),     // Data prodi untuk dropdown
        'unit' => $this->UnitModel->findAll(),       // Data unit untuk dropdown
        'user' => $user, // Data user yang login
    ];

    return view('/pembuat/edit', $data);
}

    

    public function update($id)
    {

        if (session()->get('role') !== 'Pembuat') {
            return view('errors/403'); // Tampilkan halaman Unauthorized
        }

        $validation = \Config\Services::validation();

        // Menambahkan aturan validasi
        $validation->setRules([
        'nama_kegiatan' => [
            'rules' => 'required|min_length[3]',
            'errors' => [
                'required' => 'Nama kegiatan tidak boleh kosong.',
                'min_length' => 'Nama kegiatan terlalu pendek, minimal 3 karakter.'
            ]
        ],
        'poster' => [
            'rules' => 'is_image[poster]|mime_in[poster,image/jpg,image/jpeg,image/png]',
            'errors' => [
                'is_image' => 'File yang diunggah bukan poster.',
                'mime_in' => 'Hanya poster dengan format JPG, JPEG, atau PNG yang diperbolehkan.'
            ]
        ],
        'video' => [
    'rules' => 'mime_in[video,video/mp4,video/x-msvideo]|max_size[video,10240]',
    'errors' => [
        'mime_in' => 'Hanya video dengan format MP4 atau AVI yang diperbolehkan.',
        'max_size' => 'Ukuran video tidak boleh lebih dari 10MB.'
    ]
],

        'deskripsi' => [
            'rules' => 'required|min_length[10]',
            'errors' => [
                'required' => 'Deskripsi kegiatan harus diisi.',
                'min_length' => 'Deskripsi kegiatan terlalu pendek, minimal 10 karakter.'
            ]
        ],
        'tanggal_mulai' => [
            'rules' => 'required|valid_date',
            'errors' => [
                'required' => 'Tanggal mulai harus diisi.',
                'valid_date' => 'Tanggal mulai tidak valid.'
            ]
        ],
        'tanggal_selesai' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Tanggal selesai kegiatan harus diisi.'
            ]
        ],
        'lokasi' => [
            'rules' => 'required|min_length[10]',
            'errors' => [
                'required' => 'Lokasi wajib diisi ya!',
                'min_length' => 'Lokasi minimal harus 10 karakter.'
            ]
        ],
        'jenis_kegiatan' => [
            'rules' => 'required|in_list[Akademik, Non Akademik, Umum]',
            'errors' => [
                'required' => 'Jenis kegiatan wajib diisi!',
                'in_list' => 'Jenis kegiatan harus berisi Akademik, Non Akademik, atau Umum.'
            ]
        ],
        'penanggung_jawab' => [
            'rules' => 'required|min_length[5]',
            'errors' => [
                'required' => 'Penanggung jawab wajib diisi!',
                'min_length' => 'Penanggung jawab minimal harus 5 karakter.'
            ]
        ],
        'peserta' => [
            'rules' => 'required|in_list[mahasiswa, karyawan, umum, pejabat]',
            'errors' => [
                'required' => 'Peserta wajib diisi!',
                'in_list' => 'Peserta harus berisi mahasiswa, karyawan, umum, atau pejabat.'
            ]
        ],
        'nara_hubung' => [
            'rules' => 'required|min_length[10]',
            'errors' => [
                'required' => 'Nara hubung wajib diisi!',
                'min_length' => 'Nara hubung harus berisi minimal 10 karakter.'
            ]
        ],
        'waktu_kegiatan' => [
            'rules' => 'required|min_length[10]',
            'errors' => [
                'required' => 'Waktu kegiatan wajib diisi!',
                'min_length' => 'Waktu kegiatan harus berisi minimal 10 karakter.'
            ]
        ]
    ]);

       // Validasi input
    if (!$validation->withRequest($this->request)->run()) {
        return redirect()->back()->withInput()->with('errors', $validation->getErrors());
    }

    // Proses upload gambar (opsional)
    $poster = $this->request->getFile('poster');
    $newName = $this->PembuatModel->find($id)['poster']; // Nama poster default
    if ($poster && $poster->isValid() && !$poster->hasMoved()) {
        $newName = $poster->getRandomName();
        $poster->move(ROOTPATH . 'public/assets/images', $newName);
    }

    // Proses upload video (opsional)
    $video = $this->request->getFile('video');
    $videoName = $this->PembuatModel->find($id)['video']; // Nama video default
    if ($video && $video->isValid() && !$video->hasMoved()) {
        $videoName = $video->getRandomName();
        $video->move(ROOTPATH . 'public/assets/videos', $videoName);
    }


    // Ambil data user yang login
    $user = $this->UserModel->find(session()->get('id_users'));
    $kegiatan = $this->PembuatModel->find($id);

    if (!$kegiatan) {
        return redirect()->to('/dashboard/pembuat/kegiatan')->with('error', 'Kegiatan tidak ditemukan.');
    }

    // Data yang tidak dapat diubah
    $penyelenggara = $kegiatan['penyelenggara'];
    $jenisKaryawan = $kegiatan['jenis_karyawan'] ?? null;
    $namaLengkap = $kegiatan['nama_lengkap'];
    $id_jurusan = $kegiatan['id_jurusan'];
    $id_prodi = $kegiatan['id_prodi'];
    $id_unit = $kegiatan['id_unit'];

    // Update data kegiatan
    $data = [
        'nama_kegiatan' => $this->request->getPost('nama_kegiatan'),
        'poster' => $this->request->getPost('poster'), // Nama file poster (jika ada)
        'video' => $this->request->getPost('video'),   // Nama file video (jika ada)
        'deskripsi' => $this->request->getPost('deskripsi'),
        'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
        'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
        'lokasi' => $this->request->getPost('lokasi'),
        'jenis_kegiatan' => $this->request->getPost('jenis_kegiatan'),
        'penanggung_jawab' => $this->request->getPost('penanggung_jawab'),
        'peserta' => $this->request->getPost('peserta'),
        'nara_hubung' => $this->request->getPost('nara_hubung'),
        'waktu_kegiatan' => $this->request->getPost('waktu_kegiatan'),
        'status' => 'Belum dimulai',
        'disetujui' => 'pending',
        'keterangan' => $this->request->getPost('keterangan'),
        'penyelenggara' => $penyelenggara,
        'jenis_karyawan' => $jenisKaryawan,
        'nama_lengkap' => $namaLengkap,
        'id_jurusan' => $id_jurusan,
        'id_prodi' => $id_prodi,
        'id_unit' => $id_unit,
    ];

    $this->PembuatModel->update($id, $data);

    return redirect()->to('/dashboard/pembuat/kegiatan')->with('success', 'Data kegiatan berhasil diperbarui!');
}


    public function createKegiatan()
    {
        // Proses pembuatan kegiatan
        $this->LogAktivitasModel->save([
            'user_id'  => session()->get('user_id'),
            'activity' => 'Created a new Kegiatan',
            'role'     => 'Pembuat',
        ]);

        return redirect()->to('/kegiatan');
}
}

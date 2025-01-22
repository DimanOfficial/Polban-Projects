<?php

namespace App\Controllers;

use App\Models\KegiatanModel;
use App\Models\JurusanModel;
use App\Models\ProdiModel;
use App\Models\UnitModel;
use App\Models\UserModel;
use App\Models\ProfilAdminModel;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class KegiatanController extends BaseController
{
    protected $kegiatanModel;
    protected $jurusanModel;
    protected $prodiModel;
    protected $unitModel;
    protected $userModel;
    protected $profilAdminModel;

    public function __construct()
    {
        $this->kegiatanModel = new kegiatanModel();
        $this->jurusanModel = new jurusanModel();  // Perbaiki inisialisasi
        $this->prodiModel = new prodiModel();      // Perbaiki inisialisasi
        $this->unitModel = new unitModel();        // Perbaiki inisialisasi  
        $this->userModel = new UserModel(); // Inisialisasi model
        $this->profilAdminModel = new ProfilAdminModel();
        
    }


//     public function getTotalPerMinggu($filter = [])
// {
//     $query = $this->db->table('kegiatan')
//                       ->select("WEEK(tanggal_mulai) as minggu, COUNT(*) as total, jurusan.nama_jurusan, prodi.nama_prodi")
//                       ->join('jurusan', 'jurusan.id_jurusan = kegiatan.id_jurusan', 'left')
//                       ->join('prodi', 'prodi.id_prodi = kegiatan.id_prodi', 'left')
//                       ->groupBy('minggu, jurusan.nama_jurusan, prodi.nama_prodi')
//                       ->orderBy('minggu', 'ASC');
                      
//     if (!empty($filter['tahun'])) {
//         $query->where('YEAR(tanggal_mulai)', $filter['tahun']);
//     }

//     return $query->get()->getResultArray();
// }

    

    public function chart()
    {
        $kegiatanModel = new KegiatanModel();

        // Ambil data kegiatan
        $kegiatan = $kegiatanModel->findAll();

        // Format data untuk Chart.js
        $data = [
            'labels' => array_column($kegiatan, 'nama_kegiatan'), // Label berdasarkan nama kegiatan
            'data' => array_map(function ($item) {
                return strtotime($item['tanggal_selesai']) - strtotime($item['tanggal_mulai']);
            }, $kegiatan), // Durasi kegiatan (tanggal selesai - tanggal mulai)
        ];

        return view('chart', ['chartData' => json_encode($data)]);
}



    public function index()
{

    if (session()->get('role') !== 'Admin') {
        return view('errors/403'); // Tampilkan halaman Unauthorized
    }

    $perPage = 5; // Sesuaikan jumlah data per halaman
    $keyword = $this->request->getGet('keyword'); // Ambil input pencarian
    $kegiatan = null;

    if ($keyword) {
        $kegiatan = $this->kegiatanModel->search($keyword, $perPage); // Cari data berdasarkan keyword
    } else {
        $kegiatan = $this->kegiatanModel->getPaginatedKegiatan($perPage); // Data normal tanpa pencarian
    }

    // Ambil tanggal hari ini
    $today = date('Y-m-d');

    // Tentukan status kegiatan berdasarkan tanggal
    foreach ($kegiatan as &$item) {
        if ($item['disetujui'] === 'disetujui') {
            if ($today < $item['tanggal_mulai']) {
                $item['status'] = 'belum dimulai';
            } elseif ($today >= $item['tanggal_mulai'] && $today <= $item['tanggal_selesai']) {
                $item['status'] = 'sedang dilaksanakan';
            } else {
                $item['status'] = 'sudah selesai';
            }
        }
    }

    $userId = session()->get('id_users'); // Ambil ID user dari session
    $user = $this->profilAdminModel->getUserById($userId); // Ambil data user dari database

    // Ambil data untuk jurusan, prodi, dan unit
    $jurusan = $this->jurusanModel->findAll();
    $prodi = $this->prodiModel->findAll();
    $unit = $this->unitModel->findAll();

    $data = [
        'title' => 'Halaman Kegiatan',
        'jurusan' => $jurusan,
        'prodi' => $prodi,
        'unit' => $unit,
        'kegiatan' => $kegiatan, // Data kegiatan
        'pager' => $this->kegiatanModel->pager, // Objek pager untuk pagination
        'keyword' => $keyword, // Simpan keyword untuk dioper ke view
        'user' => $user,
    ];
    return view('admin/kegiatan/index', $data);
}

//bagian fungsi proses approve
public function approve($id)
    {
        if (session()->get('role') !== 'Admin') {
            return view('errors/403'); // Tampilkan halaman Unauthorized
        }

        $this->kegiatanModel->update($id, [
            'disetujui' => 'disetujui',
            'keterangan' => 'Kegiatan telah disetujui'
        ]);

        return redirect()->to('/dashboard/kegiatan')->with('success', 'Kegiatan berhasil disetujui.');
    }

    public function reject($id)
    {
        if (session()->get('role') !== 'Admin') {
            return view('errors/403'); // Tampilkan halaman Unauthorized
        }

        $alasan = $this->request->getPost('alasan');
    
        if (!$alasan) {
            return redirect()->back()->with('error', 'Alasan penolakan harus diisi!');
        }
    
        // Update status kegiatan ke 'ditolak' dan simpan keterangan
        $this->kegiatanModel->update($id, [
            'disetujui' => 'ditolak',
            'keterangan' => $alasan
        ]);
    
        return redirect()->to('/dashboard/kegiatan')->with('success', 'Kegiatan berhasil ditolak.');
    }
    


    public function create()
    {
        if (session()->get('role') !== 'Admin') {
            return view('errors/403'); // Tampilkan halaman Unauthorized
        }

        $userId = session()->get('id_users'); // Ambil ID user dari session
        $user = $this->userModel->getUserById($userId);
        $pengguna = $this->profilAdminModel->getUserById($userId); // Ambil data user dari database

        $penyelenggara = $user['jenis_users'] === 'Mahasiswa' ? 'Mahasiswa' : 'Karyawan';
        

        $data = [
            'title' => 'Tambah Kegiatan',
            'kegiatan' => $this->kegiatanModel->find(),
            'jurusan' => $this->jurusanModel->findAll(),
            'prodi' => $this->prodiModel->findAll(),
            'unit' => $this->unitModel->findAll(),
            'user' => $user,
            'pengguna' => $pengguna,
            'penyelenggara' => $penyelenggara,
        ];

        return view('admin/kegiatan/create', $data);
    }

    public function store()
{
     if (session()->get('role') !== 'Admin') {
        return view('errors/403'); // Tampilkan halaman Unauthorized
    }

        $id_users = session()->get('id_users');
        $user = $this->userModel->getUserById($id_users);

    // Menggunakan validation service untuk validasi
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
        'penyelenggara' => [
            'rules' => 'required|in_list[Mahasiswa, Karyawan]',
            'errors' => [
                'required' => 'Penyelenggara wajib diisi!',
                'in_list' => 'Penyelenggara harus berisi Mahasiswa atau Karyawan'
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
$existingKegiatan = $this->kegiatanModel
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
 
     // Logika penyelenggara
    // Ambil nilai penyelenggara dari form
    $penyelenggara = $this->request->getPost('penyelenggara');
    $jenis_karyawan = $user['jenis_karyawan'] ?? null;

    // Logika penyelenggara = Mahasiswa
    if ($penyelenggara === 'Mahasiswa' && $user['jenis_users'] === 'Mahasiswa') {
        $this->kegiatanModel->save([
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
            $this->kegiatanModel->save([
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
            $this->kegiatanModel->save([
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

    return redirect()->to('/dashboard/kegiatan')->with('success', 'Data berhasil ditambahkan.');
}

    public function edit($id)
    {
        if (session()->get('role') !== 'Admin') {
            return view('errors/403'); // Tampilkan halaman Unauthorized
        }

        $userId = session()->get('id_users'); // Ambil ID user dari session
        $user = $this->profilAdminModel->getUserById($userId); // Ambil data user dari database

         // Ambil data kegiatan dengan join (termasuk nama jurusan, prodi, dan unit)
    $kegiatan = $this->kegiatanModel->select('kegiatan.*, jurusan.nama_jurusan, prodi.nama_prodi, unit.nama_unit')
    ->join('jurusan', 'kegiatan.id_jurusan = jurusan.id_jurusan', 'left')
    ->join('prodi', 'kegiatan.id_prodi = prodi.id_prodi', 'left')
    ->join('unit', 'kegiatan.id_unit = unit.id_unit', 'left')
    ->where('kegiatan.id_kegiatan', $id)
    ->first();

         $data = [
            'title' => 'Halaman Kegiatan',
            'kegiatan' => $this->kegiatanModel->find($id),
            'jurusan' => $this->jurusanModel->findAll(),
            'prodi' => $this->prodiModel->findAll(),
            'unit' => $this->unitModel->findAll(),
            'user' => $user,
            'kegiatan' => $kegiatan, // Data kegiatan hasil join
        ];

        return view('admin/kegiatan/edit', $data);
    }

    public function update($id)
    {
        if (session()->get('role') !== 'Admin') {
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
        'penyelenggara' => [
            'rules' => 'required|in_list[Mahasiswa, Karyawan]',
            'errors' => [
                'required' => 'Penyelenggara wajib diisi!',
                'in_list' => 'Penyelenggara harus berisi Mahasiswa atau Karyawan.'
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

        // Validasi
       // Validasi input
    if (!$validation->withRequest($this->request)->run()) {
        return redirect()->back()->withInput()->with('errors', $validation->getErrors());
    }

    // Proses upload gambar (opsional)
    $poster = $this->request->getFile('poster');
    $newName = $this->kegiatanModel->find($id)['poster']; // Nama poster default
    if ($poster && $poster->isValid() && !$poster->hasMoved()) {
        $newName = $poster->getRandomName();
        $poster->move(ROOTPATH . 'public/assets/images', $newName);
    }

    // Proses upload video (opsional)
    $video = $this->request->getFile('video');
    $videoName = $this->kegiatanModel->find($id)['video']; // Nama video default
    if ($video && $video->isValid() && !$video->hasMoved()) {
        $videoName = $video->getRandomName();
        $video->move(ROOTPATH . 'public/assets/videos', $videoName);
    }

    // Ambil data user yang login
    $user = $this->userModel->find(session()->get('id_users'));
    $kegiatan = $this->kegiatanModel->find($id);

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
        'jenis_karyawan' => $jenisKaryawan,
        'nama_lengkap' => $namaLengkap,
        'id_jurusan' => $id_jurusan,
        'id_prodi' => $id_prodi,
        'id_unit' => $id_unit,
    ];

    $this->kegiatanModel->update($id, $data);

    return redirect()->to('/dashboard/kegiatan')->with('success', 'Data kegiatan berhasil diperbarui!');
}


    public function delete($id)
    {
        if (session()->get('role') !== 'Admin') {
            return view('errors/403'); // Tampilkan halaman Unauthorized
        }

        $this->kegiatanModel->delete($id);
        return redirect()->to('/dashboard/kegiatan')->with('success', 'Data berhasil dihapus.');
    }

    public function getDetail($id)
    {
        $model = new \App\Models\KegiatanModel();
        $data = $model->find($id);

        if ($data) {
            return $this->response->setJSON($data);
        } else {
            return $this->response->setJSON(['error' => 'Data tidak ditemukan, silahkan hubungi admin Diman awokawok'], 404);
        }
    }

    public function downloadPdf()
    {
        if (session()->get('role') !== 'Admin') {
            return view('errors/403'); // Tampilkan halaman Unauthorized
        }

        // Ambil data dari database
        $kegiatan = $this->kegiatanModel->findAll();

        // Generate tampilan HTML dari view
        $html = view('admin/kegiatan/pdf-kegiatan', ['kegiatan' => $kegiatan]);

        // Buat instance Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);

        // Atur ukuran dan orientasi kertas (opsional)
        $dompdf->setPaper('A4', 'landscape');

        // Render PDF
        $dompdf->render();

        // Kirim file PDF ke browser untuk diunduh
        $dompdf->stream('data_kegiatant.pdf', ['Attachment' => true]);
    }

    public function downloadExcel()
{
    if (session()->get('role') !== 'Admin') {
        return view('errors/403'); // Tampilkan halaman Unauthorized
    }
    
    // Ambil data dari database
    $kegiatan = $this->kegiatanModel->findAll();

    // Buat spreadsheet baru
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Atur header tabel
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'nama kegiatan');
    $sheet->setCellValue('C1', 'deskripsi');
    $sheet->setCellValue('D1', 'tanggal_mulai');
    $sheet->setCellValue('E1', 'tanggal_selesai');
    $sheet->setCellValue('F1', 'lokasi');
    $sheet->setCellValue('G1', 'jenis_kegiatan');
    $sheet->setCellValue('H1', 'penanggung_jawab');
    $sheet->setCellValue('I1', 'peserta');
    $sheet->setCellValue('J1', 'nara_hubung');
    $sheet->setCellValue('K1', 'penyelenggara');
    $sheet->setCellValue('L1', 'jenis_penyelenggara');
    $sheet->setCellValue('M1', 'detail_penyelenggara');
    $sheet->setCellValue('N1', 'waktu_kegiatan');

    // Atur gaya header
    $sheet->getStyle('A1:D1')->getFont()->setBold(true); // Membuat header tebal
    $sheet->getStyle('A1:D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFFF00'); // Memberikan warna kuning pada header
    $sheet->getColumnDimension('B')->setAutoSize(true); // Membuat kolom 'Nama Unit' lebar otomatis
    $sheet->getColumnDimension('C')->setAutoSize(true); // Membuat kolom 'Kode Unit' lebar otomatis
    $sheet->getColumnDimension('D')->setAutoSize(true); // Membuat kolom 'Deskripsi' lebar otomatis
    $sheet->getColumnDimension('E')->setAutoSize(true); // Membuat kolom 'Deskripsi' lebar otomatis
    $sheet->getColumnDimension('F')->setAutoSize(true); // Membuat kolom 'Deskripsi' lebar otomatis
    $sheet->getColumnDimension('G')->setAutoSize(true); // Membuat kolom 'Deskripsi' lebar otomatis
    $sheet->getColumnDimension('H')->setAutoSize(true); // Membuat kolom 'Deskripsi' lebar otomatis
    $sheet->getColumnDimension('I')->setAutoSize(true); // Membuat kolom 'Deskripsi' lebar otomatis
    $sheet->getColumnDimension('J')->setAutoSize(true); // Membuat kolom 'Deskripsi' lebar otomatis
    $sheet->getColumnDimension('K')->setAutoSize(true); // Membuat kolom 'Deskripsi' lebar otomatis
    $sheet->getColumnDimension('L')->setAutoSize(true); // Membuat kolom 'Deskripsi' lebar otomatis
    $sheet->getColumnDimension('M')->setAutoSize(true); // Membuat kolom 'Deskripsi' lebar otomatis
    $sheet->getColumnDimension('N')->setAutoSize(true); // Membuat kolom 'Deskripsi' lebar otomatis

    // Isi data ke dalam tabel
    $row = 2; // Mulai dari baris kedua (setelah header)
    foreach ($kegiatan as $index => $k) {
        $sheet->setCellValue('A' . $row, $index + 1);
        $sheet->setCellValue('B' . $row, $k['nama_kegiatan']);
        $sheet->setCellValue('C' . $row, $k['deskripsi']);
        $sheet->setCellValue('D' . $row, $k['tanggal_mulai']);
        $sheet->setCellValue('E' . $row, $k['tanggal_selesai']);
        $sheet->setCellValue('F' . $row, $k['lokasi']);
        $sheet->setCellValue('G' . $row, $k['jenis_kegiatan']);
        $sheet->setCellValue('H' . $row, $k['penanggung_jawab']);
        $sheet->setCellValue('I' . $row, $k['peserta']);
        $sheet->setCellValue('J' . $row, $k['nara_hubung']);
        $sheet->setCellValue('K' . $row, $k['penyelenggara']);
        $sheet->setCellValue('L' . $row, $k['jenis_penyelenggara']);
        $sheet->setCellValue('M' . $row, $k['detail_penyelenggara']);
        $sheet->setCellValue('N' . $row, $k['waktu_kegiatan']);
        $row++;
    }

    // Atur nama file Excel
    $filename = 'data_kegiatan.xlsx';

    // Set header untuk file download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    // Tulis file Excel dan kirim ke output
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

public function getChartData()
{
    if (session()->get('role') !== 'Pejabat') {
        return view('errors/403'); // Tampilkan halaman Unauthorized
    }

    $input = $this->request->getJSON();
    $chartType = $input->chartType ?? '';
    $tahun = $input->tahun ?? '';
    $filter = $input->filter ?? '';

    if (empty($chartType) || empty($tahun)) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Parameter tidak valid.',
        ]);
    }

    $data = [];
    if ($chartType === 'total') {
        $data = $this->KegiatanModel->getKegiatanByYear($tahun);
    } elseif (in_array($chartType, ['peserta', 'penyelenggara'])) {
        $data = $this->KegiatanModel->getKegiatanByFilter($tahun, $chartType, $filter);
    }

    if ($chartType === 'penyelenggara') {
        if ($filter === 'mahasiswa' || $filter === 'karyawan') {
            $data = $this->KegiatanModel->getKegiatanByFilter($tahun, 'penyelenggara', ucfirst($filter));
        } elseif (is_numeric($filter)) {
            $data = $this->KegiatanModel->getKegiatanByFilter($tahun, 'penyelenggara', $filter);
       }
    }

    return $this->response->setJSON([
        'status' => 'success',
        'data' => $data,
    ]);
}

public function grafik()
{
    $kegiatanModel = new KegiatanModel();

    // Ambil data kegiatan dengan jumlah peserta berdasarkan tanggal_mulai
    $data = $kegiatanModel->select("tanggal_mulai, SUM(peserta) as total_peserta")
                          ->groupBy("tanggal_mulai")
                          ->orderBy("tanggal_mulai", "ASC")
                          ->findAll();

    // Kirim data ke view
    return view('grafik_kegiatan', ['data' => $data]);
}

// Ferri
// public function index2()
// {
//     $model = new KegiatanModel();

//     // Inisialisasi bulan dan tahun
//     $currentMonth = date('n');
//     $currentYear = date('Y');


//     // Ambil filter dari request
//     // Handle filter status
//     $filterStatus = $this->request->getVar('filter') ? $this->request->getVar('filter') : null;
//     if (is_array($filterStatus)) {
//         $filterStatus = implode(',', $filterStatus); // Gabungkan array menjadi string jika diperlukan
// }
    

//     $tahun = $this->request->getVar('tahun') ? (int)$this->request->getVar('tahun') : $currentYear;
//     $bulan = $this->request->getVar('bulan') ? (int)$this->request->getVar('bulan') : $currentMonth;

//     // Data default untuk dropdown bulan dan tahun
//     $data['tahunRange'] = range(2020, date('Y'));
//     $data['bulan'] = $bulan;
//     $data['tahun'] = $tahun;
//     $data['kategori'] = $this->request->getVar('kategori') ?? 'total';
//     $data['filter']=$filterStatus;  

//     // Tambahkan data untuk dropdown dinamis
//     if ($data['kategori'] === 'jenis_penyelenggara') {
//         if ($data['filter'] === 'jurusan') {
//             $data['list'] = $model->getAllJurusan();
//         } elseif ($data['filter'] === 'prodi') {
//             $data['list'] = $model->getAllProdi();
//         } elseif ($data['filter'] === 'unit') {
//             $data['list'] = $model->getAllUnit();
//         } else {
//             $data['list'] = [];
//         }
//     }

//     // Ambil data sesuai kategori
//     switch ($data['kategori']) {
//         case 'peserta':
//             $data['grafik'] = $model->getGrafikByPeserta($bulan, $tahun, $data['filter']);
//             break;
//         case 'penyelenggara':
//             $data['grafik'] = $model->getGrafikByPenyelenggara($bulan, $tahun, $data['filter']);
//             break;
//         case 'jenis_penyelenggara':
//             $data['grafik'] = $model->getGrafikByJenisPenyelenggara($bulan, $tahun, $data['filter']);
//             break;
//         case 'jenis_kegiatan':
//             $data['grafik'] = $model->getGrafikByJenisKegiatan($bulan, $tahun, $data['filter']);
//             break;
//         case 'status':
//                 $filterStatus = $this->request->getVar('filter'); // Ambil filter dari request
//                 if ($filterStatus) {
//                     $filterStatus = explode(',', $filterStatus); // Ubah menjadi array jika ada banyak status
//                 }
//                 $data['grafik'] = $model->getGrafikByStatus($tahun, $bulan, $filterStatus);
//                 // $data['grafik'] = $model->getGrafikByStatus($tahun, $bulan, $data[filter]);
//             break;
//         default:
//             $data['grafik'] = $model->getGrafikTotal($bulan, $tahun);
//             break;
//     }

//     $dynamicFilter = $this->request->getVar('dynamic_filter') ?? null;
//     if ($dynamicFilter) {
//         $data['grafik'] = $model->getGrafikByJenisPenyelenggara($bulan, $tahun, $dynamicFilter);
//     }


//     // Data fallback untuk grafik
//     $data['grafik'] = $data['grafik'] ?? [];

//     return view('pejabat/index2', $data);
// }



// public function detail()
// {
//     $model = new KegiatanModel();

//     $minggu = $this->request->getVar('minggu');
//     $bulan = $this->request->getVar('bulan');
//     $tahun = $this->request->getVar('tahun');

//     $data['kegiatan'] = $model->getRincianByMinggu($minggu, $bulan, $tahun);

//     // Tambahkan minggu ke dalam data untuk dikirim ke view
//     $data['minggu'] = $minggu;

//     return view('views/pejabat/detail', [
//         'kegiatan' => $kegiatan,
//         'minggu' => $month,
//     ]);
// }

//index3
public function grafikTotal()
    {
        $model = new KegiatanModel();
        $tahun = $this->request->getVar('tahun') ?? date('Y');
        $bulan = $this->request->getVar('bulan') ?? date('n');

        $data['kegiatan'] = 'total'; // Aktifkan tab 'Total Kegiatan'
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['grafik'] = $model->getGrafikTotal($bulan, $tahun);
        return view('pejabat/grafik/total', $data);
    }

    public function grafikPeserta()
{
    $model = new KegiatanModel();
    $tahun = $this->request->getVar('tahun') ?? date('Y');
    $bulan = $this->request->getVar('bulan') ?? date('n');
    $filter = $this->request->getVar('filter') ?? 'mahasiswa'; // Default filter

    $data['kegiatan'] = 'peserta'; // Aktifkan tab 'Peserta'
    $data['bulan'] = $bulan;
    $data['tahun'] = $tahun;
    $data['filter'] = $filter; // Kirimkan filter ke view
    $data['grafik'] = $model->getGrafikByPeserta($bulan, $tahun, $filter);
    return view('pejabat/grafik/peserta', $data);
}


public function grafikPenyelenggara()
{
    $model = new KegiatanModel();
    $tahun = $this->request->getVar('tahun') ?? date('Y');
    $bulan = $this->request->getVar('bulan') ?? date('n');
    $filter = $this->request->getVar('filter') ?? 'mahasiswa'; // Default filter

    $data['kegiatan'] = 'penyelenggara'; // Aktifkan tab 'Penyelenggara'
    $data['bulan'] = $bulan;
    $data['tahun'] = $tahun;
    $data['filter'] = $filter; // Kirimkan filter ke view
    $data['grafik'] = $model->getGrafikByPenyelenggara($bulan, $tahun, $filter);
    return view('pejabat/grafik/penyelenggara', $data);
}

public function grafikJenisPenyelenggara()
{
    $model = new KegiatanModel();
    $tahun = $this->request->getVar('tahun') ?? date('Y');
    $bulan = $this->request->getVar('bulan') ?? date('n');
    $filter = $this->request->getVar('filter') ?? 'jurusan'; // Default filter

    $data['kegiatan'] = 'jenis_penyelenggara'; // Aktifkan tab 'Jenis Penyelenggara'
    $data['bulan'] = $bulan;
    $data['tahun'] = $tahun;
    $data['filter'] = $filter; // Kirimkan filter ke view
    $data['grafik'] = $model->getGrafikByJenisPenyelenggara($bulan, $tahun, $filter);
    return view('pejabat/grafik/jenis_penyelenggara', $data);
}

public function grafikJenisKegiatan()
{
    $model = new KegiatanModel();
    $tahun = $this->request->getVar('tahun') ?? date('Y');
    $bulan = $this->request->getVar('bulan') ?? date('n');
    $filter = $this->request->getVar('filter') ?? 'Akademik'; // Default filter

    $data['kegiatan'] = 'jenis_kegiatan'; // Aktifkan tab 'Jenis Kegiatan'
    $data['bulan'] = $bulan;
    $data['tahun'] = $tahun;
    $data['filter'] = $filter; // Kirimkan filter ke view
    $data['grafik'] = $model->getGrafikByJenisKegiatan($bulan, $tahun, $filter);
    return view('pejabat/grafik/jenis_kegiatan', $data);
}

    public function grafikStatus()
    {
        $model = new KegiatanModel();
        $tahun = $this->request->getVar('tahun') ?? date('Y');
        $bulan = $this->request->getVar('bulan') ?? date('m');

        $data['kegiatan'] = 'status'; // Aktifkan tab 'Status'
        $data['grafik'] = $model->getGrafikByStatus($tahun, $bulan);
        $data['tahun'] = $tahun;
        $data['bulan'] = $bulan;

        return view('pejabat/grafik/status', $data);
    }

    public function index4()
    {
        $tahun = $this->request->getVar('tahun') ?? date('Y');
        $kategori = $this->request->getVar('kategori') ?? 'total';
        $filterValue = $this->request->getVar('filter') ?? null;
    
        $data['tahun'] = $tahun;
        $data['kategori'] = $kategori;
        $data['filter'] = $filterValue;
    
        if ($kategori === 'total') {
            // Total kegiatan per tahun
            $kegiatan = $this->kegiatanModel->getKegiatanByYear($tahun);
        } else {
            // Filter peserta atau penyelenggara
            $filterKey = ($kategori === 'peserta') ? 'peserta' : 'penyelenggara';
            if ($filterValue) {
                $kegiatan = $this->kegiatanModel->getKegiatanByFilter($tahun, $filterKey, $filterValue);
            } else {
                $kegiatan = []; // Jika filter tidak dipilih, data kosong
            }
        }
    
        // Hitung jumlah kegiatan per bulan
        $data['kegiatanPerBulan'] = array_fill(0, 12, 0);
        foreach ($kegiatan as $item) {
            $month = (int) date('m', strtotime($item['bulan'])) - 1;
            $data['kegiatanPerBulan'][$month]++;
        }
    
        return view('kegiatan2/index4', $data);
    }

    public function approveUsers()
{
    $id = $this->request->getPost('id_users');
    $user = $this->userModel->find($id);

    if ($user) {
        $this->userModel->update($id, ['status' => 'Aktif']);

        // Kirim email persetujuan
        $email = \Config\Services::email();
        $email->setFrom('zahkianur2013@gmail.com', 'Admin');
        $email->setTo($user['email']);
        $email->setSubject('Persetujuan Akun');
        $email->setMessage("Selamat {$user['username']}, akun Anda telah disetujui. Silakan login di: " . base_url('/login'));
        // Cek apakah email berhasil dikirim
        if ($email->send()) {
            return $this->response->setJSON(['message' => 'Akun berhasil disetujui dan email terkirim.']);
        } else {
            // Menampilkan pesan kesalahan email
            return $this->response->setJSON(['message' => 'Akun berhasil disetujui, tetapi email gagal terkirim. Error: ' . $email->printDebugger()]);
        }
    }

    return $this->response->setJSON(['message' => 'Gagal menyetujui akun.']);
}

public function rejectUsers()
{
    $id = $this->request->getPost('id_users');
    $reason = $this->request->getPost('reason');
    $user = $this->userModel->find($id);

    if ($user) {
        $this->userModel->update($id, ['status' => 'Non Aktif']);

        // Kirim email penolakan
        $email = \Config\Services::email();
        $email->setFrom('zahkianur2013@gmail.com', 'Admin');
        $email->setTo($user['email']);
        $email->setSubject('Penolakan Akun');
        $email->setMessage("Mohon maaf {$user['username']}, akun Anda ditolak. Alasan: {$reason}");
        if ($email->send()) {
            return $this->response->setJSON(['message' => 'Akun berhasil ditolak dan email terkirim.']);
        } else {
            return $this->response->setJSON(['message' => 'Akun berhasil ditolak, tetapi email gagal terkirim.']);
        }
    }

    return $this->response->setJSON(['message' => 'Gagal menolak akun.']);
}

public function toggleStatus()
{
    $id = $this->request->getPost('id_users');
    $status = $this->request->getPost('status');
    $user = $this->userModel->find($id);

    if ($user) {
        $this->userModel->update($id, ['status' => $status]);
        return $this->response->setJSON(['message' => "Status berhasil diubah menjadi {$status}."]);
    }

    return $this->response->setJSON(['message' => 'Gagal mengubah status.']);
}
}

//     public function getChartData()
// {
//     $chartType = $this->request->getJSON()->chartType ?? '';
//     $tahun = $this->request->getJSON()->tahun ?? '';
//     $filter = $this->request->getJSON()->filter ?? '';

//     if (empty($chartType) || empty($tahun)) {
//         return $this->response->setJSON([
//             'status' => 'error',
//             'message' => 'Parameter tidak valid.',
//         ]);
//     }

//     $data = [];
//     if ($chartType === 'total') {
//         $data = $this->model->getKegiatanByYear($tahun);
//     } elseif ($chartType === 'peserta' || $chartType === 'penyelenggara') {
//         $data = $this->model->getKegiatanByFilter($tahun, $chartType, $filter);
//     }

//     return $this->response->setJSON([
//         'status' => 'success',
//         'data' => $data,
//     ]);
// }



//     public function grafik2()
//     {
//         $tahun = $this->request->getGet('tahun') ?? date('Y');
//     $grafikData = $this->model->getTotalKegiatanPerBulan($tahun);

//     // Kirim data ke view
//     return view('pejabat/index2', [
//         'grafik' => $grafikData,
//         'tahun' => $tahun,
//         'months' => [
//             1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
//             5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
//             9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
//         ]
//     ]);
//     }

//     public function index2()
//     {
//         // Ambil parameter dari query string
//         $selectedYear = $this->request->getGet('tahun') ?? date('Y');
//         $kategori = $this->request->getGet('kategori') ?? 'total';
//         $filter = $this->request->getGet('filter') ?? null;

//         // Ambil rentang tahun dari database
//         $tahunRange = $this->kegiatanModel->getTahunRange();

//         // Ambil data grafik berdasarkan kategori
//         $grafikData = $this->kegiatanModel->getDataByYearAndFilter($selectedYear, $kategori, $filter);

//         // Kirim data ke view
//         return view('pejabat/index2', [
//             'grafik' => $grafikData,
//             'kategori' => $kategori,
//             'selectedYear' => $selectedYear,
//             'selectedFilter' => $filter,
//             'tahunRange' => $tahunRange,
//         ]);
//     }

    


    // kiss dulu2
//     public function loadData()
// {
//     $kategori = $this->request->getGet('kategori');
//     $tahun = $this->request->getGet('tahun');

//     // Validasi input
//     if (!$kategori || !$tahun) {
//         return 'Parameter tidak valid!';
//     }

//     // Ambil data berdasarkan kategori dan tahun
//     $data = [];
//     switch ($kategori) {
//         case 'total':
//             $data = $this->kegiatanModel->getTotalKegiatanByYear($tahun);
//             break;
//         case 'peserta':
//             $data = $this->kegiatanModel->getPesertaKegiatanByYear($tahun);
//             break;
//         case 'penyelenggara':
//             $data = $this->kegiatanModel->getPenyelenggaraKegiatanByYear($tahun);
//             break;
//     }

//     // Kirimkan data ke View yang relevan
//     return view('partials/data_kegiatan', ['data' => $data]);
// }
// public function getKegiatanByMonth($tahun, $bulan = null)
// {
//     $query = $this->db->table($this->table)
//         ->select('WEEK(tanggal_mulai, 1) AS minggu, COUNT(*) AS jumlah_kegiatan')
//         ->where('YEAR(tanggal_mulai)', $tahun);

//     if ($bulan !== null) {
//         $query->where('MONTH(tanggal_mulai)', $bulan);
//     }

//     return $query->groupBy('minggu')
//                  ->orderBy('minggu', 'ASC')
//                  ->get()
//                  ->getResultArray();
// }

// public function detail()
// {
//     $model = new KegiatanModel();

//     $minggu = $this->request->getVar('minggu');
//     $bulan = $this->request->getVar('bulan');
//     $tahun = $this->request->getVar('tahun');

//     $kegiatan = $model->getRincianByMinggu($minggu, $bulan, $tahun);

//     return view('pejabat/detail', [
//         'kegiatan' => $kegiatan,
//         'minggu' => $minggu,
//     ]);
// }

// public function pejabatDashboard()
// {
//     $data = [
//         'tittle' => 'halaman pejabat'
//     ];
//     return view('kegiatan2/dashboard');
// }




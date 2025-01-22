<?php

namespace App\Controllers;

use App\Models\ProdiModel;
use App\Models\JurusanModel;
use App\Models\ProfilAdminModel;
use App\Models\UserModel;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ProdiController extends BaseController
{
    protected $prodiModel;
    protected $jurusanModel;
    protected $profilAdminModel;
    protected $userModel;

    public function __construct()
    {
        $this->prodiModel = new prodiModel();
        $this->jurusanModel = new jurusanModel();
        $this->profilAdminModel = new ProfilAdminModel();
        $this->userModel = new UserModel();
    }

    // Menampilkan data Prodi dengan pagination
    public function index()
    {
        if (session()->get('role') !== 'Admin') {
            return view('errors/403'); // Tampilkan halaman Unauthorized
        }

        $page = $this->request->getVar('page') ?? 1;
        $perPage = 5; // Jumlah data per halaman
        $keyword = $this->request->getVar('search'); // Ambil keyword dari input search

        if ($keyword) {
            // Jika ada keyword, gunakan metode pencarian
            $prodi = $this->prodiModel->searchProdi($keyword, $perPage);
        } else {
            // Jika tidak ada keyword, ambil semua data
            $prodi = $this->prodiModel->getProdiWithJurusan($perPage);
        }

        $userId = session()->get('id_users'); // Ambil ID user dari session
        $user = $this->profilAdminModel->getUserById($userId); // Ambil data user dari database

        $data = [
            'title' => 'Halaman prodi',
            'prodi' => $prodi,
            'pager' => $this->prodiModel->pager,
            'keyword' => $keyword, // Kirim kembali keyword untuk ditampilkan di input search
            'user' => $user,
        ];

        return view('admin/prodi/index', $data);
    }


    // Menampilkan form untuk membuat Prodi baru
    public function create()
    {

        $userId = session()->get('id_users'); // Ambil ID user dari session
        $user = $this->userModel->getUserById($userId);

        // Ambil semua data jurusan
        $data = [
            'title' => 'Create Data Prodi',
            'jurusan' => $this->jurusanModel->findAll(),
            'user' => $user,
        ];

        // Kirim data ke view
        return view('/admin/prodi/create', $data);
    }

    // Menyimpan data prodi baru
    public function store()
    {
        // Validasi input data
        if (!$this->validateProdi()) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Simpan data prodi
        $this->prodiModel->save($this->request->getPost());

        return redirect()->to('/prodi')->with('success', 'Data berhasil ditambahkan.');
    }

    // Menampilkan form untuk mengedit data prodi
    public function edit($id)
    {

        $userId = session()->get('id_users'); // Ambil ID user dari session
        $user = $this->userModel->getUserById($userId);

        // Ambil data prodi berdasarkan ID dan semua data jurusan
         $data = [
            'title' => 'Halaman Prodi', // Menambahkan title
            'prodi' => $this->prodiModel->find($id), // Ambil data prodi berdasarkan ID
            'jurusan' => $this->jurusanModel->findAll(), // Ambil semua data jurusan
            'user' => $user,
        ];
        return view('/admin/prodi/edit', $data);
    }

    // Menyimpan perubahan data prodi
    public function update($id)
    {
        // Validasi input data
        if (!$this->validateProdi()) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Menyimpan perubahan data
        $data = [
            'id_prodi' => $id,
            'nama_prodi' => $this->request->getPost('nama_prodi'),
            'kode_prodi' => $this->request->getPost('kode_prodi'),
            'id_jurusan' => $this->request->getPost('id_jurusan'),
            'jenjang' => $this->request->getPost('jenjang'),
            'akreditasi' => $this->request->getPost('akreditasi'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'status' => $this->request->getPost('status')
        ];

        // Update data prodi
        $this->prodiModel->update($id, $data);

        return redirect()->to('/dashboard/prodi')->with('success', 'Data prodi berhasil diperbarui!');
    }

    // Menghapus data prodi
    public function delete($id)
    {
        $this->prodiModel->delete($id);
        return redirect()->to('/dashboard/prodi')->with('success', 'Data berhasil dihapus.');
    }

    // Fungsi validasi untuk store dan update
    private function validateProdi()
    {
        // Menambahkan aturan validasi
        $validation = \Config\Services::validation();
        return $this->validate([
            'nama_prodi' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Nama prodi tidak boleh kosong.',
                    'min_length' => 'Nama prodi terlalu pendek, minimal 3 karakter.'
                ]
            ],
            'kode_prodi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kode prodi harus diisi.'
                ]
            ],
            'id_jurusan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Jurusan harus diisi.'
                ]
            ],
            'jenjang' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jenjang prodi tidak boleh kosong.'
                ]
            ],
            'akreditasi' => [
                'rules' => 'required|in_list[A,B,C]',
                'errors' => [
                    'required' => 'Akreditasi prodi harus diisi.',
                    'in_list' => 'Akreditasi prodi harus A, B, C.'
                ]
            ],
            'deskripsi' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Deskripsi tidak boleh kosong.',
                    'min_length' => 'Deskripsi terlalu pendek, minimal 3 karakter.'
                ]
            ],
            'status' => [
                'rules' => 'required|in_list[aktif, tidak aktif]',
                'errors' => [
                    'required' => 'Status prodi harus diisi.',
                    'in_list' => 'Status prodi harus Aktif, Tidak aktif'
                ]
            ]
        ]);
    }

    public function downloadPdf()
    {
        // Ambil data dari database
        $builder = $this->prodiModel->builder();
        $builder->select('prodi.*, jurusan.nama_jurusan');
        $builder->join('jurusan', 'prodi.id_jurusan = jurusan.id_jurusan');
        $prodi = $builder->get()->getResultArray();

        // Generate tampilan HTML dari view
        $html = view('prodi/pdf-prodi', ['prodi' => $prodi]);

        // Buat instance Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);

        // Atur ukuran dan orientasi kertas (opsional)
        $dompdf->setPaper('A4', 'landscape');

        // Render PDF
        $dompdf->render();

        // Kirim file PDF ke browser untuk diunduh
        $dompdf->stream('data_prodi.pdf', ['Attachment' => true]);
    }

    public function downloadExcel()
{
    // Ambil data dari database
    $prodi = $this->prodiModel->findAll();

    // Buat spreadsheet baru
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Atur header tabel
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'nama prodi');
    $sheet->setCellValue('C1', 'kode prodi');
    $sheet->setCellValue('D1', 'nama jurusan');
    $sheet->setCellValue('E1', 'jenjang');
    $sheet->setCellValue('F1', 'akreditasi');
    $sheet->setCellValue('G1', 'deskripsi');
    $sheet->setCellValue('H1', 'status');

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

    // Isi data ke dalam tabel
    $row = 2; // Mulai dari baris kedua (setelah header)
    foreach ($prodi as $index => $p) {
        $sheet->setCellValue('A' . $row, $index + 1);
        $sheet->setCellValue('B' . $row, $p['nama_prodi']);
        $sheet->setCellValue('C' . $row, $p['kode_prodi']);
        $sheet->setCellValue('D' . $row, $p['id_jurusan']);
        $sheet->setCellValue('E' . $row, $p['jenjang']);
        $sheet->setCellValue('F' . $row, $p['akreditasi']);
        $sheet->setCellValue('G' . $row, $p['deskripsi']);
        $sheet->setCellValue('H' . $row, $p['status']);
        $row++;
    }

    // Atur nama file Excel
    $filename = 'data_prodi.xlsx';

    // Set header untuk file download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    // Tulis file Excel dan kirim ke output
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
}

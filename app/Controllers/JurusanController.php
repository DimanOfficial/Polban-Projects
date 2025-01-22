<?php

namespace App\Controllers;

use App\Models\jurusanModel;
use App\Models\unitModel;
use App\Models\UserModel;
use App\Models\ProfilAdminModel;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class JurusanController extends BaseController
{
    protected $jurusanModel;
    protected $profilAdminModel;
    protected $unitModel;
    protected $UserModel;

    public function __construct()
    {
        $this->jurusanModel = new jurusanModel();
        $this->profilAdminModel = new ProfilAdminModel();
        $this->unitModel = new unitModel();
        $this->UserModel = new UserModel();
    }

    public function index()
    {
        if (session()->get('role') !== 'Admin') {
            return view('errors/403'); // Tampilkan halaman Unauthorized
        }

        $perPage = 5; // Jumlah data per halaman
        $keyword = $this->request->getGet('keyword'); // Ambil input pencarian

        if ($keyword) {
            $jurusan = $this->jurusanModel->search($keyword, $perPage); // Cari data berdasarkan keyword
        } else {
            $jurusan = $this->jurusanModel->getPaginatedJurusan($perPage); // Data normal tanpa pencarian
        }

        $userId = session()->get('id_users'); // Ambil ID user dari session
        $user = $this->profilAdminModel->getUserById($userId); // Ambil data user dari database

        $data = [
            'title' => 'Halaman jurusan',
            'jurusan' => $jurusan,
            'pager' => $this->jurusanModel->pager, // Objek pager untuk pagination
            'keyword' => $keyword, // Simpan keyword untuk dioper ke view
            'user' => $user,
        ];

        return view('admin/jurusan/index', $data);
    }


    public function create()
    {

        $userId = session()->get('id_users'); // Ambil ID user dari session
        $pengguna = $this->profilAdminModel->getUserById($userId); // Ambil data user dari database
        $user = $this->UserModel->getUserById($userId);

        $penyelenggara = $user['jenis_users'] === 'Mahasiswa' ? 'Mahasiswa' : 'Karyawan';

        $data = [
            'title' => 'Halaman jurusan',
            'user' => $user,
            'pengguna' => $pengguna,
            'penyelenggara' => $penyelenggara,
        ];

        return view('/admin/jurusan/create', $data);
    }

    public function store()
    {
        // Validasi input data
        if (!$this->validateJurusan()) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Simpan data jurusan
        $this->jurusanModel->save($this->request->getPost());

        return redirect()->to('/dashboard/jurusan')->with('success', 'Data berhasil ditambahkan.');
    }

    private function validateJurusan()
    {
        // Menambahkan aturan validasi
        $validation = \Config\Services::validation();
        return $this->validate([
            'nama_jurusan' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Nama jurusan tidak boleh kosong.',
                    'min_length' => 'Nama jurusan terlalu pendek, minimal 3 karakter.'
                ]
            ],
            'kode_jurusan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kode jurusan harus diisi.'
                ]
            ],
            'deskripsi' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Deskripsi tidak boleh kosong.',
                    'min_length' => 'Deskripsi terlalu pendek, minimal 3 karakter.'
                ]
            ],
            'akreditasi' => [
                'rules' => 'required|in_list[A,B,C]',
                'errors' => [
                    'required' => 'Akreditasi jurusan harus diisi.',
                    'in_list' => 'Akreditasi jurusan harus A, B, C.'
                ]
            ],
            'status' => [
                'rules' => 'required|in_list[aktif, tidak aktif]',
                'errors' => [
                    'required' => 'Status jurusan harus diisi.',
                    'in_list' => 'Status jurusan harus Aktif, Tidak aktif'
                ]
            ]
        ]);
    }

    public function edit($id)
    {
        $userId = session()->get('id_users'); // Ambil ID user dari session
        $pengguna = $this->profilAdminModel->getUserById($userId); // Ambil data user dari database
        $user = $this->UserModel->getUserById($userId);


        $data = [
            'title' => 'Halaman jurusan',
            'jurusan' => $this->jurusanModel->find($id),
            'user' => $user,
        ]; 
        return view('/admin/jurusan/edit', $data);
    }

    public function update($id)
    {
        // Validasi input data
        if (!$this->validateJurusan()) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Menyimpan perubahan data
        $data = [
            'id_jurusan' => $id,
            'nama_jurusan' => $this->request->getPost('nama_jurusan'),
            'kode_jurusan' => $this->request->getPost('kode_jurusan'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'akreditasi' => $this->request->getPost('akreditasi'),
            'status' => $this->request->getPost('status')
        ];

        // Update data prodi
        $this->jurusanModel->update($id, $data);

        return redirect()->to('/dashboard/jurusan')->with('success', 'Data prodi berhasil diperbarui!');
    }

    public function delete($id)
    {
        $this->jurusanModel->delete($id);
        return redirect()->to('/dashboard/jurusan')->with('success', 'Data berhasil dihapus.');
    }

    public function downloadPdf()
    {
        // Ambil data dari database
        $jurusan = $this->jurusanModel->findAll();

        // Generate tampilan HTML dari view
        $html = view('/admin/jurusan/pdf-jurusan', ['jurusan' => $jurusan]);

        // Buat instance Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);

        // Atur ukuran dan orientasi kertas (opsional)
        $dompdf->setPaper('A4', 'landscape');

        // Render PDF
        $dompdf->render();

        // Kirim file PDF ke browser untuk diunduh
        $dompdf->stream('data_jurusan.pdf', ['Attachment' => true]);
    }

    public function downloadExcel()
    {
        // Ambil data dari database
        $jurusan = $this->jurusanModel->findAll();

        // Buat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Atur header tabel
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'nama jurusan');
        $sheet->setCellValue('C1', 'kode jurusan');
        $sheet->setCellValue('D1', 'deskripsi');
        $sheet->setCellValue('E1', 'akreditasi');
        $sheet->setCellValue('F1', 'status');

        // Atur gaya header
        $sheet->getStyle('A1:D1')->getFont()->setBold(true); // Membuat header tebal
        $sheet->getStyle('A1:D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFF00'); // Memberikan warna kuning pada header
        $sheet->getColumnDimension('B')->setAutoSize(true); // Membuat kolom 'Nama Unit' lebar otomatis
        $sheet->getColumnDimension('C')->setAutoSize(true); // Membuat kolom 'Kode Unit' lebar otomatis
        $sheet->getColumnDimension('D')->setAutoSize(true); // Membuat kolom 'Deskripsi' lebar otomatis
        $sheet->getColumnDimension('E')->setAutoSize(true); // Membuat kolom 'Deskripsi' lebar otomatis
        $sheet->getColumnDimension('F')->setAutoSize(true); // Membuat kolom 'Deskripsi' lebar otomatis

        // Isi data ke dalam tabel
        $row = 2; // Mulai dari baris kedua (setelah header)
        foreach ($jurusan as $index => $j) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $j['nama_jurusan']);
            $sheet->setCellValue('C' . $row, $j['kode_jurusan']);
            $sheet->setCellValue('D' . $row, $j['deskripsi']);
            $sheet->setCellValue('E' . $row, $j['akreditasi']);
            $sheet->setCellValue('F' . $row, $j['status']);
            $row++;
        }

        // Atur nama file Excel
        $filename = 'data_jurusan.xlsx';

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

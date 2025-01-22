<?php

namespace App\Controllers;

use Dompdf\Dompdf;
use App\Models\unitModel;
use App\Models\UserModel;
use App\Models\ProfilAdminModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UnitController extends BaseController
{
    protected $unitModel;
    protected $profilAdminModel;
    protected $userModel;

    public function __construct()
    {
        $this->unitModel = new unitModel();
        $this->profilAdminModel = new ProfilAdminModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if (session()->get('role') !== 'Admin') {
            return view('errors/403'); // Tampilkan halaman Unauthorized
        }

        $perPage = 5; // Jumlah data per halaman
        $keyword = $this->request->getGet('keyword'); // Ambil input pencarian

        if ($keyword) {
            $unit = $this->unitModel->search($keyword, $perPage); // Cari data berdasarkan keyword
        } else {
            $unit = $this->unitModel->getPaginatedUnit($perPage); // Data normal tanpa pencarian
        }

        $userId = session()->get('id_users'); // Ambil ID user dari session
        $user = $this->profilAdminModel->getUserById($userId); // Ambil data user dari database

        $data = [
            'title' => 'Halaman unit',
            'unit' => $unit,
            'pager' => $this->unitModel->pager, // Objek pager untuk pagination
            'keyword' => $keyword, // Simpan keyword untuk dioper ke view
            'user' => $user,
        ];

        return view('admin/unit/index', $data);
    }

    public function create()
    {
        $userId = session()->get('id_users'); // Ambil ID user dari session
        $user = $this->userModel->getUserById($userId);

        $data = [
            'title' => 'Halaman unit',
            'user' => $user,
        ];

        return view('/admin/unit/create', $data);
    }

    public function store()
    {
        // Menggunakan validation service untuk validasi
        $validation = \Config\Services::validation();

        // Menambahkan aturan validasi
        $validation->setRules([
            'nama_unit' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Nama unit tidak boleh kosong.',
                    'min_length' => 'Nama unit terlalu pendek, minimal 3 karakter.'
                ]
            ],
            'kode_unit' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Kode unit harus diisi.',
                    'min_length' => 'Kode unit terlalu pendek, minimal 3 karakter.'
                ]
            ],
            'deskripsi' => [
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'deskripsi unit harus diisi.',
                    'min_length' => 'deskripsi unit minimal 10 karakter.'
                ]
            ]
        ]);

        // Validasi
        if (!$validation->withRequest($this->request)->run()) {
            // Mengarahkan kembali jika validasi gagal
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Simpan data setelah validasi berhasil
        $this->unitModel->save($this->request->getPost());
        return redirect()->to('/unit')->with('success', 'Data berhasil ditambahkan.');
    }


    public function edit($id)
    {
        $userId = session()->get('id_users'); // Ambil ID user dari session
        $user = $this->userModel->getUserById($userId);

        $data = [
            'title' => 'Halaman unit',
            'unit' => $this->unitModel->find($id),
            'user' => $user
        ];

        return view('/admin/unit/edit', $data);
    }

    public function update($id)
    {
        $validation = \Config\Services::validation();

        // Menambahkan aturan validasi
        $validation->setRules([
            'nama_unit' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Nama unit tidak boleh kosong.',
                    'min_length' => 'Nama unit terlalu pendek, minimal 3 karakter.'
                ]
            ],
            'kode_unit' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Kode unit harus diisi.',
                    'min_length' => 'Kode unit terlalu pendek, minimal 3 karakter.'
                ]
            ],
            'deskripsi' => [
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'deskripsi unit harus diisi.',
                    'min_length' => 'deskripsi unit minimal 10 karakter.'
                ]
            ]
        ]);

        // Validasi
        if (!$validation->withRequest($this->request)->run()) {
            // Mengarahkan kembali dengan error jika validasi gagal
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Update data unit
        $unitModel = new unitModel();
        $data = [
            'id_unit' => $id,
            'nama_unit' => $this->request->getPost('nama_unit'),
            'kode_unit' => $this->request->getPost('kode_unit'),
            'deskripsi' => $this->request->getPost('deskripsi'),
        ];

        $unitModel->update($id, $data);
        return redirect()->to('/dashboard/unit')->with('success', 'Data unit berhasil diperbarui!');
    }


    public function delete($id)
    {
        $this->unitModel->delete($id);
        return redirect()->to('/dashboard/unit')->with('success', 'Data unit berhasil dihapus.');
    }

    public function downloadPdf()
    {
        // Ambil data dari database
        $units = $this->unitModel->findAll();

        // Generate tampilan HTML dari view
        $html = view('/admin/unit/pdf-unit', ['units' => $units]);

        // Buat instance Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);

        // Atur ukuran dan orientasi kertas (opsional)
        $dompdf->setPaper('A4', 'landscape');

        // Render PDF
        $dompdf->render();

        // Kirim file PDF ke browser untuk diunduh
        $dompdf->stream('data_unit.pdf', ['Attachment' => true]);
    }
    
    public function downloadExcel()
    {
        // Ambil data dari database
        $units = $this->unitModel->findAll();

        // Buat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Atur header tabel
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Unit');
        $sheet->setCellValue('C1', 'Kode Unit');
        $sheet->setCellValue('D1', 'Deskripsi');

        // Atur gaya header
        $sheet->getStyle('A1:D1')->getFont()->setBold(true); // Membuat header tebal
        $sheet->getStyle('A1:D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFF00'); // Memberikan warna kuning pada header
        $sheet->getColumnDimension('B')->setAutoSize(true); // Membuat kolom 'Nama Unit' lebar otomatis
        $sheet->getColumnDimension('C')->setAutoSize(true); // Membuat kolom 'Kode Unit' lebar otomatis
        $sheet->getColumnDimension('D')->setAutoSize(true); // Membuat kolom 'Deskripsi' lebar otomatis

        // Isi data ke dalam tabel
        $row = 2; // Mulai dari baris kedua (setelah header)
        foreach ($units as $index => $unit) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $unit['nama_unit']);
            $sheet->setCellValue('C' . $row, $unit['kode_unit']);
            $sheet->setCellValue('D' . $row, $unit['deskripsi']);
            $row++;
        }

        // Atur nama file Excel
        $filename = 'data_unit.xlsx';

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

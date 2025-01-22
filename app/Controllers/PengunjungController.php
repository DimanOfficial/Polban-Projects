<?php

namespace App\Controllers;

use App\Models\PengunjungModel;
use App\Models\KegiatanModel;
use App\Models\JurusanModel;
use App\Models\ProdiModel;
use App\Models\UnitModel;

class PengunjungController extends BaseController
{
    protected $pengunjungModel;
    protected $kegiatanModel;
    protected $jurusanModel;
    protected $prodiModel;
    protected $unitModel;

    public function __construct()
    {
        $this->pengunjungModel = new pengunjungModel();
        $this->kegiatanModel = new kegiatanModel();
        $this->jurusanModel = new jurusanModel();  // Perbaiki inisialisasi
        $this->prodiModel = new prodiModel();      // Perbaiki inisialisasi
        $this->unitModel = new unitModel();        // Perbaiki inisialisasi
    }

    public function index()
    {
        // Ambil default limit
        $defaultLimit = 6;

        // Ambil parameter dari URL
        $limit = $this->request->getGet('limit') ?? $defaultLimit;
        $keyword = $this->request->getGet('keyword');

        // Jika tidak ada parameter limit di URL, redirect ke default limit
        if (!$this->request->getGet('limit')) {
            return redirect()->to('/?limit=' . $defaultLimit);
        }

        // Ambil data berdasarkan keyword dan status
        if ($keyword) {
            // Cari kegiatan dengan status tertentu dan keyword
            $kegiatan = $this->pengunjungModel->searchKegiatan($keyword, $limit);
        } else {
            // Ambil kegiatan dengan status tertentu dengan pagination
            $kegiatan = $this->pengunjungModel->getPaginatedPengunjung($limit);
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

        // Data untuk view
        $data = [
            'kegiatan' => $kegiatan,
            'pager' => $this->pengunjungModel->pager,
            'keyword' => $keyword,
            'limit' => $limit, // Kirim limit ke view untuk dropdown
            'title' => 'Daftar Kegiatan',
        ];

        return view('pengunjung/index', $data);
    }



public function detail($id)
{
    $kegiatanModel = new \App\Models\KegiatanModel();
    $data['kegiatan'] = $kegiatanModel->find($id);

    if (!$data['kegiatan']) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Kegiatan tidak ditemukan');
    }

    // Tambahkan title di sini
    $data['title'] = 'Detail Kegiatan';

    return view('pengunjung/detail', $data);
}


    
    public function rincian()
    {
        $perPage = 5; // Jumlah data per halaman
        $keyword = $this->request->getGet('keyword'); // Ambil input pencarian

        if ($keyword) {
            $kegiatan = $this->kegiatanModel->searchKegiatan($keyword, $perPage); // Cari data berdasarkan keyword
        } else {
            $kegiatan = $this->kegiatanModel->getPaginatedKegiatan($perPage); // Data normal tanpa pencarian
        }

        $data = [
            'kegiatan' => $kegiatan,
            'pager' => $this->kegiatanModel->pager, // Objek pager untuk pagination
            'keyword' => $keyword, // Simpan keyword untuk dioper ke view
            'title' => 'Rincian Kegiatan',
        ];

        return view('pengunjung/rincian', $data);
    }
}

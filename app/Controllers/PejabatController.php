<?php 
 
namespace App\Controllers;

use App\Models\KegiatanModel;
use App\Models\LogAktivitasModel;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\JurusanModel;
use App\Models\ProdiModel;
use App\Models\UnitModel;
use App\Models\ProfilAdminModel;

class PejabatController extends BaseController
{
    protected $KegiatanModel;
    protected $LogAktivitasModel;
    protected $JurusanModel;
    
    protected $ProdiModel;
    protected $UnitModel;
    protected $profilAdminModel;

    public function __construct()
    {
        $this->KegiatanModel = new KegiatanModel();
        $this->LogAktivitasModel = new LogAktivitasModel();
        $this->profilAdminModel = new ProfilAdminModel();
    }

    // PejabatController.php
    public function index()
{

    $kegiatanModel = new KegiatanModel();

    $tahun = $this->request->getGet('tahun') ?? date('Y');
    $bulan = $this->request->getGet('bulan');

    // Ambil data berdasarkan filter
    $filteredKegiatan = $kegiatanModel->getFilteredKegiatan($tahun, $bulan);
    $weeklyData = $kegiatanModel->getWeeklyData($tahun, $bulan);

    // Konversi data mingguan untuk grafik
    $weeks = [];
    $kegiatanPerWeek = [];
    foreach ($weeklyData as $data) {
        $weeks[] = "Minggu " . $data['minggu'];
        $kegiatanPerWeek[] = $data['total'];
    }

    $username = session()->get('username');

    $userId = session()->get('id_users'); // Ambil ID user dari session
    $user = $this->profilAdminModel->getUserById($userId); // Ambil data user dari database

    return view('pejabat/index' , [
        'title' => 'profilepejabat',
        'username' => $username,
        'tahun' => $tahun,
        'bulan' => $bulan,
        'total_kegiatan' => $filteredKegiatan->total_kegiatan ?? 0,
        'weeks' => $weeks,
        'kegiatan_per_week' => $kegiatanPerWeek,
        'user' => $user,
]);

}




    // Controller PejabatController.php
    public function getGrafikByJenisKegiatan()
{
    $tahun = $this->request->getGet('tahun');
    $bulan = $this->request->getGet('bulan');
    
    // Mengambil data jenis kegiatan berdasarkan filter tahun dan bulan
    $filteredKegiatan = $this->kegiatanModel->getGrafikByJenisKegiatan([
        'tahun' => $tahun,
        'bulan' => $bulan,
    ]);
    
    // Menyiapkan label jenis kegiatan
    $labels = ['Akademik', 'Non Akademik', 'Umum'];
    
    // Inisialisasi jumlah kegiatan per jenis
    $jumlahKegiatan = [
        'Akademik' => 0,
        'Non Akademik' => 0,
        'Umum' => 0
    ];
    
    // Mengelompokkan data berdasarkan jenis kegiatan
    foreach ($filteredKegiatan as $data) {
        $jenis = $data['jenis_kegiatan'];
        if (isset($jumlahKegiatan[$jenis])) {
            $jumlahKegiatan[$jenis] = $data['jumlah_kegiatan'];
        }
    }
    
    // Mengirimkan data ke view
    return $this->response->setJSON([
        'labels' => $labels,
        'kegiatan' => array_values($jumlahKegiatan) // Menyusun jumlah kegiatan per jenis
    ]);

    // Controller - Debugging data
    log_message('debug', 'Labels: ' . json_encode($labels));
    log_message('debug', 'Data: ' . json_encode($kegiatan));
}


// peserta peler
public function peserta() 
{
    $tahun = $this->request->getGet('tahun') ?? date('Y');
    $bulan = $this->request->getGet('bulan') ?? null;
    
    // Data peserta berdasarkan tahun dan bulan
    $filteredKegiatan = $this->KegiatanModel->getFilteredKegiatan($tahun, $bulan);
    
    // Data mingguan untuk grafik
    $weeklyData = $this->KegiatanModel->getWeeklyData($tahun, $bulan);
    
    // Memproses data untuk grafik mingguan
    $weeks = [];
    $kegiatanPerWeek = [];
    foreach ($weeklyData as $data) {
        $weeks[] = "Minggu " . $data['minggu'];
        $kegiatanPerWeek[] = $data['total'];
    }

    // Jika data mingguan kosong, tambahkan data default
    if (empty($weeks)) {
        $weeks = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'];
        $kegiatanPerWeek = [0, 0, 0, 0];
    }
    
    // Memproses data untuk grafik bulanan
    $bulanLabels = [];
    $pesertaPerBulan = [];
    foreach ($filteredKegiatan as $data) {
        $bulanLabels[] = date('F', mktime(0, 0, 0, $data['bulan'], 1));
        $pesertaPerBulan[] = $data['jumlah_peserta'];
    }
    
    // Jika data bulanan kosong, tambahkan data default
    if (empty($bulanLabels)) {
        $bulanLabels = array_map(function ($month) {
            return date('F', mktime(0, 0, 0, $month, 1));
        }, range(1, 12));
        $pesertaPerBulan = array_fill(0, 12, 0);
    }
    
    return view('pejabat/index', [
        'weeks' => $weeks,
        'kegiatan_per_week' => $kegiatanPerWeek,
        'bulan_labels' => $bulanLabels,
        'peserta_per_bulan' => $pesertaPerBulan,
        'tahun' => $tahun,
        'bulan' => $bulan
    ]);
}

public function dataPeserta()
    {
        $filter = $this->request->getVar('filter') ?? 'mahasiswa'; // Default filter mahasiswa
        $kegiatanModel = new KegiatanModel();
        
        // Ambil data peserta berdasarkan filter
        $dataPeserta = $kegiatanModel
            ->select('MONTH(tanggal_mulai) as bulan, COUNT(*) as jumlah')
            ->where('peserta', $filter)
            ->groupBy('bulan')
            ->findAll();

            $bulan = [];
        $jumlahPeserta = [];
        
        foreach ($dataPeserta as $data) {
            $bulan[] = $data['bulan'];
            $jumlahPeserta[] = $data['jumlah'];
        }

        return view('/pejabat/berdasarkanPeserta', [
            'bulan' => json_encode($bulan),
            'jumlahPeserta' => json_encode($jumlahPeserta),
            'filter' => $filter
        ]);
    }

    
    public function tbl_kegiatan()
    {
        $perPage = 5; // Sesuaikan jumlah data per halaman
        $keyword = $this->request->getGet('keyword'); // Ambil input pencarian
        $kegiatan = null;
        
        if ($keyword) {
            $kegiatan = $this->KegiatanModel->search($keyword, $perPage); // Cari data berdasarkan keyword
        } else {
            $kegiatan = $this->KegiatanModel->getPaginatedKegiatan($perPage); // Data normal tanpa pencarian
        }

        $userId = session()->get('id_users'); // Ambil ID user dari session
        $user = $this->profilAdminModel->getUserById($userId); // Ambil data user dari database
        
        $data = [
            'title' => 'Halaman Kegiatan',
            'kegiatan' => $kegiatan, // Data kegiatan
            'pager' => $this->KegiatanModel->pager, // Objek pager untuk pagination
            'keyword' => $keyword, // Simpan keyword untuk dioper ke view
            'user' => $user,
        ];
        
        return view('pejabat/tbl_kegiatan/tbl_kegiatan', $data);
    }
    
    public function filterGrafik()
{
    $tahun = $this->request->getGet('tahun') ?? date('Y'); // Default ke tahun sekarang
    $bulan = $this->request->getGet('bulan') ?? null; // Biarkan null jika tidak diberikan

    $data = $this->KegiatanModel->getKegiatanByMonth($tahun, $bulan);

    return $this->response->setJSON([
        'labels' => array_map(fn($week) => 'Minggu ' . $week, array_column($data, 'minggu')),
        'kegiatan' => array_column($data, 'jumlah_kegiatan')
    ]);
}
public function index4()
{
    if (session()->get('role') !== 'Pejabat') {
        return view('errors/403');
    }
    
    $userId = session()->get('id_users');
    $user = $this->profilAdminModel->getUserById($userId);

    $tahun = $this->request->getVar('tahun') ?? date('Y');
    $kategori = $this->request->getVar('kategori') ?? 'total';
    $filterValue = $this->request->getVar('filter') ?? null;

    $data = [
        'tahun' => $tahun,
        'kategori' => $kategori,
        'filter' => $filterValue,
        'user' => $user,
        'units' => $this->KegiatanModel->getUnitNames(),
        'jurusans' => $this->KegiatanModel->getJurusanNames(),
        'prodis' => $this->KegiatanModel->getProdiNames(),
        'kegiatanPerBulan' => array_fill(0, 12, 0),
        'penyelenggaraCount' => ['mahasiswa' => 0, 'karyawan' => 0],
    ];

    // Ambil data kegiatan berdasarkan kategori
    if ($kategori === 'total') {
        $kegiatan = $this->KegiatanModel
                        ->select('id_kegiatan, penyelenggara, id_jurusan, id_prodi, id_unit, tanggal_mulai')
                        ->getKegiatanByYear($tahun);
    } else {
        $filterKey = ($kategori === 'peserta') ? 'peserta' : 'penyelenggara';
        $kegiatan = $filterValue 
                    ? $this->KegiatanModel
                          ->select('id_kegiatan, penyelenggara, id_jurusan, id_prodi, id_unit, tanggal_mulai')
                          ->getKegiatanByFilter($tahun, $filterKey, $filterValue) 
                    : [];
    }

    // Validasi data kegiatan
    if (!empty($kegiatan)) {
        foreach ($kegiatan as $item) {
            if (!empty($item['tanggal_mulai'])) {
                $month = date('n', strtotime($item['tanggal_mulai'])) - 1;
                $data['kegiatanPerBulan'][$month]++;
            }

            if (!empty($kegiatan)) {
                foreach ($kegiatan as $item) {
                    log_message('debug', 'Item Kegiatan: ' . json_encode($item));
            
                    if (!empty($item['tanggal_mulai'])) {
                        $month = date('n', strtotime($item['tanggal_mulai'])) - 1;
                        $data['kegiatanPerBulan'][$month]++;
                    }
            
                    if (isset($item['penyelenggara']) && !empty($item['penyelenggara'])) {
                        $penyelenggara = strtolower($item['penyelenggara']);
                        if ($penyelenggara === 'mahasiswa') {
                            if (!empty($item['id_jurusan']) || !empty($item['id_prodi'])) {
                                $data['penyelenggaraCount']['mahasiswa']++;
                            }
                        } elseif ($penyelenggara === 'karyawan') {
                            if (!empty($item['id_unit'])) {
                                $data['penyelenggaraCount']['karyawan']++;
                            }
                        }
                    }
                }
            } else {
                log_message('debug', 'Kegiatan kosong atau tidak ditemukan.');
            }

            // Pastikan 'penyelenggara' ada sebelum mengaksesnya
            if (isset($item['penyelenggara']) && !empty($item['penyelenggara'])) {
                $penyelenggara = strtolower($item['penyelenggara']);
                if ($penyelenggara === 'mahasiswa') {
                    if (!empty($item['id_jurusan']) || !empty($item['id_prodi'])) {
                        $data['penyelenggaraCount']['mahasiswa']++;
                    }
                } elseif ($penyelenggara === 'karyawan') {
                    if (!empty($item['id_unit'])) {
                        $data['penyelenggaraCount']['karyawan']++;
                    }
                }
            }
        }
    }

    return view('pejabat/grafik/index', $data);
}
public function getChartDataPenyelenggara()
{
    if (session()->get('role') !== 'Pejabat') {
        return view('errors/403');
    }

    $input = $this->request->getJSON();
    $tahun = $input->tahun ?? date('Y');
    $filter = $input->filter ?? null;

    if (empty($tahun)) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Tahun tidak valid.',
        ]);
    }

    $data = [];

    // Proses logika berdasarkan filter penyelenggara
    if ($filter) {
        if (strpos($filter, '-') !== false) {
            [$jurusan, $prodi] = explode('-', $filter);
            $jurusan = $jurusan !== 'showAllJurusan' ? $jurusan : null;
            $prodi = $prodi !== 'showAllProdi' ? $prodi : null;

            $data = $this->KegiatanModel->getDataPenyelenggaraKaryawan($tahun, $jurusan, $prodi);
        } else {
            $data = $this->KegiatanModel->getKegiatanByFilter($tahun, 'penyelenggara', ucfirst($filter));
        }
    } else {
        $data = $this->KegiatanModel->getDataPenyelenggaraKaryawan($tahun);
    }

    return $this->response->setJSON([
        'status' => 'success',
        'data' => $data,
]);
}

public function getChartData()
{
    if (session()->get('role') !== 'Pejabat') {
        return view('errors/403');
    }

    $input = $this->request->getJSON();
    $chartType = $input->chartType ?? '';
    $tahun = $input->tahun ?? date('Y');
    $filter = $input->filter ?? null;

    if (empty($chartType) || empty($tahun)) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Parameter tidak valid.',
        ]);
    }

    $data = [];
    
    if ($chartType === 'penyelenggara') {
        if ($filter) {
            if (strpos($filter, '-') !== false) {
                [$jurusan, $prodi] = explode('-', $filter);

                // Pastikan filter valid
                $jurusan = ($jurusan !== 'showAllJurusan') ? $jurusan : null;
                $prodi = ($prodi !== 'showAllProdi') ? $prodi : null;

                $data = $this->KegiatanModel->getDataPenyelenggaraKaryawan($tahun, $jurusan, $prodi);
            } else {
                $data = $this->KegiatanModel->getKegiatanByFilter($tahun, 'penyelenggara', ucfirst($filter));
            }
        } else {
            $data = $this->KegiatanModel->getDataPenyelenggaraKaryawan($tahun);
        }
    } elseif ($chartType === 'total') {
        $data = $this->KegiatanModel->getKegiatanByYear($tahun);
    } elseif ($chartType === 'peserta') {
        $data = $this->KegiatanModel->getKegiatanByFilter($tahun, $chartType, $filter);
    }

    log_message('debug', 'Data untuk grafik: ' . json_encode($data));

    return $this->response->setJSON([
        'status' => 'success',
        'data' => $data,
]);
}

private function processChartFilter($chartType, $tahun, $filter)
{
    return match ($filter) {
        'showAllMahasiswa' => $this->KegiatanModel->getAllDataByPenyelenggara('mahasiswa'),
        'showAllJurusan' => $this->KegiatanModel->getAllDataByPenyelenggara('mahasiswa', 'jurusan'),
        'showAllProdi' => $this->KegiatanModel->getAllDataByPenyelenggara('mahasiswa', 'prodi'),
        'showAllUnit' => $this->KegiatanModel->getAllDataByPenyelenggara('karyawan'),
        default => $this->KegiatanModel->getKegiatanByFilter($tahun, $chartType, $filter),
    };
    if ($filter === 'showAllJurusan' || $filter === 'showAllProdi') {
        // Tangani filter khusus jurusan dan prodi
        $data = $this->KegiatanModel->getDataPenyelenggaraKaryawan(
            $tahun, 
            $filter === 'showAllJurusan' ? null : $filter, 
            $filter === 'showAllProdi' ? null : $filter
        );
    } else {
        $data = $this->KegiatanModel->getKegiatanByFilter($tahun, $chartType, $filter);
    }

    return $data;
}

public function getDropdownOptions()
{
    if (session()->get('role') !== 'Pejabat') {
        return view('errors/403');
    }

    $penyelenggaraType = $this->request->getJSON()->penyelenggaraType ?? '';

    if (empty($penyelenggaraType)) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Parameter penyelenggara tidak valid.',
        ]);
    }

    $options = [];
    if ($penyelenggaraType === 'mahasiswa') {
        $kategori = $this->request->getJSON()->kategori ?? '';
        if ($kategori === 'jurusan') {
            $options = $this->KegiatanModel->getJurusanNames();
        } elseif ($kategori === 'prodi') {
            $options = $this->KegiatanModel->getProdiNames();
        }
    } elseif ($penyelenggaraType === 'karyawan') {
        $options = $this->KegiatanModel->getUnitNames();
    }

    return $this->response->setJSON([
        'status' => 'success',
        'options' => $options,
    ]);
}

public function getKegiatanByFilter($tahun, $kategori, $filterValue)
{
    $builder = $this->db->table('kegiatan');
    $builder->select('id_kegiatan, penyelenggara, id_jurusan, id_prodi, id_unit, tanggal_mulai');
    $builder->where('YEAR(tanggal_mulai)', $tahun);

    if ($kategori === 'penyelenggara') {
        if ($filterValue === 'jurusan') {
            $builder->where('penyelenggara', 'karyawan');
            $builder->where('id_jurusan', $this->request->getVar('id_jurusan')); // Pastikan parameter jurusan diteruskan
        } elseif ($filterValue === 'prodi') {
            $builder->where('penyelenggara', 'karyawan');
            $builder->where('id_prodi', $this->request->getVar('id_prodi')); // Pastikan parameter prodi diteruskan
        } elseif ($filterValue === 'unit') {
            $builder->where('penyelenggara', 'karyawan');
            $builder->where('id_unit', $this->request->getVar('id_unit')); // Unit karyawan
        }
    }

    return $builder->get()->getResultArray();
}

// public function getAllDataByPenyelenggara($penyelenggara, $kategori = null)
// {
//     $builder = $this->db->table('kegiatan');
//     $builder->select('MONTH(tanggal_mulai) AS bulan, COUNT(*) AS total')
//             ->where('YEAR(tanggal_mulai)', date('Y'));

//     if ($penyelenggara === 'mahasiswa') {
//         $builder->where('penyelenggara', 'Mahasiswa');
//         if ($kategori) {
//             $builder->where('jenis_penyelenggara', $kategori);
//         }
//     } elseif ($penyelenggara === 'karyawan') {
//         $builder->where('penyelenggara', 'Karyawan');
//         if ($kategori === 'unit') {
//             $builder->where('jenis_penyelenggara', 'unit');
//         }
//     }

//     return $builder->groupBy('MONTH(tanggal_mulai)')->get()->getResultArray();
// }

// public function getAllDataByPenyelenggara($penyelenggara, $kategori = null)
// {
//     $builder = $this->db->table('kegiatan');
//     $builder->select('MONTH(tanggal_mulai) AS bulan, COUNT(*) AS total')
//             ->where('YEAR(tanggal_mulai)', date('Y'));

//     if ($penyelenggara === 'mahasiswa') {
//         $builder->where('penyelenggara', 'Mahasiswa');
//         if ($kategori) {
//             $builder->where('jenis_penyelenggara', $kategori);
//         }
//     } elseif ($penyelenggara === 'karyawan') {
//         $builder->where('penyelenggara', 'Karyawan');
//         if ($kategori === 'unit') {
//             $builder->where('jenis_penyelenggara', 'unit');
//         }
//     }

//     return $builder->groupBy('MONTH(tanggal_mulai)')->get()->getResultArray();
// }

// public function grafikJenisPenyelenggara()
// {
//     $model = new KegiatanModel();
//     $tahun = $this->request->getVar('tahun') ?? date('Y');
//     $bulan = $this->request->getVar('bulan') ?? date('n');
//     $filter = $this->request->getVar('filter') ?? 'jurusan'; // Default filter
    
//     $data['kegiatan'] = 'jenis_penyelenggara'; // Aktifkan tab 'Jenis Penyelenggara'
//     $data['bulan'] = $bulan;
//     $data['tahun'] = $tahun;
//     $data['filter'] = $filter; // Kirimkan filter ke view
//     $data['grafik'] = $model->getGrafikByJenisPenyelenggara($bulan, $tahun, $filter);
//     return view('pejabat/grafik/jenis_penyelenggara', $data);
// }

public function detail()
{
    $model = new KegiatanModel();
    
    $minggu = $this->request->getVar('minggu');
    $bulan = $this->request->getVar('bulan');
    $tahun = $this->request->getVar('tahun');
    
    $kegiatan = $model->getRincianByMinggu($minggu, $bulan, $tahun);
    
    return view('pejabat/detail', [
        'kegiatan' => $kegiatan,
        'minggu' => $minggu,
    ]);
}

public function pejabatDashboard()
{
    $data = [
        'tittle' => 'halaman pejabat'
    ];
    // return view('pejabat/dashboard');
}

public function approveKegiatan($id)
    {
        // Proses approve kegiatan
        $this->activityLogModel->save([
            'user_id'  => session()->get('user_id'),
            'activity' => "Approved Kegiatan ID $id",
            'role'     => 'Pejabat',
        ]);

        return redirect()->to('/kegiatan');
}

public function getFilteredData()
{
    $input = $this->request->getJSON();
    $penyelenggaraType = $input->penyelenggaraType ?? '';
    $jenisKaryawan = $input->jenisKaryawan ?? '';
    $jurusan = $input->jurusan ?? '';
    $prodi = $input->prodi ?? '';

    $builder = $this->db->table('kegiatan');
    $builder->select('id_kegiatan, penyelenggara, id_jurusan, id_prodi, id_unit, tanggal_mulai');

    // Filter untuk penyelenggara karyawan
    if ($penyelenggaraType === 'karyawan') {
        $builder->where('penyelenggara', 'Karyawan');

        if ($jenisKaryawan === 'jurusan' && !empty($jurusan)) {
            $builder->where('id_jurusan', $jurusan);

            if (!empty($prodi)) {
                $builder->where('id_prodi', $prodi);
            }
        } elseif ($jenisKaryawan === 'unit') {
            $builder->where('jenis_penyelenggara', 'unit');
        }
    }

    $data = $builder->get()->getResultArray();

    return $this->response->setJSON([
        'status' => 'success',
        'data' => $data,
    ]);
}


public function downloadPdf()
    {
        if (session()->get('role') !== 'Pejabat') {
            return view('errors/403');
        }

        // Ambil data dari database
        $kegiatanModel = $this->KegiatanModel->findAll();

        // Generate tampilan HTML dari view
        $html = view('/pejabat/tbl_kegiatan/pdf-kegiatan', ['kegiatan' => $kegiatanModel]);

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
    if (session()->get('role') !== 'Pejabat') {
        return view('errors/403'); // Tampilkan halaman Unauthorized
    }
    
    // Ambil data dari database
    $kegiatanModel = $this->KegiatanModel->findAll();


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
    foreach ($kegiatanModel as $index => $k) {
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

}


// public function getKegiatanByMonth($tahun, $bulan = null)
// {
//     $query = $this->db->table($this->table)
//     ->select('WEEK(tanggal_mulai, 1) AS minggu, COUNT(*) AS jumlah_kegiatan')
//     ->where('YEAR(tanggal_mulai)', $tahun);
    
//     if ($bulan !== null) {
//         $query->where('MONTH(tanggal_mulai)', $bulan);
//     }

//     return $query->groupBy('minggu')
//     ->orderBy('minggu', 'ASC')
//     ->get()
//     ->getResultArray();
// }


// public function getTahunRange()
// {
//     return $this->select('YEAR(tanggal_mulai) as tahun')
//     ->groupBy('tahun')
//     ->orderBy('tahun', 'ASC')
//                 ->findAll();
// }



// public function grafikPerMinggu()
// {
    //     $tahun = $this->request->getGet('tahun') ?? date('Y');
    //     $bulan = $this->request->getGet('bulan') ?? null;
    
//     $filteredKegiatan = $this->KegiatanModel->getFilteredKegiatan($tahun, $bulan);
//     $weeklyData = $this->KegiatanModel->getWeeklyData($tahun, $bulan);

//     $weeks = [];
//     $kegiatanPerWeek = [];
//     foreach ($weeklyData as $data) {
//         $weeks[] = "Minggu " . $data['minggu'];
//         $kegiatanPerWeek[] = $data['total'];
//     }

//     return view('pejabat/grafik_perminggu', [
//         'weeks' => $weeks,
//         'kegiatan_per_week' => $kegiatanPerWeek,
//         'tahun' => $tahun,
//         'bulan' => $bulan
//     ]);
// }


// public function getChartData()
// {
//     if (session()->get('role') !== 'Pejabat') {
//         return view('errors/403'); // Tampilkan halaman Unauthorized
//     }

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
//         $data = $this->KegiatanModel->getKegiatanByYear($tahun);
//     } elseif ($chartType === 'peserta') {
//         $data = $this->KegiatanModel->getKegiatanByFilter($tahun, 'peserta', $filter);
//     } elseif ($chartType === 'penyelenggara') {
//         if ($filter === 'Show All Mahasiswa') {
//             $data = $this->KegiatanModel->getAllDataByPenyelenggara('mahasiswa');
//         } elseif ($filter === 'Show All Jurusan') {
//             $data = $this->KegiatanModel->getAllDataByPenyelenggara('mahasiswa', 'jurusan');
//         } elseif ($filter === 'Show All Prodi') {
//             $data = $this->KegiatanModel->getAllDataByPenyelenggara('mahasiswa', 'prodi');
//         } elseif ($filter === 'Show All Unit') {
//             $data = $this->KegiatanModel->getAllDataByPenyelenggara('direktur');
//         }
//     }

//     return $this->response->setJSON([
//         'status' => 'success',
//         'data' => $data,
//     ]);
// }


// public function grafik2()
// {
    //     $tahun = $this->request->getGet('tahun') ?? date('Y');
    // $grafikData = $this->model->getTotalKegiatanPerBulan($tahun);
    
    // // Kirim data ke view
    // return view('pejabat/index2', [
        //     'grafik' => $grafikData,
        //     'tahun' => $tahun,
        //     'months' => [
            //         1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            //         5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            //         9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            //     ]
            // ]);
            // }
            
    // public function index2()
    // {
        //     // Ambil parameter dari query string
        //     $selectedYear = $this->request->getGet('tahun') ?? date('Y');
        //     $kategori = $this->request->getGet('kategori') ?? 'total';
        //     $filter = $this->request->getGet('filter') ?? null;
        
        //     // Ambil rentang tahun dari database
        //     $tahunRange = $this->kegiatanModel->getTahunRange();
        
        //     // Ambil data grafik berdasarkan kategori
        //     $grafikData = $this->kegiatanModel->getDataByYearAndFilter($selectedYear, $kategori, $filter);
        
        //     // Kirim data ke view
        //     return view('pejabat/index2', [
            //         'grafik' => $grafikData,
            //         'kategori' => $kategori,
            //         'selectedYear' => $selectedYear,
            //         'selectedFilter' => $filter,
    //         'tahunRange' => $tahunRange,
    //     ]);
    // }
    
    
    
    
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

    
    // public function tbl_kegiatan()
    // {
    //     $perPage = 3; // Jumlah data per halaman
    //     $keyword = $this->request->getGet('keyword'); // Ambil input pencarian
    
    //     if ($keyword) {
    //         $kegiatan = $this->KegiatanModel->search($keyword, $perPage); // Cari data berdasarkan keyword
    //     } else {
    //         $kegiatan = $this->kegiatanModel->getPaginatedKegiatan($perPage); // Data normal tanpa pencarian
    //     }
    
    //     $data = [
    //         'kegiatan' => $kegiatan,
    //         'pager' => $this->KegiatanModel->pager, // Objek pager untuk pagination
    //         'keyword' => $keyword, // Simpan keyword untuk dioper ke view
    //         'title' => 'Rincian Kegiatan',
    //     ];
    
    //     return view('pejabat/tbl_kegiatan', $data);
    // }
    // }
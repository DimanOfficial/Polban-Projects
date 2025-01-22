<?php

namespace App\Models;

use CodeIgniter\Model;

class KegiatanModel extends Model
{
    protected $table = 'kegiatan';
    protected $primaryKey = 'id_kegiatan';
    protected $allowedFields = [
        'nama_kegiatan',
        'poster',
        'video',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi',
        'jenis_kegiatan',
        'penanggung_jawab',
        'peserta',
        'nara_hubung',
        'penyelenggara',
        'waktu_kegiatan',
        'status',
        'disetujui', 
        'keterangan',
        'id_users',
        'nama_lengkap',
        'jenis_karyawan',
        'id_jurusan', 
        'id_prodi', 
        'id_unit'
    ];

    public function filterGrafikKegiatan($filters = [])
    {
        $query = $this->select('YEAR(tanggal_mulai) as tahun, MONTH(tanggal_mulai) as bulan, id_jurusan, id_prodi, COUNT(*) as jumlah_kegiatan');
    
        if (!empty($filters['tahun'])) {
            $query->where('YEAR(tanggal_mulai)', $filters['tahun']);
        }
        if (!empty($filters['bulan'])) {
            $query->where('MONTH(tanggal_mulai)', $filters['bulan']);
        }
      
    
        return $query->groupBy('tahun, bulan')->findAll();
    }

    public function getPaginatedKegiatan($perPage)
{
    return $this->select('kegiatan.*, jurusan.nama_jurusan, prodi.nama_prodi, unit.nama_unit')
        ->join('jurusan', 'kegiatan.id_jurusan = jurusan.id_jurusan', 'left')
        ->join('prodi', 'kegiatan.id_prodi = prodi.id_prodi', 'left')
        ->join('unit', 'kegiatan.id_unit = unit.id_unit', 'left')
        ->orderBy('tanggal_mulai', 'DESC')
        ->paginate($perPage, 'kegiatan');
}

public function search($keyword, $perPage)
{
    return $this->select('kegiatan.*, jurusan.nama_jurusan, prodi.nama_prodi, unit.nama_unit')
        ->join('jurusan', 'kegiatan.detail_penyelenggara = jurusan.id_jurusan', 'left')
        ->join('prodi', 'kegiatan.detail_penyelenggara = prodi.id_prodi', 'left')
        ->join('unit', 'kegiatan.detail_penyelenggara = unit.id_unit', 'left')
        ->groupStart() // Memulai grouping kondisi pencarian
            ->like('kegiatan.nama_kegiatan', $keyword)
            ->orLike('kegiatan.deskripsi', $keyword) // Pastikan menyertakan nama tabel
            ->orLike('kegiatan.tanggal_mulai', $keyword)
            ->orLike('kegiatan.tanggal_selesai', $keyword)
            ->orLike('kegiatan.lokasi', $keyword)
            ->orLike('kegiatan.jenis_kegiatan', $keyword)
            ->orLike('kegiatan.penanggung_jawab', $keyword)
            ->orLike('kegiatan.peserta', $keyword)
            ->orLike('kegiatan.nara_hubung', $keyword)
            ->orLike('kegiatan.penyelenggara', $keyword)
            ->orLike('kegiatan.jenis_penyelenggara', $keyword)
            ->orLike('kegiatan.detail_penyelenggara', $keyword)
            ->orLike('kegiatan.waktu_kegiatan', $keyword)
            ->orLike('kegiatan.status', $keyword)
        ->groupEnd() // Mengakhiri grouping kondisi pencarian
        ->orderBy('kegiatan.tanggal_mulai', 'DESC')
        ->paginate($perPage, 'kegiatan');
}



public function getPesertaCount()
{
    return $this->select('peserta, COUNT(*) as total')
                ->groupBy('peserta')
                ->orderBy('peserta', 'ASC')
                ->findAll();
}

public function getJenisPenyelenggaraCount()
{
    return $this->select('jenis_penyelenggara, COUNT(*) as total')
                ->groupBy('jenis_penyelenggara')
                ->orderBy('jenis_penyelenggara', 'ASC')
                ->findAll();
}

public function getGrafikJenisKegiatan($tahun = null, $bulan = null)
{
    $this->select('jenis_kegiatan, COUNT(id_kegiatan) as total')
         ->groupBy('jenis_kegiatan')
         ->orderBy('jenis_kegiatan', 'ASC');

    if ($tahun) {
        $this->where('YEAR(tanggal_mulai)', $tahun);
    }

    if ($bulan) {
        $this->where('MONTH(tanggal_mulai)', $bulan);
    }

    return $this->findAll();
}

public function getKegiatanPerWeek($month)
{
    $builder = $this->builder();
    $builder->select("tanggal_mulai")
            ->where("MONTH(tanggal_mulai)", $month)
            ->orderBy("tanggal_mulai", "ASC");

    $kegiatan = $builder->get()->getResult();

    // Inisialisasi array untuk 4 minggu
    $weeks = [0, 0, 0, 0];

    // Menghitung jumlah kegiatan per minggu
    foreach ($kegiatan as $item) {
        $start_date = strtotime($item->tanggal_mulai);
        $day_of_month = date('j', $start_date); // Hari dalam bulan (1-31)
        $week_number = ceil($day_of_month / 7); // Tentukan minggu keberapa (1-4)

        // Batasi hanya minggu 1 hingga 4
        if ($week_number >= 1 && $week_number <= 4) {
            $weeks[$week_number - 1]++; // Simpan di array (0-based index)
        }
    }

    return $weeks;
}

public function getKegiatanByPenyelenggara($year)
{
    $builder = $this->db->table('kegiatan')
        ->select('MONTH(tanggal_mulai) AS bulan, COUNT(k.id) AS total')
        ->join('jurusan', 'jenis_penyelenggara = "jurusan" AND k.id_jenis_penyelenggara = id_jurusan', 'left')
        ->join('prodi', 'jenis_penyelenggara = "prodi" AND k.id_jenis_penyelenggara = id_prodi', 'left')
        ->join('unit', 'jenis_penyelenggara = "unit" AND k.id_jenis_penyelenggara = id_unit', 'left')
        ->where('YEAR(tanggal_mulai)', $year)
        ->groupBy('MONTH(tanggal_mulai)')
        ->orderBy('MONTH(tanggal_mulai)', 'ASC');

    // Debug query SQL
    echo $builder->getCompiledSelect();
    die();

    log_message('debug', 'Query result: ' . json_encode($result));
return $result;

    return $builder->get()->getResultArray();
}

public function getKegiatanByMonth($tahun, $bulan = null)
{
    // Mulai query untuk mengambil data
    $query = $this->db->table($this->table)
        ->select('WEEK(tanggal_mulai, 1) AS minggu, COUNT(*) AS jumlah_kegiatan')
        ->where('YEAR(tanggal_mulai)', $tahun); // Filter berdasarkan tahun

    // Jika bulan diset, filter juga berdasarkan bulan
    if ($bulan !== null) {
        $query->where('MONTH(tanggal_mulai)', $bulan);
    }

    // Debug untuk memastikan query yang dijalankan benar
    log_message('debug', 'SQL Query: ' . $this->db->getLastQuery());

    // Eksekusi query dan ambil hasilnya
    return $query->groupBy('minggu')
                 ->orderBy('minggu', 'ASC')
                 ->get()
                 ->getResultArray();
                 
}

// kegiatanModel.php


// peler
public function getJumlahPesertaPerTahun()
{
    return $this->select('YEAR(tanggal_mulai) as tahun, COUNT(id_kegiatan) as total')
                ->groupBy('YEAR(tanggal_mulai)')
                ->orderBy('tahun', 'ASC')
                ->findAll();
}

public function getJumlahPesertaPerBulan($tahun)
{
    return $this->select('MONTH(tanggal_mulai) as bulan, COUNT(id_kegiatan) as total')
                ->where('YEAR(tanggal_mulai)', $tahun)
                ->groupBy('MONTH(tanggal_mulai)')
                ->orderBy('bulan', 'ASC')
                ->findAll();
}

public function getPesertaPerBulanDetail($tahun, $bulan)
{
    return $this->select('tanggal_mulai, nama_kegiatan, COUNT(id_kegiatan) as total')
                ->where('YEAR(tanggal_mulai)', $tahun)
                ->where('MONTH(tanggal_mulai)', $bulan)
                ->groupBy('tanggal_mulai, nama_kegiatan')
                ->orderBy('tanggal_mulai', 'ASC')
                ->findAll();
}


// peserta2
// public function getFilteredKegiatan($tahun, $bulan = null)
// {
//     $query = $this->db->table($this->table)
//         ->select("COUNT(peserta) as jumlah_peserta, YEAR(tanggal_mulai) as tahun, MONTH(tanggal_mulai) as bulan")
//         ->where('YEAR(tanggal_mulai)', $tahun);

//     if ($bulan) {
//         $query->where('MONTH(tanggal_mulai)', $bulan);
//     }

//     return $query->groupBy('tahun, bulan')->get()->getResultArray();
// }

public function getWeeklyData($tahun, $bulan = null)
{
    $query = $this->db->table($this->table)
        ->select("WEEK(tanggal_mulai) as minggu, COUNT(peserta) as total")
        ->where('YEAR(tanggal_mulai)', $tahun);

    if ($bulan) {
        $query->where('MONTH(tanggal_mulai)', $bulan);
    }

    return $query->groupBy('minggu')->get()->getResultArray();
}


// pejabat
public function getDataByYear()
{
    return $this->db->table($this->table)
        ->select('YEAR(tanggal_mulai) as tahun, COUNT(*) as jumlah_kegiatan')
        ->groupBy('tahun')
        ->orderBy('tahun', 'ASC')
        ->get()
        ->getResultArray();
}

public function getDataByMonth($tahun)
{
    return $this->db->table($this->table)
        ->select('MONTH(tanggal_mulai) as bulan, COUNT(*) as jumlah_kegiatan')
        ->where('YEAR(tanggal_mulai)', $tahun)
        ->groupBy('bulan')
        ->orderBy('bulan', 'ASC')
        ->get()
        ->getResultArray();
}

// Ferri
public function getGrafikTotal($bulan, $tahun)
    {
        return $this->select("WEEK(tanggal_mulai) as minggu, COUNT(*) as total")
                    ->where("YEAR(tanggal_mulai)", $tahun)
                    ->where("MONTH(tanggal_mulai)", $bulan)
                    ->groupBy("minggu")
                    ->findAll();
    }

    public function getGrafikByPeserta($bulan, $tahun, $peserta)
    {
        return $this->select("WEEK(tanggal_mulai) as minggu, COUNT(*) as total")
                    ->where("YEAR(tanggal_mulai)", $tahun)
                    ->where("MONTH(tanggal_mulai)", $bulan)
                    ->where("peserta", $peserta)
                    ->groupBy("minggu")
                    ->findAll();
    }

    public function getGrafikByPenyelenggara($bulan, $tahun, $penyelenggara)
    {
        return $this->select("WEEK(tanggal_mulai) as minggu, COUNT(*) as total")
                    ->where("YEAR(tanggal_mulai)", $tahun)
                    ->where("MONTH(tanggal_mulai)", $bulan)
                    ->where("penyelenggara", $penyelenggara)
                    ->groupBy("minggu")
                    ->findAll();
    }

    public function getGrafikByJenisKegiatan($bulan, $tahun, $jenisKegiatan)
{
    return $this->select("WEEK(tanggal_mulai) as minggu, COUNT(*) as total")
                ->where("YEAR(tanggal_mulai)", $tahun)
                ->where("MONTH(tanggal_mulai)", $bulan)
                ->where("jenis_kegiatan", $jenisKegiatan)
                ->groupBy("minggu")
                ->findAll();
}

public function getGrafikByJenisPenyelenggara($bulan, $tahun, $jenisPenyelenggara)
{
    return $this->select("WEEK(tanggal_mulai) as minggu, COUNT(*) as total")
                ->where("YEAR(tanggal_mulai)", $tahun)
                ->where("MONTH(tanggal_mulai)", $bulan)
                ->where("jenis_penyelenggara", $jenisPenyelenggara)
                ->groupBy("minggu")
                ->findAll();
}
public function getGrafikByStatus($tahun = null, $bulan = null, $filter = null)
{

   
    $builder = $this->db->table('kegiatan');
    $builder->select("status, COUNT(*) as total");
    $builder->groupStart()
                ->where("YEAR(tanggal_mulai)", $tahun)
                ->where("MONTH(tanggal_mulai)", $bulan)
             ->groupEnd()
             ->groupStart()
                ->where("status", "Sedang dilaksanakan")
                ->orWhere("status", "Sudah selesai");
    // Keterangan pendek:
    $this->select('status, COUNT(*) as total');

    // Tambahkan filter bulan dan tahun jika tersedia
    if ($bulan && $tahun) {
        $this->where('MONTH(tanggal_mulai)', $bulan);
        $this->where('YEAR(tanggal_mulai)', $tahun);
    }

    // Tambahkan filter untuk status
    if ($filter === 'sedang dilaksanakan') {
        $this->where('status', 'Sedang dilaksanakan');
    } elseif ($filter === 'sudah selesai') {
        $this->where('status','tanggal_selesai');
    } elseif ($filter === 'belum dimulai') {
        $this->where('status', 'Belum dimulai');
    }

    return $this->groupBy('status')
                ->get()
                ->getResultArray();
}

// public function getRincianByMinggu($minggu, $bulan, $tahun)
// {
//     return $this->select('*')
//                 ->where("YEAR(tanggal_mulai)", $tahun)
//                 ->where("MONTH(tanggal_mulai)", $bulan)
//                 ->where("WEEK(tanggal_mulai)", $minggu)
//                 ->findAll();
// }


// public function getTotalKegiatanPerBulan($tahun)
// {
//     return $this->select('MONTH(tanggal) as bulan, COUNT(*) as total')
//         ->where('YEAR(tanggal)', $tahun)
//         ->groupBy('MONTH(tanggal)')
//         ->findAll();
// }

// public function getKegiatanPesertaPerBulan($filter, $tahun)
// {
//     return $this->select('MONTH(tanggal) as bulan, COUNT(*) as total')
//         ->where('YEAR(tanggal)', $tahun)
//         ->where('peserta', $filter)
//         ->groupBy('MONTH(tanggal)')
//         ->findAll();
// }

// public function getKegiatanPenyelenggaraPerBulan($filter, $tahun)
// {
//     return $this->select('MONTH(tanggal) as bulan, COUNT(*) as total')
//         ->where('YEAR(tanggal)', $tahun)
//         ->where('penyelenggara', $filter)
//         ->groupBy('MONTH(tanggal)')
//         ->findAll();
// }


// peler
//     `public function getTotalKegiatanPerBulan($tahun)
// {
//     return $this->db->table('kegiatan')
//         ->select('MONTH(tanggal_mulai) AS bulan, COUNT(*) AS total')
//         ->where('YEAR(tanggal_mulai)', $tahun)
//         ->groupBy('MONTH(tanggal_mulai)')
//         ->orderBy('MONTH(tanggal_mulai)', 'ASC')
//         ->get()
//         ->getResultArray();
// }`

// public function getAllDataByPenyelenggara($penyelenggara, $kategori = null)
    // {
    //     $builder = $this->db->table('kegiatan');
    //     $builder->select('*');
        
    //     if ($penyelenggara === 'mahasiswa') {
    //         $builder->where('penyelenggara', 'Mahasiswa');
    //         if ($kategori === 'jurusan') {
    //             $builder->where('jenis_penyelenggara', 'jurusan');
    //         } elseif ($kategori === 'prodi') {
    //             $builder->where('jenis_penyelenggara', 'prodi');
    //         }
    //     } elseif ($penyelenggara === 'karyawan') {
    //         $builder->where('penyelenggara', 'Karyawan');
    //         $builder->where('jenis_penyelenggara', 'unit');
    //     }

    //     return $builder->get()->getResultArray();
    // }

    // kiss dulu
    public function getUnitNames()
    {
        return $this->db->table('unit')->select('id_unit, nama_unit')->get()->getResultArray();
    }

    public function getJurusanNames()
    {
        return $this->db->table('jurusan')->select('id_jurusan, nama_jurusan')->get()->getResultArray();
    }

    public function getProdiNames()
    {
        return $this->db->table('prodi')->select('id_prodi, nama_prodi')->get()->getResultArray();
    }

    public function getJurusanByJenisKaryawan($jenisKaryawan)
    {
        $db = \Config\Database::connect();
        return $db->table('jurusan')->where('jenis_karyawan', $jenisKaryawan)->get()->getResult();
    }

    public function getProdiByJurusan($idJurusan)
    {
        $db = \Config\Database::connect();
        return $db->table('prodi')->where('id_jurusan', $idJurusan)->get()->getResult();
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
            // Filter berdasarkan jurusan dan prodi
            [$jurusan, $prodi] = explode('-', $filter);
            $jurusan = $jurusan !== 'showAllJurusan' ? $jurusan : null;
            $prodi = $prodi !== 'showAllProdi' ? $prodi : null;

            $data = $this->KegiatanModel->getDataPenyelenggaraKaryawan($tahun, $jurusan, $prodi);
        } else {
            // Filter penyelenggara secara umum (karyawan/mahasiswa)
            $data = $this->KegiatanModel->getKegiatanByFilter($tahun, 'penyelenggara', ucfirst($filter));
        }
    } else {
        // Data default untuk semua karyawan
        $data = $this->KegiatanModel->getDataPenyelenggaraKaryawan($tahun);
    }

    // Debug log untuk memastikan data diproses
    log_message('debug', 'Data grafik penyelenggara: ' . json_encode($data));

    return $this->response->setJSON([
        'status' => 'success',
        'data' => $data,
    ]);
}








    public function getKegiatanByYear($year)
{
    return $this->select("MONTH(tanggal_mulai) AS bulan, COUNT(*) AS total")
                ->where("YEAR(tanggal_mulai)", $year)
                ->groupBy("MONTH(tanggal_mulai)")
                ->findAll();
}

public function getKegiatanByFilter($year, $filterKey, $filterValue)
{
    $this->select("MONTH(tanggal_mulai) AS bulan, COUNT(*) AS total")
         ->where("YEAR(tanggal_mulai)", $year);

    if ($filterKey === 'penyelenggara') {
        if (in_array($filterValue, ['Mahasiswa', 'Karyawan'])) {
            $this->where('penyelenggara', $filterValue);
        } elseif (is_numeric($filterValue)) {
            // Untuk kategori jurusan, prodi, atau unit
            $this->groupStart()
                 ->where('id_jurusan', $filterValue)
                 ->orWhere('id_prodi', $filterValue)
                 ->orWhere('id_unit', $filterValue)
                 ->groupEnd();
        }
    } else {
        $this->where($filterKey, $filterValue);
    }

    return $this->groupBy("MONTH(tanggal_mulai)")->findAll();
}

   // Perbaikan fungsi getAllDataByPenyelenggara
public function getAllDataByPenyelenggara($penyelenggara, $kategori = null)
{
    $builder = $this->db->table('kegiatan');
    $builder->select('*');
    
    if ($penyelenggara === 'mahasiswa') {
        // Penyaring untuk mahasiswa
        $builder->where('penyelenggara', 'Mahasiswa');
        if ($kategori === 'jurusan') {
            $builder->where('id_jurusan IS NOT NULL'); // Pastikan ada id_jurusan
        } elseif ($kategori === 'prodi') {
            $builder->where('id_prodi IS NOT NULL'); // Pastikan ada id_prodi
        }
    } elseif ($penyelenggara === 'karyawan') {
        // Penyaring untuk karyawan
        $builder->where('penyelenggara', 'Karyawan');
        if ($kategori === 'unit') {
            $builder->where('id_unit IS NOT NULL'); // Pastikan ada id_unit
        }
    }

    return $builder->get()->getResultArray();
}

    public function getDataByJurusanAndProdi($tahun, $jurusan, $prodi)
{
    $builder = $this->db->table('kegiatan');
    $builder->select('MONTH(tanggal_mulai) as bulan, COUNT(*) as total');
    $builder->where('YEAR(tanggal_mulai)', $tahun);
    $builder->where('id_jurusan', $jurusan);
    $builder->where('id_prodi', $prodi);
    $builder->groupBy('MONTH(tanggal_mulai)');
    $builder->orderBy('bulan', 'ASC');

    return $builder->get()->getResultArray();
}

public function getDataByJurusan($tahun, $jurusan)
{
    $builder = $this->db->table('kegiatan');
    $builder->select('MONTH(tanggal_mulai) as bulan, COUNT(*) as total');
    $builder->where('YEAR(tanggal_mulai)', $tahun);
    $builder->where('id_jurusan', $jurusan);
    $builder->groupBy('MONTH(tanggal_mulai)');
    $builder->orderBy('bulan', 'ASC');

    return $builder->get()->getResultArray();
}
public function getDataPenyelenggaraKaryawan($tahun, $jurusan = null, $prodi = null)
{
    $builder = $this->db->table('kegiatan');
    $builder->select('MONTH(tanggal_mulai) as bulan, COUNT(id_kegiatan) as total');
    $builder->where('YEAR(tanggal_mulai)', $tahun);
    $builder->where('jenis_penyelenggara', 'karyawan');

    // Validasi jurusan
    if (!empty($jurusan) && $jurusan !== 'showAllJurusan') {
        $builder->where('id_jurusan', $jurusan);
    }

    // Validasi prodi
    if (!empty($prodi) && $prodi !== 'showAllProdi') {
        $builder->where('id_prodi', $prodi);
    }

    $builder->groupBy('MONTH(tanggal_mulai)');
    $builder->orderBy('bulan', 'ASC');

    log_message('debug', 'Query: ' . $builder->getCompiledSelect());
    $result = $builder->get()->getResultArray();

    log_message('debug', 'Data: ' . print_r($result, true));
    return $result;
}


  // Fungsi untuk mengambil rentang tahun dari kegiatan
  public function getTahunRange()
  {
      $this->select('YEAR(tanggal_mulai) as tahun');
      $this->groupBy('tahun');
      $this->orderBy('tahun', 'ASC');
      $result = $this->findAll();

      return array_column($result, 'tahun');
  }



  // Fungsi untuk mengambil data berdasarkan tahun dan filter
  public function getDataByYearAndFilter($year, $kategori, $filter)
{
    $this->select('MONTH(tanggal_mulai) as bulan, COUNT(*) as total');
    $this->where('YEAR(tanggal_mulai)', $year);

    if ($kategori === 'penyelenggara') {
        if (is_array($filter)) {
            // Filter untuk penyelenggara berdasarkan jurusan dan prodi
            if (isset($filter['jurusan'])) {
                $this->where('jurusan', $filter['jurusan']);
            }
            if (isset($filter['prodi'])) {
                $this->where('prodi', $filter['prodi']);
            }
        } elseif ($filter) {
            $this->where('jenis_penyelenggara', $filter);
        }
    }

    $this->groupBy('bulan');
    $this->orderBy('bulan', 'ASC');

    return $this->findAll();
}
  public function getDataByKategoriAndYear($kategori, $tahun)
{
    $builder = $this->db->table('kegiatan');
    $builder->select('MONTH(tanggal_mulai) as bulan, COUNT(id) as total');

    // Filter tahun
    $builder->where('YEAR(tanggal_mulai)', $tahun);

    // Filter kategori
    if ($kategori === 'peserta') {
        $builder->where('kategori', 'peserta');
    } elseif ($kategori === 'penyelenggara') {
        $builder->where('kategori', 'penyelenggara');
    }

    $builder->groupBy('MONTH(tanggal_mulai)');
    return $builder->get()->getResultArray();
}

public function getRincianByMinggu($minggu, $bulan, $tahun)
{
    return $this->db->table($this->table)
        ->where('WEEK(tanggal_mulai, 1)', $minggu)
        ->where('MONTH(tanggal_mulai)', $bulan)
        ->where('YEAR(tanggal_mulai)', $tahun)
        ->get()
        ->getResultArray();
}

 //F
 public function getApprovedData($tahun, $filter = null)
 {
     $this->select("MONTH(tanggal_mulai) AS bulan, COUNT(*) AS total")
         ->where('disetujui', 'disetujui')
         ->where('YEAR(tanggal_mulai)', $tahun);

     if ($filter) {
         $this->where('id_jurusan', $filter)
             ->orWhere('id_prodi', $filter)
             ->orWhere('id_unit', $filter);
     }

     return $this->groupBy('bulan')->findAll();
}


}
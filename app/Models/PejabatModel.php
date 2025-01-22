<?php

namespace App\Models;

use CodeIgniter\Model;

class PejabatModel extends Model
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
        'jenis_penyelenggara',
        'detail_penyelenggara',
        'waktu_kegiatan',
        'status',
        'disetujui', 
        'keterangan'
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
        ->join('jurusan', 'kegiatan.detail_penyelenggara = jurusan.id_jurusan', 'left')
        ->join('prodi', 'kegiatan.detail_penyelenggara = prodi.id_prodi', 'left')
        ->join('unit', 'kegiatan.detail_penyelenggara = unit.id_unit', 'left')
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

public function getTotalPerMinggu($filter = [])
{
    $builder = $this->select("WEEK(tanggal_mulai, 1) AS minggu, COUNT(*) AS total");

    // Filter Tahun
    if (!empty($filter['tahun'])) {
        $builder->where('YEAR(tanggal_mulai)', $filter['tahun']);
    }

    // Filter Bulan (opsional)
    if (!empty($filter['bulan'])) {
        $builder->where('MONTH(tanggal_mulai)', $filter['bulan']);
    }

    return $builder->groupBy('minggu')
                   ->orderBy('minggu', 'ASC')
                   ->findAll();
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
public function getFilteredKegiatan($tahun, $bulan = null)
{
    $query = $this->db->table($this->table)
        ->select("COUNT(peserta) as jumlah_peserta, YEAR(tanggal_mulai) as tahun, MONTH(tanggal_mulai) as bulan")
        ->where('YEAR(tanggal_mulai)', $tahun);

    if ($bulan) {
        $query->where('MONTH(tanggal_mulai)', $bulan);
    }

    return $query->groupBy('tahun, bulan')->get()->getResultArray();
}

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


public function getAllJurusan()
{
    return $this->db->table('jurusan')->select('*')->get()->getResultArray();
}

public function getAllProdi()
{
    return $this->db->table('prodi')->select('*')->get()->getResultArray();
}

public function getAllUnit()
{
    return $this->db->table('unit')->select('*')->get()->getResultArray();
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
    return $this->select("MONTH(tanggal_mulai) AS bulan, COUNT(*) AS total")
                ->where("YEAR(tanggal_mulai)", $year)
                ->where($filterKey, $filterValue)
                ->groupBy("MONTH(tanggal_mulai)")
                ->findAll();
}


// public function getTotalKegiatanPerBulan($tahun)
// {
//     return $this->select('MONTH(tanggal) as bulan, COUNT(*) as total')
//         ->where('YEAR(tanggal)', $tahun)
//         ->groupBy('MONTH(tanggal)')
//         ->findAll();
// }

public function getKegiatanPesertaPerBulan($filter, $tahun)
{
    return $this->select('MONTH(tanggal) as bulan, COUNT(*) as total')
        ->where('YEAR(tanggal)', $tahun)
        ->where('peserta', $filter)
        ->groupBy('MONTH(tanggal)')
        ->findAll();
}

public function getKegiatanPenyelenggaraPerBulan($filter, $tahun)
{
    return $this->select('MONTH(tanggal) as bulan, COUNT(*) as total')
        ->where('YEAR(tanggal)', $tahun)
        ->where('penyelenggara', $filter)
        ->groupBy('MONTH(tanggal)')
        ->findAll();
    }

public function getGrafikData($tahun, $kategori, $filter = null)
    {
        $db = db_connect();
        $builder = $db->table($this->table);

        // Filter tahun
        $builder->select("MONTH(tanggal_mulai) AS bulan, COUNT(*) AS total");
        $builder->where("YEAR(tanggal_mulai)", $tahun);

        // Filter kategori jika ada
        if ($kategori === 'peserta' && $filter) {
            $builder->where('peserta', $filter);
        } elseif ($kategori === 'penyelenggara' && $filter) {
            $builder->where('penyelenggara', $filter);
        }
        
        $builder->groupBy("MONTH(tanggal_mulai)");
        $result = $builder->get()->getResultArray();

        return $result;
    }
// peler
    public function getTotalKegiatanPerBulan($tahun)
{
    return $this->db->table('kegiatan')
        ->select('MONTH(tanggal_mulai) AS bulan, COUNT(*) AS total')
        ->where('YEAR(tanggal_mulai)', $tahun)
        ->groupBy('MONTH(tanggal_mulai)')
        ->orderBy('MONTH(tanggal_mulai)', 'ASC')
        ->get()
        ->getResultArray();
}


// kiss dulu
// mie goreng mie kuah 
// mas?! muah muah

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

      if ($kategori === 'peserta' && $filter) {
          $this->like('peserta', $filter); // Peserta disimpan dalam satu kolom
      } elseif ($kategori === 'penyelenggara' && $filter) {
          $this->where('jenis_penyelenggara', $filter); // Berdasarkan jenis penyelenggara
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


}
        // public function getRincianByMinggu($minggu, $bulan, $tahun)
        // {
        //     return $this->select('*')
        //                 ->where("YEAR(tanggal_mulai)", $tahun)
        //                 ->where("MONTH(tanggal_mulai)", $bulan)
        //                 ->where("WEEK(tanggal_mulai)", $minggu)
        //                 ->findAll();
        // }
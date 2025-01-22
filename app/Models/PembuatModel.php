<?php

namespace App\Models;

use CodeIgniter\Model;

class PembuatModel extends Model
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
        'id_users',
        'jenis_karyawan',
        'nama_lengkap',
        'id_jurusan',
        'id_prodi',
        'id_unit',
        'waktu_kegiatan',
        'status'
    ];

    // Diman
    // Optional: Tambahkan metode khusus untuk query kompleks
//     public function getKegiatanById($id)
//     {
//         return $this->where('id_kegiatan', $id)->first();
//     }

//      public function getPaginatedKegiatan($perPage)
// {
//     return $this->select('kegiatan.*, jurusan.nama_jurusan, prodi.nama_prodi, unit.nama_unit')
//         ->join('jurusan', 'kegiatan.detail_penyelenggara = jurusan.id_jurusan', 'left')
//         ->join('prodi', 'kegiatan.detail_penyelenggara = prodi.id_prodi', 'left')
//         ->join('unit', 'kegiatan.detail_penyelenggara = unit.id_unit', 'left')
//         ->orderBy('tanggal_mulai', 'DESC')
//         ->paginate($perPage, 'kegiatan');
// }

// public function search($keyword, $perPage)
// {
//     return $this->select('kegiatan.*, jurusan.nama_jurusan, prodi.nama_prodi, unit.nama_unit')
//         ->join('jurusan', 'kegiatan.detail_penyelenggara = jurusan.id_jurusan', 'left')
//         ->join('prodi', 'kegiatan.detail_penyelenggara = prodi.id_prodi', 'left')
//         ->join('unit', 'kegiatan.detail_penyelenggara = unit.id_unit', 'left')
//         ->groupStart() // Memulai grouping kondisi pencarian
//             ->like('kegiatan.nama_kegiatan', $keyword)
//             ->orLike('kegiatan.deskripsi', $keyword) // Pastikan menyertakan nama tabel
//             ->orLike('kegiatan.tanggal_mulai', $keyword)
//             ->orLike('kegiatan.tanggal_selesai', $keyword)
//             ->orLike('kegiatan.lokasi', $keyword)
//             ->orLike('kegiatan.jenis_kegiatan', $keyword)
//             ->orLike('kegiatan.penanggung_jawab', $keyword)
//             ->orLike('kegiatan.peserta', $keyword)
//             ->orLike('kegiatan.nara_hubung', $keyword)
//             ->orLike('kegiatan.penyelenggara', $keyword)
//             ->orLike('kegiatan.jenis_penyelenggara', $keyword)
//             ->orLike('kegiatan.detail_penyelenggara', $keyword)
//             ->orLike('kegiatan.waktu_kegiatan', $keyword)
//             ->orLike('kegiatan.status', $keyword)
//         ->groupEnd() // Mengakhiri grouping kondisi pencarian
//         ->orderBy('kegiatan.tanggal_mulai', 'DESC')
//         ->paginate($perPage, 'kegiatan');
// }


 // Fungsi mengambil data kegiatan berdasarkan ID
 public function getKegiatanById($id)
 {
     return $this->select('kegiatan.*, jurusan.nama_jurusan, prodi.nama_prodi, unit.nama_unit')
         ->join('jurusan', 'kegiatan.id_jurusan = jurusan.id_jurusan', 'left')
         ->join('prodi', 'kegiatan.id_prodi = prodi.id_prodi', 'left')
         ->join('unit', 'kegiatan.id_unit = unit.id_unit', 'left')
         ->where('kegiatan.id_kegiatan', $id)
         ->first();
 }

 // Fungsi untuk paginasi data kegiatan
 public function getPaginatedKegiatan($perPage, $userId, $keyword = null)
 {
     $builder = $this->select('kegiatan.*, jurusan.nama_jurusan, prodi.nama_prodi, unit.nama_unit')
         ->join('jurusan', 'kegiatan.id_jurusan = jurusan.id_jurusan', 'left')
         ->join('prodi', 'kegiatan.id_prodi = prodi.id_prodi', 'left')
         ->join('unit', 'kegiatan.id_unit = unit.id_unit', 'left')
         ->where('kegiatan.id_users', $userId); // Hanya data user yang login

     // Jika ada keyword pencarian
     if ($keyword) {
         $builder = $builder->groupStart()
             ->like('kegiatan.nama_kegiatan', $keyword)
             ->orLike('kegiatan.deskripsi', $keyword)
             ->orLike('jurusan.nama_jurusan', $keyword)
             ->orLike('prodi.nama_prodi', $keyword)
             ->orLike('unit.nama_unit', $keyword)
             ->groupEnd();
     }

     return $builder->orderBy('kegiatan.tanggal_mulai', 'DESC')->paginate($perPage, 'kegiatan');
 }

}
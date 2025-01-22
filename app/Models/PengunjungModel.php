<?php

namespace App\Models;

use CodeIgniter\Model;

class PengunjungModel extends Model
{
    protected $table = 'kegiatan'; // Nama tabel
    protected $primaryKey = 'id_kegiatan'; // Primary key
    protected $allowedFields = [
        'nama_kegiatan', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai', 
        'lokasi', 'jenis_kegiatan', 'penanggung_jawab', 'peserta',
        'nara_hubung', 'penyelenggara', 'jenis_penyelenggara', 
        'detail_penyelenggara', 'waktu_kegiatan', 'status', 'disetujui', 'keterangan'
    ];

    // Pagination data kegiatan (hanya kegiatan yang sudah disetujui)
    public function getPaginatedPengunjung($perPage)
    {
        return $this->whereIn('disetujui', ['disetujui']) // Filter status
                    ->orderBy('tanggal_mulai', 'DESC')
                    ->paginate($perPage, 'kegiatan');
    }

    // Pencarian data kegiatan (hanya kegiatan yang sudah disetujui)
    public function searchKegiatan($keyword, $perPage)
    {
        return $this->groupStart()
                        ->like('nama_kegiatan', $keyword)
                        ->orLike('deskripsi', $keyword)
                        ->orLike('tanggal_mulai', $keyword)
                        ->orLike('tanggal_selesai', $keyword)
                        ->orLike('lokasi', $keyword)
                        ->orLike('jenis_kegiatan', $keyword)
                        ->orLike('penanggung_jawab', $keyword)
                        ->orLike('peserta', $keyword)
                        ->orLike('nara_hubung', $keyword)
                        ->orLike('penyelenggara', $keyword)
                        ->orLike('jenis_penyelenggara', $keyword)
                        ->orLike('detail_penyelenggara', $keyword)
                        ->orLike('waktu_kegiatan', $keyword)
                    ->groupEnd()
                    ->whereIn('disetujui', ['disetujui']) // Filter status
                    ->orderBy('tanggal_mulai', 'DESC')
                    ->paginate($perPage, 'kegiatan');
    }

    // Metode tambahan untuk mendapatkan data kegiatan dengan batas limit
    public function getLimitedKegiatan($limit)
    {
        return $this->whereIn('disetujui', ['disetujui']) // Filter status
                    ->orderBy('tanggal_mulai', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}

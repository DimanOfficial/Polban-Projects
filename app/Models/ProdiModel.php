<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdiModel extends Model
{
    // Nama tabel yang akan digunakan
    protected $table = 'prodi';

    // Primary key untuk tabel
    protected $primaryKey = 'id_prodi';

    // Daftar field yang boleh diinsert/update
    protected $allowedFields = [
        'kode_prodi',
        'nama_prodi',
        'id_jurusan',
        'jenjang',
        'akreditasi',
        'deskripsi',
        'status'
    ];

    // Jika menggunakan timestamps (created_at, updated_at)
    protected $useTimestamps = true;

    /**
     * Mengambil data prodi dengan pagination
     * 
     * @param int $perPage Jumlah data per halaman
     * @param int $page Halaman yang sedang diminta
     * @return array Hasil query data prodi dengan pagination
     */
    public function getProdiPerPage($perPage, $page)
    {
        // Menggunakan pagination built-in CodeIgniter
        return $this->paginate($perPage, 'prodi', $page);
    }

    public function getProdiWithJurusan($perPage)
    {
        return $this->select('prodi.*, jurusan.nama_jurusan')
                    ->join('jurusan', 'jurusan.id_jurusan = prodi.id_jurusan', 'left') // Join dengan tabel jurusan
                    ->orderBy('id_prodi', 'DESC')
                    ->paginate($perPage, 'prodi');
    }

    public function searchProdi($keyword, $perPage)
    {
        return $this->select('prodi.*, jurusan.nama_jurusan')
        ->join('jurusan', 'jurusan.id_jurusan = prodi.id_jurusan', 'left')
        ->like('prodi.nama_prodi', $keyword)
            ->orLike('prodi.kode_prodi', $keyword)
            ->orLike('jurusan.nama_jurusan', $keyword)
            ->orderBy('id_prodi', 'DESC')
            ->paginate($perPage, 'prodi');
    }

}

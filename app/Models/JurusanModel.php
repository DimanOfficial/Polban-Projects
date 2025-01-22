<?php

namespace App\Models;

use CodeIgniter\Model;

class JurusanModel extends Model
{
    protected $table = 'jurusan';
    protected $primaryKey = 'id_jurusan';
    protected $allowedFields = [
        'kode_jurusan',
        'nama_jurusan',
        'deskripsi',
        'akreditasi',
        'status'
    ];

    public function getPaginatedJurusan($perPage)
    {
        return $this->orderBy('id_jurusan', 'DESC')->paginate($perPage, 'jurusan'); // Menambahkan urutan DESC
    }

    public function search($keyword, $perPage)
    {
        return $this->like('nama_jurusan', $keyword)
            ->orLike('kode_jurusan', $keyword)
            ->orLike('deskripsi', $keyword)
            ->orLike('akreditasi', $keyword)
            ->orLike('status', $keyword)
            ->orderBy('id_jurusan', 'DESC') // Menambahkan urutan DESC
            ->paginate($perPage, 'jurusan');
    }

}

<?php

namespace App\Models;

use CodeIgniter\Model;

class UnitModel extends Model
{
    protected $table = 'unit';
    protected $primaryKey = 'id_unit';
    protected $allowedFields = ['nama_unit', 'kode_unit', 'deskripsi'];

    public function getPaginatedUnit($perPage)
    {
        return $this->orderBy('id_unit', 'DESC')->paginate($perPage, 'unit'); // Menambahkan urutan DESC
    }

    public function search($keyword, $perPage)
    {
        return $this->like('nama_unit', $keyword)
            ->orLike('kode_unit', $keyword)
            ->orLike('deskripsi', $keyword)
            ->orderBy('id_unit', 'DESC') // Menambahkan urutan DESC
            ->paginate($perPage, 'unit');
    }

}

<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfilModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id_users';
    protected $allowedFields = ['username', 'name', 'nim', 'nip', 'address', 'password', 'profile_pic', 'id_jurusan', 'id_prodi', 'id_unit', 'role', 'jenis_karyawan', 'updated_at'];

    /**
 * Get user data by ID
 */
public function getUserById($id)
{
    return $this->db->table($this->table)
                    ->where('users.id_users', $id)
                    ->select('users.id_users, users.username, users.nama_lengkap, users.profile_pic, users.nim, users.nip, users.jenis_karyawan, jurusan.nama_jurusan, prodi.nama_prodi, unit.nama_unit')
                    ->join('jurusan', 'users.id_jurusan = jurusan.id_jurusan', 'left')
                    ->join('prodi', 'users.id_prodi = prodi.id_prodi', 'left')
                    ->join('unit', 'users.id_unit = unit.id_unit', 'left')
                    ->get()
                    ->getRowArray();
}


}

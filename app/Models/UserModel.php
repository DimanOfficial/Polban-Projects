<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id_users';
    protected $allowedFields = [
        'username', 'email', 'password', 'role', 'jenis_users', 'jenis_karyawan', 'nama_lengkap', 
        'nim', 'id_jurusan', 'id_prodi', 'id_unit', 'nip', 'profile_pic', 'status', 'created_at', 'updated_at','reset_otp', 'otp_expiry'
    ];        
    protected $returnType = 'array';
    protected $useTimestamps = true;

    // Diman
    public function getUserById($id)
    {
        return $this->select('users.*, jurusan.nama_jurusan, prodi.nama_prodi, unit.nama_unit')
            ->join('jurusan', 'jurusan.id_jurusan = users.id_jurusan', 'left')
            ->join('prodi', 'prodi.id_prodi = users.id_prodi', 'left')
            ->join('unit', 'unit.id_unit = users.id_unit', 'left')
            ->where('id_users', $id)
            ->first();
    }
    
    // Method untuk pagination data users
    public function getPaginatedUsers($perPage)
    {
        return $this->orderBy('id_users', 'DESC')->paginate($perPage, 'users'); // Data diurutkan berdasarkan id_users secara DESC
    }

    // Method untuk pencarian dengan keyword
    public function search($keyword, $perPage)
    {
        return $this->like('username', $keyword)
            ->orLike('email', $keyword)
            ->orLike('role', $keyword)
            ->orLike('status', $keyword)
            ->orderBy('id_users', 'DESC') // Urutkan data secara DESC
            ->paginate($perPage, 'users');
    }
}

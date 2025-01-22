<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfilPejabatModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id_users';
    protected $allowedFields = ['nama_lengkap', 'jabatan', 'address','username', 'password', 'profile_pic','created_at','role','updated_at'];
    
    public function getUserById($id)
{
    return $this->db->table('users')
                    ->select('id_users,username, nama_lengkap, jabatan, address,created_at,role, profile_pic') // Pastikan 'nama_lengkap' ada di sini
                    ->where('id_users', $id)
                    ->get()
                    ->getRowArray();
}

    


    public function updateProfile($id, $data)
    {
        return $this->update($id, $data);
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfilAdminModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id_users';
    protected $allowedFields = ['address', 'nama_lengkap', 'password', 'profile_pic', 'updated_at'];

    /**
     * Get user data by ID
     */
    public function getUserById($id)
    {
        return $this->where('id_users', $id)
                    ->select('id_users, nama_lengkap, username, address, profile_pic, created_at')
                    ->first();
    }


    /**
     * Update user profile
     */
    public function updateProfile($id, $data)
    {
        return $this->update($id, $data);
    }
}

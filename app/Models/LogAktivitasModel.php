<?php

namespace App\Models;

use CodeIgniter\Model;

class LogAktivitasModel extends Model
{
    protected $table = 'log_aktivitas'; // Nama tabel
    protected $primaryKey = 'id';       // Primary key
    protected $allowedFields = ['id_users', 'username', 'role', 'aktivitas', 'waktu']; // Kolom yang boleh diisi
    protected $useTimestamps = false;  // Tidak menggunakan kolom created_at dan updated_at bawaan
}

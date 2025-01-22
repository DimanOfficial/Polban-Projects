<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\LogAktivitasModel;

class LogActivityFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Tidak ada aksi sebelum request
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Mendapatkan session yang sudah ada
        $session = session();

        if ($session->has('id_users')) {
            $logModel = new LogAktivitasModel();
            $role = $session->get('role');
            $userId = $session->get('id_users');
            $username = $session->get('username');
            
            // Jika user baru login, catat aktivitas login
            if ($request->getUri()->getPath() == '/login') {
                $aktivitas = 'Login ke sistem';
            } else {
                // Catat aktivitas mengakses halaman setelah login
                $aktivitas = 'Mengakses ' . $request->getUri()->getPath();
            }

            // Menyimpan aktivitas ke log
            $logModel->insert([
                'id_users' => $userId,
                'username' => $username,
                'role'     => $role,
                'aktivitas'=> $aktivitas,
            ]);
        }
    }
}

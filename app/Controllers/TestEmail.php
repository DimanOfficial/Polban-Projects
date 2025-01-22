<?php

namespace App\Controllers;

class TestEmail extends BaseController
{
    public function send()
    {
        $emailService = \Config\Services::email();

        $emailService->setTo('raqil748@gmail.com'); // Ganti dengan email penerima
        $emailService->setFrom('your-email@example.com', 'Web POLBAN');
        $emailService->setSubject('Test Email');
        $emailService->setMessage('This is a test email from CodeIgniter.');

        if ($emailService->send()) {
            echo 'Email berhasil dikirim!';
        } else {
            echo $emailService->printDebugger(['headers']);
        }
    }
}

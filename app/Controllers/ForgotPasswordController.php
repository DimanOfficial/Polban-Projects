<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class ForgotPasswordController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['url', 'session']); // Tambahkan helper untuk URL dan sesi
    }

    public function forgotPassword()
    {
        return view('auth/forgot_password');
    }

    public function sendResetLink()
    {
        $emailInput = $this->request->getPost('email');

        // Validasi email
        if (!filter_var($emailInput, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('error', 'Email tidak valid.');
        }

        // Cek apakah email ada di database
        $user = $this->userModel->where('email', $emailInput)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Email tidak terdaftar.');
        }

        // Generate OTP dan expiry
        $otp = random_int(100000, 999999);
        $otpExpiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        // Simpan OTP di database
        $this->userModel->update($user['id_users'], [
            'reset_otp' => $otp,
            'otp_expiry' => $otpExpiry,
        ]);

        // Konfigurasi email
        $email = \Config\Services::email();
        $email->setFrom('zahkianur2013@gmail.com', 'Admin');
        $email->setTo($emailInput);
        $email->setSubject('Reset Password OTP');
        $email->setMessage("
            <p>Halo {$user['username']},</p>
            <p>Berikut adalah kode OTP untuk mereset password Anda:</p>
            <h2>$otp</h2>
            <p>Kode ini berlaku selama 15 menit.</p>
            <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
            <p>Terima kasih,<br>Admin</p>
        ");

        if ($email->send()) {
            session()->set('reset_email', $emailInput);
            return redirect()->to('/forgot-password/verify-otp');
        } else {
            log_message('error', 'Email gagal dikirim: ' . $email->printDebugger(['headers']));
            return redirect()->back()->with('error', 'Gagal mengirim email. Silakan coba lagi.');
        }
    }

    public function verifyOtp()
    {
        $resetEmail = session()->get('reset_email');
        if (!$resetEmail) {
            return redirect()->to('/forgot-password')->with('error', 'Silakan masukkan email Anda terlebih dahulu.');
        }

        return view('auth/verify_otp', ['email' => $resetEmail]);
    }

    public function processOtp()
    {
        $otpInput = $this->request->getPost('otp');
        $resetEmail = session()->get('reset_email');

        // Pastikan email ada di sesi
        if (!$resetEmail) {
            return redirect()->to('/forgot-password')->with('error', 'Silakan masukkan email Anda terlebih dahulu.');
        }

        // Validasi OTP
        $user = $this->userModel->where('email', $resetEmail)->first();

        if ($user && $user['reset_otp'] == $otpInput && strtotime($user['otp_expiry']) > time()) {
            // Hapus OTP dari database untuk keamanan
            $this->userModel->update($user['id_users'], [
                'reset_otp' => null,
                'otp_expiry' => null,
            ]);

            // Simpan ID pengguna ke sesi untuk reset password
            session()->set('reset_user_id', $user['id_users']);
            return redirect()->to('/forgot-password/reset-password');
        } else {
            return redirect()->back()->with('error', 'OTP tidak valid atau telah kedaluwarsa.');
        }
    }

    public function resetPassword()
    {
        $resetUserId = session()->get('reset_user_id');
        if (!$resetUserId) {
            return redirect()->to('/forgot-password')->with('error', 'Akses tidak diizinkan.');
        }

        return view('auth/reset_password');
    }

    public function processResetPassword()
    {
        $resetUserId = session()->get('reset_user_id');

        if (!$resetUserId) {
            return redirect()->to('/forgot-password')->with('error', 'Akses tidak diizinkan.');
        }

        $newPassword = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Validasi password
        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'Konfirmasi password tidak cocok.');
        }

        // Update password di database
        $this->userModel->update($resetUserId, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
        ]);

        // Hapus sesi
        session()->remove(['reset_email', 'reset_user_id']);

        return redirect()->to('/login')->with('success', 'Password berhasil diubah. Silakan login.');
    }
}

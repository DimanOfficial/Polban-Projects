<?php

namespace App\Controllers;

use App\Models\LogAktivitasModel;
use CodeIgniter\I18n\Time;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LogController extends BaseController
{
    protected $LogAktivitasModel;

    public function __construct()
    {
        $this->LogAktivitasModel = new LogAktivitasModel();
    }

    public function index()
    {
        $userRole = session()->get('role');
        $username = session()->get('username');

        $isAdmin = $userRole === 'Admin';
        $isPembuat = $userRole === 'Pembuat';
        $isPejabat = $userRole === 'Pejabat';

        $filterRole = $this->request->getGet('role') ?? '';
        $filterWeek = $this->request->getGet('week') ?? '';

        $query = $this->LogAktivitasModel;

        if ($isAdmin && $filterRole) {
            $query = $query->where('role', $filterRole);
        }

        if ($filterWeek) {
            $startDate = date('Y-m-d', strtotime($filterWeek));
            $endDate = date('Y-m-d', strtotime($filterWeek . ' +6 days'));
            $query = $query->where('DATE(waktu) >=', $startDate)
                ->where('DATE(waktu) <=', $endDate);
        }

        if ($isPembuat) {
            $query = $query->where('username', $username);
        }

        if (!$isAdmin && !$isPembuat) {
            $query = $query->where('role', $userRole);
        }

        // Urutkan berdasarkan waktu terbaru
        $logs = $query->orderBy('waktu', 'DESC')->findAll();

        return view('logs/index', [
            'logs' => $logs,
            'isAdmin' => $isAdmin,
            'isPembuat' => $isPembuat,
            'isPejabat' => $isPejabat,
            'filterRole' => $filterRole,
            'filterWeek' => $filterWeek,
        ]);
    }
    public function export($type)
    {
        $logModel = new LogAktivitasModel();
        $session = session();

        $role = $session->get('role');
        $userId = $session->get('id_users');
        $username = $session->get('username');

        // Ambil log berdasarkan hak akses
        if ($role === 'Admin') {
            $logs = $logModel->findAll(); // Admin melihat semua log
        } elseif ($role === 'Pembuat') {
            $logs = $logModel->where('username', $username)->findAll(); // Pembuat hanya melihat log mereka sendiri
        } else {
            $logs = $logModel->where('id_users', $userId)->findAll(); // Pejabat hanya melihat log mereka
        }

        if ($type === 'pdf') {
            return $this->exportPDF($logs);
        } elseif ($type === 'excel') {
            return $this->downloadExcel($logs);
        }

        return redirect()->back()->with('error', 'Format export tidak valid.');
    }
    private function exportPDF($logs)
    {
        $dompdf = new Dompdf();
        $html = view('logs/pdf_template', ['logs' => $logs]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("log_aktivitas.pdf");
    }

    private function downloadExcel($logs)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Username');
        $sheet->setCellValue('B1', 'Role');
        $sheet->setCellValue('C1', 'Aktivitas');
        $sheet->setCellValue('D1', 'Tanggal');

        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);

        $row = 2;
        foreach ($logs as $log) {
            $sheet->setCellValue('A' . $row, $log['username']);
            $sheet->setCellValue('B' . $row, $log['role']);
            $sheet->setCellValue('C' . $row, $log['aktivitas']);
            $sheet->setCellValue('D' . $row, $log['waktu']);
            $row++;
        }

        $filename = 'log_aktivitas_' . strtolower(session()->get('role')) . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}

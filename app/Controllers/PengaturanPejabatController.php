<?php

namespace App\Controllers;

class PengaturanPejabatController extends BaseController
{
    public function index()
    {
        return view('pengaturanpejabat/pengaturan'); // Semua elemen pengaturan ada dalam satu view
    }
}

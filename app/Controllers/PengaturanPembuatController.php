<?php

namespace App\Controllers;

class PengaturanPembuatController extends BaseController
{
    public function index()
    {
        return view('pengaturanpembuat/pengaturan'); // Semua elemen pengaturan ada dalam satu view
    }
}

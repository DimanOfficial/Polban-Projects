<?php

namespace App\Controllers;

class PengaturanController extends BaseController
{
    public function index()
    {
        return view('pengaturan/pengaturan'); // Semua elemen pengaturan ada dalam satu view
    }
}

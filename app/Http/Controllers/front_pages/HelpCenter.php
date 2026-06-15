<?php

namespace App\Http\Controllers\front_pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;

class HelpCenter extends Controller
{
  public function index()
  {
    $documents = Document::where('status_verifikasi', 2) // cuma yang diverifikasi
                     ->latest()
                     ->take(10) // maksimal 10 dokumen terakhir
                     ->get(); // jangan lupa get() biar hasilnya keluar
 
    $pageConfigs = ['myLayout' => 'front'];
    return view('content.front-pages.help-center-landing', compact('documents'), ['pageConfigs' => $pageConfigs]);
  }
}

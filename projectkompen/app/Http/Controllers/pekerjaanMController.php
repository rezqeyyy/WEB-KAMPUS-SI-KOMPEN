<?php

namespace App\Http\Controllers;

use App\Models\MPekerjaan;
use App\Models\MMahasiswa;
use Illuminate\Http\Request;

class pekerjaanMController extends Controller
{
    public function pekerjaanMShow(Request $request)
    {
        $dataPekerjaan = MPekerjaan::where('batas_pekerja', '>', 0)->get();
        return view('mahasiswa.listpekerjaan', ['dataPekerjaan' => $dataPekerjaan]);
    }

    public function pekerjaanAmbilShow(Request $request)
    {
        $dataPekerjaan = MPekerjaan::all(); //narik semua dari db kirim ke view
        return view('mahasiswa.pekerjaan.listdiambil', ['dataPekerjaan' => $dataPekerjaan]);
    }

    public function ambilPekerjaanShowCreate(Request $request)
    {
        return view('mahasiswa.pekerjaan.create');
    }
}

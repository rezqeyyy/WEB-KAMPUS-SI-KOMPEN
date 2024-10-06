<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MMahasiswa;
use App\Models\MPekerjaan;
use App\Models\MUser;
use App\Models\MProdi;
use App\Models\MKelas;
use App\Models\MSetupBertugas;

class dashboardController extends Controller
{
    public function adminprodicount()
    {
        // Counting total number of users
        $totalUser = MUser::count();

        // Counting total number of mahasiswa
        $totalMahasiswa = MMahasiswa::count();

        // Counting total number of pekerjaan
        $totalPekerjaan = MPekerjaan::count();

        // Counting total number of kelas
        $totalKelas = MKelas::count();

        // Counting total number of prodi
        $totalProdi = MProdi::count();

        // Counting total Setup Bertugas
        $totalSetup = MSetupBertugas::count();

        return view('master.dashboard', [
            'totalUser' => $totalUser,
            'totalMahasiswa' => $totalMahasiswa,
            'totalPekerjaan' => $totalPekerjaan,
            'totalKelas' => $totalKelas,
            'totalProdi' => $totalProdi,
            'totalSetup' => $totalSetup,

        ]);
    }
}

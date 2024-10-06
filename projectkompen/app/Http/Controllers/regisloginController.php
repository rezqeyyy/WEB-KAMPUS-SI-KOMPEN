<?php

namespace App\Http\Controllers;

use App\Models\MUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;


class regisloginController extends Controller
{


    public function showLoginForm()
    {
        return view('login'); //
    }

    public function login(Request $request)
    {
        // Validasi data login
        $this->validate($request, [
            'kode_user' => 'required',
            'password' => 'required',
        ]);

        // Coba melakukan autentikasi
        if (Auth::guard('pengguna')->attempt(['kode_user' => $request->kode_user, 'password' => $request->password])) {
            // Autentikasi berhasil
            $user = Auth::guard('pengguna')->user();
            $data = ['kode_user' => $user->kode_user];
            $request->session()->regenerate();

            // Jika pengguna belum mengedit password, redirect ke halaman pengaturan pengguna
            if ($user->edit_password == 0) {
                return redirect()->route('user.profile.edit'); // Redirect ke halaman pengaturan pengguna
            }

            // Redirect ke halaman yang sesuai berdasarkan peran pengguna
            switch ($user->role) {
                case 'Admin Prodi':
                    return redirect()->intended('/master/user/listuser'); // Redirect ke halaman Admin Prodi
                case 'Pengawas':
                    return redirect()->intended('/pengawas/listpengajuan'); // Redirect ke halaman Pengawas
                case 'Kepala Lab':
                    return redirect()->intended('/kalab/listpengajuan'); // Redirect ke halaman Kepala Lab
                case 'PLP':
                    return redirect()->intended('/plp/listpengajuan'); // Redirect ke halaman PLP
                case 'Dosen Pembimbing Akademik':
                    return redirect()->intended('/dospem/list/bebaskompen'); // Redirect ke halaman Dosen Pembimbing Akademik
                case 'KPS':
                    return redirect()->intended('/kps/listmahasiswa'); // Redirect ke halaman KPS
                case 'Kajur':
                    return redirect()->intended('/kajur/listmahasiswa'); // Redirect ke halaman Kajur
                default:
                    return redirect('/login')->with($data); // Redirect to home if role is undefined
            }
        } else {
            // Autentikasi gagal
            Session::flash('alert-error', 'Username Atau Password Salah!');
            return redirect('/login');
        }
    }


    // Metode untuk logout
    public function logout(Request $request)
    {
        Auth::guard('pengguna')->logout();
        $request->session()->regenerate();
        return redirect()->intended(route('login'));
    }
}

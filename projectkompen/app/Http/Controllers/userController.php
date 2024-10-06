<?php

namespace App\Http\Controllers;

use App\Mail\resetPasswordMail;
use App\Models\MUser;
use App\Models\MProdi;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class userController extends Controller
{
    public function userShow(Request $request)
    {
        $dataUser = MUser::all(); //narik semua dari db kirim ke view
        return view('master.user.listuser', ['dataUser' => $dataUser]);
    }

    public function userShowCreate(Request $request)
    {

        return view('master.user.create');
    }

    public function userProsesAdd(Request $request)
    {
        // Mengecek username apakah sudah ada
        $kode_user = $request->input('kode_user');
        $kode_userExists = MUser::where('kode_user', $kode_user)->exists();
        if ($kode_userExists) {
            Session::flash('alert-error', 'OOPSSS! NIP sudah ada!');
            return redirect()->route('master.user.create');
        }

        // Lanjutkan dengan proses penyimpanan data user jika username belum ada di database
        $form_ttd = $request->file('ttd');
        if ($form_ttd) {
            // Jika ada file ttd yang diunggah, simpan file tersebut
            $form_ttd->storeAs('public/signature', $form_ttd->hashName());
            $ttdFileName = $form_ttd->hashName();
        } else {
            // Jika tidak ada file ttd yang diunggah, set nama file ttd menjadi null
            $ttdFileName = null;
        }

        $form_kode_user = $request->post('kode_user');
        $form_nama_user = $request->post('nama_user');
        $form_email = $request->post('email');
        $form_password = $request->post('password');
        $form_role = $request->post('role');
        $hashedPassword = bcrypt($form_password); // Hash password sebelum menyimpannya

        // Set ke tabel
        $tblUser = new MUser();
        $tblUser->kode_user = $form_kode_user;
        $tblUser->nama_user = $form_nama_user;
        $tblUser->email = $form_email;
        $tblUser->password = $hashedPassword;
        $tblUser->role = $form_role;
        $tblUser->ttd = $ttdFileName;

        $tblUser->save(); // Simpan data ke database

        Session::flash('alert-success', 'Berhasil Menambahkan Data');

        // Redirect ke halaman selanjutnya
        return redirect()->route('master.user.listuser');
    }


    public function userShowEdit(Request $request)
    {
        // -- ambil dari request id
        $form_id_user = $request->query('id_user', '');

        $dataUser = MUser::findOrFail($form_id_user);

        return view('master.user.edit', compact('dataUser'));
    }

    public function userProsesEdit(Request $request)
    {
        $form_oldid = $request->post('oldid');
        $tblUser = MUser::findOrFail($form_oldid);

        $form_nama_user = $request->post('nama_user');
        $form_oldkode_user = $tblUser->kode_kegiatan; // Simpan kode user lama untuk memeriksa perubahan
        $form_kode_user = $request->post('kode_user');
        $form_email = $request->post('email');
        $form_role = $request->post('role');
        $form_ttd = $request->file('ttd');



        // Jika ada file ttd yang diunggah, simpan file tersebut dan ganti nilai ttd lama
        if ($form_ttd) {
            $form_ttd->storeAs('public/signature', $form_ttd->hashName());
            $ttdFileName = $form_ttd->hashName();
            $ttdOldFile = $tblUser->ttd;
            // Hapus file ttd lama jika ada
            if ($ttdOldFile) {
                Storage::delete('public/signature/' . $ttdOldFile);
            }

            $tblUser->ttd = $ttdFileName;
        }

        // Update data pengguna
        $tblUser->kode_user = $form_kode_user;
        $tblUser->nama_user = $form_nama_user;
        $tblUser->email = $form_email;
        $tblUser->role = $form_role;
        $tblUser->save();

        // Sesuai dengan kebutuhan Anda, Anda dapat menambahkan pesan sukses atau melakukan tindakan lainnya
        Session::flash('alert-success', 'Berhasil Mengubah Data');
        return redirect()->route('master.user.listuser');
    }

    public function userProsesDelete(Request $request)
    {
        // Ambil ID user dari permintaan
        $id_user = $request->input('id_user');

        // Temukan pengguna berdasarkan ID
        $tblUser = MUser::findOrFail($id_user);

        // Hapus pengguna dari database
        $tblUser->delete();

        // Beri pesan sukses
        Session::flash('alert-success', 'Berhasil Hapus Data');

        // Redirect ke halaman daftar pengguna
        return redirect()->route('master.user.listuser');
    }

    public function userProfileShow(Request $request)
    {

        $user = Auth::guard('pengguna')->user();

        return view('user.profile.edit', ['dataUserMaster' => $user]);
    }


    public function userProfileProsesEdit(Request $request)
    {
        $user = Auth::guard('pengguna')->user();

        $form_kode_user = $request->input('kode_user');
        $form_nama_user = $request->input('nama_user');
        $form_email = $request->input('email');
        $form_new_password = $request->input('new_password');

        // Update basic user information
        $user->kode_user = $form_kode_user;
        $user->nama_user = $form_nama_user;
        $user->email = $form_email;

        if (!empty($form_new_password)) {
            // Only update password if new password is provided
            $user->password = bcrypt($form_new_password);
            $user->edit_password = '1'; // Ensure password is hashed
        }

        // Handle signature (ttd) upload if applicable
        if ($request->hasFile('form_ttd')) {
            $form_ttd = $request->file('form_ttd');
            $form_ttd->storeAs('public/signature', $form_ttd->hashName());

            // Delete old signature file if exists
            if ($user->ttd) {
                Storage::delete('public/signature/' . $user->ttd);
            }

            // Update user's signature file name
            $user->ttd = $form_ttd->hashName();
        }

        // Save updated user information
        $user->save();

        // Flash success message
        Session::flash('alert-success', 'Berhasil Mengubah Data');

        // Redirect based on user role
        switch ($user->role) {
            case 'Admin Prodi':
                return redirect()->route('master.user.listuser');
            case 'Pengawas':
                return redirect()->route('pengawas.listpengajuan');
            case 'Kepala Lab':
                return redirect()->route('kalab.listpengajuan');
            case 'PLP':
                return redirect()->route('plp.listpengajuan');
            case 'KPS':
                return redirect()->route('kps.listmahasiswa');
            case 'Kajur':
                return redirect()->route('kajur.listmahasiswa');
            case 'Dosen Pembimbing Akademik':
                return redirect()->route('dospem.listbebas');
            default:
                // Handle default redirection or error case
                return redirect()->back();
        }
    }

    public function forgetPasswordUserShow(Request $request)
    {
        return view('forgetPasswordUser');
    }


    public function SendToken(Request $request)
    {
        $request->validate(['kode_user' => 'required']);

        $user = MUser::where('kode_user', $request->kode_user)->first();

        if (!$user) {
            return response()->json(['error' => 'NIP not found'], 404);
        }

        $token = Str::random(12);

        $user->token = $token;
        $user->status_token = 0;

        $user->save();

        $nama_user = $user->nama_user;

        $mailData = [
            'title' => 'Hi, !, Kodemu Adalah: ',
            'body' => $token,
        ];

        try {
            Mail::to($user->email)->send(new resetPasswordMail($mailData));
        } catch (Exception $e) {
            // Tangani kesalahan pengiriman email di sini
            Log::error('Kesalahan pengiriman email: ' . $e->getMessage());
        }

        return response()->json(['message' => 'Token sent to your email']);
    }

    public function ValidasiToken(Request $request)
    {
        $form_kode_user = $request->post('kode_user');
        $form_token = $request->post('token');
        // dd($form_nip);

        $user = MUser::where('kode_user', $form_kode_user)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        $tokenUser = $user->token;
        $statusToken = $user->status_token;

        // dd($form_token, $tokenUser);

        if ($form_token === $tokenUser) {
            if ($statusToken === 0) {
                Session::put('kode_user', $form_kode_user);
                Session::flash('alert-success', 'Berhasil Reset Password');
                return redirect()->route('resetPasswordUser');
            } else {
                Session::flash('alert-error', 'kode Anda Sudah Digunakan, Silahkan Request Kode Baru');
                return redirect()->route('forgetPasswordUser');
            }
        } else {
            Session::flash('alert-error', 'kode salah');
            return redirect()->route('forgetPasswordUser');
            // return response()->json(['success' => false, 'message' => 'ga cocok'], 404);
        }
    }
    public function resetPasswordUserShow(Request $request)
    {
        $kode_user = Session::get('kode_user');

        if (!$kode_user) {
            return redirect()->route('forgetPasswordUser')->with('error-alert',   'NIP tidak ditemukan');
        }
        return view('resetPasswordUser', compact('kode_user'));
    }

    public function ResetPasswordUserProses(Request $request)
    {
        $form_password_baru = $request->post('new_password');
        $form_konfirmasi_password = $request->post('confirm_password');
        $hashedPassword = bcrypt($form_password_baru); // Hash password baru, bukan konfirmasi password
        $form_kode_user = $request->post('kode_user');
        // dd($form_password_baru, $form_konfirmasi_password,$form_nip);

        if ($form_password_baru === $form_konfirmasi_password) {
            $user = MUser::where('kode_user', $form_kode_user)->first();

            if ($user) {
                $user->password = $hashedPassword;
                $user->status_token = 1;
                $user->save();
                Session::flash('alert-success', 'Berhasil Reset Password');
                return redirect()->route('login');
            } else {
                Session::flash('alert-error', 'User dengan NIP tersebut tidak ditemukan');
            }
        } else {
            Session::flash('alert-error', 'Password Tidak Cocok');
        }

        return redirect()->route('forgetPasswordUser');
    }
}

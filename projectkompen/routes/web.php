<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userController;
use App\Http\Controllers\mhswController;
use App\Http\Controllers\pekerjaanController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\regisloginController;
use App\Http\Controllers\prodiController;
use App\Http\Controllers\kelasController;
use App\Http\Controllers\pekerjaanMController;
use App\Http\Controllers\pengajuanController;
use App\Http\Controllers\pengawasController;
use App\Http\Controllers\kalabController;
use App\Http\Controllers\plpController;
use App\Http\Controllers\setupController;
use App\Http\Controllers\dospemController;
use App\Http\Controllers\kpsController;
use App\Http\Controllers\kajurController;
use App\Http\Controllers\paksaPengajuanController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//      return view('welcome');
//  });
//- [Login/Logout User Lain] -- \\
Route::get('/login', [regisloginController::class, 'showLoginForm'])->name('showlogin')->middleware('guest');
Route::post('/login', [regisloginController::class, 'login'])->name('login')->middleware('guest');

Route::get('/logout', function (Request $request) {
    $regisloginController = new RegisloginController();
    return $regisloginController->logout($request, 'pengguna');
})->name('logout');

//- [Reset Password User Lain] -- \\
Route::get('/forgetPasswordUser', [userController::class, 'forgetPasswordUserShow'])->name('forgetPasswordUser');
Route::post('/SendToken', [userController::class, 'SendToken'])->name('SendToken');
Route::post('/ValidasiToken', [userController::class, 'ValidasiToken'])->name('ValidasiToken');
Route::get('/resetPasswordUser', [userController::class, 'resetPasswordUserShow'])->name('resetPasswordUser');
Route::post('/resetPasswordUser/proses', [userController::class, 'ResetPasswordUserProses'])->name('ResetPasswordUser.proses');

Route::middleware(['allrole'])->group(function () {
    Route::get('/user/profile/edit', [userController::class, 'userProfileShow'])->name('user.profile.edit');
    Route::post('/user/profile/edit/proses', [userController::class, 'userProfileProsesEdit'])->name('user.profile.edit.proses');
});

Route::middleware(['pekerjaan'])->group(function () {
    //- [Pekerjaan Master] -- \\
    Route::get('/master/pekerjaan/listpekerjaan', [pekerjaanController::class, 'pekerjaanShow'])->name('master.pekerjaan.listpekerjaan');
    Route::get('/master/pekerjaan/create', [pekerjaanController::class, 'pekerjaanShowCreate'])->name('master.pekerjaan.create');
    Route::get('/master/pekerjaan/edit', [pekerjaanController::class, 'pekerjaanShowEdit'])->name('master.pekerjaan.edit');

    Route::post('/master/pekerjaan/create/proses', [pekerjaanController::class, 'pekerjaanProsesAdd'])->name('pekerjaan.add.proses');
    Route::post('/master/pekerjaan/edit/proses', [pekerjaanController::class, 'pekerjaanProsesEdit'])->name('pekerjaan.edit.proses');
    Route::get('/master/pekerjaan/delete/proses', [pekerjaanController::class, 'pekerjaanProsesDelete'])->name('pekerjaan.delete.proses');

    Route::post('/master/pekerjaan/import/proses', [pekerjaanController::class, 'importExcel'])->name('master.pekerjaan.import.proses');
    Route::get('/master/pekerjaan/deleteAll/proses', [pekerjaanController::class, 'pekerjaanDeleteAll'])->name('master.pekerjaan.deleteAll.proses');
});


//- [Login Mahasiswa] -- \\
Route::get('/loginmhsw', [mhswController::class, 'showmhswloginform'])->name('showloginmhsw')->middleware('guest');
Route::post('/loginmhsw', [mhswController::class, 'loginmhsw'])->name('loginmhsw')->middleware('guest');

Route::get('/logoutmhsw', [mhswController::class, 'logoutmhsw'])->name('logoutmhsw');

//- [Reset Password Mahasiswa] -- \\
Route::get('/forgetPasswordMhsw', [mhswController::class, 'forgetPasswordMhswShow'])->name('forgetPasswordMhsw');
Route::post('/SendTokenMhsw', [mhswController::class, 'SendTokenMhsw'])->name('SendTokenMhsw');
Route::post('/ValidasiTokenMhsw', [mhswController::class, 'ValidasiTokenMhsw'])->name('ValidasiTokenMhsw');
Route::get('/resetPasswordMhsw', [mhswController::class, 'resetPasswordMhswShow'])->name('resetPasswordMhsw');
Route::post('/resetPasswordMhsw/proses', [mhswController::class, 'ResetPasswordMhswProses'])->name('ResetPasswordMhsw.proses');

//- [Menu Admin Prodi] -- \\
Route::middleware(['adminprodi'])->group(function () {

    //- [Dashboard Master] -- \\
    Route::get('/master/dashboard', [dashboardController::class, 'adminprodicount'])->name('master.dashboard');

    //- [User Master] -- \\
    Route::get('/master/user/listuser', [userController::class, 'userShow'])->name('master.user.listuser');
    Route::get('/master/user/create', [userController::class, 'userShowCreate'])->name('master.user.create');
    Route::get('/master/user/edit', [userController::class, 'userShowEdit'])->name('master.user.edit');

    Route::post('/master/create/proses', [userController::class, 'userProsesAdd'])->name('user.add.proses');
    Route::post('/master/user/edit/proses', [userController::class, 'userProsesEdit'])->name('user.edit.proses');
    Route::post('/master/user/delete/proses', [userController::class, 'userProsesDelete'])->name('user.delete.proses');

    //- [Mahasiswa Master] -- \\
    Route::get('/master/mahasiswa/listmahasiswa', [mhswController::class, 'mahasiswaShow'])->name('master.mahasiswa.listmahasiswa');
    Route::get('/master/mahasiswa/create', [mhswController::class, 'mahasiswaShowCreate'])->name('master.mahasiswa.create');
    Route::get('/master/mahasiswa/edit', [mhswController::class, 'mahasiswaShowEdit'])->name('master.mahasiswa.edit');

    Route::post('/master/mahasiswa/create/proses', [mhswController::class, 'mahasiswaProsesAdd'])->name('mahasiswa.add.proses');
    Route::post('/master/mahasiswa/edit/proses', [mhswController::class, 'mahasiswaProsesEdit'])->name('mahasiswa.edit.proses');
    Route::get('/master/mahasiswa/delete/proses', [mhswController::class, 'mahasiswaProsesDelete'])->name('mahasiswa.delete.proses');

    Route::post('/master/mahasiswa/import/proses', [mhswController::class, 'importMahasiswa'])->name('master.mahasiswa.import.proses');


    //- [Kelas Master] -- \\
    Route::get('/master/kelas/listkelas', [kelasController::class, 'kelasShow'])->name('master.kelas.listkelas');
    Route::get('/master/kelas/create', [kelasController::class, 'kelasShowCreate'])->name('master.kelas.create');
    Route::get('/master/kelas/edit', [kelasController::class, 'kelasShowEdit'])->name('master.kelas.edit');

    Route::post('/master/kelas/create/proses', [kelasController::class, 'kelasProsesAdd'])->name('kelas.add.proses');
    Route::post('/master/kelas/edit/proses', [kelasController::class, 'kelasProsesEdit'])->name('kelas.edit.proses');
    Route::get('/master/kelas/delete/proses', [kelasController::class, 'kelasProsesDelete'])->name('kelas.delete.proses');
    Route::post('/kelas/import', [kelasController::class, 'importKelas'])->name('master.kelas.import');


    //- [Prodi Master] -- \\
    Route::get('/master/prodi/listprodi', [prodiController::class, 'prodiShow'])->name('master.prodi.listprodi');
    Route::get('/master/prodi/create', [prodiController::class, 'prodiShowCreate'])->name('master.prodi.create');
    Route::get('/master/prodi/edit', [prodiController::class, 'prodiShowEdit'])->name('master.prodi.edit');

    Route::post('/master/prodi/create/proses', [prodiController::class, 'prodiProsesAdd'])->name('prodi.add.proses');
    Route::post('/master/prodi/edit/proses', [prodiController::class, 'prodiProsesEdit'])->name('prodi.edit.proses');
    Route::get('/master/prodi/delete/proses', [prodiController::class, 'prodiProsesDelete'])->name('prodi.delete.proses');
    Route::post('/prodi/import', [prodiController::class, 'importProdi'])->name('master.prodi.import');
    //- [Setup Bertugas Master] -- \\
    Route::get('/master/setup/listsetup', [setupController::class, 'setupShow'])->name('master.setup.listsetup');
    Route::get('/master/setup/create', [setupController::class, 'setupShowCreate'])->name('master.setup.create');
    Route::get('/master/setup/edit', [setupController::class, 'setupShowEdit'])->name('master.setup.edit');

    Route::post('/master/setup/create/proses', [setupController::class, 'setupProsesAdd'])->name('setup.add.proses');
    Route::post('/master/setup/edit/proses', [setupController::class, 'setupProsesEdit'])->name('setup.edit.proses');
    Route::get('/master/setup/delete/proses', [setupController::class, 'setupProsesDelete'])->name('setup.delete.proses');

    //- [Pengajuan Admin] -- \\
    Route::get('/master/pengajuan/listpengajuan', [paksaPengajuanController::class, 'adminPengajuanShowList'])->name('master.pengajuan.listpengajuan');
    Route::post('/master/pengajuan/adminCeklistPaksaAcc', [paksaPengajuanController::class, 'adminCeklistPaksaAcc'])->name('master.pengajuan.adminCeklistPaksaAcc');

    Route::get('/master/pengajuan/detail', [paksaPengajuanController::class, 'pengajuanAdminDetailShow'])->name('master.pengajuan.edit');
    Route::put('/master/pengajuan/edit/proses', [paksaPengajuanController::class, 'lanjutiPaksaAdminProses'])->name('master.pengajuan.edit.proses');
});

//- [Menu Mahasiswa] -- \\
Route::middleware(['mahasiswa'])->group(function () {
    Route::get('/mahasiswa/listdata', [mhswController::class, 'mahasiswaDataShow'])->name('mahasiswa.listdata');
    Route::get('/mahasiswa/listpekerjaan', [pekerjaanMController::class, 'pekerjaanMShow'])->name('mahasiswa.listpekerjaan');

    // Pengajuan routes
    Route::get('/mahasiswa/pekerjaan/create', [PengajuanController::class, 'ambilShowCreate'])->name('mahasiswa.pekerjaan.create');
    Route::post('/mahasiswa/pekerjaan/proses', [PengajuanController::class, 'ambilCreateProses'])->name('mahasiswa.pekerjaan.proses');
    Route::get('/mahasiswa/pekerjaan/listdiambil', [PengajuanController::class, 'listDiambilShow'])->name('mahasiswa.pekerjaan.listdiambil');
    Route::delete('/mahasiswa/pengajuan/delete/{kode_kegiatan}', [PengajuanController::class, 'deletePengajuan'])->name('mahasiswa.pengajuan.delete');
    // Route untuk menampilkan halaman edit pengajuan detail
    Route::get('/mahasiswa/pekerjaan/{kode_kegiatan}/edit', [PengajuanController::class, 'editPengajuanDetailShow'])->name('mahasiswa.pekerjaan.edit');

    // Route untuk melakukan pembaruan (update) pengajuan detail
    Route::put('/mahasiswa/pekerjaan/{kode_kegiatan}/proses/update', [PengajuanController::class, 'editPengajuanDetailProses'])->name('mahasiswa.pekerjaan.update');
    Route::get('/mahasiswa/pekerjaan/delete/proses', [PengajuanController::class, 'pengajuanProsesDelete'])->name('mahasiswa.pengajuan.delete.proses');
    Route::get('/mahasiswa/{kode_kegiatan}/surat', [PengajuanController::class, 'suratShow'])->name('mahasiswa.surat');

    // Form Bebas Kompen Routes
    Route::get('/mahasiswa/bebas/create', [PengajuanController::class, 'bebasShowCreate'])->name('mahasiswa.bebas.create');
    Route::get('/mahasiswa/bebas/get-pengajuan/{id_pengajuan}', [PengajuanController::class, 'getPengajuan']);
    Route::post('/mahasiswa/bebas/proses', [PengajuanController::class, 'bebasCreateProses'])->name('mahasiswa.bebas.create.proses');
    Route::get('/mahasiswa/bebas/listbebas', [PengajuanController::class, 'listFormBebasShow'])->name('mahasiswa.bebas.listbebas');

    Route::get('/mahasiswa/bebas/{id}/edit', [PengajuanController::class, 'bebasEditShow'])->name('mahasiswa.bebas.edit');
    Route::put('/mahasiswa/bebas/{id}/edit/proses', [PengajuanController::class, 'bebasEditProses'])->name('mahasiswa.bebas.edit.proses');

    Route::delete('/mahasiswa/bebas/delete/{id}', [PengajuanController::class, 'bebasDeleteProses'])->name('mahasiswa.bebas.delete');

    Route::get('/mahasiswa/profile/edit', [mhswController::class, 'mhswProfileShow'])->name('mahasiswa.profile.edit');
    Route::post('/mahasiswa/profile/edit/proses', [mhswController::class, 'mhswProfileProsesEdit'])->name('mahasiswa.profile.edit.proses');
});

//- [Menu Pengawas] -- \\
Route::middleware(['pengawas'])->group(function () {
    Route::get('/pengawas/listpengajuan', [pengawasController::class, 'listPengajuanShow'])->name('pengawas.listpengajuan');
    Route::get('/pengawas/edit', [pengawasController::class, 'editPengawasDetailShow'])->name('pengawas.edit');
    Route::put('/pengawas/edit/proses/{kode_kegiatan}', [pengawasController::class, 'pengajuanEditProses'])->name('pengawas.edit.proses');
    Route::get('/pengawas/listdisetujui', [pengawasController::class, 'listDisetujuiShow'])->name('pengawas.listdisetujui');
    Route::post('/pengawas/approve-selected', [pengawasController::class, 'approveSelected'])->name('pengawas.approve-selected');
});

//- [Menu Kepala Lab] -- \\
Route::middleware(['kalab'])->group(function () {
    Route::get('/kalab/listpengajuan', [kalabController::class, 'kalabShowlist'])->name('kalab.listpengajuan');
    Route::get('/kalab/edit', [kalabController::class, 'editKalabDetailShow'])->name('kalab.edit');
    Route::put('/kalab/edit/proses/{kode_kegiatan}', [kalabController::class, 'kalabEditProses'])->name('kalab.edit.proses');
    Route::post('/kalab/approve-selected', [kalabController::class, 'approveSelected'])->name('kalab.approveSelected');

    Route::get('/kalab/listdisetujui', [kalabController::class, 'kalabdisetujuiShow'])->name('kalab.listdisetujui');


    //- [Kalab Pengajuan Paksa] -- \\
    Route::get('/kalab/paksa/listpengajuan', [paksaPengajuanController::class, 'kalabPaksaPengajuanShowList'])->name('kalab.paksa.listpengajuan');
    Route::post('/kalab/paksa/kalabCeklistPaksaAcc', [paksaPengajuanController::class, 'kalabCeklistPaksaAcc'])->name('kalab.paksa.kalabCeklistPaksaAcc');

    Route::get('/kalab/paksa/detail', [paksaPengajuanController::class, 'detailPaksaKalabShow'])->name('kalab.paksa.edit');
    Route::put('/kalab/paksa/edit/proses', [paksaPengajuanController::class, 'lanjutiPaksaKalabProses'])->name('kalab.paksa.edit.proses');
});

//- [Menu PLP] -- \\
Route::middleware(['plp'])->group(function () {
    Route::get('/plp/listpengajuan', [plpController::class, 'plpShowlist'])->name('plp.listpengajuan');
    Route::get('/plp/edit', [plpController::class, 'editPlpDetailShow'])->name('plp.edit');
    Route::put('/plp/edit/proses/{kode_kegiatan}', [plpController::class, 'plpEditProses'])->name('plp.edit.proses');
    Route::post('/plp/approve-selected', [plpController::class, 'approveSelected'])->name('plp.approveSelected');
    Route::get('/plp/plpdisetujui', [plpController::class, 'plpListDisetujui'])->name('plp.listdisetujui');

    //- [PLP Pengajuan Paksa] -- \\
    Route::get('/plp/paksa/listpengajuan', [paksaPengajuanController::class, 'plpPaksaPengajuanShowList'])->name('plp.paksa.listpengajuan');
    Route::post('/plp/paksa/plpCeklistPaksaAcc', [paksaPengajuanController::class, 'plpCeklistPaksaAcc'])->name('plp.paksa.plpCeklistPaksaAcc');
    Route::get('/plp/paksa/detail', [paksaPengajuanController::class, 'detailPaksaPlpShow'])->name('plp.paksa.edit');
    Route::put('/plp/paksa/edit/proses', [paksaPengajuanController::class, 'lanjutiPaksaPlpProses'])->name('plp.paksa.edit.proses');
});

//- [Menu Dosen Pembimbing Akademik] -- \\
Route::middleware(['dospem'])->group(function () {
    Route::get('/dospem/list/bebaskompen', [dospemController::class, 'dospemShowlist'])->name('dospem.listbebas');
    Route::get('/dospem/showdetail/{id}', [dospemController::class, 'dospemShowDetail'])->name('dospem.detail');
    Route::get('/dospem/listpengajuan', [dospemController::class, 'dospemPengajuanShowList'])->name('dospem.listpengajuan');
    Route::get('/dospem/dospemPengajuanShowDetail/', [dospemController::class, 'dospemPengajuanShowDetail'])->name('dospem.lihatdetail');
    Route::get('/dospem/generateChartDatal/', [dospemController::class, 'generateChartData'])->name('dospem.generateChartData');
});

//- [Menu KPS] -- \\
Route::middleware(['kps'])->group(function () {
    Route::get('/kps/listmahasiswa', [kpsController::class, 'kpsShowlist'])->name('kps.listmahasiswa');
    Route::get('/kps/detail/{id}', [kpsController::class, 'kpsShowDetail'])->name('kps.detail');
    Route::get('/kps/listpengajuan', [kpsController::class, 'kpsPengajuanShowList'])->name('kps.listpengajuan');
    Route::get('/kps/kpsPengajuanShowDetail/', [kpsController::class, 'kpsPengajuanShowDetail'])->name('kps.lihatdetail');
});

//- [Menu Kajur] -- \\
Route::middleware(['kajur'])->group(function () {
    Route::get('/kajur/listmahasiswa', [kajurController::class, 'kajurShowlist'])->name('kajur.listmahasiswa');
    Route::get('/kajur/detail/{id}', [kajurController::class, 'kajurShowDetail'])->name('kajur.detail');
    Route::get('/kajur/listpengajuan', [kajurController::class, 'kajurPengajuanShowList'])->name('kajur.listpengajuan');
    Route::get('/kajur/kajurPengajuanShowDetail/', [kajurController::class, 'kajurPengajuanShowDetail'])->name('kajur.lihatdetail');
    Route::get('/kajur/generateChartDatal/', [kajurController::class, 'generateChartData'])->name('kajur.generateChartData');
});

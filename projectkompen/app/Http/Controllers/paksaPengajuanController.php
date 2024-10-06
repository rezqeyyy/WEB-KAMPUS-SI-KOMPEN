<?php

namespace App\Http\Controllers;

use App\Models\MPengajuan;
use App\Models\MPengajuanDetail;
use App\Models\MSetupBertugas;
use App\Models\MPekerjaan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class paksaPengajuanController extends Controller
{
    public function adminPengajuanShowList()
    {
        $dataPengajuan = MPengajuan::where('status_approval1', 'Sudah Upload')
            ->where('status_approval2', 'Belum Disetujui')
            ->where('status_approval3', 'Belum Disetujui')
            ->get();

        $filteredPengajuan = $dataPengajuan->filter(function ($pengajuan) {
            return $pengajuan->total <= 1500;
        });

        return view('master.pengajuan.listpengajuan', ['dataPengajuan' => $filteredPengajuan]);
    }
    public function adminCeklistPaksaAcc(Request $request)
    {
        $selectedPengajuanIds = $request->input('selected_pengajuan');

        // Ambil data setup terakhir
        $setupDataTerakhir = MSetupBertugas::orderBy('tgl_bertugas', 'desc')->take(3)->get();

        // Cek apakah setupDataTerakhir mencakup peran yang sesuai
        $approval1_by = $setupDataTerakhir->where('role', 'Pengawas')->first()->nama_user ?? null;
        $approval2_by = $setupDataTerakhir->where('role', 'PLP')->first()->nama_user ?? null;
        $approval3_by = $setupDataTerakhir->where('role', 'Kepala Lab')->first()->nama_user ?? null;

        // Misalnya, kita menganggap $selectedPengajuanIds adalah array id pengajuan yang dipilih
        foreach ($selectedPengajuanIds as $pengajuanId) {
            // Temukan pengajuan berdasarkan id
            $pengajuan = MPengajuan::findOrFail($pengajuanId);

            // Lakukan pembaruan pengajuan sesuai dengan informasi approval dari setup terakhir
            $pengajuan->sisa = '0';
            $pengajuan->status_approval1 = 'Disetujui';
            $pengajuan->status_approval2 = 'Disetujui';
            $pengajuan->status_approval3 = 'Disetujui';
            $pengajuan->approval1_by = $approval1_by;
            $pengajuan->approval2_by = $approval2_by;
            $pengajuan->approval3_by = $approval3_by;

            // Simpan perubahan
            $pengajuan->save();
        }

        // Redirect atau kembali ke halaman yang sesuai setelah selesai memperbarui
        return redirect()->route('master.pengajuan.listpengajuan')->with('alert-success', 'Pengajuan berhasil disetujui secara paksa.');
    }

    public function pengajuanAdminDetailShow(Request $request)
    {
        $kode_kegiatan = $request->query('kode_kegiatan');

        // Retrieve the main pengajuan data
        $dataPengajuan = MPengajuan::where('kode_kegiatan', $kode_kegiatan)->firstOrFail();

        // Retrieve the related pengajuan detail
        $pengajuanDetail = MPengajuanDetail::where('kode_kegiatan', $kode_kegiatan)->get();

        // Retrieve the related pekerjaan data and associated images
        foreach ($pengajuanDetail as $value) {
            $value->pekerjaan = MPekerjaan::where('kode_pekerjaan', $value->kode_pekerjaan)->first();
            // Assuming these columns exist, fetch the URL/path of the images
            $value->before_pekerjaan_url = $value->before_pekerjaan;
            $value->after_pekerjaan_url = $value->after_pekerjaan;
        }

        // Pass all required data to the view
        return view('master.pengajuan.edit', [
            'dataPengajuan' => $dataPengajuan,
            'pengajuanDetail' => $pengajuanDetail,
        ]);
    }

    public function lanjutiPaksaAdminProses(Request $request)
    {
        $kode_kegiatan = $request->input('kode_kegiatan'); // Pastikan Anda mendapatkan kode_kegiatan dari form

        // Ambil data setup terakhir
        $setupDataTerakhir = MSetupBertugas::orderBy('tgl_bertugas', 'desc')->take(3)->get();

        // Cek apakah setupDataTerakhir mencakup peran yang sesuai
        $approval1_by = $setupDataTerakhir->where('role', 'Pengawas')->first()->nama_user ?? null;
        $approval2_by = $setupDataTerakhir->where('role', 'PLP')->first()->nama_user ?? null;
        $approval3_by = $setupDataTerakhir->where('role', 'Kepala Lab')->first()->nama_user ?? null;

        // Temukan pengajuan berdasarkan kode_kegiatan
        $pengajuan = MPengajuan::where('kode_kegiatan', $kode_kegiatan)->firstOrFail();

        // Lakukan pembaruan pengajuan sesuai dengan informasi approval dari setup terakhir
        $pengajuan->sisa = '0';
        $pengajuan->status_approval1 = 'Disetujui';
        $pengajuan->status_approval2 = 'Disetujui';
        $pengajuan->status_approval3 = 'Disetujui';
        $pengajuan->approval1_by = $approval1_by;
        $pengajuan->approval2_by = $approval2_by;
        $pengajuan->approval3_by = $approval3_by;

        // Simpan perubahan
        $pengajuan->save();

        // Redirect ke halaman yang sesuai setelah pemrosesan selesai
        return redirect()->route('master.pengajuan.listpengajuan')->with('alert-success', 'Pengajuan berhasil Diproses Paksa!');
    }

    public function kalabPaksaPengajuanShowList()
    {
        $dataPengajuan = MPengajuan::where('status_approval1', 'Sudah Upload')
            ->where('status_approval2', 'Belum Disetujui')
            ->get();

        $filteredPengajuan = $dataPengajuan->filter(function ($pengajuan) {
            return $pengajuan->total <= 1500;
        });

        return view('kalab.paksa.listpengajuan', ['dataPengajuan' => $filteredPengajuan]);
    }
    public function kalabCeklistPaksaAcc(Request $request)
    {
        $selectedPengajuanIds = $request->input('selected_pengajuan');

        // Ambil data setup terakhir
        $setupDataTerakhir = MSetupBertugas::orderBy('tgl_bertugas', 'desc')->take(3)->get();

        // Cek apakah setupDataTerakhir mencakup peran yang sesuai
        $approval1_by = $setupDataTerakhir->where('role', 'Pengawas')->first()->nama_user ?? null;
        $approval2_by = $setupDataTerakhir->where('role', 'PLP')->first()->nama_user ?? null;
        $approval3_by = $setupDataTerakhir->where('role', 'Kepala Lab')->first()->nama_user ?? null;

        // Misalnya, kita menganggap $selectedPengajuanIds adalah array id pengajuan yang dipilih
        foreach ($selectedPengajuanIds as $pengajuanId) {
            // Temukan pengajuan berdasarkan id
            $pengajuan = MPengajuan::findOrFail($pengajuanId);

            // Lakukan pembaruan pengajuan sesuai dengan informasi approval dari setup terakhir
            $pengajuan->sisa = '0';
            $pengajuan->status_approval1 = 'Disetujui';
            $pengajuan->status_approval2 = 'Disetujui';
            $pengajuan->status_approval3 = 'Disetujui';
            $pengajuan->approval1_by = $approval1_by;
            $pengajuan->approval2_by = $approval2_by;
            $pengajuan->approval3_by = $approval3_by;

            // Simpan perubahan
            $pengajuan->save();
        }

        // Redirect atau kembali ke halaman yang sesuai setelah selesai memperbarui
        return redirect()->route('kalab.paksa.listpengajuan')->with('alert-success', 'Pengajuan berhasil disetujui secara paksa.');
    }

    public function detailPaksaKalabShow(Request $request)
    {
        $kode_kegiatan = $request->query('kode_kegiatan');

        // Retrieve the main pengajuan data
        $dataPengajuan = MPengajuan::where('kode_kegiatan', $kode_kegiatan)->firstOrFail();

        // Retrieve the related pengajuan detail
        $pengajuanDetail = MPengajuanDetail::where('kode_kegiatan', $kode_kegiatan)->get();

        // Retrieve the related pekerjaan data and associated images
        foreach ($pengajuanDetail as $value) {
            $value->pekerjaan = MPekerjaan::where('kode_pekerjaan', $value->kode_pekerjaan)->first();
            // Assuming these columns exist, fetch the URL/path of the images
            $value->before_pekerjaan_url = $value->before_pekerjaan;
            $value->after_pekerjaan_url = $value->after_pekerjaan;
        }

        // Pass all required data to the view
        return view('kalab.paksa.edit', [
            'dataPengajuan' => $dataPengajuan,
            'pengajuanDetail' => $pengajuanDetail,
        ]);
    }

    public function lanjutiPaksaKalabProses(Request $request)
    {
        $kode_kegiatan = $request->input('kode_kegiatan'); // Pastikan Anda mendapatkan kode_kegiatan dari form

        // Ambil data setup terakhir
        $setupDataTerakhir = MSetupBertugas::orderBy('tgl_bertugas', 'desc')->take(3)->get();

        // Cek apakah setupDataTerakhir mencakup peran yang sesuai
        $approval1_by = $setupDataTerakhir->where('role', 'Pengawas')->first()->nama_user ?? null;
        $approval2_by = $setupDataTerakhir->where('role', 'PLP')->first()->nama_user ?? null;
        $approval3_by = $setupDataTerakhir->where('role', 'Kepala Lab')->first()->nama_user ?? null;

        // Temukan pengajuan berdasarkan kode_kegiatan
        $pengajuan = MPengajuan::where('kode_kegiatan', $kode_kegiatan)->firstOrFail();

        // Lakukan pembaruan pengajuan sesuai dengan informasi approval dari setup terakhir
        $pengajuan->sisa = '0';
        $pengajuan->status_approval1 = 'Disetujui';
        $pengajuan->status_approval2 = 'Disetujui';
        $pengajuan->status_approval3 = 'Disetujui';
        $pengajuan->approval1_by = $approval1_by;
        $pengajuan->approval2_by = $approval2_by;
        $pengajuan->approval3_by = $approval3_by;

        // Simpan perubahan
        $pengajuan->save();

        // Redirect ke halaman yang sesuai setelah pemrosesan selesai
        return redirect()->route('kalab.paksa.listpengajuan')->with('alert-success', 'Pengajuan berhasil diproses paksa!');
    }

    public function plpPaksaPengajuanShowList()
    {
        $dataPengajuan = MPengajuan::where('status_approval1', 'Sudah Upload')
            ->where('status_approval2', 'Belum Disetujui')
            ->where('status_approval3', 'Belum Disetujui')
            ->get();

        $filteredPengajuan = $dataPengajuan->filter(function ($pengajuan) {
            return $pengajuan->total <= 1500;
        });

        return view('plp.paksa.listpengajuan', ['dataPengajuan' => $filteredPengajuan]);
    }

    public function plpCeklistPaksaAcc(Request $request)
    {
        $selectedPengajuanIds = $request->input('selected_pengajuan');

        // Ambil data setup terakhir
        $setupDataTerakhir = MSetupBertugas::orderBy('tgl_bertugas', 'desc')->take(3)->get();

        // Cek apakah setupDataTerakhir mencakup peran yang sesuai
        $approval1_by = $setupDataTerakhir->where('role', 'Pengawas')->first()->nama_user ?? null;
        $approval2_by = $setupDataTerakhir->where('role', 'PLP')->first()->nama_user ?? null;
        $approval3_by = $setupDataTerakhir->where('role', 'Kepala Lab')->first()->nama_user ?? null;

        // Misalnya, kita menganggap $selectedPengajuanIds adalah array id pengajuan yang dipilih
        foreach ($selectedPengajuanIds as $pengajuanId) {
            // Temukan pengajuan berdasarkan id
            $pengajuan = MPengajuan::findOrFail($pengajuanId);

            // Lakukan pembaruan pengajuan sesuai dengan informasi approval dari setup terakhir
            $pengajuan->sisa = '0';
            $pengajuan->status_approval1 = 'Disetujui';
            $pengajuan->status_approval2 = 'Disetujui';
            $pengajuan->status_approval3 = 'Disetujui';
            $pengajuan->approval1_by = $approval1_by;
            $pengajuan->approval2_by = $approval2_by;
            $pengajuan->approval3_by = $approval3_by;

            // Simpan perubahan
            $pengajuan->save();
        }

        // Redirect atau kembali ke halaman yang sesuai setelah selesai memperbarui
        return redirect()->route('plp.paksa.listpengajuan')->with('alert-success', 'Pengajuan berhasil disetujui secara paksa.');
    }

    public function detailPaksaPlpShow(Request $request)
    {
        $kode_kegiatan = $request->query('kode_kegiatan');

        // Retrieve the main pengajuan data
        $dataPengajuan = MPengajuan::where('kode_kegiatan', $kode_kegiatan)->firstOrFail();

        // Retrieve the related pengajuan detail
        $pengajuanDetail = MPengajuanDetail::where('kode_kegiatan', $kode_kegiatan)->get();

        // Retrieve the related pekerjaan data and associated images
        foreach ($pengajuanDetail as $value) {
            $value->pekerjaan = MPekerjaan::where('kode_pekerjaan', $value->kode_pekerjaan)->first();
            // Assuming these columns exist, fetch the URL/path of the images
            $value->before_pekerjaan_url = $value->before_pekerjaan;
            $value->after_pekerjaan_url = $value->after_pekerjaan;
        }

        // Pass all required data to the view
        return view('plp.paksa.edit', [
            'dataPengajuan' => $dataPengajuan,
            'pengajuanDetail' => $pengajuanDetail,
        ]);
    }

    public function lanjutiPaksaPlpProses(Request $request)
    {
        $kode_kegiatan = $request->input('kode_kegiatan'); // Pastikan Anda mendapatkan kode_kegiatan dari form

        // Ambil data setup terakhir
        $setupDataTerakhir = MSetupBertugas::orderBy('tgl_bertugas', 'desc')->take(3)->get();

        // Cek apakah setupDataTerakhir mencakup peran yang sesuai
        $approval1_by = $setupDataTerakhir->where('role', 'Pengawas')->first()->nama_user ?? null;
        $approval2_by = $setupDataTerakhir->where('role', 'PLP')->first()->nama_user ?? null;
        $approval3_by = $setupDataTerakhir->where('role', 'Kepala Lab')->first()->nama_user ?? null;

        // Temukan pengajuan berdasarkan kode_kegiatan
        $pengajuan = MPengajuan::where('kode_kegiatan', $kode_kegiatan)->firstOrFail();

        // Lakukan pembaruan pengajuan sesuai dengan informasi approval dari setup terakhir
        $pengajuan->sisa = '0';
        $pengajuan->status_approval1 = 'Disetujui';
        $pengajuan->status_approval2 = 'Disetujui';
        $pengajuan->status_approval3 = 'Disetujui';
        $pengajuan->approval1_by = $approval1_by;
        $pengajuan->approval2_by = $approval2_by;
        $pengajuan->approval3_by = $approval3_by;

        // Simpan perubahan
        $pengajuan->save();

        // Redirect ke halaman yang sesuai setelah pemrosesan selesai
        return redirect()->route('plp.paksa.listpengajuan')->with('alert-success', 'Pengajuan berhasil Diproses Secara Paksa!');
    }
}

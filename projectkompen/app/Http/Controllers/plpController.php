<?php

namespace App\Http\Controllers;

use App\Models\MPekerjaan;
use App\Models\MPengajuan;
use App\Models\MPengajuanDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;


class plpController extends Controller
{
    public function plpShowlist()
    {
        $dataPengajuan = MPengajuan::where('status_approval1', 'Disetujui')
            ->where('status_approval2', 'Belum Disetujui')
            ->get();

        return view('plp.listpengajuan', ['dataPengajuan' => $dataPengajuan]);
    }
    public function plpListDisetujui()
    {
        $dataPengajuan = MPengajuan::where('status_approval2', 'Disetujui')
            ->get();

        return view('plp.listdisetujui', ['dataPengajuan' => $dataPengajuan]);
    }

    public function editPlpDetailShow(Request $request)
    {
        $kode_kegiatan = $request->query('kode_kegiatan');
        // Retrieve the main pengajuan data
        $dataPengajuan = MPengajuan::where('kode_kegiatan', '=', $kode_kegiatan)->firstOrFail();

        // Retrieve the related pengajuan detail
        $pengajuanDetail = MPengajuanDetail::where('kode_kegiatan', $kode_kegiatan)->get();

        // Retrieve the related pekerjaan data and associated images
        foreach ($pengajuanDetail as $key => $value) {
            $value->pekerjaan = MPekerjaan::where('kode_pekerjaan', $value->kode_pekerjaan)->first();
            // Assuming these columns exist, fetch the URL/path of the images
            $value->before_pekerjaan_url = $value->before_pekerjaan;
            $value->after_pekerjaan_url = $value->after_pekerjaan;
        }

        // Pass all required data to the view
        return view('plp.edit', [
            'dataPengajuan' => $dataPengajuan,
            'pengajuanDetail' => $pengajuanDetail,
        ]);
    }

    public function plpEditProses(Request $request, $kode_kegiatan)
    {
        $form_oldid = $request->post('oldid');
        $tblPengajuan = MPengajuan::where('kode_kegiatan', '=', $kode_kegiatan)->first();

        $tblDetails = MPengajuanDetail::where('kode_kegiatan', '=', $kode_kegiatan)->get();

        $form_status_approval2 = $request->post('status_approval2');
        $form_keterangan_approval2 = $request->post('keterangan_approval2');
        $form_approval2_by = $request->post('approval2_by');  // ensure the correct name

        $tblPengajuan->status_approval2 = $form_status_approval2;
        $tblPengajuan->approval2_by = $form_approval2_by;
        $tblPengajuan->keterangan_approval2 = $form_keterangan_approval2;
        $tblPengajuan->save();

        // Redirect to the appropriate route after updating
        return redirect()->route('plp.listpengajuan')->with('alert-success', 'Berhasil Mengubah Data');
    }

    public function approveSelected(Request $request)
    {
        $selectedPengajuan = $request->input('selected_pengajuan');
        $currentUser = Auth::guard('pengguna')->user(); // Dapatkan pengguna yang saat ini masuk

        foreach ($selectedPengajuan as $pengajuanId) {
            $pengajuan = MPengajuan::find($pengajuanId);
            if ($pengajuan && $pengajuan->status_approval2 != 'Disetujui') {
                // Lakukan logika untuk menyetujui pengajuan
                $pengajuan->status_approval2 = 'Disetujui';
                $pengajuan->approval2_by = $currentUser->nama_user; // Simpan nama pengguna yang menyetujui
                $pengajuan->save();
            }
        }

        return redirect()->back()->with('alert-success', 'Pengajuan yang dipilih berhasil disetujui.');
    }
}

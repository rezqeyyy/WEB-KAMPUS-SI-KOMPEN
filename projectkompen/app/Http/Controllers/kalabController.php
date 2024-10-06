<?php

namespace App\Http\Controllers;

use App\Models\MPekerjaan;
use App\Models\MPengajuan;
use App\Models\MPengajuanDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;


class kalabController extends Controller
{
    public function kalabShowlist(Request $request)
    {
        $dataPengajuan = MPengajuan::where('status_approval2', 'Disetujui')
            ->where('status_approval3', 'Belum Disetujui')
            ->get();

        return view('kalab.listpengajuan', ['dataPengajuan' => $dataPengajuan]);
    }


    public function kalabdisetujuiShow(Request $request)
    {
        $dataPengajuan = MPengajuan::where('status_approval3', 'Disetujui')
            ->get();

        return view('kalab.listdisetujui', ['dataPengajuan' => $dataPengajuan]);
    }

    public function editKalabDetailShow(Request $request)
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
        return view('kalab.edit', [
            'dataPengajuan' => $dataPengajuan,
            'pengajuanDetail' => $pengajuanDetail,
        ]);
    }

    public function kalabEditProses(Request $request, $kode_kegiatan)
    {
        $form_oldid = $request->post('oldid');
        $tblPengajuan = MPengajuan::where('kode_kegiatan', '=', $kode_kegiatan)->first();

        $tblDetails = MPengajuanDetail::where('kode_kegiatan', '=', $kode_kegiatan)->get();

        $form_status_approval3 = $request->post('status_approval3');
        $form_keterangan_approval3 = $request->post('keterangan_approval3');
        $form_approval3_by = $request->post('approval3_by');  // ensure the correct name

        $tblPengajuan->status_approval3 = $form_status_approval3;
        $tblPengajuan->approval3_by = $form_approval3_by;
        $tblPengajuan->keterangan_approval3 = $form_keterangan_approval3;
        $tblPengajuan->save();

        // Redirect to the appropriate route after updating
        return redirect()->route('kalab.listpengajuan')->with('alert-success', 'Berhasil Menyetujui');
    }

    public function approveSelected(Request $request)
    {
        $selectedPengajuan = $request->input('selected_pengajuan');
        $currentUser = Auth::guard('pengguna')->user(); // Dapatkan pengguna yang saat ini masuk

        foreach ($selectedPengajuan as $pengajuanId) {
            $pengajuan = MPengajuan::find($pengajuanId);
            if ($pengajuan && $pengajuan->status_approval3 != 'Disetujui') {
                // Lakukan logika untuk menyetujui pengajuan
                $pengajuan->status_approval3 = 'Disetujui';
                $pengajuan->save();

                // Simpan nama pengguna yang menyetujui
                $pengajuan->approval3_by = $currentUser->nama_user; // Pastikan nama kolom sesuai

                // Simpan perubahan approval3_by
                $pengajuan->save();
            }
        }

        return redirect()->back()->with('alert-success', 'Pengajuan yang dipilih berhasil disetujui.');
    }
}

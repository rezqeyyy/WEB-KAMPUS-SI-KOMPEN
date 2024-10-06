<?php

namespace App\Http\Controllers;

use App\Models\MMahasiswa;
use App\Models\MPekerjaan;
use App\Models\MPengajuan;
use App\Models\MPengajuanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class pengawasController extends Controller
{
    public function listPengajuanShow(Request $request)
    {
        $user = auth()->guard('pengguna')->user();
        // Valid statuses
        $validStatuses = ['Sudah Upload', 'Pending', 'Ditolak'];

        // Get distinct kelas and semester values for filter options
        $kelasOptions = MPengajuan::select('kelas')->distinct()->get();
        $semesterOptions = MPengajuan::select('semester')->distinct()->get();

        // Get selected kelas and semester from request
        $selectedKelas = $request->input('kelas');
        $selectedSemester = $request->input('semester');

        // Fetch data based on selected kelas and semester
        $query = MPengajuan::where('id_penanggung_jawab', '=', $user->id_user)
            ->whereIn('status_approval1', $validStatuses);

        if ($selectedKelas) {
            $query->where('kelas', $selectedKelas);
        }

        if ($selectedSemester) {
            $query->where('semester', $selectedSemester);
        }

        $dataPengajuan = $query->get();

        return view('pengawas.listpengajuan', compact('dataPengajuan', 'kelasOptions', 'semesterOptions', 'selectedKelas', 'selectedSemester'));
    }


    public function listDisetujuiShow(Request $request)
    {
        $user = auth()->guard('pengguna')->user();
        // dd($user);
        // Valid statuses
        $validStatuses = ['Disetujui'];

        // Mengambil pengajuan yang memiliki nilai status_approval1 yang valid
        $dataPengajuan = MPengajuan::where('id_penanggung_jawab', '=', $user->id_user)->whereIn('status_approval1', $validStatuses)->get();


        return view('pengawas.listdisetujui', compact('dataPengajuan'));
    }
    public function editPengawasDetailShow(Request $request)
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
        return view('pengawas.edit', [
            'dataPengajuan' => $dataPengajuan,
            'pengajuanDetail' => $pengajuanDetail,
        ]);
    }


    public function pengajuanEditProses(Request $request, $kode_kegiatan)
    {
        // Ambil data pengajuan berdasarkan kode kegiatan
        $tblPengajuan = MPengajuan::where('kode_kegiatan', $kode_kegiatan)->first();
        if (!$tblPengajuan) {
            return redirect()->route('pengawas.listpengajuan')->with('alert-danger', 'Pengajuan tidak ditemukan');
        }

        // Ambil detail pengajuan berdasarkan kode kegiatan
        $tblDetails = MPengajuanDetail::where('kode_kegiatan', $kode_kegiatan)->get();

        // Ambil nilai form status_approval1 dan keterangan_approval1 dari request
        $form_status_approval1 = $request->post('status_approval1');
        $form_keterangan_approval1 = $request->post('keterangan_approval1');
        $form_approval1_by = $request->post('approval1_by');

        // Jika form_status_approval1 adalah 'Ditolak', langsung atur status_approval1 dan keterangan_approval1
        if ($form_status_approval1 === 'Ditolak') {
            $tblPengajuan->status_approval1 = $form_status_approval1;
            $tblPengajuan->keterangan_approval1 = $form_keterangan_approval1;
        } else {
            // Hitung total jam pekerjaan dari detail pengajuan
            $countJamPekerjaan = 0;
            foreach ($tblDetails as $detail) {
                $countJamPekerjaan += intval($detail->jam_pekerjaan);
            }

            // Hitung sisa kompen berdasarkan aturan yang diberikan
            if (intval($tblPengajuan->total) > 1500) {
                $total = intval($tblPengajuan->total) - ($countJamPekerjaan > 1500 ? 1500 : $countJamPekerjaan);
            } else {
                $total = intval($tblPengajuan->total) - $countJamPekerjaan;
                if ($total < 0) {
                    $total = 0; // Total tidak boleh negatif
                }
            }

            // Jika bukti tambahan sudah ada, langsung atur sisa menjadi 0
            if ($tblPengajuan->bukti_tambahan != null) {
                $total = 0;
            }

            // Atur nilai sisa kompen pada pengajuan
            $tblPengajuan->sisa = $total;

            // Atur status_approval1 berdasarkan sisa kompen
            if ($tblPengajuan->sisa == 0) {
                // Jika sisa = 0, langsung setujui tanpa perlu bukti tambahan
                $tblPengajuan->status_approval1 = 'Disetujui';
            } else {
                // Jika sisa > 0, cek apakah bukti tambahan ada
                if ($tblPengajuan->bukti_tambahan != null) {
                    $tblPengajuan->status_approval1 = $form_status_approval1;
                } else {
                    $tblPengajuan->status_approval1 = 'Pending';
                }
            }
        }

        // Atur keterangan_approval1 dan approval1_by
        $tblPengajuan->keterangan_approval1 = $form_keterangan_approval1;
        $tblPengajuan->approval1_by = $form_approval1_by;

        // Simpan perubahan pada pengajuan
        $tblPengajuan->save();

        // Redirect ke route yang sesuai setelah berhasil mengubah data
        return redirect()->route('pengawas.listpengajuan')->with('alert-success', 'Berhasil Mengubah Data');
    }


    public function approveSelected(Request $request)
    {
        $selectedPengajuan = $request->input('selected_pengajuan');
        $currentUser = Auth::guard('pengguna')->user(); // Dapatkan pengguna yang saat ini masuk

        foreach ($selectedPengajuan as $pengajuanId) {
            $pengajuan = MPengajuan::find($pengajuanId);
            if ($pengajuan && $pengajuan->status_approval1 != 'Disetujui') {
                // Check if bukti_tambahan is filled
                if ($pengajuan->bukti_tambahan != null) {
                    // If bukti_tambahan is filled, set sisa to 0 and status to 'Disetujui'
                    $pengajuan->sisa = 0;
                    $pengajuan->status_approval1 = 'Disetujui';
                } else {
                    // Proses pengurangan sisa
                    $tblDetails = MPengajuanDetail::where('kode_kegiatan', '=', $pengajuan->kode_kegiatan)->get();
                    $countJamPekerjaan = 0;
                    foreach ($tblDetails as $value) {
                        $countJamPekerjaan += intval($value->jam_pekerjaan);
                    }

                    // Calculate sisa and assign it
                    $total = 0;
                    if (intval($pengajuan->total) > 1500) {
                        $total = intval($pengajuan->total) - ($countJamPekerjaan > 1500 ? 1500 : $countJamPekerjaan);
                    }

                    if (intval($pengajuan->sisa) >= 0 && $pengajuan->bukti_tambahan != null) {
                        $total = 0;
                    }

                    // Jika kurang dari 1500
                    if (intval($pengajuan->total) <= 1500) {
                        $total = intval($pengajuan->total) - $countJamPekerjaan;
                        // Jika hasil pengurangan dari kompen - jam kerja jadi minus, maka total = 0 (SUDAH HABIS)
                        if (intval($pengajuan->total) - $countJamPekerjaan < 0) {
                            $total = 0;
                        }
                    }

                    // Assign calculated total to sisa
                    $pengajuan->sisa = $total;

                    // Check if sisa is zero and set status accordingly
                    if ($pengajuan->sisa == 0) {
                        $pengajuan->status_approval1 = $request->post('status_approval1');
                    } else {
                        $pengajuan->status_approval1 = 'Pending';
                    }
                }

                // Simpan nama pengguna yang menyetujui
                $pengajuan->approval1_by = $currentUser->nama_user; // Ganti 'nama_user' dengan kolom yang sesuai

                // Simpan perubahan
                $pengajuan->save();
            }
        }

        return redirect()->back()->with('alert-success', 'Pengajuan yang dipilih berhasil disetujui.');
    }
}

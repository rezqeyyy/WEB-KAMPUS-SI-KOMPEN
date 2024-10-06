<?php

namespace App\Http\Controllers;

use App\Models\MBebasKompen;
use App\Models\MPekerjaan;
use App\Models\MPengajuan;
use App\Models\MUser;
use App\Models\MKelas;
use App\Models\MPengajuanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class pengajuanController extends Controller
{
    public function __construct()
    {
        $this->middleware('mahasiswa');
    }

    public function ambilShowCreate()
    {
        $mahasiswa = Auth::guard('pmahasiswa')->user();
        $pekerjaanList = MPekerjaan::where('batas_pekerja', '>', 0)->get();

        return view('mahasiswa.pekerjaan.create', compact('mahasiswa', 'pekerjaanList'));
    }

    public function ambilCreateProses(Request $request)
    {
        DB::beginTransaction();

        try {
            // Ambil data dari form
            $form_kode_mahasiswa = $request->post('kode_mahasiswa');
            $form_nama_mahasiswa = $request->post('nama_mahasiswa');
            $form_kelas = $request->post('kelas');
            $form_prodi = $request->post('prodi');
            $form_semester = $request->post('semester');
            $form_jumlah_terlambat = $request->post('jumlah_terlambat');
            $form_jumlah_alfa = $request->post('jumlah_alfa');
            $form_status_approval1 = $request->post('status_approval1');
            $form_total = $request->post('total');
            $pekerjaanList = $request->post('pekerjaan', []);

            // Simpan ke tabel pengajuan
            $tbl_pengajuan = new MPengajuan();
            $tbl_pengajuan->kode_user = $form_kode_mahasiswa;
            $tbl_pengajuan->nama_user = $form_nama_mahasiswa;
            $tbl_pengajuan->kelas = $form_kelas;
            $tbl_pengajuan->prodi = $form_prodi;
            $tbl_pengajuan->semester = $form_semester;
            $tbl_pengajuan->status_approval1 = $form_status_approval1;
            $tbl_pengajuan->jumlah_alfa = $form_jumlah_alfa;
            $tbl_pengajuan->jumlah_terlambat = $form_jumlah_terlambat;
            $tbl_pengajuan->total = $form_total;

            // Ambil penanggung jawab dari pekerjaan pertama yang dipilih
            $pekerjaanPertama = MPekerjaan::where('kode_pekerjaan', $pekerjaanList[0])->first();
            $tbl_pengajuan->id_penanggung_jawab = $pekerjaanPertama->id_penanggung_jawab;
            $tbl_pengajuan->penanggung_jawab = $pekerjaanPertama->penanggung_jawab;

            $tbl_pengajuan->save();

            // Ambil kode_kegiatan dari pengajuan yang baru disimpan
            $kode_kegiatan = $tbl_pengajuan->kode_kegiatan;

            // Simpan ke tabel pengajuan_detail
            foreach ($pekerjaanList as $kode_pekerjaan) {
                $pekerjaan = MPekerjaan::where('kode_pekerjaan', $kode_pekerjaan)->first();

                $detail = new MPengajuanDetail();
                $detail->kode_kegiatan = $kode_kegiatan;
                $detail->kode_pekerjaan = $kode_pekerjaan;
                $detail->nama_pekerjaan = $pekerjaan->nama_pekerjaan;
                $detail->jam_pekerjaan = $pekerjaan->jam_pekerjaan;
                $detail->batas_pekerja = $pekerjaan->batas_pekerja;
                $detail->save();

                // Kurangi batas_pekerja di tbl_pekerjaan
                if ($pekerjaan) {
                    $pekerjaan->batas_pekerja -= 1; // Kurangi 1 dari batas_pekerja
                    $pekerjaan->save();
                }
            }

            DB::commit();

            return redirect()->route('mahasiswa.pekerjaan.listdiambil')->with('alert-success', 'Pengajuan berhasil diajukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return redirect()->back()->with('alert-danger', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function listDiambilShow(Request $request)
    {
        $mahasiswa = Auth::guard('pmahasiswa')->user();
        // Mengambil pengajuan berdasarkan kode_mahasiswa
        $dataPengajuan = MPengajuan::where('kode_user', $mahasiswa->kode_user)->select('id_pengajuan', 'kelas', 'sisa', 'semester', 'kode_kegiatan', 'nama_user', 'total', 'status_approval1', 'status_approval2', 'status_approval3')->get();

        return view('mahasiswa.pekerjaan.listdiambil', compact('dataPengajuan'));
    }

    public function editPengajuanDetailShow(Request $request, $kode_kegiatan)
    {

        $kode_user = Auth::guard('pmahasiswa')->user()->kode_user;
        // Retrieve the main pengajuan data
        $dataPengajuan = MPengajuan::where('kode_kegiatan', '=', $kode_kegiatan)->where('kode_user', '=', $kode_user)->firstOrFail();

        // Retrieve the related pengajuan detail
        $pengajuanDetail = MPengajuanDetail::where('kode_kegiatan', $kode_kegiatan)->get();

        // Retrieve the related pekerjaan data
        foreach ($pengajuanDetail as $key => $value) {
            $value->pekerjaan = MPekerjaan::where('kode_pekerjaan', $value->kode_pekerjaan)->first();
        }

        // Pass all required data to the view
        return view('mahasiswa.pekerjaan.edit', [
            'dataPengajuan' => $dataPengajuan,
            'pengajuanDetail' => $pengajuanDetail,
        ]);
    }

    public function editPengajuanDetailProses(Request $request, $kode_kegiatan)
    {
        $tblPengajuan = MPengajuan::where('kode_kegiatan', '=', $kode_kegiatan)->first();
        $tblDetails = MPengajuanDetail::where('kode_kegiatan', '=', $kode_kegiatan)->get();

        // Handle file uploads
        $form_before_pekerjaans = $request->file('before_pekerjaan');
        $form_after_pekerjaans = $request->file('after_pekerjaan');
        $form_bukti_tambahan = $request->file('bukti_tambahan');

        if ($form_bukti_tambahan) {
            $form_bukti_tambahan->storeAs('public/bukti_tambahan', $form_bukti_tambahan->hashName());
            $tblPengajuan->bukti_tambahan = $form_bukti_tambahan->hashName();
        }

        foreach ($tblDetails as $index => $tblDetail) {
            $form_before_pekerjaan = $form_before_pekerjaans[$index] ?? null;
            $form_after_pekerjaan = $form_after_pekerjaans[$index] ?? null;

            if ($form_before_pekerjaan) {
                $form_before_pekerjaan->storeAs('public/before', $form_before_pekerjaan->hashName());
                Storage::delete('public/before/' . $tblDetail->before_pekerjaan);
                $tblDetail->before_pekerjaan = $form_before_pekerjaan->hashName();
            }

            if ($form_after_pekerjaan) {
                $form_after_pekerjaan->storeAs('public/after', $form_after_pekerjaan->hashName());
                Storage::delete('public/after/' . $tblDetail->after_pekerjaan);
                $tblDetail->after_pekerjaan = $form_after_pekerjaan->hashName();
            }

            $tblDetail->save();
        }

        // Ensure status_approval1 is being correctly set and saved
        $tblPengajuan->status_approval1 = 'Sudah Upload';
        $tblPengajuan->save();

        return redirect()->route('mahasiswa.pekerjaan.listdiambil')->with('alert-success', 'Berhasil Mengubah Data');
    }



    public function suratShow(Request $request, $kode_kegiatan)
    {
        $kode_user = Auth::guard('pmahasiswa')->user()->kode_user;

        // Retrieve the main pengajuan data
        $dataPengajuan = MPengajuan::where('kode_kegiatan', '=', $kode_kegiatan)
            ->where('kode_user', '=', $kode_user)
            ->firstOrFail();

        // Retrieve the related pengajuan detail
        $pengajuanDetail = MPengajuanDetail::where('kode_kegiatan', $kode_kegiatan)->get();

        // Retrieve the related pekerjaan data
        foreach ($pengajuanDetail as $key => $value) {
            $value->pekerjaan = MPekerjaan::where('kode_pekerjaan', $value->kode_pekerjaan)->first();
        }

        $namaUser1 = $dataPengajuan->approval1_by;
        $namaUser2 = $dataPengajuan->approval2_by;
        $namaUser3 = $dataPengajuan->approval3_by;

        // Initialize variables to null
        $ttd1 = null;
        $ttd2 = null;
        $ttd3 = null;
        $user1 = null;
        $user2 = null;
        $user3 = null;

        // Find matching users in tbl_user and get their signatures
        if ($namaUser1) {
            $user1 = MUser::where('nama_user', $namaUser1)->first();
            if ($user1) {
                $ttd1 = asset('storage/signature/' . $user1->ttd);
            }
        }

        if ($namaUser2) {
            $user2 = MUser::where('nama_user', $namaUser2)->first();
            if ($user2) {
                $ttd2 = asset('storage/signature/' . $user2->ttd);
            }
        }

        if ($namaUser3) {
            $user3 = MUser::where('nama_user', $namaUser3)->first();
            if ($user3) {
                $ttd3 = asset('storage/signature/' . $user3->ttd);
            }
        }

        // $kategoriNama = null; // Initialize variable $kategoriNama

        // Pass all required data to the view
        return view('mahasiswa.surat', [
            'dataPengajuan' => $dataPengajuan,
            'namaUser1' => $namaUser1,
            'namaUser2' => $namaUser2,
            'namaUser3' => $namaUser3,
            'ttd1' => $ttd1,
            'ttd2' => $ttd2,
            'ttd3' => $ttd3,
            'user1' => $user1,
            'user2' => $user2,
            'user3' => $user3,
        ]);
    }

    public function listFormBebasShow(Request $request)
    {
        $mahasiswa = Auth::guard('pmahasiswa')->user();
        // Mengambil pengajuan berdasarkan kode_mahasiswa
        $dataPengajuan = MBebasKompen::where('kode_user', $mahasiswa->kode_user)->select('id_bebas_kompen', 'id_pengajuan', 'nama_user', 'kelas', 'semester', 'form_bebas_kompen', 'status_approval1', 'status_approval2', 'status_approval3', 'approval1_by', 'approval2_by', 'approval3_by')->get();

        return view('mahasiswa.bebas.listbebas', compact('dataPengajuan'));
    }

    public function bebasShowCreate()
    {
        $mahasiswa = Auth::guard('pmahasiswa')->user();
        $pengajuan = MPengajuan::where('kode_user', $mahasiswa->kode_user)
            ->where(function ($query) {
                $query->where('status_approval1', 'Disetujui')
                    ->Where('status_approval2', 'Disetujui')
                    ->Where('status_approval3', 'Disetujui');
            })
            ->get();

        return view('mahasiswa.bebas.create', compact('mahasiswa', 'pengajuan'));
    }

    public function getPengajuan($id_pengajuan)
    {
        $pengajuan = MPengajuan::where('id_pengajuan', $id_pengajuan)
            ->where(function ($query) {
                $query->where('status_approval1', 'Disetujui')
                    ->Where('status_approval2', 'Disetujui')
                    ->Where('status_approval3', 'Disetujui');
            })
            ->get();

        if ($pengajuan->isEmpty()) {
            // Return an error response if no data is found
            return response()->json(['error' => 'No data found'], 404);
        }

        $data = $pengajuan->first();

        return response()->json([
            'kode_user' => $data->kode_user,
            'nama_user' => $data->nama_user,
            'kelas' => $data->kelas,
            'prodi' => $data->prodi,
            'semester' => $data->semester,
            'jumlah_terlambat' => $data->jumlah_terlambat,
            'jumlah_alfa' => $data->jumlah_alfa,
            'total' => $data->total,
            'sisa' => $data->sisa,

            'status_approval1' => $data->status_approval1,
            'status_approval2' => $data->status_approval2,
            'status_approval3' => $data->status_approval3,
            'approval1_by' => $data->approval1_by,
            'approval2_by' => $data->approval2_by,
            'approval3_by' => $data->approval3_by,
        ]);
    }


    public function bebasCreateProses(Request $request)
    {
        $form_id_pengajuan = $request->post('id_pengajuan');
        $form_kode_mahasiswa = $request->post('kode_mahasiswa');
        $form_nama_mahasiswa = $request->post('nama_mahasiswa');
        $form_kelas = $request->post('kelas');
        $form_prodi = $request->post('prodi');
        $form_semester = $request->post('semester');
        $form_jumlah_terlambat = $request->post('jumlah_terlambat');
        $form_jumlah_alfa = $request->post('jumlah_alfa');
        $form_total = $request->post('total');
        $form_sisa = $request->post('sisa');
        $form_status_approval1 = $request->post('status_approval1');
        $form_status_approval2 = $request->post('status_approval2');
        $form_status_approval3 = $request->post('status_approval3');
        $form_approval1_by = $request->post('approva1_by');
        $form_approval2_by = $request->post('approva2_by');
        $form_approval3_by = $request->post('approva3_by');

        $tbl_bebas_kompen = new MBebasKompen();
        $tbl_bebas_kompen->id_pengajuan = $form_id_pengajuan;
        $tbl_bebas_kompen->kode_user = $form_kode_mahasiswa;
        $tbl_bebas_kompen->nama_user = $form_nama_mahasiswa;
        $tbl_bebas_kompen->kelas = $form_kelas;
        $tbl_bebas_kompen->prodi = $form_prodi;
        $tbl_bebas_kompen->semester = $form_semester;
        $tbl_bebas_kompen->jumlah_terlambat = $form_jumlah_terlambat;
        $tbl_bebas_kompen->jumlah_alfa = $form_jumlah_alfa;
        $tbl_bebas_kompen->total = $form_total;
        $tbl_bebas_kompen->sisa = $form_sisa;
        $tbl_bebas_kompen->status_approval1 = $form_status_approval1;
        $tbl_bebas_kompen->status_approval2 = $form_status_approval2;
        $tbl_bebas_kompen->status_approval3 = $form_status_approval3;
        $tbl_bebas_kompen->approval1_by = $form_approval1_by;
        $tbl_bebas_kompen->approval2_by = $form_approval2_by;
        $tbl_bebas_kompen->approval3_by = $form_approval3_by;
        $form_bebas_kompen = $request->file('form_bebas_kompen');

        if ($form_bebas_kompen) {
            $form_bebas_kompen->storeAs('public/form_bebas_kompen', $form_bebas_kompen->hashName());
            $tbl_bebas_kompen->form_bebas_kompen = $form_bebas_kompen->hashName();
        }

        $tbl_bebas_kompen->save();

        return redirect()->route('mahasiswa.bebas.listbebas')->with('alert-success', 'Berhasil Mengubah Data');
    }

    public function bebasEditShow($id)
    {
        $mahasiswa = Auth::guard('pmahasiswa')->user();
        $pengajuan = MPengajuan::where('kode_user', $mahasiswa->kode_user)->get();
        $dataPengajuan = MBebasKompen::findOrFail($id);

        return view('mahasiswa.bebas.edit', compact('mahasiswa', 'pengajuan', 'dataPengajuan'));
    }

    public function bebasEditProses(Request $request, $id)
    {
        $form_id_pengajuan = $request->post('id_pengajuan');
        $form_kode_mahasiswa = $request->post('kode_mahasiswa');
        $form_nama_mahasiswa = $request->post('nama_mahasiswa');
        $form_kelas = $request->post('kelas');
        $form_prodi = $request->post('prodi');
        $form_semester = $request->post('semester');
        $form_jumlah_terlambat = $request->post('jumlah_terlambat');
        $form_jumlah_alfa = $request->post('jumlah_alfa');
        $form_total = $request->post('total');
        $form_sisa = $request->post('sisa');
        $form_status_approval1 = $request->post('status_approval1');
        $form_status_approval2 = $request->post('status_approval2');
        $form_status_approval3 = $request->post('status_approval3');
        $form_approval1_by = $request->post('approva1_by');
        $form_approval2_by = $request->post('approva2_by');
        $form_approval3_by = $request->post('approva3_by');

        $tbl_bebas_kompen = MBebasKompen::findOrFail($id);
        $tbl_bebas_kompen->id_pengajuan = $form_id_pengajuan;
        $tbl_bebas_kompen->kode_user = $form_kode_mahasiswa;
        $tbl_bebas_kompen->nama_user = $form_nama_mahasiswa;
        $tbl_bebas_kompen->kelas = $form_kelas;
        $tbl_bebas_kompen->prodi = $form_prodi;
        $tbl_bebas_kompen->semester = $form_semester;
        $tbl_bebas_kompen->jumlah_terlambat = $form_jumlah_terlambat;
        $tbl_bebas_kompen->jumlah_alfa = $form_jumlah_alfa;
        $tbl_bebas_kompen->total = $form_total;
        $tbl_bebas_kompen->sisa = $form_sisa;
        $tbl_bebas_kompen->status_approval1 = $form_status_approval1;
        $tbl_bebas_kompen->status_approval2 = $form_status_approval2;
        $tbl_bebas_kompen->status_approval3 = $form_status_approval3;
        $tbl_bebas_kompen->approval1_by = $form_approval1_by;
        $tbl_bebas_kompen->approval2_by = $form_approval2_by;
        $tbl_bebas_kompen->approval3_by = $form_approval3_by;

        $form_bebas_kompen = $request->file('form_bebas_kompen');

        if ($form_bebas_kompen) {
            $form_bebas_kompen->storeAs('public/form_bebas_kompen', $form_bebas_kompen->hashName());
            $tbl_bebas_kompen->form_bebas_kompen = $form_bebas_kompen->hashName();
        }

        $tbl_bebas_kompen->save();

        return redirect()->route('mahasiswa.bebas.listbebas')->with('alert-success', 'Berhasil Mengubah Data');
    }

    public function bebasDeleteProses($id)
    {
        $bebas = MBebasKompen::findOrFail($id);
        $bebas->delete();

        return redirect()->route('mahasiswa.bebas.listbebas')->with('alert-success', 'Data berhasil dihapus');
    }

    public function deletePengajuan(Request $request, $kode_kegiatan)
    {
        DB::beginTransaction();

        try {
            // Cari pengajuan berdasarkan kode_kegiatan dan kode_user dari mahasiswa yang sedang login
            $mahasiswa = Auth::guard('pmahasiswa')->user();
            $pengajuan = MPengajuan::where('kode_kegiatan', $kode_kegiatan)
                ->where('kode_user', $mahasiswa->kode_user)
                ->firstOrFail();

            // Ambil daftar kode pekerjaan dari pengajuan yang akan dihapus
            $pekerjaanList = MPengajuanDetail::where('kode_kegiatan', $kode_kegiatan)
                ->pluck('kode_pekerjaan')
                ->toArray();

            // Hapus detail pengajuan terlebih dahulu
            MPengajuanDetail::where('kode_kegiatan', $kode_kegiatan)->delete();

            // Hapus pengajuan utama
            $pengajuan->delete();

            // Kembalikan batas_pekerja untuk setiap pekerjaan yang terlibat
            foreach ($pekerjaanList as $kode_pekerjaan) {
                $pekerjaan = MPekerjaan::where('kode_pekerjaan', $kode_pekerjaan)->first();
                if ($pekerjaan) {
                    $pekerjaan->batas_pekerja += 1; // Kembalikan 1 ke batas_pekerja
                    $pekerjaan->save();
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pengajuan berhasil dihapus.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}

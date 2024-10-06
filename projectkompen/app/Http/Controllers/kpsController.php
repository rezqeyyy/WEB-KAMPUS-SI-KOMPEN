<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MBebasKompen;
use App\Models\MPengajuan;
use App\Models\MPengajuanDetail;
use App\Models\MPekerjaan;

class kpsController extends Controller
{
    public function kpsShowlist(Request $request)
    {
        // Ambil nilai filter dari request
        $classFilter = $request->input('class');
        $prodiFilter = $request->input('prodi');
        $semesterFilter = $request->input('semester');
        $yearFilter = $request->input('year');

        // Query untuk mengambil data berdasarkan filter
        $query = MBebasKompen::query();

        if ($classFilter) {
            $query->where('kelas', $classFilter);
        }
        if ($prodiFilter) {
            $query->where('prodi', $prodiFilter);
        }
        if ($semesterFilter) {
            $query->where('semester', $semesterFilter);
        }
        if ($yearFilter) {
            $query->whereYear('created_at', $yearFilter);
        }

        // Ambil data berdasarkan query yang sudah difilter
        $dataPengajuan = $query->get();

        // Ambil opsi kelas, program studi, semester, dan tahun untuk filter
        $kelasOptions = MBebasKompen::select('kelas')->distinct()->pluck('kelas');
        $prodiOptions = MBebasKompen::select('prodi')->distinct()->pluck('prodi');
        $semesterOptions = MBebasKompen::select('semester')->distinct()->pluck('semester');
        $yearOptions = MBebasKompen::selectRaw('YEAR(created_at) as year')->distinct()->pluck('year');

        return view('kps.listmahasiswa', compact('dataPengajuan', 'kelasOptions', 'prodiOptions', 'semesterOptions', 'yearOptions'));
    }


    public function kpsShowDetail($id)
    {
        $dataPengajuan = MBebasKompen::findOrFail($id);

        return view('kps.detail', compact('dataPengajuan'));
    }


    public function kpsPengajuanShowList(Request $request)
    {
        // Ambil nilai filter dari request
        $classFilter = $request->input('class');
        $prodiFilter = $request->input('prodi');
        $semesterFilter = $request->input('semester');
        $yearFilter = $request->input('year');

        // Query untuk mengambil data berdasarkan filter
        $query = MPengajuan::query();

        if ($classFilter) {
            // Sesuaikan dengan kolom yang ada di MPengajuan
            $query->where('class_column_name', $classFilter);
        }
        if ($prodiFilter) {
            // Sesuaikan dengan kolom yang ada di MPengajuan
            $query->where('prodi_column_name', $prodiFilter);
        }
        if ($semesterFilter) {
            // Sesuaikan dengan kolom yang ada di MPengajuan
            $query->where('semester_column_name', $semesterFilter);
        }
        if ($yearFilter) {
            // Sesuaikan dengan kolom yang ada di MPengajuan dan kolom tanggal yang sesuai
            $query->whereYear('created_at', $yearFilter);
        }

        // Ambil data berdasarkan query yang sudah difilter
        $dataPengajuan = $query->get();

        // Ambil opsi kelas, program studi, semester, dan tahun untuk filter
        $kelasOptions = MBebasKompen::select('kelas')->distinct()->pluck('kelas');
        $prodiOptions = MBebasKompen::select('prodi')->distinct()->pluck('prodi');
        $semesterOptions = MBebasKompen::select('semester')->distinct()->pluck('semester');
        $yearOptions = MBebasKompen::selectRaw('YEAR(created_at) as year')->distinct()->pluck('year');

        return view('kps.listpengajuan', compact('dataPengajuan', 'kelasOptions', 'prodiOptions', 'semesterOptions', 'yearOptions'));
    }

    public function kpsPengajuanShowDetail(Request $request)
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
            $value->before_pekerjaan_url = $value->before_pekerjaan; // Adjust according to your column name
            $value->after_pekerjaan_url = $value->after_pekerjaan;   // Adjust according to your column name
        }

        // Pass all required data to the view
        return view('kps.lihatdetail', [
            'dataPengajuan' => $dataPengajuan,
            'pengajuanDetail' => $pengajuanDetail,
        ]);
    }
}

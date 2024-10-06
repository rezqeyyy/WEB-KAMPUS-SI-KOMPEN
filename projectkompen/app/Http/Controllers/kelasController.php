<?php

namespace App\Http\Controllers;

use App\Models\MKelas;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Session;

class kelasController extends Controller
{
    public function KelasShow(Request $request)
    {
        $dataKelas = MKelas::all(); //narik semua dari db kirim ke view
        return view('master.kelas.listkelas', ['dataKelas' => $dataKelas]);
    }
    public function kelasShowCreate(Request $request)
    {
        return view('master.kelas.create');
    }
    public function kelasShowEdit(Request $request)
    {
        // -- ambil dari request id
        $form_id_kelas = $request->query('id_kelas', '');

        $dataKelas = MKelas::findOrFail($form_id_kelas);

        return view('master.kelas.edit', ['dataKelas' => $dataKelas]);
    }
    public function KelasProsesAdd(Request $request)
    {
        //ambil dari form
        $form_kelas = $request->post('kelas');

        $namaKelasExists = MKelas::where('kelas', $form_kelas)->exists();
        if ($namaKelasExists) {
            Session::flash('alert-error', 'OOPSSS! Nama Kelas sudah ada!');
            return redirect()->route('master.kelas.create');
        }
        //set ke table
        $tblKelas = new MKelas();
        $tblKelas->kelas = $form_kelas;

        // Simpan data ke database
        $tblKelas->save();

        Session::flash('alert-success', 'Berhasil Menambahkan Data');
        return redirect()->route('master.kelas.listkelas');
    }
    public function kelasProsesEdit(Request $request)
    {
        $form_oldid = $request->post('oldid');
        //ambil dari form
        $form_kelas = $request->post('kelas');

        $namaKelasExists = MKelas::where('kelas', $form_kelas)->exists();
        if ($namaKelasExists) {
            Session::flash('alert-error', 'OOPSSS! Nama Kelas sudah ada!');
            return redirect()->route('master.kelas.listkelas');
        }

        $tblKelas = MKelas::findOrFail($form_oldid);
        $tblKelas->kelas = $form_kelas;

        // Simpan data ke database
        $tblKelas->save();

        Session::flash('alert-success', 'Berhasil Mengubah Data');
        return redirect()->route('master.kelas.listkelas');
    }

    public function kelasProsesDelete(Request $request)
    {
        // -- ambil dari form
        $form_oldid = $request->query('id_kelas');
        $tblKelas = MKelas::findOrFail($form_oldid);

        $tblKelas->delete();

        // kasih pesan success
        Session::flash('alert-success', 'Berhasil Hapus Data');
        return redirect()->route('master.kelas.listkelas');
    }

    public function importKelas(Request $request)
    {
        // Pastikan file Excel telah diunggah
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Baca file Excel menggunakan PhpSpreadsheet
            $reader = IOFactory::createReader('Xlsx'); // Sesuaikan jenis file Excel yang diunggah
            $spreadsheet = $reader->load($file);

            // Mendapatkan data dari lembar kerja pertama
            $worksheet = $spreadsheet->getActiveSheet();
            $data = $worksheet->toArray();

            // Variabel untuk statistik impor
            $importedCount = 0;
            $skippedCount = 0;
            $errorCount = 0;
            $errorMessages = [];

            foreach (array_slice($data, 1) as $row) {
                // Validasi apakah data sudah ada di database berdasarkan kolom kelas (kolom 1)
                $kelas = MKelas::where('kelas', $row[0])->first();

                if (!$kelas) {
                    // Simpan data baru ke database
                    MKelas::create([
                        'kelas' => $row[0],    // Kolom 1: Kode Kelas
                    ]);
                    $importedCount++;
                } else {
                    // Data sudah ada, tidak perlu disimpan kembali
                    $skippedCount++;
                }
            }

            // Setelah impor selesai, kembalikan dengan pesan sukses atau error
            $successMessage = "Data berhasil diimpor. $importedCount data baru diimpor, $skippedCount data diabaikan karena sudah ada.";
            if ($errorCount > 0) {
                $errorMessage = "Terdapat $errorCount error:<br>" . implode("<br>", $errorMessages);
                return redirect()->route('master.kelas.listkelas')->with('alert-error', $errorMessage);
            } else {
                return redirect()->route('master.kelas.listkelas')->with('alert-success', $successMessage);
            }
        }

        // Jika tidak ada file yang diunggah
        return redirect()->route('master.kelas.listkelas')->with('alert-danger', "Tidak ada file yang diunggah.");
    }
}

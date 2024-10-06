<?php

namespace App\Http\Controllers;

use App\Models\MProdi;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Session;

class prodiController extends Controller
{

    public function prodiShow(Request $request)
    {
        $dataProdi = MProdi::all(); //narik semua dari db kirim ke view
        return view('master.prodi.listprodi', ['dataProdi' => $dataProdi]);
    }

    public function prodiShowCreate(Request $request)
    {
        return view('master.prodi.create');
    }
    public function prodiShowEdit(Request $request)
    {
        // -- ambil dari request id
        $form_id_prodi = $request->query('id_prodi', '');

        $dataProdi = MProdi::findOrFail($form_id_prodi);

        return view('master.prodi.edit', ['dataProdi' => $dataProdi]);
    }
    public function prodiProsesAdd(Request $request)
    {
        //ambil dari form
        $form_prodi = $request->post('prodi');

        $namaProdiExists = MProdi::where('prodi', $form_prodi)->exists();
        if ($namaProdiExists) {
            Session::flash('alert-error', 'OOPSSS! Nama prodi sudah ada!');
            return redirect()->route('master.prodi.create');
        }
        //set ke table
        $tblProdi = new MProdi();
        $tblProdi->prodi = $form_prodi;

        // Simpan data ke database
        $tblProdi->save();


        Session::flash('alert-success', 'Berhasil Menambahkan Data');
        return redirect()->route('master.prodi.listprodi');
    }
    public function prodiProsesEdit(Request $request)
    {
        $form_oldid = $request->post('oldid');
        //ambil dari form
        $form_prodi = $request->post('prodi');
        $namaProdiExists = MProdi::where('prodi', $form_prodi)->exists();
        if ($namaProdiExists) {
            Session::flash('alert-error', 'OOPSSS! Nama prodi sudah ada!');
            return redirect()->route('master.prodi.listprodi');
        }

        $tblProdi = Mprodi::findOrFail($form_oldid);
        $tblProdi->prodi = $form_prodi;


        // Simpan data ke database
        $tblProdi->save();


        Session::flash('alert-success', 'Berhasil Mengubah Data');
        return redirect()->route('master.prodi.listprodi');
    }

    public function prodiProsesDelete(Request $request)
    {
        // -- ambil dari form
        $form_oldid = $request->query('id_prodi');
        $tblProdi = MProdi::findOrFail($form_oldid);

        $tblProdi->delete();

        // kasih pesan success
        Session::flash('alert-success', 'Berhasil Hapus Data');
        return redirect()->route('master.prodi.listprodi');
    }

    public function importProdi(Request $request)
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
                // Validasi apakah data sudah ada di database berdasarkan kolom prodi (kolom 1)
                $prodi = MProdi::where('prodi', $row[0])->first();

                if (!$prodi) {
                    // Simpan data baru ke database
                    MProdi::create([
                        'prodi' => $row[0],    // Kolom 1: Kode prodi
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
                return redirect()->route('master.prodi.listprodi')->with('alert-error', $errorMessage);
            } else {
                return redirect()->route('master.prodi.listprodi')->with('alert-success', $successMessage);
            }
        }

        // Jika tidak ada file yang diunggah
        return redirect()->route('master.prodi.listprodi')->with('alert-danger', "Tidak ada file yang diunggah.");
    }
}

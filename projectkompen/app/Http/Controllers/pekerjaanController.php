<?php

namespace App\Http\Controllers;

use App\Models\MPekerjaan;
use App\Models\MUser;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class pekerjaanController extends Controller
{
    public function pekerjaanShow(Request $request)
    {
        $dataPekerjaan = MPekerjaan::all(); //narik semua dari db kirim ke view
        return view('master.pekerjaan.listpekerjaan', ['dataPekerjaan' => $dataPekerjaan]);
    }
    public function pekerjaanShowCreate(Request $request)
    {
        // Ambil data pengguna dengan peran "Pengawas" dari tabel MUser
        $pengawas = MUser::where('role', 'Pengawas')->pluck('nama_user', 'id_user');

        return view('master.pekerjaan.create', compact('pengawas'));
    }


    public function pekerjaanProsesAdd(Request $request)
    {
        // Mengecek apakah kode pekerjaan sudah ada
        $kode_pekerjaan = $request->input('kode_pekerjaan');
        $kode_pekerjaanExists = MPekerjaan::where('kode_pekerjaan', $kode_pekerjaan)->exists();
        if ($kode_pekerjaanExists) {
            Session::flash('alert-error', 'OOPSSS! Kode Pekerjaan sudah ada!');
            return redirect()->route('master.pekerjaan.create');
        }

        // Mengecek apakah nama pekerjaan sudah ada
        $nama_pekerjaan = $request->input('nama_pekerjaan');
        $nama_pekerjaanExists = MPekerjaan::where('nama_pekerjaan', $nama_pekerjaan)->exists();
        if ($nama_pekerjaanExists) {
            Session::flash('alert-error', 'OOPSSS! Nama Pekerjaan sudah ada!');
            return redirect()->route('master.pekerjaan.create');
        }

        // Memperoleh data dari request
        $form_kode_pekerjaan = $request->post('kode_pekerjaan');
        $form_nama_pekerjaan = $request->post('nama_pekerjaan');
        $form_jam_pekerjaan = $request->post('jam_pekerjaan');
        $form_batas_pekerja = $request->post('batas_pekerja');
        $nama_penanggung_jawab = $request->post('penanggung_jawab'); // Ambil nama user yang dipilih dari dropdown

        // Ambil id_user berdasarkan nama_penanggung_jawab yang dipilih
        $id_penanggung_jawab = MUser::where('nama_user', $nama_penanggung_jawab)->value('id_user');

        // Menyimpan data pekerjaan baru ke database
        $tblPekerjaan = new MPekerjaan();
        $tblPekerjaan->kode_pekerjaan = $form_kode_pekerjaan;
        $tblPekerjaan->nama_pekerjaan = $form_nama_pekerjaan;
        $tblPekerjaan->jam_pekerjaan = $form_jam_pekerjaan;
        $tblPekerjaan->batas_pekerja = $form_batas_pekerja;
        $tblPekerjaan->penanggung_jawab = $nama_penanggung_jawab; // Simpan nama_user dalam penanggung_jawab
        $tblPekerjaan->id_penanggung_jawab = $id_penanggung_jawab; // Simpan id_user dalam id_penanggung_jawab
        $tblPekerjaan->save(); // Menyimpan data ke database

        Session::flash('alert-success', 'Berhasil Menambahkan Data');

        // Redirect ke halaman selanjutnya
        return redirect()->route('master.pekerjaan.listpekerjaan');
    }

    public function pekerjaanShowEdit(Request $request)
    {
        // -- ambil dari request id
        $form_id_pekerjaan = $request->query('id_pekerjaan', '');

        $dataPekerjaan = MPekerjaan::findOrFail($form_id_pekerjaan);

        // Set batas_pekerja to 0 if it is negative
        if ($dataPekerjaan->batas_pekerja < 0) {
            $dataPekerjaan->batas_pekerja = 0;
        }

        // Ambil data pengguna dengan peran "Pengawas" dari tbl_user
        $pengawas = MUser::where('role', 'Pengawas')->pluck('nama_user', 'id_user');

        return view('master.pekerjaan.edit', ['dataPekerjaan' => $dataPekerjaan, 'pengawas' => $pengawas]);
    }


    public function pekerjaanProsesEdit(Request $request)
    {
        $form_oldid = $request->post('oldid');
        $tblPekerjaan = MPekerjaan::findOrFail($form_oldid);

        $form_nama_pekerjaan = $request->post('nama_pekerjaan');
        $form_oldkode_pekerjaan = $tblPekerjaan->kode_pekerjaan; // Simpan kode pekerjaan lama untuk memeriksa perubahan
        $form_kode_pekerjaan = $request->post('kode_pekerjaan');
        $form_jam_pekerjaan = $request->post('jam_pekerjaan');
        $form_batas_pekerja = $request->post('batas_pekerja');
        $form_penanggung_jawab = $request->post('penanggung_jawab');
        // Ambil id_user berdasarkan nama_penanggung_jawab yang dipilih
        $id_penanggung_jawab = MUser::where('nama_user', $form_penanggung_jawab)->value('id_user');

        // Cek apakah kode pekerjaan baru sudah ada di database
        if ($form_kode_pekerjaan !== $form_oldkode_pekerjaan) {
            $kode_pekerjaanExists = MPekerjaan::where('kode_pekerjaan', $form_kode_pekerjaan)->exists();
            if ($kode_pekerjaanExists) {
                Session::flash('alert-error', 'OOPSSS! Kode pekerjaan sudah ada!');
                return redirect()->route('master.pekerjaan.listpekerjaan');
            }
        }

        // Update data pekerjaan
        $tblPekerjaan->kode_pekerjaan = $form_kode_pekerjaan;
        $tblPekerjaan->nama_pekerjaan = $form_nama_pekerjaan;
        $tblPekerjaan->jam_pekerjaan = $form_jam_pekerjaan;
        $tblPekerjaan->batas_pekerja = $form_batas_pekerja;
        $tblPekerjaan->penanggung_jawab = $form_penanggung_jawab;
        $tblPekerjaan->id_penanggung_jawab = $id_penanggung_jawab; // Simpan id_user dalam id_penanggung_jawab
        $tblPekerjaan->save();

        Session::flash('alert-success', 'Berhasil Mengubah Data');
        return redirect()->route('master.pekerjaan.listpekerjaan');
    }

    public function pekerjaanProsesDelete(Request $request)
    {
        // Ambil ID pekerjaan dari permintaan
        $id_pekerjaan = $request->input('id_pekerjaan');

        // Temukan pengguna berdasarkan ID
        $tblPekerjaan = MPekerjaan::findOrFail($id_pekerjaan);

        // Hapus pengguna dari database
        $tblPekerjaan->delete();

        // Beri pesan sukses
        Session::flash('alert-success', 'Berhasil Hapus Data');

        // Redirect ke halaman daftar pengguna
        return redirect()->route('master.pekerjaan.listpekerjaan');
    }

    public function pekerjaanDeleteAll(Request $request)
    {
        // Hapus semua data pekerjaan dari database
        MPekerjaan::truncate();

        // Beri pesan sukses
        Session::flash('alert-success', 'Semua data pekerjaan berhasil dihapus');

        // Redirect ke halaman daftar pekerjaan
        return redirect()->route('master.pekerjaan.listpekerjaan');
    }

    public function importExcel(Request $request)
    {
        $user = auth()->guard('pengguna')->user();

        // Pastikan file Excel telah diunggah
        if ($request->hasFile('excel_file')) {
            $file = $request->file('excel_file');

            // Baca file Excel menggunakan PhpSpreadsheet
            $reader = IOFactory::createReader('Xlsx'); // Sesuaikan jenis file Excel yang diunggah
            $spreadsheet = $reader->load($file);

            // Mendapatkan data dari lembar kerja pertama
            $worksheet = $spreadsheet->getActiveSheet();
            $data = $worksheet->toArray();

            // Loop melalui baris data (mulai dari baris kedua, karena baris pertama biasanya adalah header)
            $importedCount = 0;
            $skippedCount = 0;
            $errorCount = 0;
            $errorMessages = [];

            foreach (array_slice($data, 1) as $row) {
                if (array_filter($row)) {
                    // Cek apakah penanggung jawab ada di database
                    $penanggungJawab = MUser::where('nama_user', $row[4])->first();

                    if ($penanggungJawab) {
                        // Validasi apakah data sudah ada di database berdasarkan kode_pekerjaan
                        $pekerjaan = MPekerjaan::where('kode_pekerjaan', $row[0])->first();

                        if (!$pekerjaan) {
                            // Simpan data baru ke database
                            MPekerjaan::create([
                                'kode_pekerjaan' => $row[0],    // Kolom 1: Kode Pekerjaan
                                'nama_pekerjaan' => $row[1],    // Kolom 2: Nama Pekerjaan
                                'jam_pekerjaan' => $row[2],     // Kolom 3: Jam Pekerjaan
                                'batas_pekerja' => $row[3],     // Kolom 4: Batas Pekerja
                                'penanggung_jawab' => $row[4],  // Kolom 5: Penanggung Jawab
                                'id_penanggung_jawab' => $penanggungJawab->id_user,
                            ]);
                            $importedCount++;
                        } else {
                            // Update data yang sudah ada di database
                            $pekerjaan->update([
                                'penanggung_jawab' => $row[4],
                                'id_penanggung_jawab' => $penanggungJawab->id_user,
                            ]);
                            $skippedCount++;
                        }
                    } else {
                        // Jika penanggung jawab tidak ditemukan, tambahkan ke pesan error
                        $errorMessages[] = "Penanggung Jawab '{$row[4]}' pada kode pekerjaan '{$row[0]}' tidak ditemukan.";
                        $errorCount++;
                    }
                }
            }

            // Setelah impor selesai, kembalikan dengan pesan sukses atau error menggunakan SweetAlert2
            $successMessage = "Data berhasil diimpor. $importedCount data baru diimpor, $skippedCount data diabaikan karena sudah ada.";
            $errorMessage = "Terdapat $errorCount error:<br>" . implode("<br>", $errorMessages);

            if ($errorCount > 0) {
                return redirect()->route('master.pekerjaan.listpekerjaan')->with('alert-error', $errorMessage);
            } else {
                return redirect()->route('master.pekerjaan.listpekerjaan')->with('alert-success', $successMessage);
            }
        }

        // Jika tidak ada file yang diunggah
        return redirect()->route('master.pekerjaan.listpekerjaan')->with('alert-danger', "Tidak ada file yang diunggah.");
    }
}

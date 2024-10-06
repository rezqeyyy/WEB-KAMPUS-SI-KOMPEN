<?php

namespace App\Http\Controllers;

use App\Models\MSetupBertugas;
use App\Models\MUser;
use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;

class setupController extends Controller
{
    public function setupShow(Request $request)
    {
        $dataBertugas = MSetupBertugas::all(); //narik semua dari db kirim ke view
        return view('master.setup.listsetup', ['dataBertugas' => $dataBertugas]);
    }

    public function setupShowCreate(Request $request)
    {
        $roles = ['Pengawas', 'PLP', 'Kepala Lab'];
        $userList = MUser::select('id_user', 'kode_user', 'nama_user', 'role')
            ->whereIn('role', $roles)
            ->get();

        return view('master.setup.create', compact('userList'));
    }


    public function setupShowEdit(Request $request)
    {
        // -- ambil dari request id
        $form_id_setup_bertugas = $request->query('id_setup_bertugas', '');

        $dataBertugas = MSetupBertugas::findOrFail($form_id_setup_bertugas);

        // Get the list of users
        $userList = MUser::select('id_user', 'kode_user', 'nama_user', 'role')->get();

        return view('master.setup.edit', ['dataBertugas' => $dataBertugas, 'userList' => $userList]);
    }

    public function setupProsesAdd(Request $request)
    {
        //ambil dari form
        $form_id_user = $request->post('id_user');
        $form_kode_user = $request->post('kode_user');
        $form_nama_user = $request->post('nama_user');
        $form_role = $request->post('role');
        $form_tgl_bertugas = $request->post('tgl_bertugas');

        $namaUserExists = MSetupBertugas::where('nama_user', $form_nama_user)->exists();
        if ($namaUserExists) {
            Session::flash('alert-error', 'OOPSSS! Namasudah ada!');
            return redirect()->route('master.setup.create');
        }
        //set ke table
        $tblSetupBertugas = new MSetupBertugas();
        $tblSetupBertugas->id_user = $form_id_user;
        $tblSetupBertugas->kode_user = $form_kode_user;
        $tblSetupBertugas->nama_user = $form_nama_user;
        $tblSetupBertugas->role = $form_role;
        $tblSetupBertugas->tgl_bertugas = $form_tgl_bertugas;

        // Simpan data ke database
        $tblSetupBertugas->save();

        Session::flash('alert-success', 'Berhasil Menambahkan Data');
        return redirect()->route('master.setup.listsetup');
    }

    public function setupProsesEdit(Request $request)
    {
        $form_oldid = $request->post('oldid');
        //ambil dari form
        $form_id_user = $request->post('id_user');
        $form_kode_user = $request->post('kode_user');
        $form_nama_user = $request->post('nama_user');
        $form_role = $request->post('role');
        $form_tgl_bertugas = $request->post('tgl_bertugas');

        $namaUserExists = MSetupBertugas::where('nama_user', $form_nama_user)->exists();
        if ($namaUserExists) {
            Session::flash('alert-error', 'OOPSSS! Nama user sudah ada!');
            return redirect()->route('master.setup.listsetup');
        }

        $tblSetupBertugas = MSetupBertugas::findOrFail($form_oldid);
        $tblSetupBertugas->id_user = $form_id_user;
        $tblSetupBertugas->kode_user = $form_kode_user;
        $tblSetupBertugas->nama_user = $form_nama_user;
        $tblSetupBertugas->role = $form_role;
        $tblSetupBertugas->tgl_bertugas = $form_tgl_bertugas;


        // Simpan data ke database
        $tblSetupBertugas->save();

        // $id_gudang = $tblGudangMaster->id_gudang;

        // MLogActivity::create([
        //     'nama_user' => auth()->guard("karyawan")->user()->nama_user, // Gantilah dengan cara Anda mengambil nama pengguna
        //     'aksi' => 'EDIT',
        //     'keterangan' => 'Mengedit data Gudang dengan ID = ' . $id_gudang

        // ]);
        Session::flash('alert-success', 'Berhasil Mengubah Data');
        return redirect()->route('master.setup.listsetup');
    }

    public function setupProsesDelete(Request $request)
    {
        // -- ambil dari form
        $form_oldid = $request->query('id_setup_bertugas');
        $tblSetupBertugas = MSetupBertugas::findOrFail($form_oldid);

        $tblSetupBertugas->delete();


        // // Simpan ID gudang yang akan dihapus ke dalam variabel sebelum menghapusnya
        // $deletedProdiID = $tblProdi->id_prodi;
        // MLogActivity::create([
        //     'nama_user' => auth()->guard("karyawan")->user()->nama_user,
        //     'aksi' => 'HAPUS',
        //     'keterangan' => 'Menghapus data Gudang dengan ID = ' . $deletedGudangID,
        // ]);

        // kasih pesan success
        Session::flash('alert-success', 'Berhasil Hapus Data');
        return redirect()->route('master.setup.listsetup');
    }
}

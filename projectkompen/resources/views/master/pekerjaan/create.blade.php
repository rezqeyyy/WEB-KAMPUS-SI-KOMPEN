@extends('super.master-layout')

@section('custom-css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('title', 'Tambah Pekerjaan')

@section('content')
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Tambah Pekerjaan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href=""><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item" aria-current="page">
                    <a href="{{ route('master.pekerjaan.listpekerjaan') }}">List Pekerjaan</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Tambah</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h4 class="mb-0">Tambah Pekerjaan</h4>
                </div>
                <hr>
                <form method="POST" action="{{ route('pekerjaan.add.proses') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Kode Pekerjaan:</label>
                            <input class="form-control form-control-sm" type="text" name="kode_pekerjaan"
                                id="kode_pekerjaan" placeholder="Masukkan Kode Pekerjaan" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Nama Pekerjaan:</label>
                            <input type="text" class="form-control form-control-sm" id="nama_pekerjaan"
                                name="nama_pekerjaan" placeholder="Masukkan Nama Pekerjaan" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Jam Pekerjaan (menit):</label>
                            <input type="number" class="form-control form-control-sm" id="jam_pekerjaan"
                                name="jam_pekerjaan" placeholder="Masukkan Jam Pekerjaan" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Limit Pekerja:</label>
                            <input type="number" class="form-control form-control-sm" id="batas_pekerja"
                                name="batas_pekerja" placeholder="Masukkan Limit Pekerja" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Penanggung Jawab:</label>
                            <select class="form-control form-control-sm select2" id="penanggung_jawab"
                                name="penanggung_jawab" required>
                                <option value="">Pilih Penanggung Jawab</option>
                                @foreach($pengawas as $id_user => $nama_user)
                                <option value="{{ $nama_user }}">{{ $nama_user }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Field untuk menyimpan id_penanggung_jawab -->
                    <input type="hidden" name="id_penanggung_jawab" id="id_penanggung_jawab">

                    <div class="row justify-content-left">
                        <div class="col-8 text-left" style="margin-bottom: 10px; margin-left: 15px;">
                            <button class="btn btn-primary" type="button" id="save-button"
                                onclick="showConfirmation()">Simpan</button>
                            <a href="{{ route('master.pekerjaan.listpekerjaan') }}" class="btn btn-secondary"
                                style="margin-left: 10px;"><i class="bx bx-x me-1"></i>Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-content')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();

        // Ketika nilai dropdown nama_user berubah
        $('#penanggung_jawab').change(function() {
            var id_user = $(this).select2('data')[0].id; // Ambil id_user yang dipilih
            $('#id_penanggung_jawab').val(id_user); // Set nilai id_user ke field id_penanggung_jawab
        });
    });

    function showConfirmation() {
        let allInputs = document.querySelectorAll('.form-control');
        let isValid = true;

        allInputs.forEach(function(input) {
            if (input.value === '') {
                isValid = false;
                return;
            }
        });

        if (isValid) {
            Swal.fire({
                title: 'Apakah anda yakin akan menambah data ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Tambah',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.querySelector('form').submit();
                }
            });
        } else {
            Swal.fire({
                title: 'Masih ada yang kosong!',
                icon: 'error',
            });
        }
    }
</script>

@if (Session::has('alert-error'))
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: 'error',
        title: '{{ Session::get('alert-error') }}'
    });
</script>
@endif
@endsection
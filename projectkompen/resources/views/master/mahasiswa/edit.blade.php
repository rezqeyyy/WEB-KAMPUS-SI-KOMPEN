@extends('super.master-layout')

@section('custom-css')
@endsection

@section('title', 'Edit Mahasiswa')

@section('content')
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Edit Mahasiswa</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="">
                        <i class="bx bx-home-alt"></i>
                    </a>
                </li>
                <li class="breadcrumb-item" aria-current="page">
                    <a href="{{ route('master.mahasiswa.listmahasiswa') }}">List Mahasiswa</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    Edit Data Mahasiswa
                </div>
                <hr>
                <form method="POST" action="{{ route('mahasiswa.edit.proses') }}">
                    @csrf
                    <!-- hidden old id -->
                    <input value="{{ $dataMahasiswa->id_mahasiswa }}" type="hidden" name="oldid" id="oldid">

                    <div class="mb-3">
                        <label for="nama_user" class="form-label">Nama Mahasiswa</label>
                        <input type="text" value="{{ $dataMahasiswa->nama_user }}" class="form-control" id="nama_user"
                            name="nama_user">
                    </div>
                    <div class="mb-3">
                        <label for="kode_user" class="form-label">NIM</label>
                        <input type="number" value="{{ $dataMahasiswa->kode_user }}" class="form-control" id="kode_user"
                            name="kode_user">
                    </div>
                    <!-- Hidden fields for kelas and prodi -->
                    <input type="hidden" value="{{ $dataMahasiswa->kelas }}" name="kelas" id="kelas">
                    <input type="hidden" value="{{ $dataMahasiswa->prodi }}" name="prodi" id="prodi">

                    <div class="mb-3">
                        <label for="semester" class="form-label">Semester</label>
                        <input type="number" value="{{ $dataMahasiswa->semester }}" class="form-control" id="semester"
                            name="semester">
                    </div>
                    <div class="mb-3">
                        <label for="jumlah_terlambat" class="form-label">Jumlah Terlambat(menit)</label>
                        <input type="number" value="{{ $dataMahasiswa->jumlah_terlambat }}" class="form-control"
                            id="jumlah_terlambat" name="jumlah_terlambat">
                    </div>
                    <div class="mb-3">
                        <label for="jumlah_alfa" class="form-label">Jumlah Alfa(jam)</label>
                        <input type="number" value="{{ $dataMahasiswa->jumlah_alfa }}" class="form-control"
                            id="jumlah_alfa" name="jumlah_alfa">
                    </div>
                    <div class="mb-3">
                        <label for="total" class="form-label">Total</label>
                        <input type="number" value="{{ $dataMahasiswa->total }}" class="form-control" id="total"
                            name="total">
                    </div>

            </div>
            <div class="row justify-content-left">
                <div class="col-8 text-start">
                    <button class="btn btn-primary" type="button" id="save-button"
                        onclick="showConfirmation()">Simpan</button>
                    <a href="{{ route('master.mahasiswa.listmahasiswa') }}" class="btn btn-secondary">
                        <i class="bx bx-x me-1"></i>Batal
                    </a>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection

@section('js-content')
<script>
    function showConfirmation() {
        let allInputs = document.querySelectorAll('.form-control');
        let isValid = true;
        let validationErrors = [];

        allInputs.forEach(function(input) {
            if (input.value === '') {
                isValid = false;
                validationErrors.push('Ada input yang kosong!');
            }
        });

        if (isValid) {
            Swal.fire({
                title: 'Apakah anda yakin akan menyimpan data ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.querySelector('form').submit();
                }
            });
        } else {
            Swal.fire({
                title: 'Validasi Gagal',
                icon: 'error',
                html: validationErrors.join('<br>'),
            });

            return false;
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
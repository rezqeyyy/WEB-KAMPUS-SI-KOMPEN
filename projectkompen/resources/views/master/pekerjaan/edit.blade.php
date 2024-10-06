@extends('super.master-layout')

@section('custom-css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('title', 'Edit Pekerjaan')

@section('content')
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Edit Pekerjaan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="">
                        <i class="bx bx-home-alt"></i>
                    </a>
                </li>
                <li class="breadcrumb-item" aria-current="page">
                    <a href="{{ route('master.pekerjaan.listpekerjaan') }}">List pekerjaan</a>
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
                    Edit Data Pekerjaan
                </div>
                <hr>
                <form method="POST" action="{{ route('pekerjaan.edit.proses') }}">
                    @csrf
                    <!-- hidden old id -->
                    <input value="{{ $dataPekerjaan->id_pekerjaan }}" type="hidden" name="oldid" id="oldid">

                    <div class="mb-3">
                        <label for="nama_pekerjaan" class="form-label">Nama Pekerjaan</label>
                        <input type="text" value="{{ $dataPekerjaan->nama_pekerjaan }}" class="form-control"
                            id="nama_pekerjaan" name="nama_pekerjaan">
                    </div>
                    <div class="mb-3">
                        <label for="kode_pekerjaan" class="form-label">Kode Pekerjaan</label>
                        <input type="text" value="{{ $dataPekerjaan->kode_pekerjaan }}" class="form-control"
                            id="kode_pekerjaan" name="kode_pekerjaan">
                    </div>
                    <div class="mb-3">
                        <label for="jam_pekerjaan" class="form-label">Jam Pekerjaan (menit)</label>
                        <input type="number" value="{{ $dataPekerjaan->jam_pekerjaan }}" class="form-control"
                            id="jam_pekerjaan" name="jam_pekerjaan">
                    </div>
                    <div class="mb-3">
                        <label for="batas_pekerja" class="form-label">Limit Pekerja</label>
                        <input type="number" value="{{ $dataPekerjaan->batas_pekerja }}" class="form-control"
                            id="batas_pekerja" name="batas_pekerja">
                    </div>
                    <div class="mb-3">
                        <label for="penanggung_jawab" class="form-label">Penanggung Jawab</label>
                        <select class="form-control select2" id="penanggung_jawab" name="penanggung_jawab">
                            <option value="">Pilih Penanggung Jawab</option>
                            @foreach($pengawas as $id_user => $nama_user)
                            <option value="{{ $nama_user }}" {{ $dataPekerjaan->penanggung_jawab == $nama_user ?
                                'selected'
                                : '' }}>
                                {{ $nama_user }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row justify-content-left">
                        <div class="col-8 text-start">
                            <button class="btn btn-primary" type="button" id="save-button"
                                onclick="showConfirmation()">Simpan</button>
                            <a href="{{ route('master.pekerjaan.listpekerjaan') }}" class="btn btn-secondary">
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });

    function showConfirmation() {
        // Script jika user tidak melengkapi/memilih pilihan
        let allInputs = document.querySelectorAll('.form-control');
        let isValid = true;
        let validationErrors = []; // Store validation error messages

        allInputs.forEach(function(input) {
            if (input.value === '') {
                isValid = false;
                validationErrors.push(input.id + ' tidak boleh kosong!'); // Include input id in the error message
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
                    // If the user confirms, you can submit the form or trigger the desired action
                    document.querySelector('form').submit(); // This submits the form
                }
            });
        } else {
            Swal.fire({
                title: 'Validasi Gagal',
                icon: 'error',
                html: validationErrors.join('<br>'), // Combine error messages
            });

            return false; // Prevent form submission
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
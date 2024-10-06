@extends('super.master-layout')

@section('custom-css')
@endsection

@section('title', 'Edit Kelas')

@section('content')
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Edit Kelas</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="">
                        <i class="bx bx-home-alt"></i>
                    </a>
                </li>
                <li class="breadcrumb-item" aria-current="page">
                    <a href="{{ route('master.kelas.listkelas') }}">List Kelas</a>
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
                    Edit Data Kelas
                </div>
                <hr>
                <form method="POST" action="{{ route('kelas.edit.proses') }}">
                    @csrf
                    <!-- hidden old id -->
                    <input value="{{ $dataKelas->id_kelas }}" type="hidden" name="oldid" id="oldid">

                    <div class="mb-3">
                        <label for="kelas" class="form-label">Nama Kelas</label>
                        <input type="text" value="{{ $dataKelas->kelas }}" class="form-control" id="kelas" name="kelas">
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
        // Script jika user tidak melengkapi/memilih pilihan
        let allInputs = document.querySelectorAll('.form-control');
        let isValid = true;
        let validationErrors = []; // Store validation error messages

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
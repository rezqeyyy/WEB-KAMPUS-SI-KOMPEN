@extends('super.master-layout')

@section('custom-css')
@endsection

@section('title', 'Tambah Prodi')

@section('content')
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Tambah Prodi</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('master.dashboard') }}"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('master.prodi.listprodi') }}">List Prodi</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah</li>
            </ol>
        </nav>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="mb-0">Tambah Prodi</h4>
                    </div>
                    <hr>
                    <form method="POST" action="{{ route('prodi.add.proses') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="prodi" class="form-label">Prodi:</label>
                            <input class="form-control form-control-sm" type="text" name="prodi" id="prodi"
                                placeholder="Masukkan Nama Prodi" required>
                        </div>
                        <div class="row justify-content-left">
                            <div class="col-12">
                                <button class="btn btn-primary" type="button"
                                    onclick="showConfirmation()">Simpan</button>
                                <a href="{{ route('master.prodi.listprodi') }}" class="btn btn-secondary ms-2"><i
                                        class="bx bx-x me-1"></i>Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    function showConfirmation() {
        let prodi = document.getElementById('prodi').value.trim();

        if (prodi === '') {
            Swal.fire({
                title: 'Masih ada yang kosong!',
                icon: 'error',
            });
        } else {
            Swal.fire({
                title: 'Apakah anda yakin akan menambah data ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Tambah',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form
                    document.querySelector('form').submit();
                }
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
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    Toast.fire({
        icon: 'error',
        title: '{{ Session::get('alert-error') }}'
    });
</script>
@endif

@if (Session::has('alert-success'))
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    Toast.fire({
        icon: 'success',
        title: '{{ Session::get('alert-success') }}'
    });
</script>
@endif
@endsection
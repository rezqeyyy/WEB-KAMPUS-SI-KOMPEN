@extends('super.master-layout')
@section('custom-css')
@endsection

@section('title')
@section('content')


<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Tambah Kelas</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href=""><i class="bx bx-home-alt"></i></a>
                </li>

                <li class="breadcrumb-item" aria-current="page">
                    <a href="">List Kelas</a>
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
                    <h4 class="mb-0">Tambah Mahasiswa</h4>
                </div>
                <hr>
                <form method="POST" action="{{ route('kelas.add.proses') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-3">`
                        <div class="col-12">
                            <label class="form-label">Kelas:</label>
                            <input class="form-control form-control-sm select-element" type="text" name="kelas"
                                id="kelas" placeholder="Masukkan Nama Kelas " required>
                        </div>
                    </div>

            </div>
            <div class="row justify-content-left">
                <div class="col-8 text-left" style="margin-bottom: 10px; margin-left: 15px;">
                    <button class="btn btn-primary" type="button" id="save-button"
                        onclick="showConfirmation()">Simpan</button>
                    <a href="{{ route('master.kelas.listkelas') }}" class="btn btn-secondary"
                        style="margin-left: 10px;"><i class="bx bx-x me-1"></i>Batal</a>
                </div>
            </div>
        </div>
    </div>

    </form>
</div>
</div>

@endsection

@section('js-content')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


<script>
    function showConfirmation() {
            //Script jika user tidak melengkapi/memilih pilihan
            let allSelects = document.querySelectorAll('.select-element');
            let isValid = true;

            allSelects.forEach(function(select) {
                if (select.value === '') {
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
                        // If the user confirms, you can submit the form or trigger the desired action
                        document.querySelector('form').submit(); // This submits the form
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
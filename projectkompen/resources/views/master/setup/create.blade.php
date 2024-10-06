@extends('super.master-layout')

@section('custom-css')
@endsection

@section('title', 'Tambah Setup Bertugas')

@section('content')
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Tambah Setup Bertugas</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href=""><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item" aria-current="page">
                    <a href="{{ route('master.setup.listsetup') }}">List Setup Bertugas</a>
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
                    <h4 class="mb-0">Tambah Setup</h4>
                </div>
                <hr>
                <form method="POST" action="{{ route('setup.add.proses') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Pilih User:</label>
                            <select class="form-control form-control-sm select-element" name="id_user" id="id_user"
                                required>
                                <option value="">-- Pilih User --</option>
                                @foreach($userList as $user)
                                <option value="{{ $user->id_user }}" data-kode="{{ $user->kode_user }}"
                                    data-nama="{{ $user->nama_user }}" data-role="{{ $user->role }}">
                                    {{ $user->nama_user }} - {{ $user->role }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Kode User:</label>
                            <input class="form-control form-control-sm" type="text" name="kode_user" id="kode_user"
                                readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Nama User:</label>
                            <input class="form-control form-control-sm" type="text" name="nama_user" id="nama_user"
                                readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Role:</label>
                            <input class="form-control form-control-sm" type="text" name="role" id="role" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Tanggal Bertugas:</label>
                            <input class="form-control form-control-sm select-element" type="date" name="tgl_bertugas"
                                id="tgl_bertugas" required>
                        </div>
                    </div>
                    <div class="row justify-content-left">
                        <div class="col-8 text-left" style="margin-bottom: 10px; margin-left: 15px;">
                            <button class="btn btn-primary" type="button" id="save-button"
                                onclick="showConfirmation()">Simpan</button>
                            <a href="{{ route('master.setup.listsetup') }}" class="btn btn-secondary"
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
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    $(document).ready(function() {
        $('#id_user').select2({
            placeholder: '-- Pilih User --'
        });

        $('#id_user').on('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var kodeUser = selectedOption.getAttribute('data-kode');
            var namaUser = selectedOption.getAttribute('data-nama');
            var role = selectedOption.getAttribute('data-role');

            document.getElementById('kode_user').value = kodeUser;
            document.getElementById('nama_user').value = namaUser;
            document.getElementById('role').value = role;
        });
    });

    function showConfirmation() {
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

@if (Session::has('alert-success'))
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
        icon: 'success',
        title: '{{ Session::get('alert-success') }}'
    });
</script>
@endif
@endsection
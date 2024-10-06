@extends('super.master-layout')

@section('custom-css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('title', 'Edit Setup Bertugas')

@section('content')
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Edit Setup Bertugas</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="">
                        <i class="bx bx-home-alt"></i>
                    </a>
                </li>
                <li class="breadcrumb-item" aria-current="page">
                    <a href="{{ route('master.setup.listsetup') }}">List Setup Bertugas </a>
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
                    Edit Setup Bertugas
                </div>
                <hr>
                <form method="POST" action="{{ route('setup.edit.proses') }}">
                    @csrf
                    <!-- hidden old id -->
                    <input value="{{ $dataBertugas->id_setup_bertugas }}" type="hidden" name="oldid" id="oldid">

                    <div class="mb-3">
                        <label for="id_user" class="form-label">Pilih User</label>
                        <select class="form-control select2" id="id_user" name="id_user" required>
                            <option value="">-- Pilih User --</option>
                            @foreach($userList as $user)
                            <option value="{{ $user->id_user }}" data-kode="{{ $user->kode_user }}"
                                data-nama="{{ $user->nama_user }}" data-role="{{ $user->role }}" {{ $user->id_user ==
                                $dataBertugas->id_user ? 'selected' : '' }}>
                                {{ $user->nama_user }} - {{ $user->role }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="kode_user" class="form-label">Kode User</label>
                        <input type="text" value="{{ $dataBertugas->kode_user }}" class="form-control" id="kode_user"
                            name="kode_user" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="nama_user" class="form-label">Nama User</label>
                        <input type="text" value="{{ $dataBertugas->nama_user }}" class="form-control" id="nama_user"
                            name="nama_user" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <input type="text" value="{{ $dataBertugas->role }}" class="form-control" id="role" name="role"
                            readonly>
                    </div>

                    <div class="mb-3">
                        <label for="tgl_bertugas" class="form-label">Tanggal Bertugas</label>
                        <input type="date" value="{{ $dataBertugas->tgl_bertugas }}" class="form-control"
                            id="tgl_bertugas" name="tgl_bertugas" required>
                    </div>

                    <div class="mb-3">
                        <label for="tgl_bertugas_lama" class="form-label">Tanggal Bertugas Lama</label>
                        <input type="text" value="{{ $dataBertugas->tgl_bertugas }}" class="form-control"
                            id="tgl_bertugas_lama" name="tgl_bertugas_lama" readonly>
                    </div>

                    <div class="row justify-content-left">
                        <div class="col-8 text-start">
                            <button class="btn btn-primary" type="button" id="save-button"
                                onclick="showConfirmation()">Simpan</button>
                            <a href="{{ route('master.setup.listsetup') }}" class="btn btn-secondary">
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2();

        $('#id_user').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var kodeUser = selectedOption.data('kode');
            var namaUser = selectedOption.data('nama');
            var role = selectedOption.data('role');

            $('#kode_user').val(kodeUser);
            $('#nama_user').val(namaUser);
            $('#role').val(role);
        });

        // Trigger change event to populate the fields if the user is preselected
        $('#id_user').trigger('change');
    });

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
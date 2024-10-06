@extends('super.master-layout')
@section('custom-css')
@endsection

@section('title', 'Edit User')
@section('content')
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Edit User</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('master.user.listuser') }}"><i
                            class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">
                    <a href="{{ route('master.user.listuser') }}">List User</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container mt-4">
    <div class="row mt-3">
        <div class="col-12">
            @if (Session::has('message'))
            <div class="alert {{ Session::get('class') }} alert-dismissible fade show alert-custom" role="alert">
                {{ Session::get('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="mb-0">Edit User</h4>
                    </div>
                    <hr />
                    <form method="POST" action="{{ route('user.edit.proses') }}" enctype="multipart/form-data">
                        @csrf

                        <input value="{{ $dataUser->id_user }}" type="hidden" name="oldid" id="oldid">

                        <div class="mb-3">
                            <label for="" class="form-label">NIP/NIM</label>
                            <input type="text" value="{{ $dataUser->kode_user }}" class="form-control" id="kode_user"
                                name="kode_user">
                        </div>

                        <!-- hidden old id -->
                        <input type="hidden" value="{{ $dataUser->nama_user }}" class="form-control" id="oldnama_user"
                            name="oldnama_user">

                        <div class="mb-3">
                            <label for="" class="form-label">Nama User</label>
                            <input type="text" value="{{ $dataUser->nama_user }}" class="form-control" id="nama_user"
                                name="nama_user">
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Email</label>
                            <input type="email" value="{{ $dataUser->email }}" class="form-control" id="email"
                                name="email">
                        </div>
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect02">Role</label>
                            <select class="form-select" id="role" name="role">
                                <option value="Kajur" {{ $dataUser->role == 'Kajur' ? 'selected' : '' }}>Kajur</option>
                                <option value="KPS" {{ $dataUser->role == 'KPS' ? 'selected' : '' }}>KPS</option>
                                <option value="Dosen Pembimbing Akademik" {{ $dataUser->role == 'Dosen Pembimbing
                                    Akademik' ? 'selected' : '' }}>Dosen Pembimbing Akademik</option>
                                <option value="Admin Prodi" {{ $dataUser->role == 'Admin Prodi' ? 'selected' : ''
                                    }}>Admin Prodi</option>
                                <option value="PLP" {{ $dataUser->role == 'PLP' ? 'selected' : '' }}>PLP</option>
                                <option value="Kepala Lab" {{ $dataUser->role == 'Kepala Lab' ? 'selected' : ''
                                    }}>Kepala Lab</option>
                                <option value="Pengawas" {{ $dataUser->role == 'Pengawas' ? 'selected' : '' }}>Pengawas
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Upload TTD:</label>
                            <input class="form-control form-control-sm" type="file" name="ttd" id="ttd" accept="image/*"
                                value="{{ $dataUser->ttd }}">
                        </div>
                        <!-- Tampilkan gambar TTD saat ini -->
                        <div class="mb-3">
                            <label for="current_ttd" class="form-label">TTD Saat Ini:</label>
                            <div>
                                <img src="{{ asset('storage/signature/' . $dataUser->ttd) }}" width="150" height="100"
                                    alt="TTD Saat Ini" />
                            </div>
                        </div>

                        <button class="btn btn-primary" type="button" id="save-button"
                            onclick="showConfirmation()">Simpan</button>
                        <!-- Tombol Kembali -->
                        <a href="{{ route('master.user.listuser') }}" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-content')
<script>
    function showConfirmation() {
        // Script jika user tidak melengkapi/memilih pilihan
        let allSelects = document.querySelectorAll('.select-element');
        let isValid = true;
        let validationErrors = []; // Store validation error messages

        allSelects.forEach(function(select) {
            if (select.value === '') {
                isValid = false;
                validationErrors.push('Ada input yang kosong!');
            }
        });

        // Validate the email input
        const emailInput = document.getElementById('email');
        if (emailInput.value === '' || !emailInput.checkValidity()) {
            isValid = false;
            if (emailInput.value === '') {
                validationErrors.push('Email masih kosong!');
            } else {
                validationErrors.push('Format email tidak valid. Gunakan format email@example.com');
            }
        }

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
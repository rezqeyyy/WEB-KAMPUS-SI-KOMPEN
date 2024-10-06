@extends('super.master-layout')

@section('custom-css')
@endsection

@section('title', 'User Setting')

@section('content')
<div class="container mt-4">
    <div class="row mt-3">
        <div class="col-12">

            @if (Session::has('alert-success'))
            <div class="alert alert-success alert-dismissible fade show alert-custom" role="alert">
                {{ Session::get('alert-success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @elseif (Session::has('alert-error'))
            <div class="alert alert-danger alert-dismissible fade show alert-custom" role="alert">
                {{ Session::get('alert-error') }}
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
                    <form method="POST" action="{{ route('user.profile.edit.proses') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="kode_user" class="form-label">Kode User</label>
                            <input type="text" value="{{ $dataUserMaster->kode_user }}"
                                class="form-control select-element" id="kode_user" name="kode_user" required>
                        </div>

                        <div class="mb-3">
                            <label for="nama_user" class="form-label">Nama User</label>
                            <input type="text" value="{{ $dataUserMaster->nama_user }}"
                                class="form-control select-element" id="nama_user" name="nama_user" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" value="{{ $dataUserMaster->email }}" class="form-control select-element"
                                id="email" name="email" pattern=".*@.*" required>
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="new_password" name="new_password"
                                    placeholder="Hanya isi jika ingin mengganti password">
                                <button class="btn btn-outline-secondary" type="button"
                                    id="showNewPasswordBtn">Show</button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirm_password"
                                    name="confirm_password" placeholder="Hanya isi jika ingin mengganti password">
                                <button class="btn btn-outline-secondary" type="button"
                                    id="showConfirmPasswordBtn">Show</button>
                            </div>
                        </div>

                        @if (in_array($dataUserMaster->role, ['Pengawas', 'PLP', 'Kepala Lab']))
                        <div class="mb-3">
                            <label for="form_ttd" class="form-label">Upload Tanda Tangan (TTD)</label>
                            <input type="file" class="form-control" id="form_ttd" name="form_ttd">
                        </div>

                        <div class="mb-3">
                            <label for="current_ttd" class="form-label">Tanda Tangan Saat Ini</label>
                            @if (!empty($dataUserMaster->ttd))
                            <div>
                                <img src="{{ asset('storage/signature/' . $dataUserMaster->ttd) }}"
                                    alt="Tanda Tangan Saat Ini" style="max-width: 200px;">
                            </div>
                            @else
                            <span>Tidak ada TTD yang diunggah.</span>
                            @endif
                        </div>
                        @endif

                        <button type="button" id="save-button" class="btn btn-primary"
                            onclick="showConfirmation()">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-content')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Password Baru
    document.getElementById('showNewPasswordBtn').addEventListener('click', function() {
        var newPasswordInput = document.getElementById('new_password');
        if (newPasswordInput.type === 'password') {
            newPasswordInput.type = 'text';
            this.textContent = 'Hide';
        } else {
            newPasswordInput.type = 'password';
            this.textContent = 'Show';
        }
    });

    // Konfirmasi Password
    document.getElementById('showConfirmPasswordBtn').addEventListener('click', function() {
        var confirmPasswordInput = document.getElementById('confirm_password');
        if (confirmPasswordInput.type === 'password') {
            confirmPasswordInput.type = 'text';
            this.textContent = 'Hide';
        } else {
            confirmPasswordInput.type = 'password';
            this.textContent = 'Show';
        }
    });

    function showConfirmation() {
        let allSelects = document.querySelectorAll('.select-element');
        let isValid = true;
        let validationErrors = [];

        allSelects.forEach(function(select) {
            if (select.value === '') {
                isValid = false;
                validationErrors.push('Ada input yang kosong!');
            }
        });

        const emailInput = document.getElementById('email');
        if (emailInput.value === '' || !emailInput.checkValidity()) {
            isValid = false;
            if (emailInput.value === '') {
                validationErrors.push('Email masih kosong!');
            } else {
                validationErrors.push('Format email tidak valid. Gunakan format email@example.com');
            }
        }

        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        if (newPassword !== confirmPassword) {
            isValid = false;
            validationErrors.push('Kata sandi tidak cocok. Harap konfirmasi kata sandi dengan benar.');
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
@endsection
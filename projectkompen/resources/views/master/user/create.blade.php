@extends('super.master-layout')
@section('custom-css')
@endsection

@section('title', 'Tambah User Baru')
@section('content')
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Tambah User Baru</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href=""><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">
                    <a href="{{ route('master.user.listuser') }}">List User</a>
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
                    <h4 class="mb-0">Tambah Master User</h4>
                </div>
                <hr>
                <form method="POST" action="{{ route('user.add.proses') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Nama User:</label>
                            <input class="form-control form-control-sm select-element" type="text" name="nama_user"
                                id="nama_user" placeholder="Masukkan Nama Lengkap" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">NIP:</label>
                            <input class="form-control form-control-sm select-element" type="text" name="kode_user"
                                id="kode_user" placeholder="Masukkan nip" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Email:</label>
                            <input class="form-control form-control-sm select-element" type="email" name="email"
                                id="email" pattern=".*@.*" placeholder="Masukkan Email Lengkap" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Password:</label>
                            <div class="input-group">
                                <input class="form-control form-control-sm select-element" type="password"
                                    name="password" id="password"
                                    placeholder="Masukkan Password | Contoh: Password123, Password231" required>
                                <button class="btn btn-outline-secondary" type="button"
                                    id="showPasswordBtn">Show</button>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <label class="input-group-text" for="role">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="Kajur">Kajur</option>
                            <option value="KPS">KPS</option>
                            <option value="Dosen Pembimbing Akademik">Dosen Pembimbing Akademik</option>
                            <option value="Admin Prodi">Admin Prodi</option>
                            <option value="PLP">PLP</option>
                            <option value="Kepala Lab">Kepala Lab</option>
                            <option value="Pengawas">Pengawas</option>
                        </select>
                    </div>


                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Upload TTD:</label>
                            <input class="form-control form-control-sm" type="file" name="ttd" id="ttd"
                                accept="image/*">
                        </div>
                    </div>

            </div>
            <div class="row justify-content-left">
                <div class="col-8 text-left" style="margin-bottom: 10px; margin-left: 15px;">
                    <button class="btn btn-primary" type="button" id="save-button"
                        onclick="showConfirmation()">Simpan</button>
                    <a href="{{ route('master.user.listuser') }}" class="btn btn-secondary"
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


<script>
    document.getElementById('showPasswordBtn').addEventListener('click', function() {
            var passwordInput = document.getElementById('password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.textContent = 'Hide';
            } else {
                passwordInput.type = 'password';
                this.textContent = 'Show';
            }
        });
</script>

{{-- <script>
    $(document).ready(function() {
            // Tambahkan event handler untuk tombol "Simpan"
            $('#save-button').on('click', function(event) {
                event.preventDefault(); // Ini akan menghentikan pengiriman formulir
                var username = $('#username').val();

                if (username.length >= 3) {
                    $.ajax({
                        type: 'POST', // Ganti ke POST jika diperlukan
                        url: '{{ route('userMaster.add.proses') }}', // Ganti dengan rute proses penyimpanan data
                        data: {_token: '{{ csrf_token() }}', // Tambahkan token CSRF jika Anda memerlukannya
                            username: username
                        },
                        success: function(data) {
                            if (data.error) {
                                Swal.fire({
                                    title: 'Username Sudah Ada!',
                                    icon: 'error',
                                });
                            } else {
                                // Berhasil disimpan, lakukan tindakan sesuai kebutuhan Anda
                                alert('Data pengguna berhasil disimpan.');
                            }
                        }
                    });
                }
            });
        });
</script> --}}

<script>
    $('.single-select').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });
        $("input[type='number']").on("input", function() {
            var nonNumReg = /[^0-9]/g
            $(this).val($(this).val().replace(nonNumReg, ''));
        });
</script>

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
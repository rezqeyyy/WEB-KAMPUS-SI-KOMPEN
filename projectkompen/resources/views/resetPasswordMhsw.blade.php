<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Reset Password Mahasiswa</title>
    <!--favicon-->
    <link rel="icon" href="{{ asset('assets/images/favicon-32x32.png')}}" type="image/png" />
    <!-- loader-->
    <link href="{{ asset('assets/css/pace.min.css')}}" rel="stylesheet" />
    <script src="{{ asset('assets/js/pace.min.js')}}"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css')}}" />
    <!-- Icons CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/icons.css')}}" />
    <!-- App CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.css')}}" />
    <!-- Custom CSS -->
    <style>
        .card {
            max-width: 900px;
            margin: auto;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 2rem;
        }

        .btn-outline-secondary {
            border-color: #6c757d;
            color: #6c757d;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: #fff;
        }

        .toggle-password {
            cursor: pointer;
        }

        .form-control::placeholder {
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }
    </style>
</head>

<body>
    <!-- wrapper -->
    <div class="wrapper">
        <div class="authentication-reset-password d-flex align-items-center justify-content-center">
            <div class="row w-100">
                <div class="col-12 col-md-8 col-lg-6 mx-auto">
                    <div class="card">
                        <div class="row no-gutters">
                            <div class="col-lg-6">
                                <div class="card-body">
                                    <div class="text-left">
                                        <img src="{{ asset('assets/images/Logo_Politeknik_Negeri_Jakarta-removebg-preview.png')}}"
                                            width="150" alt="">
                                    </div>
                                    <h4 class="mt-4 font-weight-bold">Buat Password Baru</h4>
                                    <p class="text-muted">Kami menerima permintaan setel ulang kata sandi Anda. Silakan
                                        masukkan kata sandi baru Anda</p>
                                    <!-- Form untuk reset password -->
                                    <form id="resetPasswordForm" method="POST"
                                        action="{{ route('ResetPasswordMhsw.proses') }}">
                                        @csrf
                                        <input type="hidden" name="kode_user" class="form-control"
                                            value="{{$kode_user}}" required />
                                        <div class="form-group mt-4">
                                            <label>Password Baru</label>
                                            <div class="input-group">
                                                <input type="password" name="new_password" class="form-control"
                                                    placeholder="Masukkan password baru" required />
                                                <div class="input-group-append">
                                                    <button type="button"
                                                        class="btn btn-outline-secondary toggle-password">
                                                        <i class="bx bx-hide"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Konfirmasi Password Baru</label>
                                            <div class="input-group">
                                                <input type="password" name="confirm_password" class="form-control"
                                                    placeholder="Konfirmasi password baru" required />
                                                <div class="input-group-append">
                                                    <button type="button"
                                                        class="btn btn-outline-secondary toggle-password">
                                                        <i class="bx bx-hide"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block"
                                            id="btnGantiPassword">Ganti Password</button>
                                        <a href="{{ route('login') }}" class="btn btn-link btn-block"><i
                                                class='bx bx-arrow-back mr-1'></i>Kembali</a>
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-6 d-none d-lg-block">
                                <img src="{{ asset('assets/images/login-images/loginpage.jpg')}}"
                                    class="card-img login-img h-100" alt="...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end wrapper -->

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        document.getElementById('btnGantiPassword').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent form submission
            Swal.fire({
                title: 'Anda yakin?',
                text: "Anda akan mengganti password Anda!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Ganti Password!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Lanjutkan dengan proses penggantian password
                    document.getElementById('resetPasswordForm').submit();
                }
            });
        });

        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function () {
                const input = this.parentElement.previousElementSibling;
                const icon = this.querySelector('i');
                if (input.type === "password") {
                    input.type = "text";
                    icon.classList.remove('bx-hide');
                    icon.classList.add('bx-show');
                } else {
                    input.type = "password";
                    icon.classList.remove('bx-show');
                    icon.classList.add('bx-hide');
                }
            });
        });
    </script>

</body>

</html>
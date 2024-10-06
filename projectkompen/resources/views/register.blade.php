<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>SiKompen</title>
    <!--favicon-->
    <link rel="icon" href="assets/images/favicon-32x32.png" type="image/png" />
    <!-- loader-->
    <link href="assets/css/pace.min.css" rel="stylesheet" />
    <script src="assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <!-- Icons CSS -->
    <link rel="stylesheet" href="assets/css/icons.css" />
    <!-- App CSS -->
    <link rel="stylesheet" href="assets/css/app.css" />
    <!-- SweetAlert2 CSS -->
    <link href="assets/css/sweetalert2.min.css" rel="stylesheet" />
</head>

<body class="bg-register">
    <!-- wrapper -->
    <div class="wrapper">
        <div class="section-authentication-register d-flex align-items-center justify-content-center">
            <div class="row">
                <div class="col-12 col-lg-10 mx-auto">
                    <div class="card radius-15">
                        <div class="row no-gutters">
                            <div class="col-lg-6">
                                <div class="card-body p-md-5">
                                    <div class="text-center">
                                        <img src="assets/images/Logo_Politeknik_Negeri_Jakarta-removebg-preview.png"
                                            width="80" alt="" />
                                        <h3 class="mt-4 font-weight-bold">Registrasi Akun</h3>
                                    </div>
                                    <form id="registrationForm" action="{{ route('register.proses') }}" method="POST">
                                        @csrf
                                        <!-- Menambahkan token CSRF Laravel -->
                                        <div class="form-group mt-4">
                                            <label for="nim">NIM</label>
                                            <input type="number" class="form-control" id="nim" name="kode_user"
                                                placeholder="Masukkan NIM" required />
                                            <div id="nimError" class="text-danger"></div>
                                        </div>
                                        <div class="form-group mt-4">
                                            <label for="nama">Nama Mahasiswa</label>
                                            <input type="text" class="form-control" id="nama_user" name="nama_user"
                                                placeholder="Masukkan Nama Lengkap" required />
                                            <div id="namaError" class="text-danger"></div>
                                        </div>
                                        <div class="form-group mt-4">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                placeholder="Masukkan Alamat Email" required />
                                            <div id="emailError" class="text-danger"></div>
                                        </div>
                                        <div class="form-group mt-4">
                                            <label for="kelas">Kelas</label>
                                            <select class="form-select" id="kelas" name="kelas" required>
                                                <option value="">Pilih Kelas</option>
                                                @foreach ($kodeKelasList as $id_kelas => $nama_kelas)
                                                <option value="{{ $id_kelas }}">{{ $nama_kelas }}</option>
                                                @endforeach
                                            </select>
                                            <div id="kelasError" class="text-danger"></div>
                                        </div>
                                        <div class="form-group mt-4">
                                            <label for="prodi">Prodi</label>
                                            <select class="form-select" id="prodi" name="prodi" required>
                                                <option value="">Pilih Prodi</option>
                                                @foreach ($kodeProdiList as $id_prodi => $nama_prodi)
                                                <option value="{{ $id_prodi }}">{{ $nama_prodi }}</option>
                                                @endforeach
                                            </select>
                                            <div id="prodiError" class="text-danger"></div>
                                        </div>
                                        <div class="form-group mt-4">
                                            <label for="notelp">No Telp</label>
                                            <input type="tel" class="form-control" id="notelp" name="notelp"
                                                placeholder="Masukkan Nomor Telepon" required />
                                            <div id="noTelpError" class="text-danger"></div>
                                        </div>
                                        <div class="form-group mt-4">
                                            <label for="role">Role</label>
                                            <br>
                                            <select class="form-select" id="role" name="role" required>
                                                <option value="Mahasiswa">Mahasiswa</option>
                                            </select>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <div class="input-group" id="show_hide_password">
                                                <input class="form-control border-right-0" type="password" id="password"
                                                    name="password" placeholder="Masukkan Password" required />
                                                <div class="input-group-append">
                                                    <a href="javascript:;"
                                                        class="input-group-text bg-transparent border-left-0">
                                                        <i class="bx bx-hide"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div id="passwordError" class="text-danger"></div>
                                        </div>
                                        <div class="btn-group mt-3 w-100">
                                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                                        </div>
                                    </form>
                                    <hr />
                                    <div class="text-center mt-4">
                                        <p class="mb-0">
                                            Sudah ada akun? <a href="{{ route('loginmhsw') }}">Login</a>
                                        </p>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <img src="assets/images/login-images/Designer.png" class="card-img login-img h-100"
                                    alt="..." />
                            </div>
                        </div>
                        <!--end row-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end wrapper -->
    <!-- JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- SweetAlert2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- Password show & hide js -->
    <script>
        $(document).ready(function () {
            $("#show_hide_password a").on("click", function (event) {
                event.preventDefault();
                if ($("#show_hide_password input").attr("type") == "text") {
                    $("#show_hide_password input").attr("type", "password");
                    $("#show_hide_password i").addClass("bx-hide");
                    $("#show_hide_password i").removeClass("bx-show");
                } else if ($("#show_hide_password input").attr("type") == "password") {
                    $("#show_hide_password input").attr("type", "text");
                    $("#show_hide_password i").removeClass("bx-hide");
                    $("#show_hide_password i").addClass("bx-show");
                }
            });
        });
    </script>
    @if(Session::has('alert-success'))
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
    @if(Session::has('alert-infostatus'))
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
            icon: 'info',
            title: '{{ Session::get('alert-infostatus') }}'
        });
    </script>
    @endif
    @if(Session::has('alert-error'))
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
</body>

</html>
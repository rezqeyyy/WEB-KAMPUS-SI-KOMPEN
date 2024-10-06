<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Sikompen Teknik Informatika PNJ</title>
    <!--favicon-->
    <link rel="icon" href="{{ asset('assets/images/favicon-32x32.png')}}" type="image/png" />
    <!-- loader-->
    <link href="{{ asset('assets/css/pace.min.css')}}" rel="stylesheet" />
    <script src="{{ asset('assets/js/pace.min.js')}}"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css')}}" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&family=Roboto&display=swap" />
    <!-- Icons CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/icons.css')}}" />
    <!-- App CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.css')}}" />
</head>

<body class="bg-login">
    <!-- wrapper -->
    <div class="wrapper">
        <div class="section-authentication-login d-flex align-items-center justify-content-center">
            <div class="row">
                <div class="col-12 col-lg-8 mx-auto">
                    <div class="card radius-15">
                        <div class="row no-gutters">
                            <div class="col-lg-6">
                                <img src="{{ asset('assets/images/login-images/loginpage.jpg')}}"
                                    class="card-img login-img h-100" alt="..." />
                            </div>
                            <div class="col-lg-6">
                                <div class="card-body p-md-5">
                                    <div class="text-center">
                                        <img src="{{ asset('assets/images/Logo_Politeknik_Negeri_Jakarta-removebg-preview.png')}}"
                                            width="150" alt="" />
                                        <h3 class="mt-4 font-weight-bold">Login</h3>
                                    </div>
                                    <div class="form-body">
                                        <form class="row g-5" action="{{ route('loginmhsw') }}" method="POST">
                                            @csrf
                                            <div class="col-12">
                                                <label for="kode_user" class="form-label">NIM</label>
                                                <input name="kode_user" type="text" class="form-control" id="kode_user"
                                                    placeholder="Masukkan NIM/NIP" />
                                            </div>
                                            <div class="col-12">
                                                <label for="inputChoosePassword" class="form-label">Password</label>
                                                <div class="input-group" id="show_hide_password">
                                                    <input name="password" type="password"
                                                        class="form-control border-end-0" id="password" value=""
                                                        placeholder="Masukkan Password" />
                                                    <a href="javascript:;" class="input-group-text bg-transparent"><i
                                                            class="bx bx-hide"></i></a>
                                                </div>
                                            </div>
                                            <div class="col-15">
                                                <div class="d-grid">
                                                    <button type="submit"
                                                        class="btn btn-success btn-md lis-rounded-circle-50 px-4 mx-1 my-1 btn-admin-gudang"
                                                        style="background-color: #2c3e50;">
                                                        <i class="fa fa-shopping-cart pl-2"> <i
                                                                class="bx bxs-lock-open"></i>Login
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-15 text-center">
                                                <a href="{{ route('forgetPasswordMhsw') }}"
                                                    class="text-decoration-underline">Lupa Password?</a>
                                                <br>
                                                <div class="col-15 text-center">
                                                    {{-- <a href="/"><i class="bx bxs-chevron-left"></i> Kembali</a>
                                                    --}}
                                                </div>
                                                <hr>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end row-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end wrapper -->
</body>
<!--plugins-->
<script src="{{ asset('assets/js/jquery.min.js')}}"></script>
<!--Password Show & Hide JS -->
<script>
    $(document).ready(function() {
        $("#show_hide_password a").on('click', function(event) {
            event.preventDefault();
            if ($('#show_hide_password input').attr("type") == "text") {
                $('#show_hide_password input').attr('type', 'password');
                $('#show_hide_password i').addClass("bx-hide");
                $('#show_hide_password i').removeClass("bx-show");
            } else if ($('#show_hide_password input').attr("type") == "password") {
                $('#show_hide_password input').attr('type', 'text');
                $('#show_hide_password i').removeClass("bx-hide");
                $('#show_hide_password i').addClass("bx-show");
            }
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
@if(Session::has('alert-successedit'))
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

</html>
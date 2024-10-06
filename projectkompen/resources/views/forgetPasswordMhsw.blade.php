<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Lupa Password Mahasiswa</title>
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
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS (Popper.js tidak diperlukan untuk modals) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <style>
        body {
            background-image: url('assets/images/wp.jpg');
            /* Sesuaikan dengan path yang benar */
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
        }


        .loading-spinner {
            display: none;
            margin-left: 10px;
        }

        .modal-dialog {
            margin: auto;
            top: 40%;
            max-width: 400px;
            border: none;
        }

        .modal-content {
            border: none;
            border-radius: 15px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="authentication-forgot d-flex align-items-center justify-content-center">
            <div class="card shadow-lg forgot-box">
                <div class="card-body p-md-5">
                    <div class="text-center">
                        <img src="{{ asset('assets/images/icons/forgot.png')}}" width="140" alt="" />
                    </div>
                    <h4 class="mt-5 font-weight-bold text-center">Forgot Password?</h4>
                    <p class="text-muted">Enter your registered NIM to reset the password</p>
                    <form method="POST" action="{{ route('ValidasiTokenMhsw') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mt-4 mb-3">
                            <label>NIM</label>
                            <div class="mb-3" style="display: inline-flex; align-items: center;">
                                <input type="text" class="form-control form-control-lg radius-30 mx-1"
                                    style="width: 250px" placeholder="nim" name="kode_user" id="kode_user" required />
                                <a href="#" id="sendCodeBtn" class="btn btn-secondary btn-sm btn-block radius-15"
                                    style="width: 80px; font-size:10pt">send code</a>
                                <div class="loading-spinner spinner-border text-primary" role="status"
                                    id="loadingSpinner">
                                    <span class="sr-only"></span>
                                </div>
                            </div>
                            <input type="text" class="form-control form-control-lg radius-30 mx-1" placeholder="Code"
                                name="token" id="token" required />
                        </div>
                        <div id="countdown" class="text-center mb-3" style="display: none;">
                            <span>Please wait <span id="countdown-timer">60</span> seconds to resend code.</span>
                        </div>
                        <div class="text-center mb-3">
                            <button class="btn btn-primary btn-lg btn-block radius-30" type="submit">Next</button>
                        </div>
                    </form>
                    <div class="text-center">
                        <a href="{{ route('loginmhsw') }}" class="btn btn-link btn-block"><i
                                class='bx bx-arrow-back mr-1'></i>Back To Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#sendCodeBtn').on('click', function(e) {
                e.preventDefault();
                var kode_user = $('#kode_user').val();

                if (!kode_user) {
                    alert('Please enter your Nim');
                    return;
                }

                // Tampilkan loading spinner dan nonaktifkan tombol
                $('#sendCodeBtn').attr('disabled', true);
                $('#loadingSpinner').show();

                $.ajax({
                    url: '{{ route('SendTokenMhsw') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        kode_user: kode_user
                    },
                    success: function(response) {
                        alert('Token has been sent to your email');
                        startCountdown();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('An error occurred while sending the token');
                    },
                    complete: function() {
                        // Sembunyikan loading spinner setelah request selesai
                        $('#loadingSpinner').hide();
                        $('#sendCodeBtn').attr('disabled', false); // Aktifkan kembali tombol setelah selesai
                    }
                });
            });

            function startCountdown() {
                var countdown = 60;
                var countdownTimer = $('#countdown-timer');
                $('#countdown').show();
                var interval = setInterval(function() {
                    countdown--;
                    countdownTimer.text(countdown);
                    if (countdown <= 0) {
                        clearInterval(interval);
                        $('#sendCodeBtn').removeAttr('disabled');
                        $('#countdown').hide();
                    }
                }, 1000);
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
</body>

</html>
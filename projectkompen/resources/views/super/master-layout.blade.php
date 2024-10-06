<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>SiKompen</title>
    <!--favicon-->
    <link rel="icon" href="{{ asset('assets/images/favicon-32x32.png')}}" type="image/png" />

    <!-- Vector CSS -->
    <link href="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet" />

    <!-- DataTables DateTime CSS -->
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css" rel="stylesheet">


    <!--plugins-->
    <link rel="stylesheet" href="{{ asset('assets/plugins/notifications/css/lobibox.min.css')}}" />
    <link href="{{ asset('assets/plugins/simplebar/css/simplebar.css')}}" rel=" stylesheet" />
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css')}}" rel=" stylesheet" />
    <link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css')}}" rel=" stylesheet" />
    <link href="{{ asset ('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" />
    <link href="{{ asset ('assets/plugins/metismenu/css/metisMenu.min.css')}}" rel="stylesheet" />

    <!-- loader-->
    <link href="{{ asset('assets/css/pace.min.css')}}" rel="stylesheet" />
    <script src="{{ asset('assets/js/pace.min.js')}}"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css')}}" />
    <link href="{{ asset('assets/css/animate.css')}}" rel="stylesheet" type="text/css" />

    <!-- Icons CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/icons.css')}}" />

    <!-- App CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/dark-sidebar.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/dark-theme.css')}}" />

    <!--Data Tables -->
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css')}}" rel="stylesheet" />

    <!-- Custom CSS -->

    <!-- jspdf -->
    <link href="{{ asset('assets/plugins/jspdf/jspdf.debug.js')}}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/jspdf/jspdf.plugin.autotable.min.js')}}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/jspdf/jspdf.umd.min.js" rel="stylesheet')}}" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.0/jspdf.plugin.autotable.js"></script>


    <!-- Include Axios script -->
    <link href="{{ asset('assets/plugins/axios/axios.min.js')}}" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet"> -->
    <link href="{{ asset('assets/css/css2.css')}}" rel="stylesheet" />
    @yield('custom-css')
</head>

<body>
    <!-- wrapper -->
    <div class="wrapper">
        @include('super.sidebar')
        @include('super.navbar')

        <!--page-wrapper-->
        <div class="page-wrapper">
            <!--page-content-wrapper-->
            <div class="page-content-wrapper">
                <div class="page-content">
                    @yield('content')
                </div>
            </div>
            <!--end page-content-wrapper-->
        </div>
        <!--end page-wrapper-->

        <!--start overlay-->
        <div class="overlay toggle-btn-mobile"></div>
        <!--end overlay-->

        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->

        <!--footer -->
        <div class="footer d-none d-md-block d-lg-block d-xl-block d-xxl-block">
            <p class="mb-0">&copy; Jurusan Teknik Informatika dan Komputer Politeknik Negeri Jakarta 2024
            </p>
        </div>
        <!-- end footer -->
    </div>
    <!-- end wrapper -->


    <!--plugins-->

    <script src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>

    <!-- Include Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>

    <!-- Include DataTables DateTime Plugin -->
    <script src="https://cdn.datatables.net/datetime/1.5.1/js/dataTables.dateTime.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js')}}"></script>

    <script src="{{ asset('assets/js/index.js')}}"></script>

    <!-- Include SweetAlert CSS -->
    <link href="{{ asset('assets/css/sweetalert2.min.css')}}" rel="stylesheet" />

    <!-- sweet alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- App JS -->
    <script src="{{ asset('assets/js/app.js')}}"></script>

    <!--Data Tables js-->
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>

    <!-- Include Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>

    <!--Select2 js-->
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js')}}"></script>

    <!--notification js -->
    <script src="{{ asset('assets/plugins/notifications/js/lobibox.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/notifications/js/notifications.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/notifications/js/notification-custom-script.js')}}"></script>

    <!--CUSTOM JS-->
    @yield('js-content')
</body>

</html>
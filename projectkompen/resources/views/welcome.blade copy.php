<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Sikompen Teknik Informatika PNJ</title>
    <!--favicon-->
    <link rel="icon" href="/assets/images/favicon-32x32.png" type="image/png" />
    <!-- loader-->
    <link href="/assets/css/pace.min.css" rel="stylesheet" />
    <script src="/assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&family=Roboto&display=swap"
        rel="stylesheet">
    <!-- Icons CSS -->
    <link rel="stylesheet" href="/assets/css/icons.css" />
    <!-- App CSS -->
    <link rel="stylesheet" href="/assets/css/app.css" />

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        body {
            color: white;
        }

        .authentication-lock-screen .card-body {
            padding: 2rem;
        }

        .authentication-lock-screen img {
            margin-top: -50px;
        }

        .authentication-lock-screen h1 {
            margin-top: -10px;
        }

        .live-time {
            font-family: 'Open Sans', sans-serif;
            font-size: 20px;
            margin-top: 15px;
        }

        .live-date {
            font-family: 'Roboto', sans-serif;
            font-size: 20px;
            margin-top: 5px;
        }

        .btn-admin-umum {
            background-color: #2980b9;
            color: white;
        }

        .btn-admin-gudang {
            background-color: #2ecc71;
            color: white;
        }
    </style>
</head>

<body class="bg-lock-screen">
    <!-- wrapper -->
    <div class="wrapper">
        <div class="authentication-lock-screen d-flex align-items-center justify-content-center">
            <div class="card shadow-none bg-transparent">
                <div class="card-body p-md-10 text-center">
                    <h1 class="mt-5 text">Sikompen Teknik Informatika PNJ</h1>
                    <div class="">
                        <img src="/assets/images/Logo_Politeknik_Negeri_Jakarta-removebg-preview.png " class="mt-5"
                            width="250 " alt="" />
                    </div>
                    <h3 class="mt-4 text">Welcome</h3>
                    <a href="{{ url('/login') }}"
                        class="btn btn-primary btn-md lis-rounded-circle-50 px-4 mx-1 my-1 btn-admin-umum"
                        data-abc="true"><i class="fa fa-shopping-cart pl-2"></i>Login</a>
                    <p class="live-time" id="live-time"></p>
                    <p class="live-date" id="live-date"></p>
                </div>
            </div>
        </div>
    </div>
    <!-- end wrapper -->
    <script>
        function updateClock() {
            const now = new Date();
            const options = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                timeZoneName: 'short'
            };
            const formattedTime = now.toLocaleTimeString('en-US', options);
            document.getElementById('live-time').textContent = 'Time: ' + formattedTime;
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const formattedDate = 'Date: ' + days[now.getDay()] + ', ' + now.toLocaleDateString();
            document.getElementById('live-date').textContent = formattedDate;
        }

        updateClock();
        setInterval(updateClock, 1000);
    </script>
</body>

</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="FlockSense is an advanced smart farm management platform designed specifically for modern poultry producers, empowering farmers to remotely monitor, analyze, and optimize every aspect of their poultry operations. Leveraging IoT sensors, real-time data analytics, and AI-powered insights, FlockSense enables seamless control over environmental factors, feed efficiency, bio-security, and flock health, resulting in higher productivity, improved animal welfare, and greater sustainability. With intuitive dashboards, mobile access, and robust security, FlockSense delivers a scalable, data-driven solution trusted by leading poultry farms worldwide.">
    <meta name="keywords" content="Smart farm management, poultry farm automation, IoT poultry farming, poultry monitoring system, real-time farm analytics, AI poultry management, poultry health monitoring, feed efficiency solutions, biosecurity poultry, smart poultry sensors, modern poultry farming, digital poultry solutions, flock management platform, poultry farm control system, smart poultry environment, poultry data analytics, automated poultry operations, remote poultry monitoring, sustainable poultry farming, advanced poultry technology, smart chicken farm, poultry performance optimization, cloud-based poultry management, AI farm insights, smart livestock solutions">
    <meta name="author" content="FlockSense">
    <meta name="robots" content="index, follow">
    <meta name="google-site-verification" content="7p6DahxeCXUehwhHcxN7p2yHr57EG8Ufugoery9m4lc" />

    <title>FlockSense - @yield('title')</title>

    <link href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon" rel="shortcut icon">

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/feather.css') }}" rel="stylesheet">

    <!-- Form Date PIckers CSS -->
    <link href="{{ asset('assets/plugins/flatpickr/flatpickr.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/jquery-timepicker/jquery-timepicker.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/plugins/tabler-icons/tabler-icons.min.css') }}" rel="stylesheet" >
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    @stack('css')
</head>
<body>
    <div class="main-wrapper">
        @include('partial.admin.topbar')
        @include('partial.admin.sidebar')
        <div class="page-wrapper">
            @yield('content')
            @include('partial.admin.footer')
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>

    <!-- Feather Icon JS -->
    <script src="{{ asset('assets/js/feather.min.js') }}" type="text/javascript"></script>

    <!-- Slimscroll JS -->
    <script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}" type="text/javascript"></script>

    <!-- Datatable JS -->
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap5.min.js') }}" type="text/javascript"></script>

    <!-- Bootstrap Core JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>

    <!-- Select2 JS -->
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/custom-select2.js') }}" type="text/javascript"></script>

    <!-- Form Date Pickers JS -->
    <script src="{{ asset('assets/js/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/flatpickr/flatpickr.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/jquery-timepicker/jquery-timepicker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/pickr/pickr.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/%40simonwep/pickr/pickr.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('assets/js/jquery.PrintArea.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/script.js') }}" type="text/javascript"></script>

    <script type="module">
        import { InputSpinner } from "{{ asset('assets/plugins/bootstrap-spinner/InputSpinner.js') }}";

        const config = {
            autoDelay: 500,
            autoInterval: 50,
            buttonsOnly: true,
            keyboardStepping: true,
            locale: navigator.language,
            template:`<div class="input-group input-group-sm">
                        <button style="min-width: 2rem" class="btn btn-decrement btn-sm btn-warning btn-minus" type="button"><i class='bi bi-dash-lg'></i></button>
                        <input type="text" inputmode="decimal" style="text-align: center; min-width: 50px; width: 50px;" class="form-control form-control-sm bs-spinner" placeholder="" readonly="">
                        <button style="min-width: 2rem" class="btn btn-increment btn-sm btn-warning btn-plus" type="button"><i class='bi bi-plus-lg'></i></button>
                      </div>`
        };

        const inputSpinnerElements = document.querySelectorAll(".bs-spinner");
        for (const inputSpinnerElement of inputSpinnerElements) {
            new InputSpinner(inputSpinnerElement, config);
        }
    </script>

    @stack('js')
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="FlockSense is an advanced smart farm management platform designed specifically for modern poultry producers, empowering farmers to remotely monitor, analyze, and optimize every aspect of their poultry operations. Leveraging IoT sensors, real-time data analytics, and AI-powered insights, FlockSense enables seamless control over environmental factors, feed efficiency, bio-security, and flock health, resulting in higher productivity, improved animal welfare, and greater sustainability. With intuitive dashboards, mobile access, and robust security, FlockSense delivers a scalable, data-driven solution trusted by leading poultry farms worldwide.">
    <meta name="keywords" content="Smart farm management, poultry farm automation, IoT poultry farming, poultry monitoring system, real-time farm analytics, AI poultry management, poultry health monitoring, feed efficiency solutions, biosecurity poultry, smart poultry sensors, modern poultry farming, digital poultry solutions, flock management platform, poultry farm control system, smart poultry environment, poultry data analytics, automated poultry operations, remote poultry monitoring, sustainable poultry farming, advanced poultry technology, smart chicken farm, poultry performance optimization, cloud-based poultry management, AI farm insights, smart livestock solutions">
    <meta name="author" content="FlockSense">
    <meta name="robots" content="index, follow">
    <title>FlockSense - @yield('title')</title>

    <link href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon" rel="shortcut icon">

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/feather.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/tabler-icons/tabler-icons.min.css') }}" rel="stylesheet" >
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

    <!-- Daterangepikcer JS -->
    <script src="{{ asset('assets/js/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}" type="text/javascript"></script>

    <script src="{{ asset('assets/js/jquery.PrintArea.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/script.js') }}" type="text/javascript"></script>

    @stack('js')
</body>
</html>

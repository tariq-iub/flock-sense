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
    <link href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/tabler-icons/tabler-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body>

@yield('content')

<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/feather.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
</body>
</html>

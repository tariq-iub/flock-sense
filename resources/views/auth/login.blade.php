<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Flock Sense</title>

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/login.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}" rel="stylesheet">
</head>

<body class="wrapper">
    <div class="login-area">
        <div class="brand-info">
            <img src="{{ asset('assets/img/logo.png') }}" alt="logo" data-first-enter-image="true">
            <div class="d-flex flex-column justify-content-between align-items-center">
                <h1>Welcome Back</h1>
                <p>Sign in to your admin account</p>
            </div>
        </div>
        <div class="login-form">
            <form action="/login" method="post">
                @csrf
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-component border-end-0"><i class="fa fa-envelope"></i></span>
                        <input type="email" class="form-control border-start-0" id="email"
                               name="email" aria-describedby="emailHelp" placeholder="Enter your email">
                    </div>


                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <a href="#" class="text-danger text-decoration-none float-end">Forgot Password?</a>
                    <div class="input-group">
                        <span class="input-group-text bg-component border-end-0"><i class="fa fa-lock"></i></span>
                        <input type="password" class="form-control border-start-0 border-end-0" id="password"
                               name="password" placeholder="Enter your password">
                        <span class="input-group-text bg-component border-start-0 icon-link-hover">
                            <i class="fa fa-eye-slash toggle-password text-gray-9"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3 w-100">Sign in</button>
            </form>
        </div>
    </div>

    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
<script>
    $(document).ready(function() {
        // toggle-password
        if ($('.toggle-password').length > 0) {
            $(document).on('click', '.toggle-password', function () {
                $(this).toggleClass("fa-eye fa-eye-slash");
                var input = $("#password");
                if (input.attr("type") == "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });
        }
    });
</script>
</body>
</html>

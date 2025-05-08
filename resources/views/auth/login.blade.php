<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/css/all.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.2.0/css/all.css">

    <!-- Owl Carousel -->
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/owl.theme.default.min.css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <!-- Custom -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/common.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>

<body>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <section class="onboard-page">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-xxl-4 col-xl-5 col-lg-6">
                        <div class="card info-card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-7">
                                        <div class="welcome-text">
                                            <h5>Welcome Back !</h5>
                                            <p>Login in to continue to .</p>
                                        </div>
                                    </div>
                                    <div class="col-5 text-end">
                                        <img class="img-fluid card-header-image" src="assets/images/login-image.svg">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="doctor-avatar">
                                    <img class="img-thumbnail" src="assets/images/profile.png">

                                </div>
                                <div class="form-group">
                                    <label>Email address</label>
                                    <input type="email" class="form-control     @error('email') is-invalid @enderror"
                                        placeholder="Enter email" name="email" value="{{ old('email') }}" required
                                        autocomplete="email" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Password" name="password" autocomplete="current-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                {{-- <div class="form-group">
                                    <input type="checkbox" class="form-check-input mr-1">
                                    <label class="mb-0">Remember me</label>
                                </div> --}}
                                <div class="remove-under">
                                    <a href=""> <button type="submit"
                                            class="btn btn-primary w-100">Login</button></a>
                                </div>
                                {{-- <a href="{{ route('password.request') }}" class="password-text">
                                    <i class="fas fa-lock"></i>
                                    <span>Forgot Your Password</span>
                                </a> --}}
                            </div>
                        </div>
                        <!-- <div class="account-text">
                        <p>Don't have an account ?</p>
                        <a href="sign-up.html">Sign Up here</a>
                    </div> -->
                    </div>
                </div>
            </div>
        </section>

        <!-- Jquery -->
        <script src="assets/js/jquery-3.6.4.min.js"></script>

        <!-- Bootstrap -->
        <script src="assets/js/bootstrap.bundle.min.js"></script>

        <!-- Owl Carousel -->
        <script src="assets/js/owl.carousel.min.js"></script>

        <!-- Uploader -->
        <script src="assets/js/jquery.uploader.min.js"></script>

        <!-- Custom -->
        <script src="assets/js/index.js"></script>
</body>

</html>

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
    <link rel="stylesheet" href="{{ asset('assets/css/all.css') }}">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.2.0/css/all.css">

    <!-- Owl Carousel -->
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.theme.default.min.css') }}">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

    <!-- Custom -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
</head>

<body>
    <section class="onboard-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-xxl-4 col-xl-5 col-lg-6">
                    <div class="card info-card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-7">
                                    <div class="welcome-text">
                                        <h5>Reset Password</h5>
                                        <p>Login in to continue to .</p>
                                    </div>
                                </div>
                                <div class="col-5 text-end">
                                    <img class="img-fluid card-header-image"
                                        src="{{ asset('assets/images/login-image.svg') }}">
                                </div>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="card-body pt-0">
                                <div class="doctor-avatar">
                                    <img class="img-thumbnail" src="{{ asset('assets/images/profile.png') }}">

                                </div>
                                <div class="form-group">
                                    <label>Email address</label>
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="new-password" maxlength="16">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password-confirm">Confirm Password</label>
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" required autocomplete="new-password" maxlength="16">
                                </div>
                                <div class="remove-under">
                                    <button type="submit" class="btn btn-primary w-100">
                                        {{ __('Reset Password') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Jquery -->
    <script src="{{ asset('assets/js/jquery-3.6.4.min.js') }}"></script>

    <!-- Bootstrap -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Owl Carousel -->
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>

    <!-- Uploader -->
    <script src="{{ asset('assets/js/jquery.uploader.min.js') }}"></script>

    <!-- Custom -->
    <script src="{{ asset('assets/js/index.js') }}"></script>
</body>

</html>

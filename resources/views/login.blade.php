<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Halaman Masuk | SSI Arena</title>

    <!-- Fonts & Styles -->
    <link href="{{ asset('assets/admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,700,900" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }

        .card-login-container {
            position: relative;
            overflow: visible !important;
        }

        .card-body {
            position: relative;
        }

        .title-row {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .home-icon {
            width: 48px;
            height: 48px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            color: #4e73df;
            margin-right: 12px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .home-icon:hover {
            background-color: #f1f1f1;
        }

        @media (max-width: 576px) {
            .home-icon {
                width: 40px;
                height: 40px;
                font-size: 14px;
                margin-right: 8px;
            }

            .title-row h1 {
                font-size: 1.2rem;
            }
        }
    </style>
</head>

<body class="bg-gradient-primary">

    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="row w-100 justify-content-center">
            <div class="col-xl-6 col-lg-8 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5 card-login-container">
                    <div class="card-body p-5">

                        <!-- Title + Floating Home Icon in One Row -->
                        <div class="title-row">
                            <a href="{{ url('/') }}" class="home-icon" title="Kembali ke Halaman Awal">
                                <i class="fas fa-home"></i>
                            </a>
                            <h1 class="h4 text-gray-900 mb-0">Halaman Masuk SSI Arena</h1>
                        </div>

                        <!-- Error Message -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Login Form -->
                        <form class="user" method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group">
                                <input type="email" name="email" class="form-control form-control-user"
                                    placeholder="Masukkan email" required autofocus>
                            </div>

                            <div class="form-group password-wrapper">
                                <input type="password" name="password" id="password"
                                    class="form-control form-control-user"
                                    placeholder="Masukkan sandi" required>
                                <span toggle="#password" class="fas fa-eye toggle-password"></span>
                            </div>

                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                Masuk
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Assets -->
    <script src="{{ asset('assets/admin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/sb-admin-2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).on('click', '.toggle-password', function () {
            let input = $("#password");
            let icon = $(this);
            if (input.attr("type") === "password") {
                input.attr("type", "text");
                icon.removeClass("fa-eye").addClass("fa-eye-slash");
            } else {
                input.attr("type", "password");
                icon.removeClass("fa-eye-slash").addClass("fa-eye");
            }
        });

        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 2500,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
        @endif
    </script>

</body>

</html>

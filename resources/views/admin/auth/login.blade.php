<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Sign In</title>
    {{-- font CDN --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    {{-- icon CDN --}}
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/fontawesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    {{-- css styles --}}
    <link href="{{ asset('assets/css/light.css') }}" rel="stylesheet">
    <style>

    </style>
</head>
</style>
</head>
@if(session('alert'))
<script>
    alert("{{ session('alert') }}");
</script>
@endif

<body class="">
    <div class="container-fluid login-container vh-100 overflow-hidden">
        <div class="row w-100 h-100">
            <!-- Left Image Panel -->
            <div class="col-md-6 left-panel d-none d-md-block py-3 px-5 bg-white">
                <div class="overlay">
                    <img src="{{ asset('assets/images/logo/Orginal.svg') }}" alt="Drivers Deck Logo">
                </div>
            </div>

            <!-- Right Form Panel -->
            <div class="col-md-6 col-sm-12 d-flex align-items-center justify-content-center bg-white mt-n5">
                <div class="w-50 w-sm-100">
                    <div class="login-title text-center">
                        <img src="{{ asset('assets/images/logo/Black.svg') }}" width="180px" alt="Drivers Deck Logo">
                    </div>
                    <form class="" action="{{ route('admin.auth.check') }}" method="POST">
                        @csrf
                        <h2 class="text-center mb-3">Sign In</h2>
                        <div class="mb-3">
                            <label class="form-label fw-bold fs-4">Number</label>
                            <input type="text" class="form-control form-control-lg border-2" maxlength="10"
                                minlength="10" name="contact" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" class="form-control form-control-lg border-2" name="password">
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input border-2 fs-4" id="rememberMe"
                                    name="remember">
                                <label class="form-check-label" for="rememberMe">Remember me</label>
                            </div>
                            <a href="{{ route('admin.auth.forgotpass') }}" class="text-decoration-none">Forgot
                                Password?</a>

                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100 fs-4">Sign In</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>

</html>

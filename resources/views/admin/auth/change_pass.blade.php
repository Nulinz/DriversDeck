<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Change Passoword</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/fontawesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


    <link href="{{ asset('assets/css/light.css') }}" rel="stylesheet">
    <style>

    </style>
</head>
</style>
</head>

<body class="">
    <div class="container-fluid login-container vh-100 overflow-hidden">
        <div class="row h-100">
            <!-- Left Image Panel -->
            <div class="col-md-6 left-panel d-none d-md-block py-3 px-5 bg-white">
                <div class="overlay">
                    <img src="{{ asset('assets/images/logo/Orginal.svg') }}" alt="Drivers Deck Logo">
                    </div>
            </div>

            <!-- Right Form Panel -->
            <div class="col-md-6 col-sm-12 d-flex align-items-center justify-content-center bg-white">
                <div class="w-50 w-sm-100">
                    <div class="login-title text-center">
                                <img src="{{ asset('assets/images/logo/Black.svg') }}" width="180px" alt="Drivers Deck Logo">
                            </div>
                    <form method="#" class="" action="{{ route('admin.auth.login') }}">
                        <h2 class="text-center my-3 fs-3">Create New Passoword</h2>
                        <p class="mt-2 fs-5 fw-bold text-center">Your new password must be different from previously
                            used password.</p>

                        <div class="mb-3">
                           <label class="form-label fw-bold">New Password</label>
                           <div class="input-group">
                            <input id="password" type="password" class="form-pad form-control form-control-lg border-2" style="border-right: 0"
                                name="n_password" required>
                            <button type="button"
                                class="input-group-text bg-transparent border-2 btn-toggle-password-visibility" style="border-left: 0">
                                <i class="fa fa-eye"></i>
                            </button>
                            </div>
                        </div>

                        <div class="mb-3">
                           <label class="form-label fw-bold">Confirm Password</label>
                           <div class="input-group">
                            <input id="password" type="password" class="form-pad form-control form-control-lg border-2" style="border-right: 0"
                                name="n_password" required>
                            <button type="button"
                                class="input-group-text bg-transparent border-2 btn-toggle-password-visibility" style="border-left: 0">
                                <i class="fa fa-eye"></i>
                            </button>
                           </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 fs-4">Reset Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script>
        $(document).on("click", ".btn-toggle-password-visibility", function() {
            const $btn = $(this);
            const $input = $btn.closest(".input-group").find("input");

            const isPassword = $input.attr("type") === "password";
            $input.attr("type", isPassword ? "text" : "password");

            const $icon = $btn.find("i");
            $icon.toggleClass("fa-eye fa-eye-slash");
        });
    </script>
</body>

</html>

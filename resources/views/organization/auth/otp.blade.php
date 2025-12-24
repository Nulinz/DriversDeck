<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>OTP</title>

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
            <div class="col-md-6 left-panel d-none d-md-block py-3 px-5  bg-white">
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
                    {{-- <form method="#" class="" action="{{ route('organization.auth.change_pass') }}">
                        <h2 class="text-start mb-1">Enter OTP</h2>
                        <p class="text-muted mb-0 fw-bold">Check your phone!</p>
                        <p class="text-muted fw-bold">Your activation code is on its way to via SMS.</p>
                        <div class="mb-3">
                            <div class="row gy-3 gx-4">
                                <div class="col-3">
                                    <input type="text" class="form-control form-control-lg border-2 text-center" name=""
                                        id="" maxlength="1" minlength="1" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>
                                <div class="col-3">
                                    <input type="text" class="form-control form-control-lg border-2 text-center" name=""
                                        id=""  maxlength="1" minlength="1" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>
                                <div class="col-3">
                                    <input type="text" class="form-control form-control-lg border-2 text-center" name=""
                                        id=""  maxlength="1" minlength="1" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>
                                <div class="col-3">
                                    <input type="text" class="form-control form-control-lg border-2 text-center" name=""
                                        id=""  maxlength="1" minlength="1" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>
                            </div>
                        </div>
                        <p class="fs-5 fw-light text-dark mt-4 text-center mb-0">Did'nt receive the code ?</p>

                        <span class="d-block text-center fs-5 mb-3"><a class="text-dark" href="">Resend
                                OTP</a></span>

                        <button type="submit" class="btn btn-primary btn-lg w-100 fs-4">Verify</button>
                    </form> --}}

                    <form method="POST" action="{{ route('auth.verify_otp') }}">
    @csrf
    <h2 class="text-start mb-1">Enter OTP</h2>
    <p class="text-muted fw-bold">Check your phone!</p>

    <div class="mb-3">
        <div class="row gy-3 gx-4">
            <div class="col-3"><input type="text" maxlength="1" class="form-control text-center otp-input"></div>
            <div class="col-3"><input type="text" maxlength="1" class="form-control text-center otp-input"></div>
            <div class="col-3"><input type="text" maxlength="1" class="form-control text-center otp-input"></div>
            <div class="col-3"><input type="text" maxlength="1" class="form-control text-center otp-input"></div>
        </div>
    </div>

    <input type="hidden" name="otp" id="otp-hidden">

    @if($errors->has('otp'))
        <div class="text-danger">{{ $errors->first('otp') }}</div>
    @endif

    <button type="submit" class="btn btn-primary btn-lg w-100 fs-4 mt-3">Verify</button>
</form>

                </div>
            </div>
        </div>
    </div>

  <script src="{{ asset('assets/js/app.js') }}"></script>

</body>

</html>


<script>
    const inputs = document.querySelectorAll(".otp-input");
    const hiddenOtp = document.getElementById("otp-hidden");

    inputs.forEach((input, index) => {
        input.addEventListener("input", () => {
            if (input.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
            let otp = '';
            inputs.forEach(inp => otp += inp.value);
            hiddenOtp.value = otp;
        });
    });
</script>

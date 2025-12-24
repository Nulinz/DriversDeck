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
        body {
            min-height: 100vh;
            background-image: url('../assets/images/bg.jpeg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            height: 100vh;
            /* Use 100vh instead of 100% */
            width: 100%;
        }
    </style>
</head>
</style>
</head>

<body class="reg-bg">
    <div class="container-fluid min-vh-100 d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <!-- Left Side -->
                <div class="col-md-6 mb-4 mb-md-0">

                    <h3 class="fw-bold fs-4 mb-1">You are in Registration Process</h3>
                    <p class="text-muted fs-5">Step 2</p>

                    <ul class="list-unstyled step-list">
                        <li class="d-flex align-items-start">
                            <i class="fa fa-fw fa-check-circle fs-4 me-2 step-icon"></i>
                            <div>
                                <h5 class="fs-4 fw-bold mb-0">Your Details</h5>
                                <p class="fw-bold text-muted">Provide Your Details and Create Password</p>
                            </div>
                        </li>
                        <li class="d-flex align-items-start">
                            <i class="fa fa-fw fa-circle fs-4 me-2 step-icon"></i>
                            <div>
                                <h5 class="fs-4 fw-bold mb-0">Contact Person</h5>
                                <p class="fw-bold text-muted">Provide Contact Person Details</p>
                            </div>
                        </li>
                        <li class="d-flex align-items-start">
                            <i class="fa fa-fw fa-circle fs-4 me-2 step-icon"></i>
                            <div>
                                <h5 class="fs-4 fw-bold mb-0">Address</h5>
                                <p class="fw-bold text-muted">Provide Your Corporate Address</p>
                            </div>
                        </li>
                        <li class="d-flex align-items-start">
                            <i class="fa fa-fw fa-circle fs-4 me-2 step-icon"></i>
                            <div>
                                <h5 class="fs-4 fw-bold mb-0">Business Details</h5>
                                <p class="fw-bold text-muted">Provide Your PAN and GST Details</p>
                            </div>
                        </li>
                        <li class="d-flex align-items-start">
                            <i class="fa fa-fw fa-circle fs-4 me-2 step-icon"></i>
                            <div>
                                <h5 class="fs-4 fw-bold mb-0">Asset Strength</h5>
                                <p class="fw-bold text-muted">Provide Your Details and Create Password</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Right Side -->
                <div class="col-md-6">
                    <div class="form-section">
                        <h3 class="text-center mb-4">Contact Person</h3>
                         @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li> @endforeach
                                        </ul>
                                    </div>
                                @endif
                        <form action="{{ route('auth.register_contact_store') }}"
        method="POST" id="form_input">
    @csrf

    <div class="mb-3">
        <label class="form-label fw-bold">Full Name</label>
        <input type="text" class="form-control form-control-lg border-2" name="full_name"
            value="{{ old('full_name') }}">
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold">Contact Number</label>
        <input type="text" class="form-control form-control-lg border-2" maxlength="10" minlength="10"
            value="{{ old('full_contact') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
            name="full_contact">
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold">Alternate Number</label>
        <input type="text" class="form-control form-control-lg border-2" maxlength="10" minlength="10"
            value="{{ old('alt_contact') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
            name="alt_contact">
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold">Mail Id</label>
        <input type="email" class="form-control form-control-lg border-2" name="f_mail" value="{{ old('f_mail') }}"
            placeholder="Enter your mail id">
    </div>

    <button type="submit" class="btn btn-primary btn-lg fw-bold fs-4 w-100 py-2 form_btn">Continue</button>

    </form>
    <p class="text-center fs-5 mt-3">
        Already have an account? <a href="{{ route('auth.login.org') }}" class="fs-4 text-dark"> Sign in</a>
    </p>
    </div>
    </div>
    </div>
    </div>
    </div>


    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/form.js') }}"></script>
    </body>

</html>

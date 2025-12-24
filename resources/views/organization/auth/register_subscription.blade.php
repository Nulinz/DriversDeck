<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Choose Subscription Plan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="{{ asset('assets/css/light.css') }}" rel="stylesheet">
    
    <style>
        body {
            min-height: 100vh;
            background-image: url('../assets/images/bg.jpeg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            height: 100vh;
            width: 100%;
        }
        .payment-btn {
            transition: all 0.3s ease;
        }
        .payment-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .selected-plan {
            border: 2px solid #007bff !important;
            box-shadow: 0 0 20px rgba(0,123,255,0.3);
        }
    </style>
</head>

<body class="reg-bg">
    <div class="container-fluid min-vh-100 d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-10 col-xl-8 mx-auto">
                    <h1 class="text-center display-5">Find your perfect plan</h1>
                    <p class="lead text-center mb-4">
                        Discover the ideal plan to fuel your business growth. Our <br> pricing options are carefully
                        crafted to cater to businesses.
                    </p>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row justify-content-center align-items-center py-4">
                        <div class="col-sm-5 mb-3 mb-md-0">
                            <div class="card border text-center h-100 plan-card" data-plan="basic">
                                <div class="card-body d-flex flex-column">
                                    <div class="mb-4">
                                        <h1 class="display-6 text-start">₹ 17,000</h1>
                                    </div>
                                     <ul class="text-start list-unstyled">
                                        <li class="mb-2">
                                            <i class="fa fa-check-circle fs-4 me-2 step-icon"></i>
                                            5 Permanent Drivers
                                        </li>
                                        <li class="mb-2">
                                            <i class="fa fa-check-circle fs-4 me-2 step-icon"></i>
                                            6 Months
                                        </li>
                                        <li class="mb-2">
                                            <i class="fa fa-check-circle fs-4 me-2 step-icon"></i>
                                            Verified & Experienced
                                        </li>
                                    </ul>
                                    <div class="mt-3">
                                        <a href="{{ route('auth.payment_details', 'basic') }}" 
                                           class="btn btn-lg btn-outline-primary rounded-4 border-0 py-2 px-5 text-light bg-dark payment-btn">
                                            Choose Basic Plan <i class="ms-2 fa fa-fw fa-arrow-right text-light"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5 mb-3 mb-md-0">
                            <div class="card border text-center h-100 plan-card" data-plan="premium">
                                <div class="card-body d-flex flex-column">
                                    <div class="mb-4">
                                        <h1 class="display-6 text-start">₹ 20,000</h1>
                                    </div>
                                    <ul class="text-start list-unstyled">
                                        <li class="mb-2">
                                            <i class="fa fa-check-circle fs-4 me-2 step-icon"></i>
                                            Unlimited Permanent Drivers
                                        </li>
                                        <li class="mb-2">
                                            <i class="fa fa-check-circle fs-4 me-2 step-icon"></i>
                                            1 year
                                        </li>
                                        <li class="mb-2">
                                            <i class="fa fa-check-circle fs-4 me-2 step-icon"></i>
                                            Verified & Experienced
                                        </li>
                                    </ul>
                                    <div class="mt-3">
                                        <a href="{{ route('auth.payment_details', 'premium') }}" 
                                           class="btn btn-lg btn-outline-primary rounded-4 border-0 py-2 px-5 text-light bg-dark payment-btn">
                                            Choose Premium Plan <i class="ms-2 fa fa-fw fa-arrow-right text-light"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script>
        // Auto-dismiss alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                if (alert.classList.contains('show')) {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                    setTimeout(() => alert.remove(), 150);
                }
            });
        }, 5000);
    </script>
</body>
</html>
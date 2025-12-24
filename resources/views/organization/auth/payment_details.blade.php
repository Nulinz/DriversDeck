<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Payment Details - {{ $selectedPlan['name'] }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="{{ asset('assets/css/light.css') }}" rel="stylesheet">
    
    <style>
        body {
            min-height: 100vh;
            background-image: url('{{ asset('assets/images/bg.jpeg') }}');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .payment-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .qr-container {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
        }
        .qr-code-img {
            max-width: 300px;
            width: 100%;
            height: auto;
            border: 3px solid #007bff;
            border-radius: 10px;
            padding: 10px;
            background: white;
        }
        .plan-details {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .payment-info {
            background: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #ffc107;
        }
        .confirm-btn {
            transition: all 0.3s ease;
        }
        .confirm-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,123,255,0.3);
        }
    </style>
</head>

<body>
    <div class="container-fluid min-vh-100 d-flex align-items-center py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <div class="payment-card p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h2 class="display-6 mb-2">Complete Your Payment</h2>
                            <p class="text-muted">Scan the QR code below to make payment</p>
                        </div>

                        <!-- Plan Details -->
                        <div class="plan-details">
                            <h4 class="mb-3">{{ $selectedPlan['name'] }}</h4>
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <strong>Duration:</strong> {{ $selectedPlan['duration'] }}
                                </div>
                                <div class="col-6 mb-2">
                                    <strong>Amount:</strong> ₹{{ number_format($selectedPlan['amount']) }}
                                </div>
                                <div class="col-12">
                                    <strong>Features:</strong> {{ $selectedPlan['drivers'] }}, Verified & Experienced
                                </div>
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <div class="payment-info mb-4">
                            <h5 class="mb-2"><i class="fa fa-info-circle me-2"></i>Payment Instructions</h5>
                            <ol class="mb-0 ps-3">
                                <li>Scan the QR code using any UPI app (Google Pay, PhonePe, Paytm, etc.)</li>
                                <li>Enter the exact amount: ₹{{ number_format($selectedPlan['amount']) }}</li>
                                <li>Complete the payment</li>
                                <li>Take a screenshot of the successful transaction</li>
                                <li>Click "Confirm Subscription" and submit your transaction details</li>
                            </ol>
                        </div>

                        <!-- QR Code Section -->
                        <div class="qr-container mb-4">
                            <h5 class="mb-3">Scan QR Code to Pay</h5>
                            <!-- Replace with your actual QR code image -->
                            <img src="{{ asset('assets/images/qrcode.jpeg') }}" 
                                 alt="Payment QR Code" 
                                 class="qr-code-img mb-3">
                            <p class="text-muted small mb-0">
                                UPI ID: 9600166427@okbizaxis<br>
                                Amount: ₹{{ number_format($selectedPlan['amount']) }}
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="text-center">
                            <a href="{{ route('auth.transaction_form', $plan) }}" 
                               class="btn btn-primary btn-lg px-5 confirm-btn">
                                <i class="fa fa-check-circle me-2"></i>Confirm Subscription
                            </a>
                            <!-- <div class="mt-3">
                                <a href="{{ route('auth.register_subscription') }}" 
                                   class="text-muted">
                                    <i class="fa fa-arrow-left me-1"></i>Back to Plans
                                </a>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>
</html>
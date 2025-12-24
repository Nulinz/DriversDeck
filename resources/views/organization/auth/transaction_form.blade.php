<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Submit Transaction Details</title>
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
        .form-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .file-upload-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }
        .file-upload-input {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
        }
        .file-upload-label {
            display: block;
            padding: 20px;
            border: 2px dashed #007bff;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: #f8f9fa;
        }
        .file-upload-label:hover {
            background: #e3f2fd;
            border-color: #0056b3;
        }
        .file-upload-label i {
            font-size: 36px;
            color: #007bff;
            margin-bottom: 8px;
        }
        .file-preview {
            margin-top: 12px;
            padding: 12px;
            background: #e3f2fd;
            border-radius: 8px;
            display: none;
        }
        .file-preview img {
            max-width: 150px;
            max-height: 150px;
            border-radius: 8px;
            margin-top: 8px;
        }
        .plan-summary {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .submit-btn {
            transition: all 0.3s ease;
        }
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,123,255,0.3);
        }
    </style>
</head>

<body>
    <div class="container-fluid min-vh-100 d-flex align-items-center py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7 col-lg-6 col-xl-5">
                    <div class="form-card p-3 p-md-4">
                        <div class="text-center mb-3">
                            <h4 class="mb-2">Submit Transaction Details</h4>
                            <p class="text-muted small mb-0">Please enter your payment information below</p>
                        </div>

                        <!-- Plan Summary -->
                        <!-- <div class="plan-summary">
                            <h5 class="mb-2">{{ $selectedPlan['name'] }}</h5>
                            <p class="mb-1"><strong>Amount Paid:</strong> ₹{{ number_format($selectedPlan['amount']) }}</p>
                            <p class="mb-0"><strong>Duration:</strong> {{ $selectedPlan['duration'] }}</p>
                        </div> -->
<!-- 
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> Please fix the following issues:
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif -->

                        <!-- Transaction Form -->
                        <form action="{{ route('auth.submit_payment') }}" method="POST" enctype="multipart/form-data" id="transactionForm">
                            @csrf
                            <input type="hidden" name="plan" value="{{ $plan }}">

                            <!-- Transaction ID -->
                            <div class="mb-3">
                                <label for="transaction_id" class="form-label small">
                                    <i class="fa fa-hashtag me-1"></i>Transaction ID / UPI Reference Number
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('transaction_id') is-invalid @enderror" 
                                       id="transaction_id" 
                                       name="transaction_id" 
                                       placeholder="Enter your transaction ID"
                                       value="{{ old('transaction_id') }}"
                                       required>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    Enter the UPI transaction ID or reference number
                                </small>
                                @error('transaction_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Payment Screenshot -->
                            <div class="mb-3">
                                <label class="form-label small">
                                    <i class="fa fa-image me-1"></i>Payment Screenshot
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="file-upload-wrapper">
                                    <input type="file" 
                                           class="file-upload-input @error('payment_screenshot') is-invalid @enderror" 
                                           id="payment_screenshot" 
                                           name="payment_screenshot" 
                                           accept="image/*,.pdf"
                                           required>
                                    <label for="payment_screenshot" class="file-upload-label">
                                        <i class="fa fa-cloud-upload-alt d-block"></i>
                                        <strong class="d-block" style="font-size: 0.9rem;">Click to upload</strong>
                                        <span class="small">or drag and drop</span>
                                        <p class="text-muted mb-0 mt-1" style="font-size: 0.75rem;">
                                            JPG, PNG, PDF (Max 5MB)
                                        </p>
                                    </label>
                                </div>
                                @error('payment_screenshot')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                                
                                <!-- File Preview -->
                                <div class="file-preview" id="filePreview">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="flex-grow-1 me-2">
                                            <i class="fa fa-file-image me-1"></i>
                                            <strong id="fileName" class="small"></strong>
                                            <small class="text-muted d-block" id="fileSize" style="font-size: 0.75rem;"></small>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeFile()">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                    <img id="imagePreview" alt="Screenshot preview">
                                </div>
                            </div>

                            <!-- Important Note -->
                            <!-- <div class="alert alert-info">
                                <i class="fa fa-info-circle me-2"></i>
                                <strong>Important:</strong> Your subscription will be activated after admin verification of the payment details. This usually takes 24-48 hours.
                            </div> -->

                            <!-- Submit Button -->
                            <div class="d-grid gap-2 mb-2">
                                <button type="submit" class="btn btn-primary submit-btn" id="submitBtn">
                                    <i class="fa fa-check-circle me-2"></i>Submit Transaction Details
                                </button>
                            </div>

                            <!-- Back Link -->
                            <!-- <div class="text-center">
                                <a href="{{ route('auth.payment_details', $plan) }}" class="text-muted small">
                                    <i class="fa fa-arrow-left me-1"></i>Back to Payment Details
                                </a>
                            </div> -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script>
        // File upload preview
        document.getElementById('payment_screenshot').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const filePreview = document.getElementById('filePreview');
                const fileName = document.getElementById('fileName');
                const fileSize = document.getElementById('fileSize');
                const imagePreview = document.getElementById('imagePreview');

                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);

                // Show preview for images
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.style.display = 'none';
                }

                filePreview.style.display = 'block';
            }
        });

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        function removeFile() {
            document.getElementById('payment_screenshot').value = '';
            document.getElementById('filePreview').style.display = 'none';
            document.getElementById('imagePreview').src = '';
        }

        // Form submission
        document.getElementById('transactionForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Submitting...';
        });

        // Auto-dismiss alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert-dismissible');
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
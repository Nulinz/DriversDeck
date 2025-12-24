<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
            width: 100%;
        }
    </style>
</head>

<body class="reg-bg">
    <div class="container-fluid min-vh-100 d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <!-- Left Side -->
                <div class="col-md-6 mb-4 mb-md-0">
                    <h2 class="fs-2 my-4 fw-bold">Welcome to Drivers Deck</h2>

                    <h3 class="fw-bold fs-4 mb-1">You are in Registration Process</h3>
                    <p class="text-muted fs-5">Progress Starts Here – Step 1</p>

                    <ul class="list-unstyled step-list">
                        <li class="d-flex align-items-start">
                            <i class="fa fa-fw fa-circle fs-4 me-2 step-icon"></i>
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
                        <h3 class="text-center mb-4">Your Details</h3>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li> 
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <form action="{{ route('auth.register_details_store') }}" method="POST" id="form_input">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Corporate Type</label>
                                <select class="form-select form-select-lg border-2" name="c_type" required>
                                    <option value="" selected disabled true>Select option</option>
                                    <option value="Private Limited" {{ old('c_type') == 'Private Limited' ? 'selected' : '' }}>Private Limited</option>
                                    <option value="LLP" {{ old('c_type') == 'LLP' ? 'selected' : '' }}>LLP</option>
                                    <option value="Proprietorship" {{ old('c_type') == 'Proprietorship' ? 'selected' : '' }}>Proprietorship</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">District</label>
                                <select class="form-select form-select-lg border-2" name="c_district" id="district_select" required>
                                    <option value="" selected disabled>Select District</option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->district_id }}" {{ old('c_district') == $district->district_id ? 'selected' : '' }}>
                                            {{ $district->district_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Location</label>
                                <select class="form-select form-select-lg border-2" name="c_loc" id="location_select" required>
                                    <option value="" selected disabled>Select Location</option>
                                    <!-- Locations will be populated via AJAX -->
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Name</label>
                                <input type="text" class="form-control form-control-lg border-2" name="c_name" value="{{ old('c_name') }}"
                                    required placeholder="Enter your Company name">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Contact Number</label>
                                <input type="text" class="form-control form-control-lg border-2" maxlength="10" name="c_contact" value="{{ old('c_contact') }}" required
                                    placeholder="Enter your contact number" minlength="10"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '') ">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Mail Id</label>
                                <input type="email" class="form-control form-control-lg border-2" name="c_email" value="{{ old('c_email') }}"
                                    required placeholder="Enter your email address">
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
    

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const districtSelect = document.getElementById('district_select');
        const locationSelect = document.getElementById('location_select');
        
        // Set up CSRF token for AJAX requests
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        districtSelect.addEventListener('change', function() {
            const selectedDistrictId = this.value; // Now this is the district ID
            
            if (selectedDistrictId) {
                // Enable location select and show loading
                locationSelect.disabled = false;
                locationSelect.innerHTML = '<option>Loading...</option>';
                
                // Fetch locations for selected district using district ID
                fetch(`/organization/get-locations/${encodeURIComponent(selectedDistrictId)}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    locationSelect.innerHTML = '<option value="" selected disabled>Select Location</option>';
                    
                    if (data.length > 0) {
                        data.forEach(location => {
                            const option = document.createElement('option');
                            option.value = location.id;
                            option.textContent = location.location;
                            // Check if this option should be selected (for old input)
                            if ("{{ old('c_loc') }}" == location.id) {
                                option.selected = true;
                            }
                            locationSelect.appendChild(option);
                        });
                    } else {
                        locationSelect.innerHTML = '<option value="" selected disabled>No locations found</option>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    locationSelect.innerHTML = '<option value="" selected disabled>Error loading locations</option>';
                });
            } else {
                locationSelect.disabled = true;
                locationSelect.innerHTML = '<option value="" selected disabled>Select District First</option>';
            }
        });

        // If there's an old district value, trigger the change event to load locations
        if (districtSelect.value && "{{ old('c_district') }}") {
            districtSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
</body>
</html>
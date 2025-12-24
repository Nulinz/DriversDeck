@extends('admin.layouts.app')
@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row mb-2 mb-xl-3">
                <div class="col-auto d-none d-sm-block">
                    <h3><strong>Add Corporate</strong></h3>
                </div>
            </div>

            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                {{-- Basic Details --}}
                <div class="card">
                    <div class="card-header pb-0">
                        <h5 class="card-title mb-0">Basic Details</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.corporate.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-md-3">
                                    <label class="form-label fw-bold">Corporate Type <span class="text-danger">*</span></label>
                                    <select class="form-select" name="c_type" required>
                                        <option value="" selected disabled>Select Corporate Type</option>
                                        <option value="Private Limited" {{ old('c_type') == 'Private Limited' ? 'selected' : '' }}>Private Limited</option>
                                        <option value="LLP" {{ old('c_type') == 'LLP' ? 'selected' : '' }}>LLP</option>
                                        <option value="Proprietorship" {{ old('c_type') == 'Proprietorship' ? 'selected' : '' }}>Proprietorship</option>
                                    </select>
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label class="form-label fw-bold">District <span class="text-danger">*</span></label>
                                    <select class="form-select" name="c_district" id="district_select" required>
                                        <option value="" selected disabled>Select District</option>
                                        @foreach ($districts as $district)
                                            <option value="{{ $district->district_id }}" {{ old('c_district') == $district->district_id ? 'selected' : '' }}>
                                                {{ $district->district_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label class="form-label fw-bold">Location <span class="text-danger">*</span></label>
                                    <select class="form-select" name="c_loc" id="location_select" required>
                                        <option value="" selected disabled>Select Location</option>
                                        {{-- Locations will be loaded via AJAX based on district selection --}}
                                    </select>
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label class="form-label fw-bold">Company Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="c_name" value="{{ old('c_name') }}" required placeholder="Enter company name">
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label class="form-label fw-bold">Contact Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="c_contact" value="{{ old('c_contact') }}" required placeholder="Enter contact number" maxlength="10" minlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="c_email" value="{{ old('c_email') }}" required placeholder="Enter email address">
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label class="form-label fw-bold">Gender</label>
                                    <select class="form-select" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>
                    </div>
                </div>

                {{-- Contact Person Details --}}
                <div class="card">
                    <div class="card-header pb-0">
                        <h5 class="card-title mb-0">Contact Person Details</h5>
                    </div>
                    <div class="card-body">
                            <div class="row">
                                <div class="mb-3 col-md-3">
                                    <label class="form-label fw-bold">Contact Person Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="full_name" value="{{ old('full_name') }}" required placeholder="Enter contact person name">
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label class="form-label fw-bold">Contact Person Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="full_contact" value="{{ old('full_contact') }}" required placeholder="Enter contact person number" maxlength="10" minlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label class="form-label fw-bold">Alternate Contact Number</label>
                                    <input type="text" class="form-control" name="alt_contact" value="{{ old('alt_contact') }}" placeholder="Enter alternate contact number" maxlength="10" minlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label class="form-label fw-bold">Contact Person Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="f_mail" value="{{ old('f_mail') }}" required placeholder="Enter contact person email">
                                </div>
                            </div>
                    </div>
                </div>

                {{-- Address Details --}}
                <div class="card">
                    <div class="card-header pb-0">
                        <h5 class="card-title mb-0">Address Details</h5>
                    </div>
                    <div class="card-body">
                            <div class="row">
                                <div class="mb-3 col-md-12">
                                    <label class="form-label fw-bold">Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="ad_1" rows="3" required placeholder="Enter complete address">{{ old('ad_1') }}</textarea>
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label class="form-label fw-bold">City <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="city" value="{{ old('city') }}" required placeholder="Enter city">
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label class="form-label fw-bold">State <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="state" value="{{ old('state') }}" required placeholder="Enter state">
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label class="form-label fw-bold">PIN Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="pin_code" value="{{ old('pin_code') }}" required placeholder="Enter PIN code" maxlength="6" minlength="6" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>
                            </div>
                    </div>
                </div>

                {{-- Business Details --}}
                <div class="card">
                    <div class="card-header pb-0">
                        <h5 class="card-title mb-0">Business Details</h5>
                    </div>
                    <div class="card-body">
                            <div class="row">
                                <div class="mb-3 col-md-3">
                                    <label class="form-label fw-bold">PAN Number</label>
                                    <input type="text" class="form-control" name="pan" value="{{ old('pan') }}"  placeholder="Enter PAN number" maxlength="10" minlength="10" style="text-transform: uppercase;">
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label class="form-label fw-bold">GST Number </label>
                                    <input type="text" class="form-control" name="gst" value="{{ old('gst') }}"  placeholder="Enter GST number" maxlength="15" minlength="15" style="text-transform: uppercase;">
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label class="form-label fw-bold">Company Logo</label>
                                    <input type="file" class="form-control" name="logo" accept="image/jpeg,image/png,image/jpg">
                                </div>
                            </div>
                    </div>
                </div>

                {{-- Asset Details --}}
                <div class="card">
                    <div class="card-header pb-0">
                        <h5 class="card-title mb-0">Asset Details</h5>
                    </div>
                    <div class="card-body">
                            <div class="row">
                                <div class="mb-3 col-md-3">
                                    <label class="form-label fw-bold">Number of Vehicles <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="no_vehicle" value="{{ old('no_vehicle') }}" required placeholder="Enter number of vehicles" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label class="form-label fw-bold">Number of Drivers <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="no_drivers" value="{{ old('no_drivers') }}" required placeholder="Enter number of drivers" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label class="form-label fw-bold">Number of Vacancies <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="no_vacancies" value="{{ old('no_vacancies') }}" required placeholder="Enter number of vacancies" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>
                            </div>

                            <div class="row">
                                <div class="mt-2 col-md-2">
                                    <input type="submit" class="btn btn-primary w-100" value="Save">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'))
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // District change handler
            const districtSelect = document.getElementById('district_select');
            const locationSelect = document.getElementById('location_select');

            districtSelect.addEventListener('change', function() {
                const districtId = this.value;
                
                // Clear location dropdown
                locationSelect.innerHTML = '<option value="" selected disabled>Loading locations...</option>';
                
                if (districtId) {
                    // Fetch locations for selected district
                    fetch(`{{ route('admin.get_locations_by_district') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            district: districtId
                        })
                    })
                    .then(response => response.json())
                    .then(locations => {
                        locationSelect.innerHTML = '<option value="" selected disabled>Select Location</option>';
                        
                        locations.forEach(location => {
                            const option = document.createElement('option');
                            option.value = location.id;
                            option.textContent = location.location;
                            
                            // Check if this location was previously selected (for old input)
                            if ('{{ old("c_loc") }}' == location.id) {
                                option.selected = true;
                            }
                            
                            locationSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching locations:', error);
                        locationSelect.innerHTML = '<option value="" selected disabled>Error loading locations</option>';
                    });
                } else {
                    locationSelect.innerHTML = '<option value="" selected disabled>Select Location</option>';
                }
            });

            // Trigger district change on page load if district is pre-selected (for old input)
            const selectedDistrict = '{{ old("c_district") }}';
            if (selectedDistrict) {
                districtSelect.value = selectedDistrict;
                districtSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>

    {{-- Add CSRF token meta tag for AJAX requests --}}
    @if(!isset($csrf_token_meta))
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endif
@endsection
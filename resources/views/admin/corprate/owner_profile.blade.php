@extends('admin.layouts.app')
@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row mb-2 mb-xl-3">
                <div class="col-auto d-none d-sm-block">
                    <h3><strong>Owner Profile</strong></h3>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                {{-- profile details --}}
                <div class="col-md-5 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center justify-content-start">
                                    <img class="profile_img" src="{{ asset($corprate->logo) }}" alt="" id="profileImage">
                                    <h4 class="fw-bold text-dark ms-3 mb-0" id="profileName">{{ $corprate->name }}</h4>
                                </div>
                                <button class="btn btn-sm btn-primary" onclick="toggleEditMode()">
                                     Edit
                                </button>
                            </div>
                            <hr>
                            <h5 class="fs-4 mb-3">Basic Details</h5>
                            
                            <!-- Display Mode -->
                            <div id="displayMode">
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h5 class="fs-6 text-muted mb-0 fw-bold">Date of Birth</h5>
                                    <p class="fs-6 text-dark mb-0 fw-bold">{{ $corprate->dob ?? 'Nil' }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h6 class="text-muted mb-0 fw-bold">Contact Number</h6>
                                    <p class="text-dark mb-0 fw-bold">{{ $corprate->contact }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h6 class="text-muted mb-0 fw-bold">Gender</h6>
                                    <p class="text-dark mb-0 fw-bold">{{ $corprate->gender }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h6 class="text-muted mb-0 fw-bold">Address Line 1</h6>
                                    <p class="text-dark mb-0 fw-bold">{{ $corprate->ad_1 }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h6 class="text-muted mb-0 fw-bold">Address Line 2</h6>
                                    <p class="text-dark mb-0 fw-bold">{{ $corprate->ad_2 }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h6 class="text-muted mb-0 fw-bold">City</h6>
                                    <p class="text-dark mb-0 fw-bold">{{ $corprate->city }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="text-muted mb-0 fw-bold">State</h6>
                                    <p class="text-dark mb-0 fw-bold">{{ $corprate->state }}</p>
                                </div>
                            </div>

                            <!-- Edit Mode -->
                            <div id="editMode" style="display: none;">
                                <form action="{{ route('admin.corprate.update_owner_profile', $corprate->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Profile Image</label>
                        <input type="file" class="form-control" name="logo" accept="image/*" onchange="previewImage(this)">
                        @if($corprate->logo)
                            <small class="text-muted">Current: {{ basename($corprate->logo) }}</small>
                        @endif
                    </div>

                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Name</label>
                                        <input type="text" class="form-control" name="name" value="{{ $corprate->name }}" required>
                                    </div>
                                    
                                    <!-- <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Date of Birth</label>
                                        <input type="date" class="form-control" name="dob" value="1998-10-02" disabled>
                                        <small class="text-muted">Date of birth cannot be modified</small>
                                    </div> -->
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Contact Number</label>
                                        <input type="text" class="form-control" name="contact" value="{{ $corprate->contact }}" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Gender</label>
                                        <select class="form-select" name="gender" required>
                                            <option value="Male" {{ $corprate->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ $corprate->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                            <option value="Other" {{ $corprate->gender == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Address Line 1</label>
                                        <input type="text" class="form-control" name="ad_1" value="{{ $corprate->ad_1 }}" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Address Line 2</label>
                                        <input type="text" class="form-control" name="ad_2" value="{{ $corprate->ad_2 }}">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">City</label>
                                        <input type="text" class="form-control" name="city" value="{{ $corprate->city }}" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">State</label>
                                        <input type="text" class="form-control" name="state" value="{{ $corprate->state }}" required>
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-success">
                                             Save Changes
                                        </button>
                                        <button type="button" class="btn btn-secondary" onclick="toggleEditMode()">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- profile tabs --}}
                <div class="col-md-7 col-xl-8">
                    <div class="nav nav-tabs d-flex justify-content-start align-items-center gap-x-4 gap-xl-4 mb-3"
                        role="tablist">
                        <a class="active" data-bs-toggle="list" href="#Details" role="tab" aria-selected="true">
                            Details
                        </a>
                        <a class="" data-bs-toggle="list" href="#Trip" role="tab" aria-selected="false"
                            tabindex="-1">
                            Trip
                        </a>
                        <a class="" data-bs-toggle="list" href="#Payments" href="#" role="tab"
                            aria-selected="false" tabindex="-1">
                            Payments
                        </a>
                        <a class="" data-bs-toggle="list" href="#Referral" href="#" role="tab"
                            aria-selected="false" tabindex="-1">
                            Referral
                        </a>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="Details" role="tabpanel">
                            {{-- Bank Details --}}
                            <div class="card">
                                <h5 class="p-3 card-title mb-0 border-bottom">Bank Details</h5>
                                <div class="card-body pb-0">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Bank Name</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $corprate->bank_name }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Acc Holder Name</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $corprate->holder_name }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Account Number</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $corprate->bank_acc_no }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">IFSC Code</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $corprate->bank_ifsc }}</p>
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Branch Name</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $corprate->bank_branch }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">UPI Id</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $corprate->bank_upi_id }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">UPI Name</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $corprate->bank_upi_name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Trip" role="tabpanel">
                            {{-- Hired Details --}}
                            <div class="card mb-3">
                                <h5 class="p-3 card-title mb-0 border-bottom">Acting Driver</h5>
                                <div class="card-body">
                                    <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Vehicle Type</th>
                                                <th>Drivers</th>
                                                <th>Trip</th>
                                                <th>Strart Date</th>
                                                <th>End Date </th>
                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            @foreach ($trips as $index => $trip)
                                                @php
                                                    $parts = explode('#', $trip->st_city);
                                                    $city = !empty($parts[0])
                                                        ? $parts[0]
                                                        : (isset($parts[1])
                                                            ? $parts[1]
                                                            : '');
                                                    $parts1 = explode('#', $trip->end_city);
                                                    $city1 = !empty($parts1[0])
                                                        ? $parts1[0]
                                                        : (isset($parts1[1])
                                                            ? $parts1[1]
                                                            : '');
                                                @endphp
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $trip->veh_type }}</td>
                                                    <td>{{ $trip->applied_drivers }}</td>
                                                    <td>{{ $city }} - {{ $city1 }}</td>

                                                    <td>{{ \Carbon\Carbon::parse($trip->start_date)->format('d-m-Y') }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($trip->end_date)->format('d-m-Y') }}</td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Payments" role="tabpanel">
                            {{-- Payments Details --}}
                            <div class="card mb-3">
                                <h5 class="p-3 card-title mb-0 border-bottom">Payments Details</h5>
                                <div class="card-body p-0">
                                    <table class="table mb-0 table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Package</th>
                                                <th>Paid Date</th>
                                                {{-- <th>Payments Mode</th> --}}
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            @foreach ($subs as $index => $subscription)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $subscription->plan }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($subscription->created_at)->format('d-m-Y') }}
                                                    </td>
                                                    <td>{{ ucfirst($subscription->status) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Referral" role="tabpanel">
                            {{-- Referral tab --}}
                            <div class="card mb-3">
                                <div class="d-flex align-items-center justify-content-between mb-0 p-3 border-bottom">
                                    <h5 class="card-title mb-0">Referral Details</h5>
                                    <p class="fs-4 fw-bold mb-0">Wallet Balance : <span>{{ $rem }}</span></p>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table mb-0 table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Register Type</th>
                                                <th>Register Date</th>
                                                <th>Mobile Number</th>
                                                {{-- <th>Location</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($referrals as $key => $ref)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>
                                                        {{ $ref->driver_name ?? ($ref->corporate_name ?? 'N/A') }}
                                                    </td>
                                                    <td>{{ ucfirst($ref->f_type) }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($ref->created_at)->format('d-m-Y') }}</td>
                                                    <td>{{ $ref->driver_phone ?? ($ref->corporate_contact ?? 'N/A') }}</td>
                                                    {{-- <td>{{ $ref->driver_location ?? ($ref->corporate_location ?? 'N/A') }} --}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Payment" role="tabpanel">
                            {{-- payment tab --}}
                            <div class="card">
                                <div class="card-body">
                                    {{-- <h5 class="card-title">Password</h5> --}}
                                    <table id="datatables-reponsive_payment" class="table table-striped"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Package</th>
                                                <th>Paid Date</th>
                                                <th>Payment Mode</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Tiger Nixon</td>
                                                <td>System Architect</td>
                                                <td>Edinburgh</td>
                                                <td><span class="badge badge-success-light">Received</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Datatables Responsive
            $("#datatables-reponsive").DataTable({
                responsive: true,
                ordering: false,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    ["5", "10", "25", "50", "All"]
                ]
            });
        });

        function toggleEditMode() {
            const displayMode = document.getElementById('displayMode');
            const editMode = document.getElementById('editMode');
            
            if (displayMode.style.display === 'none') {
                displayMode.style.display = 'block';
                editMode.style.display = 'none';
            } else {
                displayMode.style.display = 'none';
                editMode.style.display = 'block';
            }
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profileImage').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection()
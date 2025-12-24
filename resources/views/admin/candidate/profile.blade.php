@extends('admin.layouts.app')
@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row mb-2 mb-xl-3">
                <div class="col-auto d-none d-sm-block">
                     <h3><strong>Driver Profile - </strong><strong>{{ ucfirst($dr->type ?? 'N/A') }}</strong></h3>
                </div>
            </div>

            <div class="row">
                {{-- profile details --}}
                <div class="col-md-5 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center justify-content-start">
                                    @if($driver->img)
                                        <img class="profile_img" src="{{ asset($driver->img) }}" alt="" id="profileImage">
                                    @else
                                        <img class="profile_img" src="{{ asset('assets/images/avatar.png') }}" alt="" id="profileImage">
                                    @endif
                                    <h4 class="fw-bold text-dark ms-3 mb-0" id="profileName">{{ $driver->name }}</h4>
                                </div>
                                <button class="btn btn-sm btn-primary" onclick="toggleEditMode()">
                                  Edit
                                </button>
                            </div>
                            <hr>
                            
                            <!-- Display Mode -->
                            <div id="displayMode">
                                <h5 class="fs-4 mb-3">Basic Details</h5>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h5 class="fs-6 text-muted mb-0 fw-bold">Date of Birth</h5>
                                    <p class="fs-6 text-dark mb-0">{{ $driver->dob ?? 'Nil' }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h6 class="text-muted mb-0 fw-bold">Contact Number</h6>
                                    <p class="text-dark mb-0 fw-bold">{{ $driver->phone }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h6 class="text-muted mb-0 fw-bold">Alternate Number</h6>
                                    <p class="text-dark mb-0">{{ $driver->alt_phone ?? 'N/A' }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h6 class="text-muted mb-0 fw-bold">Gender</h6>
                                    <p class="text-dark mb-0 fw-bold" id="display-gender">{{ $driver->gender }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h6 class="text-muted mb-0 fw-bold">Blood Group</h6>
                                    <p class="text-dark mb-0 fw-bold" id="display-blood">{{ $driver->b_group }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h6 class="text-muted mb-0 fw-bold">Address Line 1</h6>
                                    <p class="text-dark mb-0 fw-bold" id="display-address1">{{ $driver->c_ad }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h6 class="text-muted mb-0 fw-bold">Address Line 2</h6>
                                    <p class="text-dark mb-0">{{ $driver->c_ad2 ?? 'N/A' }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h6 class="text-muted mb-0 fw-bold">City</h6>
                                    <p class="text-dark mb-0 fw-bold" id="display-city">{{ $driver->c_city }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h6 class="text-muted mb-0 fw-bold">State</h6>
                                    <p class="text-dark mb-0 fw-bold" id="display-state">{{ $driver->c_state }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h6 class="text-muted mb-0 fw-bold">PIN Code</h6>
                                    <p class="text-dark mb-0" id="display-pin">{{ $driver->c_pin ?? 'N/A' }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="text-muted mb-0 fw-bold">About</h6>
                                    <p class="text-dark mb-0" id="display-about">{{ $driver->about ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <!-- Edit Mode -->
                            <div id="editMode" style="display: none;">
                                <form action="{{ route('admin.candidate.update_profile', $driver->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    
                                    <h5 class="fs-4 mb-3">Basic Details (Editable)</h5>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Gender</label>
                                        <select class="form-select" name="gender" required>
                                            <option value="">Select Gender</option>
                                            <option value="Male" {{ $driver->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ $driver->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                            <option value="Other" {{ $driver->gender == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Blood Group</label>
                                        <select class="form-select" name="b_group" required>
                                            <option value="">Select Blood Group</option>
                                            <option value="A+" {{ $driver->b_group == 'A+' ? 'selected' : '' }}>A+</option>
                                            <option value="A-" {{ $driver->b_group == 'A-' ? 'selected' : '' }}>A-</option>
                                            <option value="B+" {{ $driver->b_group == 'B+' ? 'selected' : '' }}>B+</option>
                                            <option value="B-" {{ $driver->b_group == 'B-' ? 'selected' : '' }}>B-</option>
                                            <option value="AB+" {{ $driver->b_group == 'AB+' ? 'selected' : '' }}>AB+</option>
                                            <option value="AB-" {{ $driver->b_group == 'AB-' ? 'selected' : '' }}>AB-</option>
                                            <option value="O+" {{ $driver->b_group == 'O+' ? 'selected' : '' }}>O+</option>
                                            <option value="O-" {{ $driver->b_group == 'O-' ? 'selected' : '' }}>O-</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Address Line 1</label>
                                        <input type="text" class="form-control" name="c_ad" value="{{ $driver->c_ad }}" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">City</label>
                                        <input type="text" class="form-control" name="c_city" value="{{ $driver->c_city }}" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">State</label>
                                        <input type="text" class="form-control" name="c_state" value="{{ $driver->c_state }}" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">PIN Code</label>
                                        <input type="text" class="form-control" name="c_pin" value="{{ $driver->c_pin }}" maxlength="10">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">About</label>
                                        <textarea class="form-control" name="about" rows="3" maxlength="500">{{ $driver->about }}</textarea>
                                    </div>

                                    <!-- Experience Fields -->
                                    <h6 class="fs-5 mb-3 mt-4">Experience Details</h6>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted">Experience Years</label>
                                            <input type="number" class="form-control" name="exp_year" value="{{ $driver->exp_year }}" min="0">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted">Experience Months</label>
                                            <input type="number" class="form-control" name="exp_mon" value="{{ $driver->exp_mon }}" min="0" max="11">
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Previous Company Name</label>
                                        <input type="text" class="form-control" name="p_com_name" value="{{ $driver->p_com_name }}">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Relieving Date</label>
                                        <input type="date" class="form-control" name="rel_date" value="{{ $driver->rel_date }}">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Company Location</label>
                                        <input type="text" class="form-control" name="com_location" value="{{ $driver->com_location }}">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Company Contact Number</label>
                                        <input type="text" class="form-control" name="contact_number" value="{{ $driver->contact_number }}" maxlength="15">
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted">Current Salary</label>
                                            <input type="number" class="form-control" name="current_salary" value="{{ $driver->current_salary }}" step="0.01">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted">Expected Salary</label>
                                            <input type="number" class="form-control" name="expert_salary" value="{{ $driver->expert_salary }}" step="0.01">
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">PF Number</label>
                                        <input type="text" class="form-control" name="pf" value="{{ $driver->pf }}">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Job Location Preference</label>
                                        <input type="text" class="form-control" name="job_loc" value="{{ $driver->job_loc }}">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Agreement</label>
                                        <input type="text" class="form-control" name="agreement" value="{{ $driver->agreement }}">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Years of Service</label>
                                        <input type="number" class="form-control" name="years" value="{{ $driver->years }}" min="0">
                                    </div>
                                    
                                    <div class="d-flex gap-2 mt-4">
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
                        <a class="active" data-bs-toggle="tab" href="#Details" role="tab" aria-selected="true">
                            Details
                        </a>
                        <a class="" data-bs-toggle="tab" href="#Experience" role="tab" aria-selected="false"
                            tabindex="-1">
                            Experience
                        </a>
                        <a class="" data-bs-toggle="tab" href="#Trips" role="tab" aria-selected="false"
                            tabindex="-1">
                            Trips
                        </a>
                        <a class="" data-bs-toggle="tab" href="#Feedback" role="tab" aria-selected="false"
                            tabindex="-1">
                            Feedback
                        </a>
                        <a class="" data-bs-toggle="tab" href="#Payment" role="tab" aria-selected="false"
                            tabindex="-1">
                            Payment
                        </a>
                        <a class="" data-bs-toggle="tab" href="#Referral" role="tab" aria-selected="false"
                            tabindex="-1">
                            Referral
                        </a>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="Details" role="tabpanel">
                            {{-- License Details --}}
                            <div class="card mb-3">
                                <h5 class="p-3 card-title mb-0 border-bottom">License Details <span class="text-muted fs-6">(Read Only)</span></h5>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Cover of Vehicle</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->cov }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Issued RTO</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->issued_rto }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Date Of Issue</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->date_of_issue }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Validity From</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->v_from }}</p>
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Validity To</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->v_to }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Batch No</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->batch_no }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Batch Issue Date</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->batch_issue_date }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Batch Issue By</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->batch_issued_by }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Card Serial No</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->card_serial_no }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Relative Name (C/o)</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->cof }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Bank Details --}}
                            <div class="card mb-3">
                                <h5 class="p-3 card-title mb-0 border-bottom">Bank Details <span class="text-muted fs-6">(Read Only)</span></h5>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Bank Name</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->bank_name }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Acc Holder Name</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->holder_name }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Account Number</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->bank_acc_no }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">IFSC Code</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->bank_ifsc }}</p>
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Branch Name</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->bank_branch }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">UPI Id</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->bank_upi_id }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">UPI Name</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->bank_upi_name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Id Proof Details --}}
                            <div class="card mb-3">
                                <h5 class="p-3 card-title mb-0 border-bottom">Id Proof <span class="text-muted fs-6">(Read Only)</span></h5>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">License Number</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->l_no }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">License Image</h5>
                                            <p class="text-muted">Protected Data</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Aadhaar Number</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->aadhaar }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Aadhaar Image</h5>
                                            <p class="text-muted">Protected Data</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Experience" role="tabpanel">
                            {{-- Experience Details --}}
                            <div class="card mb-3">
                                <h5 class="p-3 card-title mb-0 border-bottom">Experience <span class="text-muted fs-6">(Read Only - Edit from Profile Section)</span></h5>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Experience</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->exp_year }} Years
                                                {{ $driver->exp_mon }} Months</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Company Name</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->p_com_name }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Current Salary</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->current_salary }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Location</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->com_location }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Contact Number</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->contact_number }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">PF</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->pf }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Expected Salary</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->expert_salary }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Job Location</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->job_loc }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Trips" role="tabpanel">
                            {{-- trip tab --}}
                            <div class="card">
                                <div class="card-body">
                                    <table id="datatables-reponsive_trips" class="table table-striped"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Client Name</th>
                                                <th>Trip From</th>
                                                <th>Trip To</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($hiredTrips as $index => $trip)
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
                                                    <td>{{ $trip->client_name ?? 'N/A' }}</td>
                                                    <td>{{ $city ?? 'N/A' }}</td>
                                                    <td>{{ $city1 ?? 'N/A' }}</td>
                                                    <td>{{ $trip->st_date ? \Carbon\Carbon::parse($trip->st_date)->format('d-m-Y') : 'N/A' }}
                                                    </td>
                                                    <td>{{ $trip->end_date ? \Carbon\Carbon::parse($trip->end_date)->format('d-m-Y') : 'N/A' }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">No hired trips found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Feedback" role="tabpanel">
                            {{-- feedback tab --}}
                            <div class="card">
                                <div class="card-body">
                                    <table id="datatables-reponsive_feedback" class="table table-striped"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Client Name</th>
                                                <th>Rating</th>
                                                <th>Remarks</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($feedbacks as $index => $feedback)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $feedback->client_name ?? 'N/A' }}</td>
                                                    <td>{{ $feedback->rating ?? 'N/A' }}</td>
                                                    <td>{{ $feedback->remarks ?? 'N/A' }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($feedback->created_at)->format('d-m-Y') }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No feedback available</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Payment" role="tabpanel">
                            {{-- payment tab --}}
                            <div class="card">
                                <div class="card-body">
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
                                            @forelse($subscriptions as $index => $sub)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $sub->plan ?? 'N/A' }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($sub->created_at)->format('d-m-Y') }}</td>
                                                    <td>Online</td>
                                                    <td><span class="badge badge-success-light">Received</span></td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No payment records found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Referral" role="tabpanel">
                            {{-- Referral tab --}}
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h5 class="card-title">Referral Details</h5>
                                        <p class="fs-4 fw-bold">Wallet Balance : <span>{{ $rem }}</span></p>
                                    </div>

                                    <table id="datatables-reponsive_referral" class="table table-striped"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Register Type</th>
                                                <th>Register Date</th>
                                                <th>Mobile Number</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($referrals as $index => $referral)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $referral->collect->driver_name ?? 'N/A' }}</td>
                                                    <td>{{ $referral->collect->driver_type ?? 'N/A' }}</td>
                                                    <td>{{ $referral->created_at ? \Carbon\Carbon::parse($referral->created_at)->format('d-m-Y') : 'N/A' }}
                                                    </td>
                                                    <td>{{ $referral->collect->driver_phone ?? 'N/A' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No referrals found</td>
                                                </tr>
                                            @endforelse
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
</script>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Initialize all DataTables
            const initDataTable = (id) => {
                return $(id).DataTable({
                    responsive: true,
                    ordering: false,
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        ["5", "10", "25", "50", "All"]
                    ]
                });
            };

            // Initialize tables
            const tripsTable = initDataTable("#datatables-reponsive_trips");
            const feedbackTable = initDataTable("#datatables-reponsive_feedback");
            const paymentTable = initDataTable("#datatables-reponsive_payment");
            const referralTable = initDataTable("#datatables-reponsive_referral");

            // Handle tab changes to adjust DataTables
            $('a[data-bs-toggle="tab"]').on("shown.bs.tab", function(e) {
                const target = $(e.target).attr("href");

                switch (target) {
                    case "#Trips":
                        tripsTable.columns.adjust().responsive.recalc();
                        break;
                    case "#Feedback":
                        feedbackTable.columns.adjust().responsive.recalc();
                        break;
                    case "#Payment":
                        paymentTable.columns.adjust().responsive.recalc();
                        break;
                    case "#Referral":
                        referralTable.columns.adjust().responsive.recalc();
                        break;
                }
            });
        });
    </script>
@endsection
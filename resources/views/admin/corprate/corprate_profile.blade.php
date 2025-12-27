@extends('admin.layouts.app')
@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row mb-2 mb-xl-3">
                <div class="col-auto d-none d-sm-block">
                    <h3><strong>Corporate Profile</strong></h3>
                </div>
            </div>

            <div class="row">
                {{-- profile details --}}
                <div class="col-md-5 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center justify-content-start">
                                    @if($corprate->logo)
                                        <img class="profile_img" src="{{ asset($corprate->logo) }}" alt="" id="profileImage">
                                    @else
                                        <img class="profile_img" src="{{ asset('uploads/default-logo.png') }}" alt="" id="profileImage">
                                    @endif
                                    <h4 class="fw-bold text-dark ms-3 mb-0" id="profileName">{{ $corprate->name }}</h4>
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
                                    <h5 class="fs-6 text-muted mb-0 fw-bold">Company Name</h5>
                                    <p class="fs-6 text-dark mb-0 fw-bold">{{ $corprate->name }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h6 class="text-muted mb-0 fw-bold">Contact Person</h6>
                                    <p class="text-dark mb-0 fw-bold">{{ $corprate->c_name }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h6 class="text-muted mb-0 fw-bold">Contact Number</h6>
                                    <p class="text-dark mb-0 fw-bold">{{ $corprate->c_num }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h6 class="text-muted mb-0 fw-bold">Alternate Number</h6>
                                    <p class="text-dark mb-0 fw-bold">{{ $corprate->a_num ?? 'N/A' }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h6 class="text-muted mb-0 fw-bold">Email</h6>
                                    <p class="text-dark mb-0 fw-bold">{{ $corprate->c_mail }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h6 class="text-muted mb-0 fw-bold">Address Line 1</h6>
                                    <p class="text-dark mb-0 fw-bold">{{ $corprate->ad_1 }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h6 class="text-muted mb-0 fw-bold">Address Line 2</h6>
                                    <p class="text-dark mb-0 fw-bold">{{ $corprate->ad_2 ?? 'N/A' }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h6 class="text-muted mb-0 fw-bold">City</h6>
                                    <p class="text-dark mb-0 fw-bold">{{ $corprate->city }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h6 class="text-muted mb-0 fw-bold">State</h6>
                                    <p class="text-dark mb-0 fw-bold">{{ $corprate->state }}</p>
                                </div>

                                <!-- Business Details -->
                                <h5 class="fs-4 mb-3 mt-4">Business Details</h5>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h5 class="fs-6 text-muted mb-0 fw-bold">Company Pan</h5>
                                    <p class="fs-6 text-dark mb-0">{{ $corprate->pan }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h6 class="text-muted mb-0 fw-bold">Company GST</h6>
                                    <p class="text-dark mb-0">{{ $corprate->gst }}</p>
                                </div>
                                
                                <!-- Requirements -->
                                <h5 class="fs-4 mb-3 mt-4">Requirements</h5>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h6 class="text-muted mb-0 fw-bold">No of vacancy</h6>
                                    <p class="text-dark mb-0">{{ $corprate->no_vac }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h6 class="text-muted mb-0 fw-bold">No of vehicle</h6>
                                    <p class="text-dark mb-0">{{ $corprate->no_veh }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-0_8">
                                    <h6 class="text-muted mb-0 fw-bold">No of Driver Works</h6>
                                    <p class="text-dark mb-0">{{ $corprate->no_driver }}</p>
                                </div>
                            </div>

                            <!-- Edit Mode -->
                            <div id="editMode" style="display: none;">
                                <form action="{{ route('admin.corprate.update_profile', $corprate->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    
                                    <h5 class="fs-4 mb-3">Basic Details</h5>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Company Logo</label>
                                        <input type="file" class="form-control" name="logo" accept="image/*" onchange="previewImage(this)">
                                        @if($corprate->logo)
                                            <small class="text-muted">Current: {{ $corprate->logo }}</small>
                                        @endif
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Company Name</label>
                                        <input type="text" class="form-control" name="name" value="{{ $corprate->name }}" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Contact Person</label>
                                        <input type="text" class="form-control" name="c_name" value="{{ $corprate->c_name }}" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Contact Number</label>
                                        <input type="text" class="form-control" name="c_num" value="{{ $corprate->c_num }}" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Alternate Number</label>
                                        <input type="text" class="form-control" name="a_num" value="{{ $corprate->a_num }}">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Email</label>
                                        <input type="email" class="form-control" name="c_mail" value="{{ $corprate->c_mail }}" required>
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
                                    
                                    <!-- Business Details Edit Fields -->
                                    <h5 class="fs-4 mb-3 mt-4">Business Details</h5>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Company PAN</label>
                                        <input type="text" class="form-control" name="pan" value="{{ $corprate->pan }}" required maxlength="10" style="text-transform: uppercase;">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Company GST</label>
                                        <input type="text" class="form-control" name="gst" value="{{ $corprate->gst }}" required maxlength="15" style="text-transform: uppercase;">
                                    </div>
                                    
                                    <!-- Requirements Edit Fields -->
                                    <h5 class="fs-4 mb-3 mt-4">Requirements</h5>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">No of Vacancy</label>
                                        <input type="number" class="form-control" name="no_vac" value="{{ $corprate->no_vac }}" required min="0">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">No of Vehicle</label>
                                        <input type="number" class="form-control" name="no_veh" value="{{ $corprate->no_veh }}" required min="0">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">No of Driver Works</label>
                                        <input type="number" class="form-control" name="no_driver" value="{{ $corprate->no_driver }}" required min="0">
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
                        <a class="active" data-bs-toggle="list" href="#Vacancies" role="tab" aria-selected="true">
                            Vacancies
                        </a>
                        <a class="" data-bs-toggle="list" href="#Hired" role="tab" aria-selected="false"
                            tabindex="-1">
                            Hired
                        </a>
                        <a class="" data-bs-toggle="list" href="#Payments" role="tab"
                            aria-selected="false" tabindex="-1">
                            Payments
                        </a>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="Vacancies" role="tabpanel">
                            {{-- Acting driver Details --}}
                            <div class="card mb-3">
                                <h5 class="p-3 card-title mb-0 border-bottom">Acting Driver</h5>
                                <div class="card-body p-0">
                                    <table class="table mb-0 table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Vehicle Type</th>
                                                <th>Trip From</th>
                                                <th>Trip To</th>
                                                <th>Status</th>
                                                <th>Start Date</th>
                                                <th>End Date </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($actingJobs as $index => $trip)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $trip->veh_type ?? 'N/A' }}</td>
                                                    <td>{{ $trip->st_loc }}</td>
                                                    <td>{{ $trip->st_dest }}</td>
                                                    <td>{{ $trip->status }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($trip->start_date)->format('d-m-Y') }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($trip->end_date)->format('d-m-Y') }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7">No Acting Jobs Found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- full time driver Details --}}
                            <div class="card mb-3">
                                <h5 class="p-3 card-title mb-0 border-bottom">Fulltime Driver</h5>
                                <div class="card-body p-0">
                                    <table class="table mb-0 table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Vehicle Type</th>
                                                <th>Job Location</th>
                                                <th>Agreement</th>
                                                <th>Status</th>
                                                <th>Join Date</th>
                                                <th>Salary</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse($permanentJobs as $index => $job)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $job->veh_type ?? 'N/A' }}</td>
                                                    <td>{{ $job->job_location }}</td>
                                                    <td>{{ $job->aggrement ?? 'N/A' }}</td>
                                                    <td>{{ $job->status }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($job->join_date)->format('d-m-Y') }}</td>
                                                    <td>{{ $job->min_salary . ' - ' . $job->max_salary }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7">No Fulltime Jobs Found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Hired" role="tabpanel">
                            {{-- Hired Details --}}
                            <div class="card mb-3">
                                <h5 class="p-3 card-title mb-0 border-bottom"></h5>
                                <div class="card-body p-0">
                                    <table class="table mb-0 table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Vehicle Type</th>
                                                <th>Location</th>
                                                <th>Job Type</th>
                                                <th>Created</th>
                                                <th>Trip Code</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse($hire as $index => $hired)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $hired->name }}</td>
                                                    <td>{{ $hired->vehicle_type }}</td>
                                                    <td>{{ $hired->source == 'act' ? $hired->st_loc : $hired->job_location }}
                                                    </td>
                                                    <td>{{ $hired->source == 'act' ? 'Acting' : 'Permanent' }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($hired->created_at)->format('d-m-Y') }}
                                                    </td>
                                                    <td>{{ $hired->t_code ?? 0 }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7">No Hired Drivers Found.</td>
                                                </tr>
                                            @endforelse
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
                                                <th>Payments Mode</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($subscriptions as $index => $sub)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $sub->plan }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($sub->created_at)->format('d/m/Y') }}</td>
                                                    <td>Online</td>
                                                    <td>
                                                        @if ($sub->paid_sts === 'success')
                                                            <span class="badge badge-success-light">Received</span>
                                                        @else
                                                            <span class="badge badge-danger-light">Failed</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5">No Payment History Found.</td>
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

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profileImage').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Auto-uppercase for PAN and GST
        document.addEventListener('DOMContentLoaded', function() {
            const panInput = document.querySelector('input[name="pan"]');
            const gstInput = document.querySelector('input[name="gst"]');
            
            if (panInput) {
                panInput.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
            }
            
            if (gstInput) {
                gstInput.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
            }
        });
    </script>
@endsection()
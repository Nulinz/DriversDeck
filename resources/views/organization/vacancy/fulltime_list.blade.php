@extends('organization.layouts.app')

@section('content')
    @if(isset($subscriptionLimit) && $subscriptionLimit)
        <div class="alert alert-warning mb-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <i class="fa fa-exclamation-triangle me-2"></i>
                    <strong>Subscription Limit:</strong> You have reached the maximum limit of 5 permanent drivers for your
                    current plan.
                    <br>
                    <small>Current hired drivers: {{ $currentHiredCount }}/5</small>
                </div>
                <div>
                    <a href="{{ route('auth.register_subscription') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-arrow-up me-1"></i>
                        Upgrade Plan
                    </a>
                </div>
            </div>
        </div>
    @endif
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row mb-xl-3 mb-2">
                <div class="d-none d-sm-block col-auto">
                    <h3><strong>Fulltime Applied List</strong></h3>
                </div>
            </div>
            <div class="row dt-row">
                <div class="col-md-4 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            @php
                                // Check if any application status is 'cancelled'
                                $hasCancelled = collect($appliedListFullTime)->contains(function ($item) {
                                    return ($item['ap_status'] ?? '') === 'Hired';
                                });
                            @endphp
                            <h5 class="fs-4 mb-3">Basic Information</h5>
                            @if (!$hasCancelled)
                                <a href="{{ route('organization.fulltime.status.cancel', ['id' => $selectedJob->job_id]) }}"
                                    class="badge bg-danger">Cancel</a>
                            @endif
                            <div class="d-flex align-items-center justify-content-between mb-0_8">
                                <h5 class="fs-6 text-muted fw-bold mb-0">Job Type</h5>
                                <p class="fs-6 text-dark fw-bold mb-0">Full Time</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-0_8">
                                <h6 class="text-muted fw-bold mb-0">Vehicle Type</h6>
                                <p class="text-dark fw-bold mb-0">{{ $selectedJob->veh_type }}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-0_8">
                                <h6 class="text-muted fw-bold mb-0">Experience</h6>
                                <p class="text-dark fw-bold mb-0">{{ $selectedJob->experience }}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-0_8">
                                <h6 class="text-muted fw-bold mb-0">Job Location</h6>
                                <p class="text-dark fw-bold mb-0">{{ $selectedJob->job_location }}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-0_8">
                                <h6 class="text-muted fw-bold mb-0">Join Date</h6>
                                <p class="text-dark fw-bold mb-0">
                                    {{ \Carbon\Carbon::parse($selectedJob->join_date)->format('d-m-Y') }}
                                </p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-0_8">
                                <h6 class="text-muted fw-bold mb-0">Salary</h6>
                                <p class="text-dark fw-bold mb-0">{{ $selectedJob->salary }}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-0_8">
                                <h6 class="text-muted fw-bold mb-0">Accommodation</h6>
                                <p class="text-dark fw-bold mb-0">{{ $selectedJob->accommodation }}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-0_8">
                                <h6 class="text-muted fw-bold mb-0">Food</h6>
                                <p class="text-dark fw-bold mb-0">{{ $selectedJob->food }}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="text-muted fw-bold mb-0">Agreement</h6>
                                <p class="text-dark fw-bold mb-0">{{ $selectedJob->aggrement }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8 col-xl-9">
                    <h5 class="card-title text-dark border-bottom mb-0 pb-3">Applied List</h5>
                    <div class="card">
                        <div class="card-body">
                            <table id="datatables-reponsive" class="table-striped table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        {{-- <th>Experience</th> --}}
                                        <th>Register Date</th>
                                        <!-- <th>Contact Number</th>
                                            <th>Location</th>
                                            <th>Action</th> -->
                                        <th>License type</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($appliedListFullTime as $index => $apply)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <img src="{{ asset($apply['image'] ?? '/assets/images/avatar.png') }}"
                                                    width="40" height="40" class="rounded-circle me-2" alt="Avatar">
                                                {{ $apply['driver_name'] }}
                                            </td>
                                            {{-- <td>{{ $apply['experience'] }}</td> --}}
                                            <td>{{ $apply['created_at'] }}</td>
                                            <td>{{ $apply['license_type'] }}</td>
                                            <!-- <td>
                                                @if ($selectedJob->status == 'approve')
                                                    @if (!$apply['conflict'])
                                                        @if (!$apply['subscription_limit_reached'])
                                                            <a data-bs-toggle="modal" data-bs-target="#statusModal{{ $apply['id'] }}">
                                                                <i class="fs-4 text-dark fa fa-fw fa-edit me-3"></i>
                                                            </a>
                                                            <a
                                                                href="{{ route('organization.hired.ft_driver_profile', ['id' => $apply['driver']['id'] ?? '']) }}">
                                                                <i class="fs-4 text-dark fa fa-external-link-alt"></i>
                                                            </a>
                                                        @else
                                                            <span class="fs-4 text-warning me-3"
                                                                title="Subscription limit reached (5 drivers maximum for your plan)">
                                                                <i class="fa fa-exclamation-triangle"></i>
                                                            </span>
                                                            <span class="badge bg-warning text-dark">Limit Reached</span>
                                                        @endif
                                                    @else
                                                        <span class="fs-4 text-dark me-3">&times;</span>
                                                    @endif
                                                @endif
                                            </td> -->
                                            <td>
                                                <span
                                                    class="badge bg-{{ $apply['ap_status'] == 'Hired' ? 'success' : 'danger' }}">
                                                    {{ $apply['ap_status'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Modals -->
        @foreach ($appliedListFullTime as $apply)
            <div class="modal fade" id="statusModal{{ $apply['id'] }}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="fs-4 fw-bold modal-title">Update Status</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('organization.fulltime.status.update', $apply['id']) }}">
                                @csrf
                                <div class="row mx-4">
                                    <div class="col-12">
                                        <button type="submit" name="action" value="Hired"
                                            class="btn btn-primary w-100">Hired</button>
                                    </div>
                                    {{-- <div class="col-12 mt-3">
                                        <button type="submit" name="action" value="Reject"
                                            class="btn btn-secondary w-100">Reject</button>
                                    </div> --}}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </main>

    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Datatables Responsive
            $("#datatables-reponsive").DataTable({
                responsive: true,
                "ordering": false,
                "lengthMenu": [
                    [100, "All", 50, 25],
                    [100, "All", 50, 25]
                ]
            });
        });
    </script>
@endsection
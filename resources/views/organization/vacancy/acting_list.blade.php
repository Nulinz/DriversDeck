@extends('organization.layouts.app')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row mb-xl-3 mb-2">
                <div class="d-none d-sm-block col-auto">
                    <h3><strong>Acting Applied List</strong></h3>
                </div>
            </div>

            <div class="row dt-row">
                <div class="col-md-4 col-xl-3">
                    <div class="card">
                        <div class="card-body">

                            @php
                                $status = $selectedJob->status ?? '';
                                $hasPendingOrHired = in_array($status, ['Hired']);
                                // \Log::info($status); // Optional debug log
                            @endphp

                            @if ($hasPendingOrHired)
                                {{-- <a href="{{ route('act_trip_cancel_owner', ['trip_id' => $selectedJob->id]) }}"
                                    class="badge bg-danger">Cancel</a> --}}
                                <a class="badge bg-danger" data-bs-toggle="modal" data-bs-target="#Cancel"><i
                                        class="fa fa-fw fa-plus"></i>Cancel</a>
                            @endif
                            <h5 class="fs-4 mb-3">Basic Information</h5>
                            <div class="d-flex align-items-center justify-content-between mb-0_8">
                                <h5 class="fs-6 text-muted fw-bold mb-0">Job Type</h5>
                                <p class="fs-6 text-dark fw-bold mb-0">Acting Driver</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-0_8">
                                <h6 class="text-muted fw-bold mb-0">Start Date</h6>
                                <p class="text-dark fw-bold mb-0">
                                    {{ $selectedJob->st_date ? \Carbon\Carbon::parse($selectedJob->st_date)->format('d-m-Y') : '' }}
                                </p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-0_8">
                                <h6 class="text-muted fw-bold mb-0">End Date</h6>
                                <p class="text-dark fw-bold mb-0">
                                    {{ $selectedJob->end_date ? \Carbon\Carbon::parse($selectedJob->end_date)->format('d-m-Y') : '' }}
                                </p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-0_8">
                                <h6 class="text-muted fw-bold mb-0">Start Time</h6>
                                <p class="text-dark fw-bold mb-0">
                                    {{ $selectedJob->st_time ? \Carbon\Carbon::parse($selectedJob->st_time)->format('h:i') : '' }}
                                </p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-0_8">
                                <h6 class="text-muted fw-bold mb-0">No of Days</h6>
                                <p class="text-dark fw-bold mb-0">{{ $selectedJob->no_days }}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-0_8">
                                <h6 class="text-muted fw-bold mb-0">Vehicle Type</h6>
                                <p class="text-dark fw-bold mb-0">{{ $selectedJob->veh_type }}</p>
                            </div>
                            <div class="d-flex align-items-start justify-content-between mb-0_8 gap-5">
                                <h6 class="text-muted fw-bold mb-0">From</h6>
                                <p class="text-dark fw-bold mb-0 text-end">{{ $selectedJob->st_loc }}</p>
                            </div>
                            <div class="d-flex align-items-start justify-content-between mb-0_8 gap-5">
                                <h6 class="text-muted fw-bold mb-0">To</h6>
                                <p class="text-dark fw-bold mb-0 text-end">{{ $selectedJob->st_dest }}</p>
                            </div>
                            <div class="d-flex align-items-start justify-content-between mb-0_8 gap-5">
                                <h6 class="text-muted fw-bold mb-0">Trip Code</h6>
                                <p class="text-dark fw-bold mb-0 text-end">{{ $selectedJob->t_code }}</p>
                            </div>
                            <div class="d-flex align-items-start justify-content-between mb-0_8 gap-5">

                                @if ($selectedJob->status == 'End')
                                    <a class="btn btn-md btn-primary" data-bs-toggle="modal" data-bs-target="#feed_pop_at"><i
                                            class="fa fa-fw fa-plus"></i> Add Feedback</a>
                                @endif
                                <!-- BEGIN primary modal -->
                                <div class="modal model-sm fade" id="feed_pop_at" tabindex="-1" role="dialog"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                        <div class="modal-content">

                                            <div class="modal-body">
                                                <img src="" alt="">
                                                <h5 class="fs-4 mb-0 fs-3 text-center fw-bold modal-title">Ratings!</h5>
                                                <p class="mt-2 text-center mb-0">Looks like you haven’t added anything to
                                                    your <br> cart yet</p>
                                                <form action="{{ route('organization.feedback.add') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="trip_id" value={{ $selectedJob->id }}>

                                                    <div class="col-md-8 mx-auto">
                                                        <div class="star-rating">
                                                            <input type="radio" id="star5" name="rating" value="5"><label
                                                                for="star5">★</label>
                                                            <input type="radio" id="star4" name="rating" value="4"><label
                                                                for="star4">★</label>
                                                            <input type="radio" id="star3" name="rating" value="3"><label
                                                                for="star3">★</label>
                                                            <input type="radio" id="star2" name="rating" value="2"><label
                                                                for="star2">★</label>
                                                            <input type="radio" id="star1" name="rating" value="1"><label
                                                                for="star1">★</label>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 col-md-12">
                                                        <label class="form-label fw-bold">Remarks</label>
                                                        <textarea class="form-control" name="remarks" id=""
                                                            rows="4"></textarea>
                                                    </div>
                                                    <div class="row g-2">
                                                        <div class="col-6">
                                                            <button type="button" class="btn btn-secondary w-100"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                        </div>
                                                        <div class="col-6">
                                                            <input type="submit" class="btn btn-primary w-100" name=""
                                                                value="Submit">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END primary modal -->

                                @if ($selectedJob->status == 'End' || $selectedJob->status == 'Start')
                                    <a class="btn btn-md btn-primary" data-bs-toggle="modal" data-bs-target="#reason_pop_at"><i
                                            class="fa fa-fw fa-plus"></i> Report</a>
                                @endif

                                <!-- BEGIN primary modal -->
                                <div class="modal model-sm fade" id="reason_pop_at" tabindex="-1" role="dialog"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                        <div class="modal-content">

                                            <div class="modal-body">
                                                <img src="" alt="">
                                                {{-- <h5 class="fs-4 mb-0 fs-3 text-center fw-bold modal-title">Ratings!
                                                </h5>
                                                <p class="mt-2 text-center mb-0">Looks like you haven’t added anything to
                                                    your <br> cart yet</p> --}}
                                                <form action="{{ route('organization.report.add') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="trip_id" value={{ $selectedJob->id }}>

                                                    <div class="col-md-8 mx-auto">
                                                        <div class="star-rating">
                                                            <select class="form-select form-select-lg" name="reason" id="">
                                                                <option value="" selected disabled>Select Option
                                                                </option>
                                                                <option value="Driver using phone while driving">Driver
                                                                    using phone while driving</option>
                                                                <option value="Driver being rude or threatening">Driver
                                                                    being rude or threatening</option>
                                                                <option value="Wrong route taken">Wrong route taken
                                                                </option>
                                                                <option value="Unscheduled stop">Unscheduled stop</option>
                                                                <option value="Vehicle Handling is poor">Vehicle Handling
                                                                    is poor</option>
                                                                <option value="Asked for extra payment">Asked for extra
                                                                    payment</option>
                                                                <option value="Driver not following instructions">Driver
                                                                    not following instructions</option>
                                                                <option value="Other (specify)">Other (specify)</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 col-md-12">
                                                        <label class="form-label fw-bold">Remarks</label>
                                                        <textarea class="form-control" name="remarks" rows="4"></textarea>
                                                    </div>
                                                    <div class="row g-2">
                                                        <div class="col-6">
                                                            <button type="button" class="btn btn-secondary w-100"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                        </div>
                                                        <div class="col-6">
                                                            <input type="submit" class="btn btn-primary w-100" name=""
                                                                value="Submit">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END primary modal -->

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
                                        <th>Register Date</th>
                                        <th>License type</th>
                                        <th>View Details</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($appliedListActing as $index => $apply)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                {{-- <img src="{{ asset('assets/images/avatar.png') }}" width="40" height="40"
                                                    class="rounded-circle me-2" alt="Avatar"> --}}
                                                {{ $apply['driver_name'] }}
                                            </td>
                                            <td>{{ $apply['created_at'] }}</td>
                                            <td>{{ $apply['license_type'] }}</td>
                                            {{-- ✅ VIEW DETAILS --}}
                                            <td>
                                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#viewModal{{ $apply['id'] }}">
                                                    Click
                                                </button>
                                            </td>
                                            <!-- <td>
                                                @if ($selectedJob->status == 'pending')
                                                    @if (!$apply['driver_conflict'])
                                                        <a data-bs-toggle="modal" data-bs-target="#statusModal{{ $apply['id'] }}">
                                                            <i class="fs-4 text-dark fa fa-fw fa-edit me-3"></i>
                                                        </a>
                                                        <a
                                                            href="{{ route('organization.hired.at_driver_profile', ['id' => $apply['driver']['id'] ?? '']) }}">
                                                            <i class="fs-4 text-dark fa fa-external-link-alt"></i>
                                                        </a>
                                                    @else
                                                        <span class="fs-4 text-dark me-3">&times;</span>
                                                    @endif
                                                @endif
                                            </td> -->
                                            <td>
                                                <span
                                                    class="badge bg-{{ $apply['act_status'] == 'Hired' ? 'success' : 'danger' }}">
                                                    {{ $apply['act_status'] }}
                                                </span>

                                            </td>
                                        </tr>

                                        <!-- Status Modal for each application -->
                                        <div class="modal fade" id="statusModal{{ $apply['id'] }}" tabindex="-1" role="dialog"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="fs-4 fw-bold modal-title">Update Status</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST"
                                                            action="{{ route('organization.acting.status.update', $apply['id']) }}">
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
                                </tbody>
                            </table>
                            @foreach ($appliedListActing as $apply)
<div class="modal fade" id="viewModal{{ $apply['id'] }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Contact Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center">
                <p class="text-muted mb-2">
                    For further process, please contact admin
                </p>
                <p><b>Drivers Deck</b></p>

                <h5 class="fw-bold text-dark">
                    <i class="fa fa-phone me-2"></i>
                     9600166472
                </h5>

                <a href="tel:+919876543210"
                   class="btn btn-success btn-sm mt-3">
                    <i class="fa fa-phone"></i> Call Admin
                </a>
            </div>

        </div>
    </div>
</div>
@endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
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


    <!-- BEGIN primary modal -->
    <div class="modal model-sm fade" id="Cancel" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">

                <div class="modal-body">
                    <img src="" alt="">
                    {{-- <h5 class="fs-4 mb-0 fs-3 text-center fw-bold modal-title">Ratings!</h5>
                    <p class="mt-2 text-center mb-0">Looks like you haven’t added anything to
                        your <br> cart yet</p> --}}
                    <form action="{{ route('act_trip_cancel_owner') }}" method="post">
                        @csrf
                        <input type="hidden" name="trip_id" value={{ $selectedJob->id }}>
                        <input type="hidden" name="status" value="Cancel">

                        <div class="col-md-8 mx-auto">
                            <div class="star-rating">
                                <label class="form-label fw-bold">Reason</label>
                                <select class="form-select form-select-lg" name="reason" id="">
                                    <option value="" selected disabled>Select Option
                                    </option>
                                    <option>Change in travel plans</option>
                                    <option>Booked by mistake</option>
                                    <option>Driver taking too long</option>
                                    <option>Found alternative transport</option>
                                    <option>Trip scheduled at wrong time</option>
                                    <option>Price too high</option>
                                    <option>Driver not moving</option>
                                    <option>Driver unreachable</option>
                                    <option>Driver asked to cancel</option>
                                    <option>Feeling unsafe with driver</option>
                                    <option>Poor driver ratings</option>
                                    <option>Wrong vehicle assigned</option>
                                    <option>Not ready to travel</option>
                                    <option>Location entered incorrectly</option>
                                    <option value="Other">Other (specify)</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label fw-bold">Remarks</label>
                            <textarea class="form-control" name="remarks" rows="4"></textarea>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="button" class="btn btn-secondary w-100"
                                    data-bs-dismiss="modal">Cancel</button>
                            </div>
                            <div class="col-6">
                                <input type="submit" class="btn btn-primary w-100" name="" value="Submit">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END primary modal -->
@endsection
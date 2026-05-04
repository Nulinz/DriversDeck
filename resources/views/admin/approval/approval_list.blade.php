@extends('admin.layouts.app')
@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row mb-2 mb-xl-3">
                <div class="col-auto d-none d-sm-block">
                    <h3><strong>Profile Approval List</strong></h3>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-xl-12">
                    <div class="card mb-3">
                        <h5 class="p-3 card-title mb-0 border-bottom">Acting/Permanent Driver</h5>
                        <div class="card-body">
                            <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Registration Type</th>
                                        <th>Contact Number</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Subscription</th>
                                        <th>Transaction ID</th>
                                        <th>Payment Proof</th>
                                        <th>Registered</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="">
                                    @foreach ($concat as $index => $driver)
                                        @php
                                            $route = in_array($driver->type, ['acting', 'permanent'])
                                                ? 'admin.candidate.profile'
                                                : ($driver->type === 'owner'
                                                    ? 'admin.corprate.owner_profile'
                                                    : 'admin.corprate.corprate_profile');
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><a target="_blank"
                                                    href="{{ route($route, ['id' => $driver->id]) }}">{{ $driver->name }}</a>
                                            </td>
                                            <td>{{ $driver->type }}</td>
                                            <td>{{ in_array($driver->type, ['acting', 'permanent']) ? $driver->phone : $driver->contact }}
                                            </td>
                                            <td>{{ $driver->loc ?? 'No location' }}</td>
                                            <td>
                                                <span
                                                    class="{{ $driver->status === 'approved' ? 'badge bg-success' : ($driver->status === 'rejected' ? 'badge bg-danger' : 'badge bg-warning') }}">
                                                    {{ ucfirst($driver->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="{{ $driver->subscription_status === 'Paid' ? 'badge bg-success' : 'badge bg-warning' }}">
                                                    {{ $driver->subscription_status }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($driver->transaction_id)
                                                    <small class="text-primary">{{ $driver->transaction_id }}</small>
                                                @else
                                                    <small class="text-muted">N/A</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($driver->payment_screenshot)
                                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                        data-bs-target="#screenshotModal{{ $driver->id }}">
                                                        View
                                                    </button>

                                                    <div class="modal fade" id="screenshotModal{{ $driver->id }}" tabindex="-1"
                                                        aria-labelledby="screenshotModalLabel{{ $driver->id }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-sm modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="screenshotModalLabel{{ $driver->id }}">
                                                                        Payment Screenshot - {{ $driver->name }}
                                                                    </h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body text-center">
                                                                    @php
                                                                        $extension = pathinfo($driver->payment_screenshot, PATHINFO_EXTENSION);
                                                                    @endphp

                                                                    @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
                                                                        <img src="{{ asset('public/' . $driver->payment_screenshot) }}"
                                                                            alt="Payment Screenshot" class="img-fluid"
                                                                            style="max-height: 70vh;">
                                                                    @elseif(strtolower($extension) === 'pdf')
                                                                        <iframe src="{{ asset($driver->payment_screenshot) }}"
                                                                            style="width: 100%; height: 70vh;" frameborder="0">
                                                                        </iframe>
                                                                    @else
                                                                        <p class="text-muted">File format not supported for preview</p>
                                                                        <a href="{{ asset($driver->payment_screenshot) }}"
                                                                            target="_blank" class="btn btn-primary">
                                                                            Open in New Tab
                                                                        </a>
                                                                    @endif

                                                                    @if($driver->transaction_id || $driver->created_at)
                                                                        <div class="mt-3 p-3 bg-light rounded">
                                                                            @if($driver->transaction_id)
                                                                                <div class="mb-2">
                                                                                    <strong>Transaction ID:</strong>
                                                                                    {{ $driver->transaction_id }}
                                                                                </div>
                                                                            @endif
                                                                            @if($driver->created_at)
                                                                                <div>
                                                                                    <strong>Transaction Date:</strong>
                                                                                    {{ Carbon\Carbon::parse($driver->created_at)->format('d-m-Y') }}
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <small class="text-muted">No proof</small>
                                                @endif
                                            </td>
                                            <td>{{ Carbon\Carbon::parse($driver->created_at)->format('d-m-Y') }}</td>
                                            <td>
                                                <div class="d-flex gap-2 flex-wrap">
                                                    @if($driver->status === 'pending')
                                                        {{-- Approve Button --}}
                                                        <form method="POST"
                                                            action="{{ route('admin.handle.approval', ['type' => $driver->type, 'id' => $driver->id, 'action' => 'approve']) }}"
                                                            onsubmit="return confirm('approve this {{ $driver->type }}?');">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                                        </form>

                                                        @if(in_array($driver->type, ['acting', 'permanent']))
                                                            {{-- For Acting/Permanent: Reject Button with Modal (Reason Required) --}}
                                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                                data-bs-target="#rejectModal{{ $driver->id }}">
                                                                Reject
                                                            </button>

                                                            {{-- For Acting/Permanent: Pending Button with Modal (Reason Required) --}}
                                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                                data-bs-target="#pendingModal{{ $driver->id }}">
                                                                Keep Pending
                                                            </button>
                                                        @else
                                                            {{-- For Corporate/Owner: Simple Reject Button (No Reason Required) --}}
                                                            <form method="POST"
                                                                action="{{ route('admin.handle.approval', ['type' => $driver->type, 'id' => $driver->id, 'action' => 'reject']) }}"
                                                                onsubmit="return confirm('Are you sure you want to reject this {{ $driver->type }}?');">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                                            </form>
                                                        @endif

                                                    @elseif($driver->type === 'acting' && $driver->status === 'approved' && $driver->subscription === 'progress')
                                                        {{-- Subscription Approval Buttons --}}
                                                        <form method="POST"
                                                            action="{{ route('admin.handle.subscription', ['type' => $driver->type, 'id' => $driver->id, 'action' => 'approve']) }}"
                                                            onsubmit="return confirm('Are you sure you want to approve this subscription payment?');">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                                        </form>

                                                        <form method="POST"
                                                            action="{{ route('admin.handle.subscription', ['type' => $driver->type, 'id' => $driver->id, 'action' => 'reject']) }}"
                                                            onsubmit="return confirm('Are you sure you want to reject this subscription payment?');">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                                        </form>
                                                    @else
                                                        <span class="badge bg-secondary">No Action</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{-- Reject Modal --}}
                            @foreach($drivers as $driver)
                                {{-- Reject Modal --}}
                                <div class="modal fade" id="rejectModal{{ $driver->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('admin.handle.approval.reason') }}">
                                                @csrf
                                                <input type="hidden" name="type" value="{{ $driver->type }}">
                                                <input type="hidden" name="id" value="{{ $driver->id }}">
                                                <input type="hidden" name="action" value="reject">

                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject
                                                        {{ ucfirst($driver->type) }} Driver
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Reason for Rejection <span
                                                                class="text-danger">*</span></label>
                                                        <textarea name="reason" class="form-control" rows="4"
                                                            placeholder="Enter reason for rejection (minimum 10 characters)"
                                                            required minlength="10" maxlength="500"></textarea>
                                                        <small class="text-muted">Please provide a clear
                                                            reason (10-500 characters)</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Submit
                                                        Rejection</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                {{-- Pending Modal --}}
                                <div class="modal fade" id="pendingModal{{ $driver->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('admin.handle.approval.reason') }}">
                                                @csrf
                                                <input type="hidden" name="type" value="{{ $driver->type }}">
                                                <input type="hidden" name="id" value="{{ $driver->id }}">
                                                <input type="hidden" name="action" value="pending">

                                                <div class="modal-header">
                                                    <h5 class="modal-title">Keep
                                                        {{ ucfirst($driver->type) }} Driver as Pending
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Reason for Keeping Pending
                                                            <span class="text-danger">*</span></label>
                                                        <textarea name="reason" class="form-control" rows="4"
                                                            placeholder="Enter reason for keeping pending (minimum 10 characters)"
                                                            required minlength="10" maxlength="500"></textarea>
                                                        <small class="text-muted">Please provide a clear
                                                            reason (10-500 characters)</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-warning">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach


                            {{-- Pending Modal --}}
                            <div class="modal fade" id="pendingModal{{ $driver->id }}" tabindex="-1" aria-hidden="true">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            $("#datatables-reponsive").DataTable({
                responsive: true,
                ordering: false,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    ["5", "10", "25", "50", "All"]
                ]
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            $("#datatables-reponsive-2").DataTable({
                responsive: true,
                ordering: false,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    ["5", "10", "25", "50", "All"]
                ]
            });
        });


        document.addEventListener('submit', function () {
            const modals = document.querySelectorAll('.modal.show');
            modals.forEach(modal => {
                const instance = bootstrap.Modal.getInstance(modal);
                if (instance) {
                    instance.hide();
                }
            });
        });
    </script>
@endsection
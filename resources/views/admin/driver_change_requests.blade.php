@extends('admin.layouts.app')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row mb-2 mb-xl-3">
                <div class="col-auto d-none d-sm-block">
                    <h3><strong>Driver Type Change Requests</strong></h3>
                </div>
            </div>

            <!-- Navigation Sections -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Section Navigation -->
                            <div class="d-flex mb-4">
                                <button class="btn btn-link text-decoration-none fw-bold me-4 section-toggle active" 
                                        data-target="pending-section" style="color: #333; border-bottom: 2px solid #007bff;">
                                    Pending Requests
                                </button>
                                <button class="btn btn-link text-decoration-none fw-bold section-toggle" 
                                        data-target="approved-section" style="color: #666;">
                                    Approved
                                </button>
                                <button class="btn btn-link text-decoration-none fw-bold section-toggle" 
                                        data-target="rejected-section" style="color: #666;">
                                    Rejected
                                </button>
                            </div>

                            <!-- Pending Requests Section -->
                            <div id="pending-section" class="request-section">
                                <h5 class="text-muted mb-3">Pending Type Change Requests</h5>
                                <table id="pending-table" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Driver Name</th>
                                            <th>Previous Type</th>
                                            <th>Requested Type</th>
                                            <th>Request Date</th>
                                            <th>Contact</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requests->where('request_status', 'pending') as $request)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $request->driver->name ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $request->previous_type }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $request->change_type_to }}</span>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($request->created_at)->format('d-m-Y') }}</td>
                                                <td>{{ $request->driver->phone ?? 'N/A' }}</td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <form action="{{ route('admin.approveDriverTypeChange', $request->id) }}" method="POST" style="display:inline-block;">
                                                            @csrf
                                                            <input type="hidden" name="action" value="approve">
                                                            <button type="submit" class="btn btn-success btn-sm" 
                                                                    onclick="return confirm('Are you sure you want to approve this type change request?')">
                                                                <i class="fa fa-check"></i> Approve
                                                            </button>
                                                        </form>
                                                        
                                                        <form action="{{ route('admin.approveDriverTypeChange', $request->id) }}" method="POST" style="display:inline-block;">
                                                            @csrf
                                                            <input type="hidden" name="action" value="reject">
                                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                                    onclick="return confirm('Are you sure you want to reject this type change request?')">
                                                                <i class="fa fa-times"></i> Reject
                                                            </button>
                                                        </form>
                                                        
                                                        @if($request->driver)
                                                        <a href="{{ route('admin.candidate.profile', ['id' => $request->driver->id]) }}" class="btn btn-outline-dark btn-sm">
                                                            <i class="fa fa-external-link-alt"></i>
                                                        </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Approved Requests Section -->
                            <div id="approved-section" class="request-section" style="display: none;">
                                <h5 class="text-muted mb-3">Approved Type Change Requests</h5>
                                <table id="approved-table" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Driver Name</th>
                                            <th>Previous Type</th>
                                            <th>Current Type</th>
                                            <th>Approved Date</th>
                                            <th>Contact</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requests->where('request_status', 'approved') as $request)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $request->driver->name ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-light text-dark">{{ $request->previous_type }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success">{{ $request->change_type_to }}</span>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($request->updated_at)->format('d-m-Y') }}</td>
                                                <td>{{ $request->driver->phone ?? 'N/A' }}</td>
                                                <td>
                                                    @if($request->driver)
                                                    <a href="{{ route('admin.candidate.profile', ['id' => $request->driver->id]) }}">
                                                        <i class="fs-4 text-dark fa fa-external-link-alt"></i>
                                                    </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Rejected Requests Section -->
                            <div id="rejected-section" class="request-section" style="display: none;">
                                <h5 class="text-muted mb-3">Rejected Type Change Requests</h5>
                                <table id="rejected-table" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Driver Name</th>
                                            <th>Previous Type</th>
                                            <th>Requested Type</th>
                                            <th>Rejected Date</th>
                                            <th>Contact</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requests->where('request_status', 'rejected') as $request)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $request->driver->name ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $request->previous_type }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-danger">{{ $request->change_type_to }}</span>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($request->updated_at)->format('d-m-Y') }}</td>
                                                <td>{{ $request->driver->phone ?? 'N/A' }}</td>
                                                <td>
                                                    @if($request->driver)
                                                    <a href="{{ route('admin.candidate.profile', ['id' => $request->driver->id]) }}">
                                                        <i class="fs-4 text-dark fa fa-external-link-alt"></i>
                                                    </a>
                                                    @endif
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
        </div>
    </main>

    <style>
        .section-toggle.active {
            color: #333 !important;
            border-bottom: 2px solid #007bff !important;
        }
        
        .section-toggle:not(.active) {
            color: #666 !important;
            border-bottom: 2px solid transparent !important;
        }
        
        .section-toggle:hover {
            color: #007bff !important;
        }
        
        .request-section {
            transition: all 0.3s ease;
        }
        
        .badge {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
        }

        .btn-sm {
            font-size: 0.875rem;
            padding: 0.25rem 0.5rem;
        }

        .d-flex.gap-1 {
            gap: 0.25rem;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Initialize DataTables for all sections
            $("#pending-table").DataTable({
                responsive: true,
                ordering: false,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    ["5", "10", "25", "50", "All"]
                ]
            });

            $("#approved-table").DataTable({
                responsive: true,
                ordering: false,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    ["5", "10", "25", "50", "All"]
                ]
            });

            $("#rejected-table").DataTable({
                responsive: true,
                ordering: false,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    ["5", "10", "25", "50", "All"]
                ]
            });

            // Section toggle functionality
            document.querySelectorAll('.section-toggle').forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    document.querySelectorAll('.section-toggle').forEach(btn => {
                        btn.classList.remove('active');
                        btn.style.color = '#666';
                        btn.style.borderBottom = '2px solid transparent';
                    });

                    // Add active class to clicked button
                    this.classList.add('active');
                    this.style.color = '#333';
                    this.style.borderBottom = '2px solid #007bff';

                    // Hide all sections
                    document.querySelectorAll('.request-section').forEach(section => {
                        section.style.display = 'none';
                    });

                    // Show target section
                    const targetSection = this.getAttribute('data-target');
                    document.getElementById(targetSection).style.display = 'block';
                });
            });
        });
    </script>
@endsection
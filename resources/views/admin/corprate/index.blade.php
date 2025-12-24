{{-- Create this file at: resources/views/admin/corprate/index.blade.php --}}

@extends('admin.layouts.app')
@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<main class="content">
    <div class="container-fluid p-0">
        <div class="row mb-2 mb-xl-3">
            <div class="col-auto d-none d-sm-block">
                <h3><strong>Corporate Management</strong></h3>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="card mb-3">
                    <div class="m-3 mb-0 pb-3 d-flex align-items-center justify-content-between border-bottom">
                        <h5 class="card-title mb-0">Corporate List</h5>
                        <a class="btn btn-primary" href="{{ route('admin.corprate.corprate_create') }}">
                            <i class="align-middle fa fa-fw fa-plus"></i>Add Corporate
                        </a>
                    </div>
                    <div class="card-body">
                        <table id="datatables-responsive" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Company Name</th>
                                    <th>Type</th>
                                    <th>Contact</th>
                                    <th>Email</th>
                                    <th>Contact Person</th>
                                    <th>Vehicles</th>
                                    <th>Drivers</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($corporates as $corporate)
                                    <tr data-id="{{ $corporate->id }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $corporate->name ?? 'N/A' }}</td>
                                        <td>{{ $corporate->c_type ?? 'N/A' }}</td>
                                        <td>{{ $corporate->contact ?? 'N/A' }}</td>
                                        <td>{{ $corporate->mail ?? 'N/A' }}</td>
                                        <td>{{ $corporate->c_name ?? 'N/A' }}</td>
                                        <td>{{ $corporate->no_veh ?? '0' }}</td>
                                        <td>{{ $corporate->no_driver ?? '0' }}</td>

                                    <td>
                                        <span class="{{ ($corporate->active_status ?? 'inactive') == 'active' ? 'text-success' : 'text-danger' }}">
                                            {{ ($corporate->active_status ?? 'inactive') == 'active' ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                          <a href="{{ route('admin.corprate.corprate_profile', $corporate->id) }}" 
                                           class="me-2"
                                           data-bs-toggle="tooltip"
                                           data-bs-title="View Profile">
                                            <i class="fa fa-user text-info align-middle"></i>
                                        </a>
                                        @if (($corporate->active_status ?? 'inactive') == 'active')
                                            {{-- If Active → show green (active) with "make inactive" option --}}
                                            <a href="javascript:void(0);" class="toggle-status"
                                                data-id="{{ $corporate->id }}" data-status="inactive">
                                                <i class="fa fa-check-circle text-success align-middle me-2"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-title="Click to make Inactive"></i>
                                            </a>
                                        @else
                                            {{-- If Inactive → show red (inactive) with "make active" option --}}
                                            <a href="javascript:void(0);" class="toggle-status"
                                                data-id="{{ $corporate->id }}" data-status="active">
                                                <i class="fa fa-times-circle text-danger align-middle me-2"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-title="Click to make Active"></i>
                                            </a>
                                        @endif
                                    </td>


                                    </tr>
                                @empty
                                  
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize DataTable
        $("#datatables-responsive").DataTable({
            responsive: true,
            ordering: false,
            lengthMenu: [
                [5, 10, 25, 50, -1],
                ["5", "10", "25", "50", "All"]
            ]
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Status toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        const icons = document.querySelectorAll('.toggle-status');
        icons.forEach(icon => {
            icon.addEventListener('click', function() {
                var con = confirm('Are you sure you want to update the status?');

                if (!con) {
                    return; // If user cancels, do nothing
                }

                const corporateId = this.getAttribute('data-id');
                const newStatus = this.getAttribute('data-status');

                fetch(`/admin/corporates1/status-toggle/${corporateId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            status: newStatus
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            location.reload(); // Reload to show updated icon and text
                        } else {
                            alert('Error updating status: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while updating status');
                    });
            });
        });
    });
</script>

@endsection
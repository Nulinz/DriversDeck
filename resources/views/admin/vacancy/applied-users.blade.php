@extends('admin.layouts.app')

@section('content')
<main class="content">
    <div class="container-fluid p-0">
        <!-- Header Section -->
        <div class="row mb-2 mb-xl-3">
            <div class="col-auto d-none d-sm-block">
                <h3><strong>Vacancy Applications</strong></h3>
            </div>
            <div class="col-auto ms-auto text-end mt-n1">
                <a href="{{ route('admin.vacancy.create') }}" class="btn btn-secondary">
                    <i class="align-middle" data-feather="arrow-left"></i> Back to Vacancies
                </a>
            </div>
        </div>

        <!-- Vacancy Information Card -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card border-primary">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <h5 class="mb-1">Vacancy Details</h5>
                                <p class="text-muted mb-0">Location: <strong>{{ $vacancy->location }}</strong></p>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted mb-0">Contact: <strong>{{ $vacancy->contact_number }}</strong></p>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted mb-0">Created: <strong>{{ $vacancy->created_at->format('d M, Y') }}</strong></p>
                            </div>
                            <div class="col-md-3">
                                <div class="text-end">
                                    <span class="badge bg-primary fs-6">{{ $appliedUsers->total() }} Total Applications</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Applied Users Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Applied Drivers</h5>
                        @if($appliedUsers->count() > 0)
                            <small class="text-muted">Showing {{ $appliedUsers->firstItem() }} to {{ $appliedUsers->lastItem() }} of {{ $appliedUsers->total() }} results</small>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($appliedUsers->count() > 0)
                            <div class="table-responsive">
                                <table id="table" class="table table-striped" style="width:100%">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Driver Name</th>
                                            <th>Phone Number</th>
                                            <th>Applied Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($appliedUsers as $index => $application)
                                        <tr id="row-{{ $application->id }}">
                                            <td>{{ $appliedUsers->firstItem() + $index }}</td>
                                            <td>{{ $application->driver->name ?? 'Driver not found' }}</td>
                                            <td>{{ $application->driver->phone ?? 'N/A' }}</td>
                                            <td>{{ $application->created_at->format('d M, Y h:i A') }}</td>
                                            
                                            <!-- Status Column -->
                                            <td>
                                                <span class="badge 
                                                    @if($application->status == 'Approved') bg-success 
                                                    @elseif($application->status == 'Rejected') bg-danger 
                                                    @else bg-secondary @endif">
                                                    {{ ucfirst($application->status) }}
                                                </span>
                                                @if($application->status == 'Rejected' && $application->rejection_reason)
                                                    <button class="btn btn-sm btn-link p-0 ms-1" 
                                                            data-bs-toggle="tooltip" 
                                                            title="{{ $application->rejection_reason }}">
                                                        <i data-feather="info"></i>
                                                    </button>
                                                @endif
                                            </td>

                                            <!-- Action Buttons (Only if status = pending) -->
                                            <td>
                                                @if($application->driver)
                                                    <a href="{{ route('admin.candidate.profile', $application->driver->id) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        View Profile
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif

                                                @if($application->status == 'Pending')
                                                    <button class="btn btn-sm btn-success update-status" 
                                                            data-id="{{ $application->id }}" 
                                                            data-status="approved">
                                                        Approve
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" 
                                                            data-id="{{ $application->id }}"
                                                            onclick="showRejectModal({{ $application->id }})">
                                                        Reject
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            @if($appliedUsers->hasPages())
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $appliedUsers->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="align-middle text-muted" data-feather="users" style="width: 64px; height: 64px;"></i>
                                </div>
                                <h5 class="text-muted">No Applications Yet</h5>
                                <p class="text-muted">This vacancy hasn't received any applications from drivers.</p>
                                <a href="{{ route('admin.vacancy.create') }}" class="btn btn-primary">
                                    <i class="align-middle" data-feather="arrow-left"></i> Back to Vacancies
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Rejection Reason Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Reject Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="rejectForm">
                    <input type="hidden" id="rejectApplicationId">
                    <div class="mb-3">
                        <label for="rejectionReason" class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea class="form-control" 
                                  id="rejectionReason" 
                                  rows="4" 
                                  placeholder="Please provide a reason for rejecting this application..."
                                  required></textarea>
                        <div class="invalid-feedback">
                            Please provide a rejection reason.
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="submitRejection()">Reject Application</button>
            </div>
        </div>
    </div>
</div>

<script>
// Handle Approve button
document.querySelectorAll('.update-status').forEach(button => {
    button.addEventListener('click', function () {
        let id = this.dataset.id;
        let status = this.dataset.status;

        fetch(`/admin/vacancys/${id}/update-status`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let row = document.getElementById(`row-${id}`);
                let statusCell = row.querySelector('td:nth-child(5) span');
                let actionCell = row.querySelector('td:nth-child(6)');

                // Update status badge
                statusCell.className = "badge " + (status === 'approved' ? 'bg-success' : 'bg-danger');
                statusCell.innerText = status.charAt(0).toUpperCase() + status.slice(1);

                // Remove approve/reject buttons after update
                actionCell.querySelectorAll('.update-status, .btn-danger').forEach(btn => btn.remove());
            }
        })
        .catch(error => console.error('Error:', error));
    });
});

// Show reject modal
function showRejectModal(id) {
    document.getElementById('rejectApplicationId').value = id;
    document.getElementById('rejectionReason').value = '';
    document.getElementById('rejectionReason').classList.remove('is-invalid');
    
    let modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}

// Submit rejection with reason
function submitRejection() {
    let id = document.getElementById('rejectApplicationId').value;
    let reason = document.getElementById('rejectionReason').value.trim();
    let reasonField = document.getElementById('rejectionReason');
    
    if (!reason) {
        reasonField.classList.add('is-invalid');
        return;
    }
    
    reasonField.classList.remove('is-invalid');

    fetch(`/admin/vacancys/${id}/update-status`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ 
            status: 'rejected',
            rejection_reason: reason
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let row = document.getElementById(`row-${id}`);
            let statusCell = row.querySelector('td:nth-child(5) span');
            let actionCell = row.querySelector('td:nth-child(6)');

            // Update status badge
            statusCell.className = "badge bg-danger";
            statusCell.innerText = "Rejected";

            // Remove approve/reject buttons after update
            actionCell.querySelectorAll('.update-status, .btn-danger').forEach(btn => btn.remove());

            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('rejectModal')).hide();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Initialize DataTables for all sections
            $("#table").DataTable({
                responsive: true,
                ordering: false,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    ["5", "10", "25", "50", "All"]
                ]
            });
        });
    </script>

@endsection

@push('styles')
<style>
.table td {
    vertical-align: middle;
}
.badge {
    font-size: 0.75rem;
}
</style>
@endpush
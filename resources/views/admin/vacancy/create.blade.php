@extends('admin.layouts.app')

@section('content')
<main class="content">
    <div class="container-fluid p-0">
        <div class="row mb-2 mb-xl-3">
            <div class="col-auto d-none d-sm-block">
                <h3><strong>Vacancy Management</strong></h3>
            </div>
            <div class="col-auto ms-auto text-end mt-n1">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVacancyModal">
                    <i class="align-middle" data-feather="plus"></i> Add New Vacancy
                </button>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Vacancy List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">All Vacancies</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                                <table id="table" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Location</th>
                                        <th>Description</th>
                                        <th>Contact Number</th>
                                        <th>Status</th>
                                        <th>Applied Count</th>
                                        <th>Created Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($vacancies as $vacancy)
                                    <tr>
                                        <td>{{ $vacancy->id }}</td>
                                        <td>{{ $vacancy->location }}</td>
                                        <td>
                                            @if($vacancy->description)
                                                <span title="{{ $vacancy->description }}">
                                                    {{ Str::limit($vacancy->description, 50) }}
                                                </span>
                                            @else
                                                <span class="text-muted">No description</span>
                                            @endif
                                        </td>
                                        <td>{{ $vacancy->contact_number }}</td>
                                        <td>
                                            <span class="badge {{ $vacancy->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                                {{ ucfirst($vacancy->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $vacancy->vacancy_applied_count }} Applications</span>
                                        </td>
                                        <td>{{ $vacancy->created_at->format('d M, Y') }}</td>
                                        <td>
                                            <!-- Status Toggle Button -->
                                            <form method="POST" action="{{ route('admin.vacancy.update-status', $vacancy->id) }}" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="{{ $vacancy->status === 'active' ? 'inactive' : 'active' }}">
                                                <button type="submit" class="btn btn-sm {{ $vacancy->status === 'active' ? 'btn-warning' : 'btn-success' }}" 
                                                        onclick="return confirm('Are you sure you want to change the status?')">
                                                    <i class="align-middle" data-feather="{{ $vacancy->status === 'active' ? 'pause' : 'play' }}"></i>
                                                    {{ $vacancy->status === 'active' ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>
                                            
                                            <!-- View Applications Button -->
                                            @if($vacancy->vacancy_applied_count > 0)
                                                <a href="{{ route('admin.vacancy.applied-details', $vacancy->id) }}" 
                                                   class="btn btn-sm btn-info mt-2">
                                                    <i class="align-middle" data-feather="eye"></i> View
                                                </a>
                                            @else
                                                <span class="text-muted small ms-1">No applications</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No vacancies found</td>
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
</main>

<!-- Add Vacancy Modal -->
<div class="modal fade" id="addVacancyModal" tabindex="-1" aria-labelledby="addVacancyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVacancyModalLabel">Add New Vacancy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.vacancy.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Location <span class="text-danger">*</span></label>
                        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" 
                               placeholder="Enter location" value="{{ old('location') }}" required>
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="4" placeholder="Enter vacancy description (optional)">{{ old('description') }}</textarea>
                        <small class="form-text text-muted">Maximum 1000 characters</small>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="">Select Status</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }} selected>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Contact Number</label>
                        <input type="text" name="contact_number" class="form-control" value="9600166427" disabled readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Vacancy</button>
                </div>
            </form>
        </div>
    </div>
</div>
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

@push('scripts')
<script>
// Auto-close modal if form submission is successful
@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('addVacancyModal'));
        if (modal) {
            modal.hide();
        }
    });
@endif

// Character counter for description
document.addEventListener('DOMContentLoaded', function() {
    const descriptionField = document.querySelector('textarea[name="description"]');
    if (descriptionField) {
        descriptionField.addEventListener('input', function() {
            const maxLength = 1000;
            const currentLength = this.value.length;
            const remaining = maxLength - currentLength;
            
            let counterElement = this.parentElement.querySelector('.char-counter');
            if (!counterElement) {
                counterElement = document.createElement('small');
                counterElement.className = 'form-text text-muted char-counter';
                this.parentElement.appendChild(counterElement);
            }
            
            counterElement.textContent = `${currentLength}/${maxLength} characters`;
            
            if (remaining < 0) {
                counterElement.classList.add('text-danger');
                counterElement.classList.remove('text-muted');
            } else {
                counterElement.classList.add('text-muted');
                counterElement.classList.remove('text-danger');
            }
        });
    }
});
</script>
@endpush
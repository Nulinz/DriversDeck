@extends('admin.layouts.app')

@section('content')

    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    </head>

    <main class="content">
        <div class="container-fluid p-0">
            <div class="row mb-xl-3 mb-2">
                <div class="d-none d-sm-block col-auto">
                    <h3><strong>Drivers Management</strong></h3>
                </div>
                <div class="col-auto ms-auto text-end">
                    @if (request()->query('type'))
                        <button id="exportExcelBtn" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Export to Excel
                        </button>
                    @endif
                </div>
            </div>

            <!-- Section Navigation -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex mb-4">
                                <button class="btn btn-link text-decoration-none fw-bold section-toggle active" data-target="approved-section">Approved</button>
                                <button class="btn btn-link text-decoration-none fw-bold section-toggle" data-target="rejected-section">Rejected</button>
                                <button class="btn btn-link text-decoration-none fw-bold section-toggle" data-target="pending-section">Pending</button>
                            </div>

                            <!-- Vehicle Filter -->
                            <div class="row mb-3">
                                <div class="col-md-3 ms-auto text-end">
                                    <select id="vehicleFilter" class="form-select">
                                        <option value="">All Vehicles</option>
                                        <option value="TRANS">TRANS</option>
                                        <option value="LMV-TR">LMV-TR</option>
                                        <option value="LMV">LMV</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Approved Section -->
                            <div id="approved-section" class="driver-section">
                                <h5 class="text-muted mb-3">Approved List</h5>
                                <table id="approved-table" class="table-striped table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Driver Name</th>
                                            <th>Types of Vehicle</th>
                                            <th>Registration Date</th>
                                            <th>Contact Number</th>
                                            <th>Location</th>
                                            <th>Registration Type</th>
                                            <th>Active Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($approvedDrivers as $candidate)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $candidate->name }}</td>
                                                <td>{{ $candidate->cov ?? '-' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($candidate->created_at)->format('d-m-Y') }}</td>
                                                <td>{{ $candidate->phone }}</td>
                                                <td>{{ $candidate->loc }}</td>
                                                <td>{{ ucfirst($candidate->type) }}</td>
                                                <td>
                                                    <div class="form-check form-switch d-flex align-items-center gap-2">
                                                        <input class="form-check-input active-status-toggle" type="checkbox" id="activeStatus{{ $candidate->id }}"
                                                            data-id="{{ $candidate->id }}" {{ $candidate->active_status == 'active' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="activeStatus{{ $candidate->id }}">
                                                            <span class="badge {{ $candidate->active_status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                                {{ ucfirst($candidate->active_status) }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2 align-items-center">
                                                        <a href="{{ route('admin.candidate.profile', ['id' => $candidate->id]) }}">
                                                            <i class="fs-4 text-dark fa fa-external-link-alt"></i>
                                                        </a>

                                                        {{-- delete --}}
                                                        <form action="{{ route('admin.candidate.delete', $candidate->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" onclick="return confirm('Delete this driver?')" style="border:none; background:none;">
                                                                <i class="text-dark fa fa-trash fs-6" aria-hidden="true"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Rejected Section -->
                            <div id="rejected-section" class="driver-section" style="display: none;">
                                <h5 class="text-muted mb-3">Rejected List</h5>
                                <table id="rejected-table" class="table-striped table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Driver Name</th>
                                            <th>Registration Date</th>
                                            <th>Contact Number</th>
                                            <th>Location</th>
                                            <th>Registration Type</th>
                                            <th>Active Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rejectedDrivers as $candidate)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $candidate->name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($candidate->created_at)->format('d-m-Y') }}</td>
                                                <td>{{ $candidate->phone }}</td>
                                                <td>{{ $candidate->loc }}</td>
                                                <td>{{ ucfirst($candidate->type) }}</td>
                                                <td>
                                                    <div class="form-check form-switch d-flex align-items-center gap-2">
                                                        <input class="form-check-input active-status-toggle" type="checkbox" id="activeStatusRej{{ $candidate->id }}"
                                                            data-id="{{ $candidate->id }}" {{ $candidate->active_status == 'active' ? 'checked' : '' }}>
                                                        <label class="form-check-label">
                                                            <span class="badge {{ $candidate->active_status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                                {{ ucfirst($candidate->active_status) }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2 align-items-center">

                                                        <a href="{{ route('admin.candidate.profile', ['id' => $candidate->id]) }}" title="View Profile">
                                                            <i class="fs-5 text-dark fa fa-external-link-alt"></i>
                                                        </a>

                                                        <form
                                                            action="{{ route('admin.handle.approval', [
                                                                'type' => $candidate->type,
                                                                'id' => $candidate->id,
                                                                'action' => 'approve',
                                                            ]) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm ms-2" onclick="return confirm('Approve this driver?')">
                                                                <i class="fa-solid fa-check"></i>
                                                            </button>
                                                        </form>

                                                        {{-- delete --}}
                                                        <form action="{{ route('admin.candidate.delete', $candidate->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" onclick="return confirm('Delete this driver?')" style="border:none; background:none;">
                                                                <i class="text-dark fas fa-trash-alt fs-6"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pending Section -->
                            <div id="pending-section" class="driver-section" style="display: none;">
                                <h5 class="text-muted mb-3">Pending List</h5>

                                <table id="pending-table" class="table-striped table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Driver Name</th>
                                            <th>Registration Date</th>
                                            <th>Contact Number</th>
                                            <th>Location</th>
                                            <th>Registration Type</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($pendingDrivers as $candidate)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $candidate->name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($candidate->created_at)->format('d-m-Y') }}</td>
                                                <td>{{ $candidate->phone }}</td>
                                                <td>{{ $candidate->loc }}</td>
                                                <td>{{ ucfirst($candidate->type) }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">

                                                        {{-- Approve --}}
                                                        <form
                                                            action="{{ route('admin.handle.approval', [
                                                                'type' => $candidate->type,
                                                                'id' => $candidate->id,
                                                                'action' => 'approve',
                                                            ]) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            <button class="btn btn-success btn-sm p-1" onclick="return confirm('Approve this driver?')">
                                                                <i class="fa fa-check fs-6"></i>
                                                            </button>
                                                        </form>

                                                        {{-- Reject --}}
                                                        <button type="button" class="btn btn-danger btn-sm p-1" data-bs-toggle="modal"
                                                            data-bs-target="#rejectModal{{ $candidate->id }}">
                                                            <i class="fa fa-times fs-6"></i>
                                                        </button>

                                                        {{-- View --}}
                                                        <a href="{{ route('admin.candidate.profile', ['id' => $candidate->id]) }}">
                                                            <i class="fs-5 text-dark fa fa-external-link-alt"></i>
                                                        </a>

                                                        {{-- delete --}}
                                                        <form action="{{ route('admin.candidate.delete', $candidate->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" onclick="return confirm('Delete this driver?')" style="border:none; background:none;">
                                                                <i class="text-dark fas fa-trash-alt fs-6"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>

                                            {{-- ✅ Reject Modal (INSIDE LOOP) --}}
                                            <div class="modal fade" id="rejectModal{{ $candidate->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                                    <div class="modal-content">
                                                        <form method="POST" action="{{ route('admin.handle.approval.reason') }}">
                                                            @csrf

                                                            <input type="hidden" name="type" value="{{ $candidate->type }}">
                                                            <input type="hidden" name="id" value="{{ $candidate->id }}">
                                                            <input type="hidden" name="action" value="reject">

                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Reject Driver</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <textarea name="reason" class="form-control" rows="4" required minlength="10" placeholder="Enter rejection reason (min 10 characters)"></textarea>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-danger">
                                                                    Reject
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="rejectModal{{ $candidate->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.handle.approval.reason') }}">
                        @csrf

                        <input type="hidden" name="type" value="{{ $candidate->type }}">
                        <input type="hidden" name="id" value="{{ $candidate->id }}">
                        <input type="hidden" name="action" value="reject">

                        <div class="modal-header">
                            <h5 class="modal-title">Reject Driver</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <textarea name="reason" class="form-control" rows="4" required placeholder="Enter rejection reason (min 10 chars)"></textarea>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">
                                Reject
                            </button>
                        </div>
                    </form>
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

        .driver-section {
            transition: all 0.3s ease;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
        }

        .form-switch .form-check-input {
            width: 2em;
            height: 1em;
            cursor: pointer;
        }

        .form-switch .form-check-input:checked {
            background-color: #28a745;
            border-color: #28a745;
        }

        .form-switch .form-check-input:not(:checked) {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        #exportExcelBtn {
            transition: all 0.3s ease;
        }

        #exportExcelBtn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let approvedTable, rejectedTable, pendingTable;

            function initTable(tableId) {
                return $(`#${tableId}`).DataTable({
                    responsive: true,
                    ordering: false,
                    lengthMenu: [
                        [10, 20, 30, 40, 50, -1],
                        ["10", "20", "30", "40", "50", "All"]
                    ],
                    language: {
                        search: "Search:",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ drivers",
                        infoEmpty: "Showing 0 to 0 of 0 drivers",
                        infoFiltered: "(filtered from _MAX_ total drivers)",
                        zeroRecords: "No matching drivers found"
                    },
                    columnDefs: [{
                        targets: -1,
                        orderable: false,
                        searchable: false
                    }]
                });
            }

            approvedTable = initTable("approved-table");

            document.querySelectorAll('.section-toggle').forEach(button => {
                button.addEventListener('click', function() {
                    document.querySelectorAll('.section-toggle').forEach(btn => {
                        btn.classList.remove('active');
                        btn.style.color = '#666';
                        btn.style.borderBottom = '2px solid transparent';
                    });

                    this.classList.add('active');
                    this.style.color = '#333';
                    this.style.borderBottom = '2px solid #007bff';

                    document.querySelectorAll('.driver-section').forEach(section => {
                        section.style.display = 'none';
                    });

                    const target = this.getAttribute('data-target');
                    document.getElementById(target).style.display = 'block';

                    if (target === 'rejected-section' && !$.fn.DataTable.isDataTable('#rejected-table')) {
                        rejectedTable = initTable("rejected-table");
                    }

                    if (target === 'pending-section' && !$.fn.DataTable.isDataTable('#pending-table')) {
                        pendingTable = initTable("pending-table");
                    }

                });
            });

            $('#vehicleFilter').on('change', function() {
                if (approvedTable) approvedTable.draw();
                if (rejectedTable) rejectedTable.draw();
                if (pendingTable) pendingTable.draw();
            });
        });

        // Export to Excel with Driver Table Data Only
        document.getElementById('exportExcelBtn')?.addEventListener('click', function() {
            // Show loading state
            const btn = this;
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exporting...';

            // Get the currently active section
            const activeSection = document.querySelector('.driver-section:not([style*="display: none"])');
            const isApproved = activeSection.id === 'approved-section';
            const status = isApproved ? 'approved' : 'rejected';

            // Get URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const type = urlParams.get('type') || 'all';

            // Fetch complete driver data from backend
            fetch(`/admin/candidate/export-data?type=${type}&status=${status}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(result => {
                    if (!result.success) {
                        throw new Error(result.message || 'Failed to fetch driver data');
                    }

                    const drivers = result.data;

                    if (drivers.length === 0) {
                        alert('No data available to export.');
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                        return;
                    }

                    // Prepare data for export
                    const data = [];

                    // Add headers - only driver table fields
                    const headers = [
                        'Driver Name',
                        'Phone Number',
                        'Gender',
                        'Marital Status',
                        'Blood Group',
                        'Location ID',
                        'Location Name',
                        'District',
                        'License Number',
                        'Aadhar Number',
                        'Reference Code',
                        'Registration Type',
                        'Subscription',
                        'Active Status',
                        'Status',
                        'Registration Date'
                    ];
                    data.push(headers);

                    // Add all driver data
                    drivers.forEach(driver => {
                        const row = [
                            driver.name || '-',
                            driver.phone || '-',
                            driver.gender ? (driver.gender.charAt(0).toUpperCase() + driver.gender.slice(1)) : '-',
                            driver.marital_status ? (driver.marital_status.charAt(0).toUpperCase() + driver.marital_status.slice(1)) : '-',
                            driver.b_group || '-',
                            driver.location || '-',
                            driver.location_name || '-',
                            driver.district || '-',
                            driver.l_no || '-',
                            driver.ad_num || '-',
                            driver.ref_code || '-',
                            driver.type ? (driver.type.charAt(0).toUpperCase() + driver.type.slice(1)) : '-',
                            driver.subscription || '-',
                            driver.active_status ? (driver.active_status.charAt(0).toUpperCase() + driver.active_status.slice(1)) : '-',
                            driver.status ? (driver.status.charAt(0).toUpperCase() + driver.status.slice(1)) : '-',
                            formatDate(driver.created_at)
                        ];
                        data.push(row);
                    });

                    // Create workbook and worksheet
                    const wb = XLSX.utils.book_new();
                    const ws = XLSX.utils.aoa_to_sheet(data);

                    // Set column widths for better readability
                    ws['!cols'] = [{
                            wch: 20
                        }, // Driver Name
                        {
                            wch: 15
                        }, // Phone
                        {
                            wch: 10
                        }, // Gender
                        {
                            wch: 15
                        }, // Marital Status
                        {
                            wch: 12
                        }, // Blood Group
                        {
                            wch: 12
                        }, // Location ID
                        {
                            wch: 20
                        }, // Location Name
                        {
                            wch: 15
                        }, // District
                        {
                            wch: 18
                        }, // License Number
                        {
                            wch: 15
                        }, // Aadhar
                        {
                            wch: 15
                        }, // Reference Code
                        {
                            wch: 18
                        }, // Registration Type
                        {
                            wch: 12
                        }, // Subscription
                        {
                            wch: 15
                        }, // Active Status
                        {
                            wch: 12
                        }, // Status
                        {
                            wch: 18
                        } // Registration Date
                    ];

                    // Add worksheet to workbook
                    const sheetName = `${status.charAt(0).toUpperCase() + status.slice(1)} Drivers`;
                    XLSX.utils.book_append_sheet(wb, ws, sheetName);

                    // Generate filename
                    const filename = `drivers_${type}_${status}_${new Date().toISOString().split('T')[0]}.xlsx`;

                    // Save file
                    XLSX.writeFile(wb, filename);

                    // Reset button
                    btn.disabled = false;
                    btn.innerHTML = originalText;

                    // Show success message
                    alert(`✅ Excel file exported successfully!\n\nTotal Records: ${drivers.length}\nFilename: ${filename}`);
                })
                .catch(error => {
                    console.error('Error:', error);
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                    alert('❌ Failed to export data. Please try again.\n\nError: ' + error.message);
                });
        });

        // Helper function to format dates
        function formatDate(dateString) {
            if (!dateString) return '-';
            try {
                const date = new Date(dateString);
                if (isNaN(date.getTime())) return '-';
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                return `${day}-${month}-${year}`;
            } catch (e) {
                return '-';
            }
        }

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
                document.querySelectorAll('.driver-section').forEach(section => {
                    section.style.display = 'none';
                });

                // Show target section
                const targetSection = this.getAttribute('data-target');
                document.getElementById(targetSection).style.display = 'block';
            });
        });

        // Function to handle active status toggle
        function handleActiveStatusToggle(event) {
            const toggle = event.target;
            if (!toggle.classList.contains('active-status-toggle')) return;

            const id = toggle.getAttribute('data-id');
            const isChecked = toggle.checked;
            const newActiveStatus = isChecked ? 'active' : 'inactive';

            const message = `Are you sure you want to mark this driver as ${newActiveStatus}?`;

            if (!confirm(message)) {
                // Revert the toggle if user cancels
                toggle.checked = !isChecked;
                return;
            }

            fetch(`/admin/candidate/active-status-toggle/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        active_status: newActiveStatus
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update badge text and color
                        const badge = toggle.parentElement.querySelector('.badge');
                        badge.textContent = data.new_status.charAt(0).toUpperCase() + data.new_status.slice(1);
                        badge.className = `badge ${data.new_status === 'active' ? 'bg-success' : 'bg-secondary'}`;
                    } else {
                        // Revert the toggle if update failed
                        toggle.checked = !isChecked;
                        alert('Failed to update active status. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Revert the toggle if there's an error
                    toggle.checked = !isChecked;
                    alert('An error occurred. Please try again.');
                });
        }

        // Use event delegation for active status toggles on both tables
        document.getElementById('approved-table').addEventListener('change', handleActiveStatusToggle);
        document.getElementById('rejected-table').addEventListener('change', handleActiveStatusToggle);

        // Status toggle functionality (existing)
        document.querySelectorAll('.toggle-status').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const newStatus = this.getAttribute('data-status');

                const message = newStatus === 'approved' ?
                    'Do you want to approve this driver?' :
                    'Do you want to reject this driver?';

                if (!confirm(message)) {
                    return;
                }

                fetch(`/admin/candidate/status-toggle/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            status: newStatus
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Failed to update status. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
            });
        });
    </script>
@endsection

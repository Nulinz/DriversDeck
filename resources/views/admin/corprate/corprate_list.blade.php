@extends('admin.layouts.app')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row mb-xl-3 mb-2">
                <div class="d-none d-sm-block col-auto">
                    <h3><strong>Corporate List</strong></h3>
                </div>
                <div class="col-auto ms-auto text-end">
                    @if (request()->query('type'))
                        <button id="exportExcelBtn" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Export to Excel
                        </button>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="datatables-reponsive" class="table-striped table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Contact Number</th>
                                        <th>Email</th>
                                        <th>Location</th>
                                        <th>Active Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($corprate_list as $corprate)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $corprate->name }}</td>
                                            <td>{{ $corprate->type }}</td>
                                            <td>{{ $corprate->contact }}</td>
                                            <td>{{ $corprate->mail }}</td>
                                            <td>{{ $corprate->loc }}</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input active-status-toggle" type="checkbox" id="activeStatus{{ $corprate->id }}"
                                                        data-id="{{ $corprate->id }}" {{ $corprate->active_status == 'active' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="activeStatus{{ $corprate->id }}">
                                                        <span class="badge {{ $corprate->active_status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                            {{ ucfirst($corprate->active_status) }}
                                                        </span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <a
                                                        href="{{ $corprate->type === 'corporate' ? route('admin.corprate.corprate_profile', ['id' => $corprate->id]) : route('admin.corprate.owner_profile', ['id' => $corprate->id]) }}">
                                                        <i class="fs-4 text-dark fa fa-external-link-alt"></i>
                                                    </a>

                                                    {{-- delete --}}
                                                    <form action="{{ route('admin.corprate.delete', $corprate->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="return confirm('Delete this corprate?')" style="border:none; background:none;">
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
                    </div>
                </div>
            </div>
        </div>
    </main>

    <style>
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

    <!-- Add SheetJS Library for Excel Export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Initialize DataTable
            var table = $("#datatables-reponsive").DataTable({
                responsive: true,
                ordering: false,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    ["5", "10", "25", "50", "All"]
                ],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ records",
                    infoEmpty: "Showing 0 to 0 of 0 records",
                    infoFiltered: "(filtered from _MAX_ total records)",
                    zeroRecords: "No matching records found"
                }
            });

            // Export to Excel with Corporate Table Data
            document.getElementById('exportExcelBtn')?.addEventListener('click', function() {
                // Show loading state
                const btn = this;
                const originalText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exporting...';

                // Get URL parameters
                const urlParams = new URLSearchParams(window.location.search);
                const type = urlParams.get('type') || 'all';
                const status = 'approved'; // Default status

                // Fetch complete corporate data from backend
                fetch(`/admin/corporate/export-data?type=${type}&status=${status}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(result => {
                        if (!result.success) {
                            throw new Error(result.message || 'Failed to fetch corporate data');
                        }

                        const corporates = result.data;

                        if (corporates.length === 0) {
                            alert('No data available to export.');
                            btn.disabled = false;
                            btn.innerHTML = originalText;
                            return;
                        }

                        // Prepare data for export
                        const data = [];

                        // Add comprehensive headers
                        const headers = [
                            'Type',
                            'Company Name',
                            'Contact Person Name',
                            'Gender',
                            'Contact Number',
                            'Email',
                            'Company Type',
                            'Company Contact',
                            'Company Email',
                            'Aadhar Number',
                            'Alternative Number',
                            'Address Line 1',
                            'Address Line 2',
                            'City',
                            'State',
                            'PIN Code',
                            'Location ID',
                            'Location Name',
                            'District',
                            'PAN Number',
                            'GST Number',
                            'Number of Vehicles',
                            'Number of Drivers',
                            'Number of Vacancies',
                            'Subscription',
                            'Reference Code',
                            'Active Status',
                            'Status',
                            'Registration Date',
                        ];
                        data.push(headers);

                        // Add all corporate data
                        corporates.forEach(corporate => {
                            const row = [
                                corporate.type ? (corporate.type.charAt(0).toUpperCase() + corporate.type.slice(1)) : '-',
                                corporate.name || '-',
                                corporate.c_name || '-',
                                corporate.gender ? (corporate.gender.charAt(0).toUpperCase() + corporate.gender.slice(1)) : '-',
                                corporate.contact || '-',
                                corporate.mail || '-',
                                corporate.c_type || '-',
                                corporate.c_num || '-',
                                corporate.c_mail || '-',
                                corporate.a_num || '-',
                                corporate.c_num || '-',
                                corporate.ad_1 || '-',
                                corporate.ad_2 || '-',
                                corporate.city || '-',
                                corporate.state || '-',
                                corporate.pin || '-',
                                corporate.location || '-',
                                corporate.location_name || '-',
                                corporate.district || '-',
                                corporate.pan || '-',
                                corporate.gst || '-',
                                corporate.no_veh || '0',
                                corporate.no_driver || '0',
                                corporate.no_vac || '0',
                                corporate.subscription || '-',
                                corporate.ref_code || '-',
                                corporate.active_status ? (corporate.active_status.charAt(0).toUpperCase() + corporate.active_status.slice(1)) : '-',
                                corporate.status ? (corporate.status.charAt(0).toUpperCase() + corporate.status.slice(1)) : '-',
                                formatDate(corporate.created_at),
                            ];
                            data.push(row);
                        });

                        // Create workbook and worksheet
                        const wb = XLSX.utils.book_new();
                        const ws = XLSX.utils.aoa_to_sheet(data);

                        // Set column widths for better readability
                        ws['!cols'] = [{
                                wch: 12
                            }, // Type
                            {
                                wch: 25
                            }, // Company Name
                            {
                                wch: 20
                            }, // Contact Person
                            {
                                wch: 10
                            }, // Gender
                            {
                                wch: 15
                            }, // Contact
                            {
                                wch: 25
                            }, // Email
                            {
                                wch: 15
                            }, // Company Type
                            {
                                wch: 15
                            }, // Company Contact
                            {
                                wch: 25
                            }, // Company Email
                            {
                                wch: 15
                            }, // Aadhar
                            {
                                wch: 15
                            }, // Alt Number
                            {
                                wch: 30
                            }, // Address 1
                            {
                                wch: 30
                            }, // Address 2
                            {
                                wch: 15
                            }, // City
                            {
                                wch: 15
                            }, // State
                            {
                                wch: 10
                            }, // PIN
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
                                wch: 15
                            }, // PAN
                            {
                                wch: 18
                            }, // GST
                            {
                                wch: 12
                            }, // Vehicles
                            {
                                wch: 12
                            }, // Drivers
                            {
                                wch: 12
                            }, // Vacancies
                            {
                                wch: 12
                            }, // Subscription
                            {
                                wch: 15
                            }, // Reference
                            {
                                wch: 15
                            }, // Active Status
                            {
                                wch: 12
                            }, // Status
                            {
                                wch: 18
                            }, // Registration
                        ];

                        // Add worksheet to workbook
                        const sheetName = `${type.charAt(0).toUpperCase() + type.slice(1)} Records`;
                        XLSX.utils.book_append_sheet(wb, ws, sheetName);

                        // Generate filename
                        const filename = `${type}_${status}_${new Date().toISOString().split('T')[0]}.xlsx`;

                        // Save file
                        XLSX.writeFile(wb, filename);

                        // Reset button
                        btn.disabled = false;
                        btn.innerHTML = originalText;

                        // Show success message
                        alert(`✅ Excel file exported successfully!\n\nTotal Records: ${corporates.length}\nFilename: ${filename}`);
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

            // Function to handle active status toggle
            function handleActiveStatusToggle(event) {
                const toggle = event.target;
                if (!toggle.classList.contains('active-status-toggle')) return;

                const id = toggle.getAttribute('data-id');
                const isChecked = toggle.checked;
                const newActiveStatus = isChecked ? 'active' : 'inactive';

                const message = `Are you sure you want to mark this corporate as ${newActiveStatus}?`;

                if (!confirm(message)) {
                    // Revert the toggle if user cancels
                    toggle.checked = !isChecked;
                    return;
                }

                fetch(`/admin/corporate/active-status-toggle/${id}`, {
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

            // Use event delegation to handle toggle switches on all pages
            document.getElementById('datatables-reponsive').addEventListener('change', handleActiveStatusToggle);

            // Handle status toggle (this appears to be for a different feature)
            document.querySelectorAll('.toggle-status').forEach(icon => {
                icon.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const newStatus = this.getAttribute('data-status');

                    // Add confirmation popup
                    const message = newStatus === 'Approved' ?
                        'Do you want to approve this?' :
                        'Do you want to reject this?';

                    if (!confirm(message)) {
                        return; // user pressed Cancel
                    }

                    fetch(`/admin/corporate/status-toggle/${id}`, {
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
                                location.reload(); // refresh to reflect change
                            }
                        });
                });
            });
        });
    </script>
@endsection

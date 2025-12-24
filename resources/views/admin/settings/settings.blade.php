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
                    <h3><strong>Settings</strong></h3>
                </div>
            </div>

            <div class="row">
                {{-- report tabs --}}
                <div class="col-md-12 col-xl-12">
                    <div class="nav nav-tabs" role="tablist">
                        <a class="active" data-bs-toggle="tab" href="#User" role="tab" aria-selected="true">
                            User Details
                        </a>
                        {{-- <a data-bs-toggle="tab" href="#Permissions" role="tab" aria-selected="false" tabindex="-1">
                            Permissions
                        </a> --}}
                        <a data-bs-toggle="tab" href="#location" role="tab" aria-selected="false" tabindex="-1">
                            Location
                        </a>
                    </div>

                    <div class="tab-content mt-3">
                        <div class="tab-pane fade show active" id="User" role="tabpanel">
                            <div class="card mb-3">
                                <div class="m-3 mb-0 pb-3 d-flex align-items-center justify-content-between border-bottom">
                                    <h5 class="card-title mb-0">Users</h5>
                                    <a class="btn btn-primary" href="{{ route('admin.settings.add_user') }}"><i
                                            class="align-middle fa fa-fw fa-plus"></i>Add User</a>
                                </div>
                                <div class="card-body">
                                    <div class="">
                                    </div>
                                    <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Designation</th>
                                                <th>Mail Id</th>
                                                <th>Contact Number</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            @forelse ($users as $user)
                                                {{-- <tr> --}}
                                                <tr data-id="{{ $user->id }}">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->designation }}</td>
                                                    <td>{{ $user->mail }}</td>
                                                    <td>{{ $user->contact }}</td>
                                                    {{-- <td><span class="badge badge-success-light">{{ ucfirst($user->status) }}</span></td> --}}
                                                    <td>
                                                        <span
                                                            class="{{ $user->status == 'active' ? 'text-success' : 'text-danger' }}">
                                                            {{ $user->status == 'active' ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </td>

                                                    {{-- <td>
                                                        <a href=""><i class="fs-4 text-danger align-middle me-2 fa fa-times-circle"></i></a>
                                                        <a href="{{ route('admin.candidate.profile', ['id' => $user->id]) }}">
                                                            <i class="fs-4 text-dark fa fa-fw fa-edit"></i>
                                                        </a>
                                                    </td> --}}

                                                    <td>
                                                        @if ($user->status == 'active')
                                                            <a href="javascript:void(0);" class="toggle-status"
                                                                data-id="{{ $user->id }}" data-status="Inactive">
                                                                <i class="text-danger align-middle me-2 fa fa-times-circle"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-title="Click to make Inactive"></i>
                                                            </a>
                                                        @else
                                                            <a href="javascript:void(0);" class="toggle-status"
                                                                data-id="{{ $user->id }}" data-status="Active">
                                                                <i class="fas fa-circle-check text-success"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-title="Click to make Active"></i>
                                                            </a>
                                                        @endif
                                                        {{-- <a
                                                            href="{{ route('admin.candidate.profile', ['id' => $user->id]) }}">
                                                            <i class="fs-4 text-dark fa fa-fw fa-edit"></i>
                                                        </a> --}}
                                                    </td>

                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">No users found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Permissions" role="tabpanel">
                            <div class="card mb-3">
                                <div class="m-3 mb-0 pb-3 d-flex align-items-center justify-content-between border-bottom">
                                    <h5 class="card-title mb-0">Permission</h5>
                                    <a class="btn btn-primary" href="{{ route('admin.settings.add_permission') }}"><i
                                            class="align-middle fa fa-fw fa-plus"></i>Add
                                        Permission</a>
                                </div>
                                <div class="card-body">
                                    <div class="">
                                    </div>
                                    <table id="datatables-reponsive-two" class="table table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Designation</th>
                                                <th>Users</th>
                                                <th>Permission</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            <tr>
                                                <td>1</td>
                                                <td>Admin</td>
                                                <td>user</td>
                                                <td>05</td>
                                                <td>
                                                    <a href="{{ route('admin.settings.edit_permission') }}"><i
                                                            class="fs-4 text-dark fa fa-fw fa-edit"></i></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="location" role="tabpanel">
                            <div class="card mb-3">

                                <div class="m-3 mb-0 pb-3 d-flex align-items-center justify-content-between border-bottom">
                                    <h5 class="card-title mb-0">Location</h5>
                                    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLoc"><i
                                            class="align-middle fa fa-fw fa-plus"></i>Add
                                        Location</a>
                                </div>
                                <div class="card-body">
                                    <div class="">
                                        @if (session('success'))
                                            <div class="alert alert-success">
                                                {{ session('success') }}
                                            </div>
                                        @endif
                                    </div>
<table id="datatables-reponsive-two" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Location</th>
            <th>District</th>
            <th>Coordinates</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($locations) && count($locations) > 0)
            @foreach ($locations as $index => $location)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $location->location }}</td>
                    <td>{{ $location->district_name ?? 'N/A' }}</td>
                    <td>{{ $location->cord }}</td>
                    <td>
                        @if($location->status == 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        @if($location->status == 'active')
                            <a href="{{ route('location.deactivate', $location->location) }}" 
                               class="btn btn-sm btn-success"
                               onclick="return confirm('Are you sure you want to deactivate this location?')">
                               Activate
                            </a>
                        @else
                            <a href="{{ route('location.activate', $location->location) }}" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Are you sure you want to activate this location?')">
                               Deactivate
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
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

    <div class="modal fade" id="addLoc" tabindex="-1" aria-labelledby="addLocLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addLocationForm" method="POST" action="{{ route('admin.settings.location.store') }}">
                        @csrf
                        <div class="mb-3">
    <label class="form-label">District</label>
    <select name="district_id" class="form-control" required>
        <option value="" disabled selected>Select District</option>
        @foreach($districts as $district)
            <option value="{{ $district->id }}">{{ $district->district }}</option>
        @endforeach
    </select>
</div>


                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-control" placeholder="Enter location"
                                required>
                            <input type="hidden" name="status" value="active">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Coordinates</label>
                            <input type="text" name="cord" class="form-control" placeholder="Enter coordinates"
                                required>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            {{-- <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Cancel</button> --}}
                            <button type="submit" class="btn btn-primary">Add Location</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 1) Initialize Table #1
            $("#datatables-reponsive").DataTable({
                responsive: true,
                ordering: false,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    ["5", "10", "25", "50", "All"]
                ]
            });
            // 2) Initialize Table #2 (even though it’s hidden initially)
            $("#datatables-reponsive-two").DataTable({
                responsive: true,
                ordering: false,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    ["5", "10", "25", "50", "All"]
                ]
            });

            // 3) WHEN the “Permissions” tab is actually shown, force a recalc on Table #2:
            $('a[data-bs-toggle="tab"][href="#Permissions"]').on("shown.bs.tab", function(e) {
                // Explicitly adjust only the second DataTable:
                $("#datatables-reponsive-two")
                    .DataTable()
                    .columns.adjust()
                    .responsive.recalc();
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const icons = document.querySelectorAll('.toggle-status');
            icons.forEach(icon => {
                icon.addEventListener('click', function() {

                    var con = confirm('Are you sure you want to update the status?');

                    if (!con) {
                        return; // If user cancels, do nothing
                    }

                    const userId = this.getAttribute('data-id');
                    const newStatus = this.getAttribute('data-status');

                    fetch(`/admin/user/status-toggle/${userId}`, {
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
                            }
                        });
                });
            });
        });
    </script>
@endsection()

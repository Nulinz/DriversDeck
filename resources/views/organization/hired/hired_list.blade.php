@extends('organization.layouts.app')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row mb-2 mb-xl-3">
                <div class="col-auto d-none d-sm-block">
                    <h3><strong>Hired List</strong></h3>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row dt-row">
                        <div class="col-sm-12">
                            <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Driver Type</th>
                                        <th>Joined Date</th>
                                        <th>Contact Number</th>
                                        <th>Job Location</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($drivers) && count($drivers) > 0)
                                        @foreach ($drivers as $index => $driver)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    {{-- <img src="{{ asset('assets/images/avatar.png') }}" width="40"
                                                        height="40" class="rounded-circle me-2" alt="Avatar"> --}}
                                                    {{ $driver->driver_name ?? 'N/A' }}
                                                </td>
                                                <td>{{ $driver->driver_type }}</td>
                                                <td>{{ \Carbon\Carbon::parse($driver->joined_date)->format('d-m-Y') }}</td>
                                                <td>{{ $driver->contact_number }}</td>
                                                <td>{{ $driver->job_location }}</td>
                                                <td>{{ $driver->driver_status }}</td>
                                                <td>
                                                    {{-- @if ($driver->driver_status == 'inactive')
                                                    <a href="#">
                                                        <i class="fs-4 text-success align-middle me-2 fa fa-fw fa-check-circle"></i>
                                                    </a>
                                                @else
                                                    <a href="#">
                                                        <i class="fs-4 text-danger align-middle me-2 fa fa-fw fa-times-circle"></i>
                                                    </a> --}}
                                                    {{-- @endif --}}

                                                    @if ($driver->driver_type == 'permanent')
                                                        <a
                                                            href="{{ route('organization.hired.ft_driver_profile', $driver->driver_id) }}">
                                                            <i class="fs-4 text-primary fa fa-external-link-alt"></i>
                                                        </a>
                                                    @else
                                                        {{-- <a
                                                            href="{{ route('organization.hired.at_driver_profile', $driver->driver_id) }}">
                                                            <i class="fs-4 text-dark fa fa-external-link-alt"></i>
                                                        </a> --}}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        {{-- <tr>
                                            <td colspan="8" class="text-center">
                                                <div class="py-4">
                                                    <i class="fa fa-users fa-3x text-muted mb-3"></i>
                                                    <h5 class="text-muted">No hired drivers found</h5>
                                                    <p class="text-muted">There are no hired drivers at the moment.</p>
                                                </div>
                                            </td>
                                        </tr> --}}
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
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
@endsection()

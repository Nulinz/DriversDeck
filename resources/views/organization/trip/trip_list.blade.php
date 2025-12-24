@extends('organization.layouts.app')
@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row mb-xl-3 mb-2">
                <div class="d-none d-sm-block col-auto">
                    <h3><strong>Trip List</strong></h3>
                </div>
            </div>

            <div class="row">
                {{-- report tabs --}}
                <div class="col-md-12 col-xl-12">
                    {{-- Acting driver Details --}}
                    <div class="card mb-3">
                        <h5 class="card-title border-bottom mb-0 p-3">Acting Driver</h5>
                        <div class="card-body">
                            <table id="datatables-reponsive" class="table-striped table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Driver Name</th>
                                        <th>Driver Gender</th>
                                        <th>Contact Number</th>
                                        <th>From Location</th>
                                        <th>To Location</th>
                                        <th>From Date</th>
                                        <th>To Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                @foreach ($trips as $trip)
                                {{-- @foreach ($trip->hiredApplication as $application) --}}
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $trip->hiredApplication?->driver?->name ?? 'N/A' }}</td>
                                        <td>{{ $trip->hiredApplication?->driver?->gender ?? 'N/A' }}</td>
                                        <td>{{ $trip->con_number }}</td>
                                        <td>{{ $trip->st_loc }}</td>
                                        <td>{{ $trip->st_dest }}</td>
                                        <td>{{ date('m/d/Y', strtotime($trip->st_date)) }}</td>
                                        <td>{{ date('m/d/Y', strtotime($trip->end_date)) }}</td>
                                        <td>{{ $trip->status }}</td>
                                        <td>
                                            <a href="{{ route('organization.trip.profile', $trip->id) }}">
                                                <i class="fs-4 text-dark fa fa-external-link-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                {{-- <tbody class="">
                                    <tr>
                                        <td>1</td>
                                        <td>Karthik</td>
                                        <td>Male</td>
                                        <td>98794545565</td>
                                        <td>Salem</td>
                                        <td>Yercad</td>
                                        <td>05/28/2025</td>
                                        <td>05/28/2025</td>
                                        <td>Trip Started</td>
                                        <td><a href="{{ route('organization.trip.trip_profile') }}"><i class="fs-4 text-dark fa fa-external-link-alt"></i></a></td>
                                    </tr>
                                </tbody> --}}
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Datatables Responsive
            $("#datatables-reponsive").DataTable({
                responsive: true,
                "ordering": false
            });
        });
    </script>
@endsection()

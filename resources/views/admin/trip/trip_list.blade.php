@extends('admin.layouts.app')
@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row mb-2 mb-xl-3">
                <div class="col-auto d-none d-sm-block">
                    <h3><strong>Trip List</strong></h3>
                </div>
            </div>

            <div class="row">
                {{-- report tabs --}}
                <div class="col-md-12 col-xl-12">
                    {{-- Acting driver Details --}}
                    <div class="card mb-3">
                        <h5 class="p-3 card-title mb-0 border-bottom">Acting Driver</h5>
                        <div class="card-body">
                            <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Driver Name</th>
                                        <th>Driver Gender</th>
                                        <th>Client Name</th>
                                        <th>Contact Number</th>
                                        <th>From Location</th>
                                        <th>To Location</th>
                                        <th>From Date</th>
                                        <th>To Date</th>.
                                        <th>Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="">
                                    {{-- <tr>
                                                <td>1</td>
                                                <td>Karthik</td>
                                                <td>Male</td>
                                                <td>Swab</td>
                                                <td>98794545565</td>
                                                <td>Salem</td>
                                                <td>Yercad</td>
                                                <td>05/28/2025</td>
                                                <td>05/28/2025</td>
                                                <td><a href="{{ route('admin.trip.trip_profile') }}"><i class="fs-4 text-dark fa fa-external-link-alt"></i></a></td>
                                            </tr> --}}

                                    @forelse($trips as $index => $trip)
                                        @php
                                            $parts = explode('#', $trip->st_city);
                                            $city = !empty($parts[0]) ? $parts[0] : (isset($parts[1]) ? $parts[1] : '');
                                            $parts1 = explode('#', $trip->end_city);
                                            $city1 = !empty($parts1[0])
                                                ? $parts1[0]
                                                : (isset($parts1[1])
                                                    ? $parts1[1]
                                                    : '');
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $trip->driver_name ?? '-' }}</td>
                                            <td>{{ ucfirst($trip->gender) ?? '-' }}</td>
                                            <td>{{ $trip->client_name ?? '-' }}</td>
                                            <td>{{ $trip->driver_phone ?? '-' }}</td>
                                            <td>{{ $city ?? '-' }}</td>
                                            <td>{{ $city1 ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($trip->st_date)->format('d-m-Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($trip->end_date)->format('d-m-Y') }}</td>
                                            <td>{{ $trip->created_by_type }}</td> {{-- Shows 'corporate' or 'owner' --}}
                                            {{-- <td><a href="{{ route('admin.trip.trip_profile') }}"><i class="fs-4 text-dark fa fa-external-link-alt"></i></a></td> --}}
                                            <td>
                                                <a href="{{ route('admin.trip.trip_profile', $trip->trip_id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    View
                                                </a>
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
            // Datatables Responsive
            $("#datatables-reponsive").DataTable({
                responsive: true,
                "ordering": false
            });
        });
    </script>
@endsection()

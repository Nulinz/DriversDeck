@extends('organization.layouts.app')
@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row mb-2 mb-xl-3">
                <div class="col-auto d-none d-sm-block">
                    <h3><strong>Dashboard</strong></h3>
                </div>
            </div>
            <div class="row">
                {{-- fulltime driver table --}}
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-between align-items-center">
                                <h5 class="col-md-6 card-title text-dark">Fulltime Driver</h5>

                                <div>

                                </div>
                            </div>
                            <div class="row dt-row">
                                <div class="col-sm-12">
                                    <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Vehicle Type</th>
                                                <th>Vehicle Name</th>
                                                <th>Experience</th>
                                                <th>Location</th>
                                                <th>Join Date</th>
                                                <th>Salary</th>
                                                <th>Applied</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($per_jobs as $pjob)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $pjob->veh_type }}</td>
                                                    <td>{{ $pjob->veh_name }}</td>
                                                    <td>{{ $pjob->min_exp . ' - ' . $pjob->max_exp }}</td>
                                                    <td>{{ $pjob->job_location }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($pjob->join_date)->format('d-m-Y') }}</td>
                                                    <td>{{ $pjob->min_salary . ' - ' . $pjob->max_salary }}</td>
                                                    <td>{{ $pjob->applied_count }}</td>
                                                    <td> <a
                                                            href="{{ route('organization.vacancy.fulltime_list', $pjob->id) }}">
                                                            <i class="fs-4 text-dark fa fa-external-link-alt"></i></a></td>
                                                </tr>
                                            @endforeach
                                            {{-- <tr>
                                                <td>1</td>
                                                <td>Car</td>
                                                <td>1-5 Year</td>
                                                <td>Tokyo</td>
                                                <td>2018/11/28</td>
                                                <td>3121</td>
                                                <td>2</td>
                                            </tr> --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- acting driver table --}}
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-between align-items-center">
                                <h5 class="col-md-6 card-title text-dark">Acting Driver</h5>

                                <div>

                                </div>
                            </div>
                            <div class="row dt-row">
                                <div class="col-sm-12">
                                    <table id="datatables-reponsive2" class="table table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Vehicle Type</th>
                                                <th>Vehicle Name</th>
                                                {{-- <th>Experience</th> --}}
                                                <th>From Location</th>
                                                <th>To Location</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Applied</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($trips as $ac)
                                                @php
                                                    $parts = explode('#', $ac->st_city);
                                                    $city = !empty($parts[0]) ? $parts[0] : $parts[1];
                                                    $parts1 = explode('#', $ac->end_city);
                                                    $city1 = !empty($parts1[0]) ? $parts1[0] : $parts1[1];

                                                    // dd($parts, $parts1);

                                                @endphp
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $ac->veh_type }}</td>
                                                    <td>{{ $ac->veh_name }}</td>
                                                    {{-- <td>{{ $ac->min_exp . ' - ' . $ac->max_exp }}</td> --}}
                                                    <td>{{ $city }}</td>
                                                    <td>{{ $city1 }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($ac->st_date)->format('d-m-Y') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($ac->end_date)->format('d-m-Y') }}</td>
                                                    <td>{{ $ac->applied_count }}</td>
                                                    <td> <a
                                                            href="{{ route('organization.vacancy.acting_list', $ac->id) }}">
                                                            <i class="fs-4 text-dark fa fa-external-link-alt"></i></a></td>
                                                </tr>
                                            @endforeach

                                            {{-- <tr>
                                                <td>1</td>
                                                <td>Airi Satou</td>
                                                <td>Accountant</td>
                                                <td>Tokyo</td>
                                                <td>Osaka</td>
                                                <td>2018/11/28</td>
                                                <td>2018/11/28</td>
                                                <td>55</td>
                                            </tr> --}}
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
    {{-- <script src="{{ asset('assets/js/jquery.js') }}"></script> --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Datatables Responsive
            $("#datatables-reponsive").DataTable({
                responsive: true,
                "ordering": false,
                "lengthMenu": [
                    [10, 25, 50, "ALL"],
                    [10, 25, 50, "All"]
                ]
            });

            $("#datatables-reponsive2").DataTable({
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

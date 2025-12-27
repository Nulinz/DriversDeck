@extends('organization.layouts.app')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row mb-2 mb-xl-3">
                <div class="col-auto d-none d-sm-block">
                    <h3><strong>Vacancy List</strong></h3>
                </div>
            </div>
            <div class="card">
                <div class="d-flex justify-content-end border-bottom p-3">
                    @if (auth('corporate')->user()->status == 'approved')
                        <a class="btn btn-md btn-primary" href="{{ route('organization.vacancy.add_vacancy') }}"><i
                                class="fa fa-fw fa-plus"></i> Add Vacancy</a>
                    @endif
                </div>
                <div class="card-body">

                    <div class="row dt-row">
                        <div class="col-sm-12">

                            <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Job Type</th>
                                        <th>Vehicle Type</th>
                                        <th>Vehicle Name</th>
                                        <th>Experience</th>
                                        <th>Location</th>
                                        <th>Salary</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jobs as $index => $job)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $job->job_type }}</td>
                                            <td>{{ $job->veh_type }}</td>
                                            <td>{{ $job->veh_name }}</td>
                                            <td>{{ $job->experience }}</td>
                                            <td>{{ $job->job_location }}</td>
                                            <td>{{ $job->salary }}</td>
                                            <td><span class="badge badge-success-light">{{ $job->status }}</span></td>
                                            <td>
                                                {{-- <a href=""><i
                                                        class="fs-4 text-danger align-middle me-2 fa fa-fw fa-times-circle"></i></a> --}}
                                                <a
                                                    href="{{ $job->job_type == 'Acting'
                                                        ? route('organization.vacancy.acting_list', $job->id)
                                                        : route('organization.vacancy.fulltime_list', $job->id) }}">
                                                    <i class="fs-4 text-dark fa fa-external-link-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        {{-- <tr>
                                                <td>1</td>
                                                <td>Acting  </td>
                                                <td>Bus</td>
                                                <td>1-8 years</td>
                                                <td>Salem</td>
                                                <td>12,000</td>
                                                <td><span class="badge badge-danger-light">Inactive</span></td>
                                               <td>
                                                    <a href=""><i class="fs-4 text-success align-middle me-2 fa fa-fw fa-check-circle"></i></a>
                                                  <a href="{{ route('organization.vacancy.acting_list') }}"><i
                                                    class="fs-4 text-dark fa fa-external-link-alt"></i></a>
                                                </td>
                                            </tr> --}}
                                    @endforeach
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
                    [25, 50, 100, "All"],
                    [25, 50, 100, "All"]
                ]
            });
        });
    </script>
@endsection()

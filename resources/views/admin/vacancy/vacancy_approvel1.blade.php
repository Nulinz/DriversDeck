@extends('admin.layouts.app')
@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row mb-2 mb-xl-3">
                <div class="col-auto d-none d-sm-block">
                    <h3><strong>Vacancy Approval List</strong></h3>
                </div>
            </div>

            <div class="row">
                {{-- report tabs --}}
                <div class="col-md-12 col-xl-12">
                    {{-- Acting driver Details --}}
                    <div class="card mb-3">
                        {{-- <h5 class="p-3 card-title mb-0 border-bottom">Acting Driver</h5> --}}
                        <div class="card-body">
                            <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Corprate Name</th>
                                        <th>Job Type</th>
                                        {{-- <th>Driver Name</th>
                                        <th>Contact Number</th> --}}
                                        <th>Contact</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="">


                                    @forelse($vacancies as $index => $vacancy)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><a
                                                    href="{{ route('admin.corprate.corprate_profile', ['id' => $vacancy->corp_id]) }}">{{ $vacancy->corporate_name }}</a>
                                            </td>
                                            <td>{{ $vacancy->job_type }}</td>
                                            {{-- <td>{{ $vacancy->driver_name }}</td>
                                            <td>{{ $vacancy->phone }}</td> --}}
                                            <td>{{ $vacancy->contact }}</td>
                                            <td>{{ $vacancy->job_location }}</td>
                                            <td>{{ $vacancy->status }}</td>
                                            {{-- <td>
                                                <a class="btn btn-sm btn-secondary" href="">Approve</a>
                                                <a class="btn btn-sm btn-secondary ms-lg-2" href="">Reject</a>
                                            </td> --}}

                                            <td>
                                                <form action="{{ route('admin.vacancy.approval') }}" method="POST"
                                                    style="display: inline-block;">
                                                    @csrf
                                                    <input type="hidden" name="type"
                                                        value="{{ $vacancy->job_category }}">
                                                    <input type="hidden" name="id" value="{{ $vacancy->job_id }}">
                                                    <input type="hidden" name="action" value="approve">
                                                    <button type="submit" class="btn btn-sm btn-success"
                                                        onclick="return confirm('Are you sure you want to approve this vacancy?')">Approve</button>
                                                </form>

                                                <form action="{{ route('admin.vacancy.approval') }}" method="POST"
                                                    style="display: inline-block; margin-left: 10px;">
                                                    @csrf
                                                    <input type="hidden" name="type"
                                                        value="{{ $vacancy->job_category }}">
                                                    <input type="hidden" name="id" value="{{ $vacancy->job_id }}">
                                                    <input type="hidden" name="action" value="reject">
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to reject this vacancy?')">Reject</button>
                                                </form>
                                            </td>

                                        @empty
                                            {{-- <tr>
                                            <td colspan="9">No vacancies found.</td>
                                        </tr> --}}
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
                "ordering": true
            });
        });
    </script>
@endsection()

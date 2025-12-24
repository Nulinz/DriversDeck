@extends('admin.layouts.app')
@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row mb-2 mb-xl-3">
                <div class="col-auto d-none d-sm-block">
                    <h3><strong>Trip Cancelled List</strong></h3>
                </div>
            </div>

            <div class="row">
                {{-- report tabs --}}
                <div class="col-md-12 col-xl-12">
                    <div class="nav nav-tabs d-flex justify-content-start align-items-center gap-x-4 gap-xl-4 mb-3"
                        role="tablist">
                        <a class="active" data-bs-toggle="tab" href="#Cancelled" role="tab" aria-selected="true">
                            Cancelled
                        </a>
                        <a class="" data-bs-toggle="tab" href="#Pending" role="tab" aria-selected="false"
                            tabindex="-1">
                            Pending
                        </a>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="Cancelled" role="tabpanel">
                            {{-- Acting driver Details --}}
                            <div class="card mb-3">
                                <h5 class="p-3 card-title mb-0 border-bottom">Cancel List</h5>
                                <div class="card-body">
                                    <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Cancelled By</th>
                                                <th>Drive Name</th>
                                                <th>Owner Name</th>
                                                <th>Trip Date</th>
                                                <th>Cancel Date</th>
                                                <th>Remarks</th>
                                                {{-- <th>Action</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            @foreach ($handledReports as $key => $report)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ ucfirst($report->type) }}</td>
                                                    <td>{{ $report->driver_name ?? 'N/A' }}</td>
                                                    <td>{{ $report->owner_name ?? 'N/A' }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($report->trip_date)->format('d-m-Y') }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($report->created_at)->format('d-m-Y') }}
                                                    </td>
                                                    <td>{{ $report->remarks ?? 'N/A' }}</td>
                                                    {{-- <td>
                                                        <!-- BEGIN primary modal -->
                                                        <div class="modal fade" id="centeredModalPrimary" tabindex="-1"
                                                            role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="fs-4 fw-bold modal-title">Trip Cancelled
                                                                        </h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form action="" method="post">
                                                                            <div class="mb-3 col-md-12">
                                                                                <label
                                                                                    class="form-label fw-bold">Remarks</label>
                                                                                <textarea name="" id="" class="form-control" rows="5"></textarea>
                                                                            </div>
                                                                            <div class="modal-footer border-0 p-0">
                                                                                <button type="button"
                                                                                    class="btn btn-secondary"
                                                                                    data-bs-dismiss="modal">Close</button>
                                                                                <input type="button"
                                                                                    class="btn btn-primary w-25"
                                                                                    name="" value="Save">
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- END primary modal -->
                                                        <a data-bs-toggle="modal" data-bs-target="#centeredModalPrimary"><i
                                                                class="fs-4 text-dark fa fa-fw fa-edit"></i></a>
                                                    </td> --}}
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="Pending" role="tabpanel">
                            {{-- Pending Details --}}
                            <div class="card mb-3">
                                <h5 class="p-3 card-title mb-0 border-bottom">Cancel List</h5>
                                <div class="card-body">
                                    <table id="datatables-reponsive-2" class="table table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Cancelled By</th>
                                                <th>Drive Name</th>
                                                <th>Owner Name</th>
                                                <th>Trip Date</th>
                                                <th>Cancel Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            @foreach ($reports as $key => $report)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ ucfirst($report->type) }}</td>
                                                    <td>{{ $report->driver_name ?? 'No Driver' }}</td>
                                                    <td>{{ $report->owner_name ?? 'No Owner' }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($report->trip_date)->format('d-m-Y') }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($report->created_at)->format('d-m-Y') }}
                                                    </td>
                                                    <td>{{ $report->status }}</td>
                                                    {{-- <td>
                                                        <a class="btn btn-sm btn-secondary" href="">Approve</a>
                                                        <a class="btn btn-sm btn-secondary ms-lg-2"
                                                            href="">Reject</a>
                                                    </td> --}}
                                                    <td>
                                                        <form action="{{ route('admin.trip_cancel.handle') }}"
                                                            method="POST" style="display:inline-block;">
                                                            @csrf
                                                            <input type="hidden" name="id"
                                                                value="{{ $report->id }}">
                                                            <input type="hidden" name="action" value="approve">
                                                            <button type="submit" class="btn btn-sm btn-success"
                                                                onclick="return confirm('Are you sure you want to approve this cancel request?')">Approve</button>
                                                        </form>

                                                        {{-- <form action="{{ route('admin.trip_cancel.handle') }}"
                                                            method="POST" style="display:inline-block;">
                                                            @csrf
                                                            <input type="hidden" name="id"
                                                                value="{{ $report->id }}">
                                                            <input type="hidden" name="action" value="reject">
                                                            <button type="submit" class="btn btn-sm btn-danger ms-lg-2"
                                                                onclick="return confirm('Are you sure you want to reject this cancel request?')">Reject</button> --}}
                                                        </form>
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
            </div>
        </div>
    </main>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Datatables Responsive
            $("#datatables-reponsive").DataTable({
                responsive: true,
                'ordering': false
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            // Datatables Responsive
            $("#datatables-reponsive-2").DataTable({
                responsive: true,
                'ordering': false
            });


            // 3) WHEN the “Permissions” tab is actually shown, force a recalc on Table #2:
            $('a[data-bs-toggle="tab"][href="#Pending"]').on("shown.bs.tab", function(e) {
                // Explicitly adjust only the second DataTable:
                $("#datatables-reponsive-2")
                    .DataTable()
                    .columns.adjust()
                    .responsive.recalc();
            });
        });
    </script>
@endsection()

@extends('admin.layouts.app')
@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row mb-2 mb-xl-3">
                <div class="col-auto d-none d-sm-block">
                    <h3><strong>Corprate Report List</strong></h3>
                </div>
            </div>

            <div class="row">
                {{-- report tabs --}}
                <div class="col-md-12 col-xl-12">
                    <div class="nav nav-tabs d-flex justify-content-start align-items-center gap-x-4 gap-xl-4 mb-3"
                        role="tablist">
                        <a class="active" data-bs-toggle="tab" href="#Report" role="tab" aria-selected="true">
                            Report
                        </a>
                        <a class="" data-bs-toggle="tab" href="#Feedback" role="tab" aria-selected="false"
                            tabindex="-1">
                            Feedback
                        </a>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="Report" role="tabpanel">
                            {{-- Acting driver Details --}}
                            <div class="card mb-3">
                                <h5 class="p-3 card-title mb-0 border-bottom">Acting Driver</h5>
                                <div class="card-body">
                                    <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Owner Name</th>
                                                <th>Drive Name</th>
                                                <th>Drive Type</th>
                                                <th>Reason Type</th>
                                                <th style="width: 25%">Description</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            @foreach ($tripReports as $key => $report)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $report->owner_name ?? 'No Owner Found' }}</td>
                                                    <td>{{ $report->driver_name }}</td>
                                                    <td>{{ ucfirst($report->driver_type) }}</td>
                                                    <td>{{ $report->reason ?? 'N/A' }}</td>
                                                    <td>{{ $report->remarks ?? 'N/A' }}</td>
                                                    <td>{{ $report->report_sts ?? 'N/A' }}</td>
                                                    {{-- <td>
                                                        <a class="btn btn-sm btn-secondary" href="">Approve</a>
                                                        <a class="btn btn-sm btn-secondary ms-lg-2"
                                                            href="">Reject</a>
                                                    </td> --}}
                                                    <td>
                                                        <form action="{{ route('admin.customer.approve') }}" method="POST"
                                                            style="display:inline-block;">
                                                            @csrf
                                                            <input type="hidden" name="id"
                                                                value="{{ $report->id }}">
                                                            <input type="hidden" name="action" value="approve">
                                                            <button class="btn btn-sm btn-success" type="submit"
                                                                onclick="return confirm('Are you sure you want to approve this report?')">Approve</button>
                                                        </form>

                                                        <form action="{{ route('admin.customer.approve') }}" method="POST"
                                                            style="display:inline-block;">
                                                            @csrf
                                                            <input type="hidden" name="id"
                                                                value="{{ $report->id }}">
                                                            <input type="hidden" name="action" value="reject">
                                                            <button class="btn btn-sm btn-danger ms-lg-2" type="submit"
                                                                onclick="return confirm('Are you sure you want to reject this report?')">Reject</button>
                                                        </form>

                                                    </td>

                                                </tr>
                                            @endforeach
                                            {{-- <tr>
                                                <td>2</td>
                                                <td>MK Finance</td>
                                                <td>Lorem</td>
                                                <td>Full Time</td>
                                                <td>Wrong Driver</td>
                                                <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Amet debitis
                                                    animi in tempore aperiam vero aspernatur cupiditate iusto praesentium.
                                                    Cum.
                                                </td>
                                                <td>-</td>
                                                <td>
                                                    <a class="btn btn-sm btn-secondary" href="">Approve</a>
                                                    <a class="btn btn-sm btn-secondary ms-lg-2" href="">Reject</a>
                                                </td>
                                            </tr> --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- full time driver Details --}}
                        </div>
                    </div>
                    <div class="tab-pane fade" id="Feedback" role="tabpanel">
                        {{-- Hired Details --}}
                        <div class="card mb-3">
                            <h5 class="p-3 card-title mb-0 border-bottom">Hired List</h5>
                            <div class="card-body">
                                <table id="datatables-reponsive-2" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Owner Name</th>
                                            <th>Drive Name</th>
                                            <th>Rating</th>
                                            <th>Remarks</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="">
                                        @foreach ($feedbackReports as $key => $feedback)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $feedback->owner_name ?? 'No Owner Found' }}</td>
                                                <td>{{ $feedback->driver_name ?? 'No Driver Found' }}</td>
                                                <td>
                                                    @for ($i = 1; $i <= $feedback->rating; $i++)
                                                        <i class="text-warning align-middle me-1 fs-4 fa fa-fw fa-star"></i>
                                                    @endfor
                                                </td>


                                                <td>{{ $feedback->remarks }}</td>
                                                <td>{{ ucfirst($feedback->status) }}</td>
                                                <td>
                                                   <form action="{{ route('admin.customer.feedback') }}" method="POST"
                                                            style="display:inline-block;">
                                                            @csrf
                                                            <input type="hidden" name="id"
                                                                value="{{ $feedback->id }}">
                                                            <input type="hidden" name="action" value="approve">
                                                            <button class="btn btn-sm btn-success" type="submit"
                                                                onclick="return confirm('Are you sure you want to approve this feedback?')">Approve</button>
                                                        </form>

                                                        <form action="{{ route('admin.customer.feedback') }}" method="POST"
                                                            style="display:inline-block;">
                                                            @csrf
                                                            <input type="hidden" name="id"
                                                                value="{{ $feedback->id }}">
                                                            <input type="hidden" name="action" value="reject">
                                                            <button class="btn btn-sm btn-danger ms-lg-2" type="submit"
                                                                onclick="return confirm('Are you sure you want to reject this feedback?')">Reject</button>
                                                        </form>
                                                </td>

                                            </tr>
                                        @endforeach
                                        {{-- <tr>
                                            <td>1</td>
                                            <td>MK Finance</td>
                                            <td>Lorem</td>
                                            <td>
                                                <a href=""><i
                                                        class="text-warning align-middle me-1 fs-4 fa fa-fw fa-star"></i></a>
                                                <a href=""><i
                                                        class="text-warning align-middle me-1 fs-4 fa fa-fw fa-star"></i></a>

                                            </td>
                                            <td>Lorem ipsum dolor sit amet consectetur adipisicing el
                                            </td>
                                            <td>-</td>
                                            <td>
                                                <a class="btn btn-sm btn-secondary" href="">Approve</a>
                                                <a class="btn btn-sm btn-secondary ms-lg-2" href="">Recject</a>
                                            </td>
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Datatables Responsive
            $("#datatables-reponsive").DataTable({
                responsive: true,
                ordering: false,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    ["5", "10", "25", "50", "All"]
                ]
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            // Datatables Responsive
            $("#datatables-reponsive-2").DataTable({
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
                $("#datatables-reponsive-2")
                    .DataTable()
                    .columns.adjust()
                    .responsive.recalc();
            });
        });
    </script>
@endsection()

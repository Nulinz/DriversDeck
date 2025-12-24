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
                    {{-- Acting driver Details --}}
                    <div class="card mb-3">
                        <h5 class="p-3 card-title mb-0 border-bottom">Acting Driver</h5>
                        <div class="card-body">
                           <table id="datatables-reponsive-2" class="table table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Drive Name</th>
                                                <th>Driver Type</th>
                                                <th>Change To</th>
                                                <th>Requested Date</th>
                                                <th>Description</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            <tr>
                                                <td>1</td>
                                                <td>MK Finance</td>
                                                <td>Lorem</td>
                                                <td>Full Time</td>
                                                <td>Wrong Driver</td>
                                                <td></td>
                                                <td>-</td>
                                                <td>

                                                    	<!-- BEGIN primary modal -->
                                                    <div class="modal fade" id="centeredModalPrimary" tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="fs-4 fw-bold modal-title">Trip Cancelled</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                   <form action="" method="post">
                                                                    <div class="mb-3 col-md-12">
                                                                        <label class="form-label fw-bold">Description</label>
                                                                        <textarea name="" id="" class="form-control" rows="5"></textarea>
                                                                    </div>
                                                                    <div class="modal-footer border-0 p-0">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                        <input type="button" class="btn btn-primary w-25" name="" value="Save">
                                                                    </div>
                                                                   </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- END primary modal -->
                                                    <a data-bs-toggle="modal" class="btn btn-sm btn-secondary" data-bs-target="#centeredModalPrimary">Approve</a>

                                                    <a class="btn btn-sm btn-secondary ms-lg-2" href="">Reject</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>MK Finance</td>
                                                <td>Lorem</td>
                                                <td>Full Time</td>
                                                <td>Wrong Driver</td>
                                                <td>22/05/2025</td>
                                                <td><span class="badge badge-success-light">Approved</span></td>
                                                <td>

                                                </td>
                                            </tr>
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
                'ordering': false
            });
        });
    </script>
@endsection()

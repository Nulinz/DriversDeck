@extends('admin.layouts.app')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row mb-2 mb-xl-3">
                <div class="col-auto d-none d-sm-block">
                    <h3><strong>Report</strong></h3>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th><input style="width: 15px; height:15px;" type="checkbox" name="" id=""></th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Contact Number</th>
                                        <th>Email</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="checkbox" name="" id=""></td>
                                        <td>Tiger Nixon</td>
                                        <td>Corprate</td>
                                        <td>Edinburgh</td>
                                        <td>61</td>
                                        <td>2023/04/25</td>
                                        <td><span class="badge badge-success-light">Active</span></td>
                                        <td>
                                                <a href=""><i class="fs-4 text-danger align-middle me-2 fa fa-times-circle"></i></a>
                                                <a href="{{ route('admin.corprate.corprate_profile') }}"><i class="fs-4 text-dark fa fa-external-link-alt"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" name="" id=""></td>
                                        <td>Garrett Winters</td>
                                        <td>Owner</td>
                                        <td>Tokyo</td>
                                        <td>63</td>
                                        <td>2023/07/25</td>
                                       <td><span class="badge badge-secondary-light">Inactive</span></td>
                                        <td>

                                               <a href=""><i class="fs-4 text-success align-middle me-2 fa fa-fw fa-check-circle"></i></a>
                                              <a href="{{ route('admin.corprate.owner_profile') }}"><i class="fs-4 text-dark fa fa-external-link-alt"></i></a>

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
                responsive: true
            });
        });
    </script>
@endsection()

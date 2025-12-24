@extends('admin.layouts.app')
@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row mb-2 mb-xl-3">
                <div class="col-auto d-none d-sm-block">
                    <h3><strong>Edit Permission</strong></h3>
                </div>
            </div>

            <div class="row">
                {{-- report tabs --}}
                <form>
                    <div class="card mb-3">
                        <h5 class="p-3 card-title mb-0 border-bottom">Permission</h5>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                            <table class="table mb-0 table-hover">
                                <thead>
                                    <tr>
                                         <th class="text-muted" style="width:10%">#</th>
                                        <th class="text-muted" style="width:20%">Designation</th>
                                        <th class="text-muted" style="width:20%">Approve / Reject</th>
                                        <th class="text-muted" style="width:20%">Active / Inactive</th>
                                        <th class="text-muted" style="width:20%">View</th>
                                        <th class="text-muted" style="width:20%">Edit</th>
                                    </tr>
                                </thead>
                                <tbody class="">
                                    <tr>
                                        <td>01</td>
                                        <td class="fw-bold">Corprate</td>
                                        <td><input type="checkbox" class="form-check-input p-2 border-2" name=""
                                                id="">
                                        </td>
                                        <td><input type="checkbox" class="form-check-input p-2 border-2" name=""
                                                id="">
                                        </td>
                                        <td><input type="checkbox" class="form-check-input p-2 border-2" name=""
                                                id="">
                                        </td>
                                        <td><input type="checkbox" class="form-check-input p-2 border-2" name=""
                                                id="">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>02</td>
                                        <td class="fw-bold">Drivers</td>
                                        <td><input type="checkbox" class="form-check-input p-2 border-2" name=""
                                                id="">
                                        </td>
                                        <td><input type="checkbox" class="form-check-input p-2 border-2" name=""
                                                id="">
                                        </td>
                                        <td><input type="checkbox" class="form-check-input p-2 border-2" name=""
                                                id="">
                                        </td>
                                        <td><input type="checkbox" class="form-check-input p-2 border-2" name=""
                                                id="">
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>03</td>
                                        <td class="fw-bold">Apporoval</td>
                                        <td><input type="checkbox" class="form-check-input p-2 border-2" name=""
                                                id="">
                                        </td>
                                        <td><input type="checkbox" class="form-check-input p-2 border-2" name=""
                                                id="">
                                        </td>
                                        <td><input type="checkbox" class="form-check-input p-2 border-2" name=""
                                                id="">
                                        </td>
                                        <td><input type="checkbox" class="form-check-input p-2 border-2" name=""
                                                id="">
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>04</td>
                                        <td class="fw-bold">Request</td>
                                        <td><input type="checkbox" class="form-check-input p-2 border-2" name=""
                                                id="">
                                        </td>
                                        <td><input type="checkbox" class="form-check-input p-2 border-2" name=""
                                                id="">
                                        </td>
                                        <td><input type="checkbox" class="form-check-input p-2 border-2" name=""
                                                id="">
                                        </td>
                                        <td><input type="checkbox" class="form-check-input p-2 border-2" name=""
                                                id=""></td>

                                    </tr>
                                    <tr>
                                        <td>05</td>
                                        <td class="fw-bold">Customer Report</td>
                                        <td><input type="checkbox" class="form-check-input p-2 border-2" name=""
                                                id=""></td>
                                        <td><input type="checkbox" class="form-check-input p-2 border-2" name=""
                                                id=""></td>
                                        <td><input type="checkbox" class="form-check-input p-2 border-2" name=""
                                                id=""></td>
                                        <td><input type="checkbox" class="form-check-input p-2 border-2" name=""
                                                id=""></td>

                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 col-md-2">
                        <input type="submit" class="btn btn-primary w-100" value="Save">
                    </div>
                <form>
            </div>
        </div>
    </main>
@endsection()

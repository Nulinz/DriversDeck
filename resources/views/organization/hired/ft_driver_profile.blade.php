@extends('organization.layouts.app')
@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row mb-2 mb-xl-3">
                <div class="col-auto d-none d-sm-block">
                    <h3><strong>Drivers</strong></h3>
                </div>
            </div>

            <div class="row">
                {{-- profile details --}}
                <div class="col-md-5 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-start">
                                <img class="profile_img" src="{{ asset('assets/images/avatar.png') }}" alt="">
                                <div>
                                    <h4 class="fw-bold text-dark ms-3 mb-0">User Name</h4>
                                    <p class="fw-bold text-muted fs-5 ms-3 mt-2 mb-0">{{ $driver->name }}</p>
                                </div>

                            </div>
                            <hr>
                            <h5 class="fs-4 mb-3">Basic Details</h5>
                            <div class="d-flex align-items-center justify-content-between mb-0_8">
                                <h5 class="fs-6 text-muted mb-0 fw-bold">Date of Birth</h5>
                                <p class="fs-6 text-dark mb-0 fw-bold">
                                    {{ $driver->dob ? \Carbon\Carbon::parse($driver->dob)->format('d-m-Y') : '' }}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-0_8">
                                <h6 class="text-muted mb-0 fw-bold">Contact Number</h6>
                                <p class="text-dark mb-0 fw-bold">{{ $driver->phone }}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-0_8">
                                <h6 class="text-muted mb-0 fw-bold">Alternate Number</h6>
                                <p class="text-dark mb-0 fw-bold">313554686</p>
                            </div>
                            {{-- <div class="d-flex align-items-center justify-content-between mb-0_8">
                                <h6 class="text-muted mb-0 fw-bold">Marital Status</h6>
                                <p class="text-dark mb-0 fw-bold">{{ $driver->marital_status }}</p>
                            </div> --}}
                            <div class="d-flex align-items-center justify-content-between mb-0_8">
                                <h6 class="text-muted mb-0 fw-bold">Gender</h6>
                                <p class="text-dark mb-0 fw-bold">{{ $driver->gender }}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-0_8">
                                <h6 class="text-muted mb-0 fw-bold">Address Line 1</h6>
                                <p class="text-dark mb-0 fw-bold">{{ $driver->c_ad }}</p>
                            </div>
                            {{-- <div class="d-flex align-items-center justify-content-between mb-0_8">
                                <h6 class="text-muted mb-0 fw-bold">Address Line 2</h6>
                                <p class="text-dark mb-0 fw-bold">Address</p>
                            </div> --}}
                            <div class="d-flex align-items-center justify-content-between mb-0_8">
                                <h6 class="text-muted mb-0 fw-bold">City</h6>
                                <p class="text-dark mb-0 fw-bold">{{ $driver->c_city }}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="text-muted mb-0 fw-bold">State</h6>
                                <p class="text-dark mb-0 fw-bold">{{ $driver->c_state }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- profile tabs --}}
                <div class="col-md-7 col-xl-8">
                    <div class="nav nav-tabs d-flex justify-content-start align-items-center gap-x-4 gap-xl-4 mb-3"
                        role="tablist">
                        <a class="active" data-bs-toggle="list" href="#Details" role="tab" aria-selected="true">
                            Details
                        </a>
                        <a class="" data-bs-toggle="list" href="#Experiance" href="#" role="tab"
                            aria-selected="false" tabindex="-1">
                            Experience
                        </a>
                        {{-- <a class="" data-bs-toggle="list" href="#Report" role="tab" aria-selected="false"
                            tabindex="-1">
                            Report
                        </a>
                        <a class="" data-bs-toggle="list" href="#Feedback" href="#" role="tab"
                            aria-selected="false" tabindex="-1">
                            Feedback
                        </a> --}}
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="Details" role="tabpanel">
                            {{-- License Details --}}
                            <div class="card mb-3">
                                <h5 class="p-3 card-title mb-0 border-bottom">License Details</h5>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Cover of Vehicle</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->cov }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Issued RTo</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->issued_rto }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Date Of Issue</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->date_of_issue }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Validity From</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->v_from }}</p>
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Validity To</h5>
                                            <p class="fs-6 text-dark mb-0">
                                                {{ $driver->v_to ? \Carbon\Carbon::parse($driver->v_to)->format('d-m-Y') : '' }}
                                            </p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Batch No</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->batch_no }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Batch Issue Date</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->batch_issue_date }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Batch Issue By</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->batch_issued_by }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Card Serial No</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->card_serial_no }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Relative Name (C/o)</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->cof }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Id Proof Details --}}
                            <div class="card mb-3">
                                <h5 class="p-3 card-title mb-0 border-bottom">Id Proof</h5>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">License Number</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->l_no }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">License Image</h5>
                                            <a href="{{ asset($driver->l_img) }}" download><i
                                                    class="align-middle text-dark fs-4 fa fa-fw fa-download"></i></a>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Aadhar Number</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->ad_num }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Aadher Image</h5>
                                            <a href="{{ asset($driver->aadhaar_img) }}" download><i
                                                    class="align-middle text-dark fs-4 fa fa-fw fa-download"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Experiance" role="tabpanel">
                            {{-- Experience Details --}}
                            <div class="card mb-3">
                                <h5 class="p-3 card-title mb-0 border-bottom">Experience</h5>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Experience</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->exp_year }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Company Name</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->p_com_name }}</p>
                                        </div>
                                        {{-- <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Reliving Date</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->p_com_name }}</p>
                                        </div> --}}
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Current Salary</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->current_salary }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Location</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->com_location }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">Contact Number</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->contact_number }}</p>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h5 class="fs-6 text-muted mb-2 fw-bold">PF</h5>
                                            <p class="fs-6 text-dark mb-0">{{ $driver->pf }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="tab-pane fade" id="Report" role="tabpanel">
                            
                            <div class="row">
                                <div class="card">
                                    <div class="card-body">
                                        <form>
                                            <div class="row">
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label fw-bold">Reason</label>
                                                    <select class="form-select form-select-lg" name="" id="">
                                                        <option value="" selected disabled>Select Option</option>
                                                        <option value="">Reason</option>
                                                        <option value="">Reason</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label fw-bold">Remarks</label>
                                                    <input type="text" class="form-control form-control-lg" name="" id="">
                                                </div>
                                            </div>
                                            <div class="mt-2 col-md-3">
                                                <input type="submit" class="btn btn-primary btn-lg w-100" value="Save">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card mb-3">
                                    <h5 class="p-3 card-title mb-0 border-bottom">Report</h5>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table mb-0 table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="text-muted">#</th>
                                                        <th class="text-muted">Client Name</th>
                                                        <th class="text-muted">Reason Type</th>
                                                        <th class="text-muted">Remarks</th>
                                                        <th class="text-muted">Date</th>
                                                    </tr>
                                                </thead>

                                                <tbody class="">
                                                    <tr>
                                                        <td>01</td>
                                                        <td>Corprate</td>
                                                        <td>Nil</td>
                                                        <td>lorem</td>
                                                        <td>02/5/2025</td>
                                                    </tr>
                                                    <tr>
                                                        <td>01</td>
                                                        <td>Corprate</td>
                                                        <td>Nil</td>
                                                        <td>lorem</td>
                                                        <td>02/5/2025</td>
                                                    </tr>
                                                    <tr>
                                                        <td>01</td>
                                                        <td>Corprate</td>
                                                        <td>Nil</td>
                                                        <td>lorem</td>
                                                        <td>02/5/2025</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Feedback" role="tabpanel">  
                            <div class="card mb-3">
                            <div class="d-flex p-3 align-items-center justify-content-between border-bottom">
                                    <h5 class="card-title mb-0 ">Feedback</h5>
                                    <a class="btn btn-md btn-primary" data-bs-toggle="modal" data-bs-target="#feed_pop"><i class="fa fa-fw fa-plus"></i> Add Feedback</a>
                            </div>
                             <!-- BEGIN primary modal -->
                            <div class="modal model-sm fade" id="feed_pop" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                    <div class="modal-content">

                                        <div class="modal-body">
                                             <h5 class="fs-4 fw-bold modal-title">Ratings!</h5>
                                            <p class="my-3">Looks like you haven’t added anything to your cart yet</p>
                                            <form action="" method="post">

                                                <div class="mb-3 col-md-12">

                                                    <input type="text" name="" id="" class="form-control" minlength="6" data-toggle="tooltip" data-placement="top"  title="Password needs to be at least 6 characters long">
                                                </div>
                                                <div class="mb-3 col-md-12">
                                                    <label class="form-label fw-bold">Remarks</label>
                                                    <textarea class="form-control" name="" id="" rows="4"></textarea>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                         <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Cancel</button>
                                                    </div>
                                                    <div class="col-6">
                                                        <input type="button" class="btn btn-primary w-100" name="" value="Submit">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END primary modal -->
                            <div class="card-body">
                               <table id="datatables-reponsive-2" class="table table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Client Name</th>
                                                <th>Rating</th>
                                                <th>Remarks</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            <tr>
                                                <td>1</td>
                                                <td>MK Finance</td>
                                                <td>
                                                    <a href=""><i class="text-warning align-middle me-1 fs-5 fa fa-fw fa-star"></i></a>
                                                    <a href=""><i class="text-warning align-middle me-1 fs-5 fa fa-fw fa-star"></i></a>
                                                    <a href=""><i class="text-warning align-middle me-1 fs-5 fa fa-fw fa-star"></i></a>
                                                    <a href=""><i class="text-warning align-middle me-1 fs-5 fa fa-fw fa-star"></i></a>
                                                    <a href=""><i class="text-warning align-middle me-1 fs-5 fa fa-fw fa-star"></i></a>
                                                </td>
                                                <td>Lorem</td>
                                                <td>5/22/1995</td>
                                            </tr>
                                          <tr>
                                                <td>1</td>
                                                <td>MK Finance</td>
                                                <td>
                                                   <a href=""><i class="text-warning align-middle me-1 fs-5 fa fa-fw fa-star"></i></a>
                                                    <a href=""><i class="text-warning align-middle me-1 fs-5 fa fa-fw fa-star"></i></a>
                                                </td>
                                                <td>Lorem</td>
                                                <td>5/22/1995</td>
                                            </tr>
                                        </tbody>
                                    </table>
                            </div>
                            </div>
                        </div> --}}
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

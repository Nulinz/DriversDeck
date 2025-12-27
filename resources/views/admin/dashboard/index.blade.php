@extends('admin.layouts.app')
@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row mb-2 mb-xl-3">
                <div class="col-auto d-none d-sm-block">
                    <h3><strong>Dashboard</strong></h3>
                </div>
            </div>
            {{-- basic cards --}}
            <div class="row">
{{-- Acting Driver Card --}}
<div class="col-12 col-md-6 col-xl-3 d-flex">
    <a href="{{ route('admin.candidate.index', ['type' => 'acting']) }}" class="w-100 text-decoration-none text-dark">
        <div class="card flex-fill">
            <div class="card-body">
                <div class="row">
                    <div class="col mb-2">
                        <h5 class="card-title mb-0">Acting Driver</h5>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <h3 class="mt-0 mb-0 fw-bold">{{ isset($all[2]) ? $all[2]->total ?? 0 : 0 }}</h3>
                    <div class="mb-0"></div>
                </div>
            </div>
        </div>
    </a>
</div>

{{-- Full Time Driver Card --}}
<div class="col-12 col-md-6 col-xl-3 d-flex">
    <a href="{{ route('admin.candidate.index', ['type' => 'permanent']) }}" class="w-100 text-decoration-none text-dark">
        <div class="card flex-fill">
            <div class="card-body">
                <div class="row">
                    <div class="col mb-2">
                        <h5 class="card-title mb-0">Full Time Driver</h5>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <h3 class="mt-0 mb-0 fw-bold">{{ isset($all[3]) ? $all[3]->total ?? 0 : 0 }}</h3>
                    <div class="mb-0"></div>
                </div>
            </div>
        </div>
    </a>
</div>

{{-- Corporate Card --}}
<div class="col-12 col-md-6 col-xl-3 d-flex">
    <a href="{{ route('admin.corprate.corprate_list', ['type' => 'corporate']) }}" class="w-100 text-decoration-none text-dark">
        <div class="card flex-fill">
            <div class="card-body">
                <div class="row">
                    <div class="col mb-2">
                        <h5 class="card-title mb-0">Corporate</h5>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <h3 class="mt-0 mb-0 fw-bold">{{ isset($all[1]) ? $all[1]->total ?? 0 : 0 }}</h3>
                    <div class="mb-0"></div>
                </div>
            </div>
        </div>
    </a>
</div>

{{-- Vehicle Owners Card --}}
<div class="col-12 col-md-6 col-xl-3 d-flex">
    <a href="{{ route('admin.corprate.corprate_list', ['type' => 'owner']) }}" class="w-100 text-decoration-none text-dark">
        <div class="card flex-fill">
            <div class="card-body">
                <div class="row">
                    <div class="col mb-2">
                        <h5 class="card-title mb-0">Vehicle Owners</h5>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <h3 class="mt-0 mb-0 fw-bold">{{ isset($all[0]) ? $all[0]->total ?? 0 : 0 }}</h3>
                    <div class="mb-0"></div>
                </div>
            </div>
        </div>
    </a>
</div>
                </a>
            </div>
            
            <div class="row">
    <div class="col-12 col-md-6 col-xl-3 d-flex">
        <div class="card flex-fill">
            <div class="card-body d-flex align-items-center">
                <div class="row m-0 p-0 g-0">
                    <div class="col">
                        <h5 class="card-title text-dark mb-0">Registration Amount</h5>
                        <h3 class="mt-3 mb-0 pb-0 fw-bold">₹ {{ number_format($amounts['registration'] ?? 0, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3 d-flex">
        <div class="card flex-fill">
            <div class="card-body">
                <div class="row">
                    <div class="col mb-2">
                        <h5 class="card-title text-dark mb-0">Full Time Driver Wallet Amount</h5>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <h3 class="mb-0 pb-0 fw-bold">₹ {{ number_format($amounts['full_time_wallet'] ?? 0, 2) }}</h3>
                    <div class="mb-0">
                        {{-- <p class="text-muted mb-1">Total Referral</p>
                        <h4 class="text-muted mt-0 mb-0 fs-5">15</h4> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3 d-flex">
        <div class="card flex-fill">
            <div class="card-body">
                <div class="row">
                    <div class="col mb-2">
                        <h5 class="card-title text-dark mb-0">Acting Driver Wallet Amount</h5>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <h3 class="mb-0 pb-0 fw-bold">₹ {{ number_format($amounts['acting_wallet'] ?? 0, 2) }}</h3>
                    <div class="mb-0">
                        {{-- <p class="text-muted mb-1">Total Referral</p>
                        <h4 class="text-muted mt-0 mb-0 fs-5">150</h4> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3 d-flex">
        <div class="card flex-fill">
            <div class="card-body">
                <div class="row">
                    <div class="col mb-2">
                        <h5 class="card-title text-dark mb-0">Vehicle Owners Wallet Amount</h5>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <h3 class="mb-0 pb-0 fw-bold">₹ {{ number_format($amounts['vehicle_owners_wallet'] ?? 0, 2) }}</h3>
                    <div class="mb-0">
                        {{-- <p class="text-muted mb-1">Total Referral</p>
                        <h4 class="text-muted mt-0 mb-0 fs-5">15</h4> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

            <div class="row">
                {{-- payment table --}}
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-between align-items-center">
                                <h5 class="col-md-6 card-title text-dark">Payment</h5>

                                <div>

                                    @php
                                        // dd($pay);
                                    @endphp
                                </div>
                            </div>
                            <div class="row dt-row">
                                <div class="col-sm-12">
                                    <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Registration Type</th>
                                                {{-- <th>Reg Date</th> --}}
                                                <th>Package</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <!-- <th>Subscription Status</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pay as $p)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $p->name ?? 0 }}</td>
                                                    <td>{{ $p->type ?? 0 }}</td>

                                                    <td>{{ $p->pk->plan ?? 0 }}</td>
                                                    <td>{{ $p->pk->amount ?? 0 }}</td>
                                                    <td>{{ $p->status}}</td>
                                                    <!-- <td><span class="badge badge-success-light">Paid</span></td> -->
                                                </tr>
                                            @endforeach
                                            {{-- <tr>
                                                <td>1</td>
                                                <td>Angelica Ramos</td>
                                                <td>Chief Executive Officer (CEO)</td>
                                                <td>London</td>
                                                <td>47</td>
                                                <td>2019/10/09</td>
                                                <td><span class="badge badge-danger-light">Unpaid</span></td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>Ashton Cox</td>
                                                <td>Junior Technical Author</td>
                                                <td>San Francisco</td>
                                                <td>66</td>
                                                <td>2019/01/12</td>
                                                <td>$86,000</td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>Bradley Greer</td>
                                                <td>Software Engineer</td>
                                                <td>London</td>
                                                <td>41</td>
                                                <td>2022/10/13</td>
                                                <td>$132,000</td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>Brenden Wagner</td>
                                                <td>Software Engineer</td>
                                                <td>San Francisco</td>
                                                <td>28</td>
                                                <td>2023/06/07</td>
                                                <td>$206,850</td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>Brielle Williamson
                                                </td>
                                                <td>Integration Specialist</td>
                                                <td>New York</td>
                                                <td>61</td>
                                                <td>2022/12/02</td>
                                                <td>$372,000</td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>Airi Satou</td>
                                                <td>Accountant</td>
                                                <td>Tokyo</td>
                                                <td>33</td>
                                                <td>2018/11/28</td>
                                                <td><span class="badge badge-success-light">Paid</span></td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>Angelica Ramos</td>
                                                <td>Chief Executive Officer (CEO)</td>
                                                <td>London</td>
                                                <td>47</td>
                                                <td>2019/10/09</td>
                                                <td><span class="badge badge-danger-light">Unpaid</span></td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>Ashton Cox</td>
                                                <td>Junior Technical Author</td>
                                                <td>San Francisco</td>
                                                <td>66</td>
                                                <td>2019/01/12</td>
                                                <td>$86,000</td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>Bradley Greer</td>
                                                <td>Software Engineer</td>
                                                <td>London</td>
                                                <td>41</td>
                                                <td>2022/10/13</td>
                                                <td>$132,000</td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>Brenden Wagner</td>
                                                <td>Software Engineer</td>
                                                <td>San Francisco</td>
                                                <td>28</td>
                                                <td>2023/06/07</td>
                                                <td>$206,850</td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>Brielle Williamson
                                                </td>
                                                <td>Integration Specialist</td>
                                                <td>New York</td>
                                                <td>61</td>
                                                <td>2022/12/02</td>
                                                <td>$372,000</td>
                                            </tr> --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- referral registration chart --}}
                {{-- <div class="col-md-4">
                    <div class="card flex-fill w-100">
                        <div class="card-header mb-0 pb-0">
                            <div class="card-actions float-end">
                                <div class="dropdown position-relative">
                                    <a href="#" data-bs-toggle="dropdown" data-bs-display="static">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-more-horizontal align-middle">
                                            <circle cx="12" cy="12" r="1"></circle>
                                            <circle cx="19" cy="12" r="1"></circle>
                                            <circle cx="5" cy="12" r="1"></circle>
                                        </svg>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                    </div>
                                </div>
                            </div>
                            <h5 class="card-title mb-0">Referral Registration</h5>
                        </div>
                        <div class="card-body d-flex">
                            <div class="align-self-center w-100">
                                <div class="">
                                    <span class="pe-2"><i class="fas fa-circle text-primary fa-fw"></i> Owner</span>
                                    <span class="pe-2"><i class="fas fa-circle text-warning fa-fw"></i>
                                        Driver</span>
                                    <span class="pe-2"><i class="fas fa-circle text-danger fa-fw"></i> Acting</span>
                                </div>
                                <div class="py-3">
                                    <div class="chart chart-xs">
                                        <div class="chartjs-size-monitor">
                                            <div class="chartjs-size-monitor-expand">
                                                <div class=""></div>
                                            </div>
                                            <div class="chartjs-size-monitor-shrink">
                                                <div class=""></div>
                                            </div>
                                        </div>
                                        <canvas id="chartjs-dashboard-pie" width="290" height="187"
                                            style="display: block; height: 150px; width: 232px;"
                                            class="chartjs-render-monitor"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
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
                    [5, 10, 25, 50, -1],
                    ['5', '10', '25', '50', 'All']
                ],
            });
        });
    </script>
@endsection()

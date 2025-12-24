@extends('admin.layouts.app')

@section('content')
<main class="content">
    <div class="container-fluid p-0">
        <div class="row mb-2 mb-xl-3">
            <div class="col-auto d-none d-sm-block">
                <h3><strong>Registration Location</strong></h3>
            </div>
        </div>

        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-body">
                        <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Location</th>
                                     <th>District</th> 
                                     <th>Corporate</th>
                                    <th>Acting Driver</th>
                                    <th>Fulltime Driver</th>
                                    <th>Vehicle Owner</th>
                                    <!-- <th>Status</th>
                                    <th>Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item['location'] ?? 0 }}</td>
                                         <td>{{ $item['district'] ?? '-' }}</td>
                                        <td>{{ $item['corporates'] ?? 0 }}</td>
                                        <td>{{ $item['acting_drivers'] ?? 0 }}</td>
                                        <td>{{ $item['permanent_drivers'] ?? 0 }}</td>
                                        <td>{{ $item['owners'] ?? 0 }}</td>
                                        
                                        <!-- {{-- ✅ Status Badge --}}
                                        <td>
                                            @if(isset($item['status']) && $item['status'] == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td> -->

                                        {{-- ✅ Action Button --}}
                                      <!-- <td>
                                            @if(isset($item['status']) && $item['status'] == 'active')
                                                <a href="{{ route('admin.location.deactivate', ['location' => $item['location']]) }}"
                                                onclick="return confirm('Are you sure you want to deactivate this location?')">
                                                    <i class="fs-4 text-success fa fa-check-circle"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('admin.location.activate', ['location' => $item['location']]) }}"
                                                onclick="return confirm('Are you sure you want to activate this location?')">
                                                    <i class="fs-4 text-danger fa fa-times-circle"></i>
                                                </a>
                                            @endif
                                        </td> -->

                                    </tr>
                                @endforeach
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
        $("#datatables-reponsive").DataTable({
            responsive: true,
            ordering: false
        });
    });
</script>
@endsection

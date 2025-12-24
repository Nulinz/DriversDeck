@extends('admin.layouts.app')
@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row mb-2 mb-xl-3">
                <div class="col-auto d-none d-sm-block">
                    <h3><strong>Withdraw Request List</strong></h3>
                </div>
            </div>

            <div class="row">
                {{-- report tabs --}}
                <div class="col-md-12 col-xl-12">
                    {{-- Acting driver Details --}}
                    <div class="card mb-3">
                        <h5 class="p-3 card-title mb-0 border-bottom">Request List</h5>
                        <div class="card-body">
                            <table id="datatables-reponsive" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Designation</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="">
                                    @foreach ($withdrawals as $index => $withdraw)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $withdraw->name }}</td>
                                            <td>{{ ucfirst($withdraw->type) }}</td>
                                            <td>₹{{ $withdraw->amt }}</td>
                                            <td>{{ $withdraw->status }}</td>
                                            <td>
                                                <form action="{{ route('admin.wallet.approve') }}" method="POST"
                                                    style="display:inline-block;">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $withdraw->id }}">
                                                    <input type="hidden" name="action" value="approve">
                                                    <button type="submit" class="btn btn-sm btn-success"
                                                        onclick="return confirm('Are you sure you want to approve this withdrawal?')">Approve</button>
                                                </form>

                                                <form action="{{ route('admin.wallet.approve') }}" method="POST"
                                                    style="display:inline-block;">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $withdraw->id }}">
                                                    <input type="hidden" name="action" value="reject">
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to reject this withdrawal?')">Reject</button>
                                                </form>

                                            </td>
                                        </tr>
                                    @endforeach

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
                ordering: false,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    ["5", "10", "25", "50", "All"]
                ]
            });
        });
    </script>
@endsection()

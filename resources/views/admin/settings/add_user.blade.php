@extends('admin.layouts.app')
@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row mb-2 mb-xl-3">
                <div class="col-auto d-none d-sm-block">
                    <h3><strong>Add User</strong></h3>
                </div>
            </div>

            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

                <div class="row">
                    {{-- report tabs --}}
                    <div class="card">
                        <div class="card-header pb-0">
                            <h5 class="card-title mb-0">User Details</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.settings.user.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label fw-bold">Name</label>
                                        <input type="text" name="name" class="form-control" placeholder="">
                                    </div>

                                    <div class="mb-3 col-md-3">
                                        <label class="form-label fw-bold">Designation</label>
                                        <select class="form-select" name="designation" id="">
                                            <option value="" selected disabled>Select Option</option>
                                            <option value="Admin">Admin</option>
                                            <option value="Employee">Employee</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label fw-bold">Contact Number</label>
                                        <input type="text" name="contact_number" class="form-control" placeholder=""
                                            maxlength="10" minlength="10"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label fw-bold">Mail Id</label>
                                        <input type="email" name="email" class="form-control" placeholder="">
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label fw-bold">Password</label>
                                        <input type="password" name="password" class="form-control" id="password"
                                            minlength="6" data-toggle="tooltip" data-placement="top"
                                            title="Password needs to be at least 6 characters long">
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label fw-bold">Profile Image</label>
                                        <input type="file" class="form-control" name="profile_image">
                                        {{-- @if (!empty($item->file))
                                            @php
                                                $filename = basename($item->file);
                                            @endphp
                                            <small class="text-muted">Current file: {{ $filename }}</small>
                                        @endif --}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mt-2 col-md-2">
                                        <input type="submit" class="btn btn-primary w-100" value="Save">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'))
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
@endsection()

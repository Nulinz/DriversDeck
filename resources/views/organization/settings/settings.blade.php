    @extends('organization.layouts.app')
    @section('content')
        <main class="content">
            <div class="container-fluid p-0">
                <div class="row mb-2 mb-xl-3">
                    <div class="col-auto d-none d-sm-block">
                        <h3><strong>Settings</strong></h3>
                    </div>
                </div>

                <div class="row">
                    {{-- report tabs --}}
                    <div class="col-md-12 col-xl-12">
                        <div class="nav nav-tabs d-flex justify-content-start align-items-center gap-x-4 gap-xl-4 mb-3"
                            id="settingsTabs" role="tablist">
                            <a class="nav-link active" id="basic-tab" data-bs-toggle="tab" href="#Basic" role="tab"
                                aria-controls="Basic" aria-selected="true">
                                Basic Details
                            </a>
                            <a class="nav-link" id="contacted-tab" data-bs-toggle="tab" href="#Contacted" role="tab"
                                aria-controls="Contacted" aria-selected="false">
                                Contacted Person
                            </a>
                            <a class="nav-link" id="address-tab" data-bs-toggle="tab" href="#Address" role="tab"
                                aria-controls="Address" aria-selected="false">
                                Address
                            </a>
                            <a class="nav-link" id="business-tab" data-bs-toggle="tab" href="#Buisness" role="tab"
                                aria-controls="Buisness" aria-selected="false">
                                Business Details
                            </a>
                            <a class="nav-link" id="asset-tab" data-bs-toggle="tab" href="#Asset" role="tab"
                                aria-controls="Asset" aria-selected="false">
                                Asset Strength
                            </a>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="Basic" role="tabpanel">
                                {{-- Basic --}}
                                <div class="card">
                                    <div class="card-header pb-0">
                                        <h5 class="card-title mb-0">Basic Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="{{ route('organization.update_details_store') }}">
                                            @csrf
                                            <div class="row">
                                                <div class="mb-3 col-md-3">
                                                    <label class="form-label fw-bold">Corporate Type</label>
                                                    <input type="text" name=""
                                                        class="form-control form-control-lg border-2" placeholder=""
                                                        value="{{ $corporate->c_type ?? '' }}" readonly>
                                                </div>

                                                <div class="mb-3 col-md-3">
                                                    <label class="form-label fw-bold">Name</label>
                                                    <input type="text" name="name"
                                                        class="form-control form-control-lg border-2" placeholder=""
                                                        value="{{ $corporate->name ?? '' }}">
                                                </div>

                                                <div class="mb-3 col-md-3">
                                                    <label class="form-label fw-bold">Contact Number</label>
                                                    <input type="text" name="contact"
                                                        class="form-control form-control-lg border-2" placeholder=""
                                                        maxlength="10" minlength="10"
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                        value="{{ $corporate->contact ?? '' }}">
                                                </div>
                                                <div class="mb-3 col-md-3">
                                                    <label class="form-label fw-bold">Mail Id</label>
                                                    <input type="email" name="mail"
                                                        class="form-control form-control-lg border-2" placeholder=""
                                                        value="{{ $corporate->mail ?? '' }}">
                                                </div>
                                                {{-- <div class="mb-3 col-md-3">
                                                    <label class="form-label fw-bold">Password</label>
                                                    <input type="password" class="form-control form-control-lg border-2" id="password" minlength="6"
                                                        data-toggle="tooltip" data-placement="top"
                                                        title="Password needs to be at least 6 characters long">
                                                </div> --}}
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
                            <div class="tab-pane fade" id="Contacted" role="tabpanel">
                                {{--  Contacted Person --}}
                                <div class="card">
                                    <div class="card-header pb-0">
                                        <h5 class="card-title mb-0">Contacted Person</h5>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="{{ route('organization.update_contact_store') }}">
                                            @csrf

                                            <div class="row">
                                                <div class="mb-3 col-md-3">
                                                    <label class="form-label fw-bold">Full Name</label>
                                                    <input type="text" name="c_name"
                                                        class="form-control form-control-lg border-2" placeholder=""
                                                        value="{{ $corporate->c_name ?? '' }}">
                                                </div>

                                                <div class="mb-3 col-md-3">
                                                    <label class="form-label fw-bold">Mobile Number</label>
                                                    <input type="text" name="c_num"
                                                        class="form-control form-control-lg border-2" placeholder=""
                                                        maxlength="10" minlength="10"
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                        value="{{ $corporate->c_num ?? '' }}">
                                                </div>

                                                <div class="mb-3 col-md-3">
                                                    <label class="form-label fw-bold">Alternate Number</label>
                                                    <input type="text" name="a_num"
                                                        class="form-control form-control-lg border-2" placeholder=""
                                                        maxlength="10" minlength="10"
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                        value="{{ $corporate->a_num ?? '' }}">
                                                </div>

                                                <div class="mb-3 col-md-3">
                                                    <label class="form-label fw-bold">Mail Id</label>
                                                    <input type="email" name="c_mail"
                                                        class="form-control form-control-lg border-2" placeholder=""
                                                        value="{{ $corporate->c_mail ?? '' }}">
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
                            <div class="tab-pane fade" id="Address" role="tabpanel">
                                {{--  Address --}}
                                <div class="card">
                                    <div class="card-header pb-0">
                                        <h5 class="card-title mb-0">Address</h5>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="{{ route('organization.update_address_store') }}">
                                            @csrf
                                            <div class="row">
                                                <div class="mb-3 col-md-3">
                                                    <label class="form-label fw-bold">Address 1</label>
                                                    <input type="text" name="ad_1"
                                                        class="form-control form-control-lg border-2" placeholder=""
                                                        value="{{ $corporate->ad_1 ?? '' }}">
                                                </div>

                                                {{-- <div class="mb-3 col-md-3">
                                                    <label class="form-label fw-bold">Address 2</label>
                                                    <input type="text" name="ad_2"
                                                        class="form-control form-control-lg border-2" placeholder=""
                                                        value="{{ $corporate->ad_2 ?? '' }}">
                                                </div> --}}

                                                <div class="mb-3 col-md-3">
                                                    <label class="form-label fw-bold">City</label>
                                                    <input type="text" name="city"
                                                        class="form-control form-control-lg border-2" placeholder=""
                                                        value="{{ $corporate->city ?? '' }}">
                                                </div>

                                                <div class="mb-3 col-md-3">
                                                    <label class="form-label fw-bold">State</label>
                                                    <input type="text" name="state"
                                                        class="form-control form-control-lg border-2" placeholder=""
                                                        value="{{ $corporate->state ?? '' }}">
                                                </div>

                                                <div class="mb-3 col-md-3">
                                                    <label class="form-label fw-bold">PIN Code</label>
                                                    <input type="text" name="pin"
                                                        class="form-control form-control-lg border-2" placeholder=""
                                                        value="{{ $corporate->pin ?? '' }}">
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
                            <div class="tab-pane fade" id="Buisness" role="tabpanel">
                                {{-- Buisness --}}
                                <div class="card">
                                    <div class="card-header pb-0">
                                        <h5 class="card-title mb-0">Buisness Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="{{ route('organization.update_business_store') }}"
                                            enctype="multipart/form-data" id="businessForm">
                                            @csrf
                                            <div class="row">
                                                <div class="mb-3 col-md-3">
                                                    <label class="form-label fw-bold">GST Number</label>
                                                    <input type="text" name="gst"
                                                        class="form-control form-control-lg border-2" placeholder=""
                                                        value="{{ $corporate->gst ?? '' }}">
                                                    @error('gst')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3 col-md-3">
                                                    <label class="form-label fw-bold">PAN Number</label>
                                                    <input type="text" name="pan"
                                                        class="form-control form-control-lg border-2" placeholder=""
                                                        value="{{ $corporate->pan ?? '' }}">
                                                    @error('pan')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3 col-md-3">
                                                    <label class="form-label fw-bold">Company Logo</label>
                                                    @if (!empty($corporate->logo))
                                                        <img src="{{ asset($corporate->logo) }}" alt="Logo"
                                                            width="100" class="mt-2">
                                                    @endif
                                                    <input name="logo" class="form-control form-control-lg border-2"
                                                        type="file">
                                                    @error('logo')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
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



                            <div class="tab-pane fade" id="Asset" role="tabpanel">
                                {{-- Asset --}}
                                <div class="card">
                                    <div class="card-header pb-0">
                                        <h5 class="card-title mb-0">Asset Strength</h5>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="{{ route('organization.update_asset_store') }}">
                                            @csrf
                                            <div class="row">
                                                <div class="mb-3 col-md-3">
                                                    <label class="form-label fw-bold">Number of Vehicle</label>
                                                    <input type="text" name="no_veh"
                                                        class="form-control form-control-lg border-2" placeholder=""
                                                        value="{{ $corporate->no_veh ?? '' }}">
                                                </div>

                                                <div class="mb-3 col-md-3">
                                                    <label class="form-label fw-bold">No of Drivers working</label>
                                                    <input type="text" name="no_driver"
                                                        class="form-control form-control-lg border-2" placeholder=""
                                                        value="{{ $corporate->no_driver ?? '' }}">
                                                </div>

                                                <div class="mb-3 col-md-3">
                                                    <label class="form-label fw-bold">No of Vacancies</label>
                                                    <input type="text" name="no_vac"
                                                        class="form-control form-control-lg border-2" placeholder=""
                                                        value="{{ $corporate->no_vac ?? '' }}">
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
                    </div>
                </div>
            </div>
        </main>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Initialize DataTables
                $("#datatables-reponsive, #datatables-reponsive-2").DataTable({
                    responsive: true
                });

                // Tab persistence on page load
                const urlParams = new URLSearchParams(window.location.search);
                const activeTab = urlParams.get('activeTab') || localStorage.getItem('activeTab');
                if (activeTab) {
                    $(`a[href="${activeTab}"]`).tab('show');
                }

                // Store active tab when clicked
                $('a[data-bs-toggle="tab"]').on('click', function() {
                    const tabId = $(this).attr('href');
                    localStorage.setItem('activeTab', tabId);
                    history.replaceState(null, null, `?activeTab=${encodeURIComponent(tabId)}`);
                });

                // Handle all form submissions
                $('.tab-content form').on('submit', function(e) {
                    e.preventDefault();
                    const form = $(this);
                    const activeTab = $('.nav-tabs .active').attr('href');

                    // Create hidden input for activeTab (works for both AJAX and fallback)
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'activeTab';
                    hiddenInput.value = activeTab;
                    form[0].appendChild(hiddenInput);

                    // Create FormData and append all form inputs
                    const formData = new FormData(form[0]);

                    // For debugging - log what's being sent
                    for (let [key, value] of formData.entries()) {
                        console.log(key, value);
                    }

                    // AJAX submission
                    $.ajax({
                        url: form.attr('action'),
                        type: form.attr('method'),
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        success: function(response) {
                            if (response.success) {
                                localStorage.setItem('activeTab', activeTab);
                                window.location.href =
                                    `${window.location.pathname}?activeTab=${encodeURIComponent(activeTab)}`;
                            }
                        },
                        error: function(xhr) {
                            console.error('AJAX failed, submitting normally');
                            form[0].submit(); // Fallback to normal submission
                        }
                    });
                });
            });
        </script>
    @endsection()

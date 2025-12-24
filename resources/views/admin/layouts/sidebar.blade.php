 <nav id="sidebar" class="sidebar js-sidebar">
     <div class="sidebar-content js-simplebar">
        <a class='sidebar-brand pb-0'>
             {{-- <span class="sidebar-brand-text align-middle">
						Drivers Deck
					</span> --}}
             <img src="{{ asset('assets/images/logo/DDMobilelogo.png') }}" width="100%" height="80px" class="barnd mb-3" alt="">
             <svg class="sidebar-brand-icon align-middle" width="32px" height="32px" viewBox="0 0 24 24" fill="none"
                 stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter" color="#FFFFFF"
                 style="margin-left: -3px">
                 <path d="M12 4L20 8.00004L12 12L4 8.00004L12 4Z"></path>
                 <path d="M20 12L12 16L4 12"></path>
                 <path d="M20 16L12 20L4 16"></path>
             </svg>
         </a>

         <ul class="sidebar-nav">


             <li class="sidebar-item {{ Route::is('admin.dashboard.index') ? 'active' : '' }}">
                 <a class='sidebar-link fw-semibold' href='{{ route('admin.dashboard.index') }}'>
                     <i class="align-middle" data-feather="grid"></i> <span
                         class="align-middle fw-semibold">Dashboard</span>
                 </a>
             </li>

             <li class="sidebar-item {{ Route::is('admin.corprate.corprate_list') ? 'active' : '' }}">
                 <a class='sidebar-link fw-semibold' href='{{ route('admin.corprate.corprate_list') }}'>
                     <i class="align-middle" data-feather="credit-card"></i> <span
                         class="align-middle fw-semibold">Corprate</span>
                 </a>
             </li>

             <li class="sidebar-item {{ Route::is('admin.candidate.index') ? 'active' : '' }}">
                 <a class='sidebar-link fw-semibold' href='{{ route('admin.candidate.index') }}'>
                     <i class="align-middle" data-feather="users"></i> <span
                         class="align-middle fw-semibold">Drivers</span>
                 </a>
             </li>

               <li class="sidebar-item {{ Route::is('admin.driverChangeRequests') ? 'active' : '' }}">
                 <a class='sidebar-link fw-semibold' href='{{ route('admin.driverChangeRequests') }}'>
                     <i class="align-middle" data-feather="users"></i> <span
                         class="align-middle fw-semibold">Drivers Request</span>
                 </a>
              </li>
             <li class="sidebar-item {{ Route::is('admin.vacancy.create') ? 'active' : '' }}">
                 <a class='sidebar-link fw-semibold' href='{{ route('admin.vacancy.create') }}'>
                     <i class="align-middle" data-feather="truck"></i> <span
                         class="align-middle fw-semibold">Add Vacancy</span>
                 </a>
             </li>
             <li class="sidebar-item {{ Route::is('admin.trip.trip_list') ? 'active' : '' }}">
                 <a class='sidebar-link fw-semibold' href='{{ route('admin.trip.trip_list') }}'>
                     <i class="align-middle" data-feather="truck"></i> <span
                         class="align-middle fw-semibold">Trip</span>
                 </a>
             </li>
             <li class="sidebar-item {{ Route::is('admin.approval.approval_list') ? 'active' : '' }}">
                 <a class='sidebar-link fw-semibold' href='{{ route('admin.approval.approval_list') }}'>
                     <i class="align-middle" data-feather="user-check"></i> <span
                         class="align-middle fw-semibold">Approval</span>
                 </a>
             </li>
             <li class="sidebar-item {{ Route::is('admin.vacancy.vacancy_approvel') ? 'active' : '' }}">
                 <a class='sidebar-link fw-semibold' href='{{ route('admin.vacancy.vacancy_approvel') }}'>
                     <i class="align-middle" data-feather="user-plus"></i> <span
                         class="align-middle fw-semibold">Corporate Vacancy</span>
                 </a>
             </li>
              <li class="sidebar-item {{ Route::is('admin.corprate.index') ? 'active' : '' }}">
                 <a class='sidebar-link fw-semibold' href='{{ route('admin.corprate.index') }}'>
                     <i class="align-middle" data-feather="user-plus"></i> <span
                         class="align-middle fw-semibold">Add Corporate</span>
                 </a>
             </li>
             <li class="sidebar-item {{ Route::is('admin.wallet.request') ? 'active' : '' }}">
                 <a class='sidebar-link fw-semibold' href='{{ route('admin.wallet.request') }}'>
                     <i class="align-middle" data-feather="credit-card"></i> <span
                         class="align-middle fw-semibold">Wallet Request</span>
                 </a>
             </li>
             <li class="sidebar-item {{ Route::is('admin.location.location') ? 'active' : '' }}">
                 <a class='sidebar-link fw-semibold' href='{{ route('admin.location.location') }}'>
                     <i class="align-middle" data-feather="map-pin"></i> <span
                         class="align-middle fw-semibold">Location</span>
                 </a>
             </li>
             <li class="sidebar-item {{ Route::is('admin.customer.customer_report') ? 'active' : '' }}">
                 <a class='sidebar-link fw-semibold' href='{{ route('admin.customer.customer_report') }}'>
                     <i class="align-middle" data-feather="message-square"></i> <span
                         class="align-middle fw-semibold">Customer Report</span>
                 </a>
             </li>
             <li class="sidebar-item {{ Route::is('admin.trip_cancel.tripcanel_list') ? 'active' : '' }}">
                 <a class='sidebar-link fw-semibold' href='{{ route('admin.trip_cancel.tripcanel_list') }}'>
                     <i class="align-middle" data-feather="x"></i> <span class="align-middle fw-semibold">Trip
                         Cancel</span>
                 </a>
             </li>
             {{-- <li class="sidebar-item {{ Route::is('admin.change_type.change_type') ? 'active' : '' }}">
 <a class='sidebar-link fw-semibold' href='{{ route('admin.change_type.change_type') }}'>
 <i class="align-middle" data-feather="refresh-ccw"></i> <span class="align-middle">Change
 Type</span>
 </a> --}}
             </li>
             <li class="sidebar-item {{ Route::is('admin.settings.settings') ? 'active' : '' }}">
                 <a class='sidebar-link fw-semibold' href='{{ route('admin.settings.settings') }}'>
                     <i class="align-middle" data-feather="settings"></i> <span
                         class="align-middle fw-semibold">Settings</span>
                 </a>
             </li>
         </ul>
     </div>
 </nav>

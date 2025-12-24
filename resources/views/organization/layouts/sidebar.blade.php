 <nav id="sidebar" class="sidebar js-sidebar">
     <div class="sidebar-content js-simplebar">
         <a class='sidebar-brand pb-0'>
             {{-- <span class="sidebar-brand-text align-middle">
						Drivers Deck
					</span> --}}
            <img src="{{ asset('assets/images/logo/DDMobilelogo.png') }}" width="100%" height="80px" class="barnd mb-3" alt="">
             <svg class="sidebar-brand-icon align-middle" width="32px" height="32px" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="square"
                 stroke-linejoin="miter" color="#FFFFFF" style="margin-left: -3px">
                 <path d="M12 4L20 8.00004L12 12L4 8.00004L12 4Z"></path>
                 <path d="M20 12L12 16L4 12"></path>
                 <path d="M20 16L12 20L4 16"></path>
             </svg>
         </a>

         <ul class="sidebar-nav">

             {{-- <li class="sidebar-item">
						<a data-bs-target="#pages" data-bs-toggle="collapse" class="sidebar-link collapsed">
							<i class="align-middle" data-feather="layout"></i> <span class="align-middle">Pages</span>
						</a>
						<ul id="pages" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
							<li class="sidebar-item"><a class='sidebar-link' href='pages-settings.html'>Settings</a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='pages-projects.html'>Projects <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='pages-clients.html'>Clients <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='pages-orders.html'>Orders <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='pages-pricing.html'>Pricing <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='pages-chat.html'>Chat <span
										class="sidebar-badge badge bg-primary">Pro</span></a></li>
							<li class="sidebar-item"><a class='sidebar-link' href='pages-blank.html'>Blank Page</a></li>
						</ul>
					</li> --}}

             <li class="sidebar-item {{ Route::is('organization.dashboard.index') ? 'active' : '' }}">
                 <a class='sidebar-link' href='{{ route('organization.dashboard.index') }}'>
                     <i class="align-middle" data-feather="grid"></i> <span class="align-middle">Dashboard</span>
                 </a>
             </li>

             <li class="sidebar-item {{ Route::is('organization.hired.hired_list') ? 'active' : '' }}">
                 <a class='sidebar-link' href='{{ route('organization.hired.hired_list') }}'>
                     <i class="align-middle" data-feather="user-plus"></i> <span class="align-middle">Hired</span>
                 </a>
             </li>

             <li class="sidebar-item {{ Route::is('organization.vacancy.vacancy_list') ? 'active' : '' }}">
                 <a class='sidebar-link' href='{{ route('organization.vacancy.vacancy_list') }}'>
                     <i class="align-middle" data-feather="briefcase"></i> <span class="align-middle">Vacancy</span>
                 </a>
             </li>

             <li class="sidebar-item {{ Route::is('organization.trip.trip_list') ? 'active' : '' }}">
                 <a class='sidebar-link' href='{{ route('organization.trip.trip_list') }}'>
                     <i class="align-middle" data-feather="truck"></i> <span class="align-middle">Trip</span>
                 </a>
             </li>

             <li class="sidebar-item {{ Route::is('organization.settings.settings') ? 'active' : '' }}">
                 <a class='sidebar-link' href='{{ route('organization.settings.settings') }}'>
                     <i class="align-middle" data-feather="settings"></i> <span class="align-middle">Settings</span>
                 </a>
             </li>
         </ul>
     </div>
 </nav>

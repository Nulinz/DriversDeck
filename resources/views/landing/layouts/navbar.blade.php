{{-- <nav class="navbar navbar-expand-md navbar-dark bg-white py-3 px-3 border-bottom">
  <div class="container-fluid">
    <img src="{{ asset('assets/images/logo/Logo.png') }}" class="brand" height="50px" alt="">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
      aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav ms-auto mb-2 mb-md-0 text-dark">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Corprate</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Drives</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Owners</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#">About Us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Features</a>
        </li>


        <li class="nav-item">
          <a class="nav-link" href="#">Contact Us</a>
        </li>

        <li class="nav-item">
          <button class="btn btn-md btn-warning text-white fw-bold " type="submit">Sign In</button>
        </li>
      </ul>
    </div>
  </div>
</nav> --}}

<nav class="navbar fixed-top navbar-expand-md navbar-light border-bottom bg-white py-3 shadow-sm">
  <div class="container-fluid px-5">
    <img src="{{ asset('assets/images/logo/Logo.png') }}" class="brand" height="50px" alt="">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
      aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse" id="navbarCollapse">
      <ul class="navbar-nav mb-md-0 mb-2 ms-auto">
        <li class="nav-item"><a class="nav-link {{ Route::is('landing.index') ? 'active' : '' }}"
            href="{{ route('landing.index') }}">Home</a></li>
        <li class="nav-item"><a class="nav-link {{ Route::is('landing.landing.about') ? 'active' : '' }}"
            href="{{ route('landing.landing.about') }}">About Us</a></li>
        <li class="nav-item"><a class="nav-link {{ Route::is('landing.landing.drivers') ? 'active' : '' }}"
            href="{{ route('landing.landing.drivers') }}">Driver</a></li>
        <li class="nav-item"><a class="nav-link {{ Route::is('landing.landing.corporate') ? 'active' : '' }}"
            href="{{ route('landing.landing.corporate') }}">Corporate</a>
        </li>
        <li class="nav-item"><a class="nav-link {{ Route::is('landing.landing.owners') ? 'active' : '' }}"
            href="{{ route('landing.landing.owners') }}">Car Owners</a></li>
        {{-- <li class="nav-item"><a class="nav-link" href="{{ route('la') }}">Features</a></li> --}}
        <li class="nav-item"><a class="nav-link {{ Route::is('landing.landing.contact') ? 'active' : '' }}"
            href="{{ route('landing.landing.contact') }}">Contact Us</a>
        </li>
        <li class="nav-item">
          <a class="btn btn-md btn-warning fw-bold text-white" href="{{ route('admin.auth.login') }}">Sign In</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
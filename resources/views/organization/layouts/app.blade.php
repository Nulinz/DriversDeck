@include('organization.layouts.header')
    <div class="wrapper">
@include('organization.layouts.sidebar')

    <div class="main">
        @include('organization.layouts.navbar')
        @yield('content')

@include('organization.layouts.footer')
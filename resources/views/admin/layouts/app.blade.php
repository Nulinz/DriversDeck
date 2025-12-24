@include('admin.layouts.header')
    <div class="wrapper">
@include('admin.layouts.sidebar')

    <div class="main">
        @include('admin.layouts.navbar')
        @yield('content')

@include('admin.layouts.footer')



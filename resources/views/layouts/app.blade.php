<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('page-title', 'Laundry')</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <link rel="manifest" href="site.webmanifest"> --}}
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.ico') }}">

    <!-- CSS here -->
    @include('layouts.partials.base-css')
    @stack('add-css')
</head>

<body>
    @include('layouts.partials.preloader')
    @include('layouts.partials.navbar')
    <main>
        @yield('contents', 'Blank Page')
    </main>
    @include('layouts.partials.footer')
    <!-- Scroll Up -->
    <div id="back-top">
        <a title="Go to Top" href="#"> <i class="fas fa-level-up-alt"></i></a>
    </div>

    <!-- JS here -->
    @include('layouts.partials.base-js')
    @stack('add-js')

</body>

</html>

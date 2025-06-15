<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.member.partials.head')
</head>

<body id="page-top">
    @include('layouts.member.partials.sidebar')
    <!-- Page Content-->
    <div class="container-fluid p-0">
        @yield('content')
    </div>

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="{{asset('assets/member/js/scripts.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @yield('scripts')
    @stack('scripts')
</body>

</html>
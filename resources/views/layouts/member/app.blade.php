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
    <script src="{{asset('member/js/scripts.js')}}"></script>
</body>

</html>
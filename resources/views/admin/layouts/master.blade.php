<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Content Launch Administrative Dashboard</title>

    <link rel="stylesheet" href="{!! asset('css/admin.css') !!}">
    <link rel="stylesheet" href="{!! asset('css/admin_vendor.css') !!}">

</head>
<body>
    <div id="wrapper">
        @include('admin.layouts.partials.navigation')

        <div id="page-wrapper" class="gray-bg">
            @include('admin.layouts.partials.topnavbar')

            @yield('header')
            <div class="wrapper wrapper-content animated fadeInRight">
                @include('admin.partials.flash')
                @include('admin.partials.errors')

                @yield('content')
            </div>

            @include('admin.layouts.partials.footer')
        </div>
    </div>

    <!-- Plugins -->
    <script src="{!! asset('js/admin/admin_vendor.js') !!}"></script>
    {{--<script src="{!! asset('plugins/iCheck/icheck.min.js') !!}"></script>--}}

    <!-- Scripts -->
    {{-- <script src="{!! asset('js/main.js') !!}"></script> --}}
    @stack('admin.scripts')
</body>
</html>
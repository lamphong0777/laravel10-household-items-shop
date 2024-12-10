<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Administrative Panel</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/dropzone/min/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/summernote/summernote.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/datetime/datetimepicker.css') }}">
    {{-- main css - adminlte min css --}}
    <link rel="stylesheet" href="{{ asset('admin-assets/css/adminlte.min.css') }}">
    {{-- Custom css --}}
    <link rel="stylesheet" href="{{ asset('admin-assets/css/custom.css') }}">
    {{-- In page css --}}
    @yield('css')
</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        @include('admin.layouts.header')
        <!-- /.navbar -->
        <!-- Main Sidebar Container -->
        @include('admin.layouts.sidebar')
        <!-- Content Wrapper. Contains page content -->
        @yield('content')
        <!-- /.content-wrapper -->
        @include('admin.layouts.footer')

    </div>
    <!-- ./wrapper -->

    {{-- sweet alert 2 --}}
    <script src="{{ asset('admin-assets/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <!-- jQuery -->
    <script src="{{ asset('admin-assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('admin-assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('admin-assets/js/adminlte.min.js') }}"></script>
    {{-- <!-- AdminLTE for demo purposes --> --}}
    {{-- <script src="{{ asset('admin-assets/js/demo.js')}}"></script> --}}

    {{-- Date time js --}}
    <script src="{{ asset('admin-assets/plugins/datetime/datetimepicker.js') }}"></script>

    {{-- dropzone , sumer note --}}
    <script src="{{ asset('admin-assets/plugins/dropzone/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('admin-assets/plugins/summernote/summernote.min.js') }}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $(".summernote").summernote({
                height: 250
            })
        })

        $("#message-alert").fadeTo(2000, 500).slideUp(500, function() {
            $("#message-alert").slideUp(500);
        });
    </script>
    @yield('js')
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Ekka Admin - @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="MyraStudio" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link href="{{ asset('manager/assets/vendors/datatables/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <!-- @yield('css') -->
    <!-- App css -->
    <link href="{{ asset("manager/assets/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("manager/assets/css/icons.min.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("manager/assets/css/theme.min.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('manager/assets/css/custom.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <header id="page-topbar">
            <div class="navbar-header">

                <div class="d-flex align-items-left">
                </div>

                <div class="d-flex align-items-center">
                    <div class="dropdown d-inline-block ml-2">
                        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="rounded-circle header-profile-user" src="assets/images/users/avatar-3.jpg">
                            <span class="d-none d-sm-inline-block ml-1">Admin.</span>
                            <i class="mdi mdi-chevron-down d-none d-sm-inline-block"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <form id="logout-form" action="#" method="POST" class="d-none">
                                @csrf </form>
                            <a class="dropdown-item d-flex align-items-center justify-content-between"
                                href="javascript:void(0)"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <span>Log Out</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </header>

        @include('admin.sidebar')

        <!-- ========== Left Sidebar Start ========== -->
        <!-- Left Sidebar End -->

        <div class="main-content">
            @yield('body')
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            2022 © Ekka.
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-right d-none d-sm-block">
                                Design & Develop by UyenNhi
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <div class="menu-overlay"></div>

    @yield('sub_layout')

    @include('admin.partials.media-picker')

    <!-- jQuery  -->
    <script src="{{ asset("manager/assets/js/jquery.min.js") }}"></script>
    <script src="{{ asset("manager/assets/js/bootstrap.bundle.min.js") }}"></script>
    <script src="{{ asset("manager/assets/js/metismenu.min.js") }}"></script>
    <script src="{{ asset("manager/assets/js/waves.js") }}"></script>
    <script src="{{ asset("manager/assets/js/simplebar.min.js") }}"></script>
    <script src="{{ asset('manager/assets/js/api.js') }}"></script>
    {{-- Custom library js --}}
    <script src="{{ asset('manager/assets/js/layout.js') }}"></script>
    {{-- Modal Template JS --}}
    <script src="{{ asset('manager/assets/js/template.js') }}"></script>

    <!-- Morris Js-->
    <script src="{{ asset("manager/plugins/morris-js/morris.min.js") }}"></script>
    <!-- Raphael Js-->
    <script src="{{ asset("manager/plugins/raphael/raphael.min.js") }}"></script>

    <!-- Morris Custom Js-->
    <script src="{{ asset("manager/assets/pages/dashboard-demo.js") }}"></script>
    {{-- Datatable --}}
    <script src="{{ asset('manager/assets/js/summernote/summernote-lite.min.js') }}"></script>

    <script src="{{ asset('manager/assets/vendors/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('manager/assets/vendors/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('manager/assets/js/pages/datatables.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset("manager/assets/js/theme.js") }}"></script>
    <script src="{{ asset('manager/assets/js/page/media-picker.js') }}"></script>
    @yield('js')

</body>

</html>
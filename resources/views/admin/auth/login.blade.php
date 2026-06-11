<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Ekka Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="MyraStudio" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="manager/assets/images/favicon.ico">

    <!-- App css -->
    <link href="{{ asset("manager/assets/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("manager/assets/css/icons.min.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("manager/assets/css/theme.min.css") }}" rel="stylesheet" type="text/css" />

</head>

<body>
 
    <div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex align-items-center min-vh-100">
                        <div class="w-100 d-block bg-white shadow-lg rounded my-5">
                            <div class="row">
                                <div class="col-lg-5 d-none d-lg-block bg-login rounded-left">
                                    <img src="{{ asset("manager/global-images/login-banner.webp") }}" style="width: 100%;" alt="">
                                </div>
                                <div class="col-lg-7">
                                    <div class="p-5">
                                        <div class="text-center mb-2">
                                            <a href="/" class="text-dark font-size-22 font-family-secondary">
                                                <i class="mdi mdi-alpha-h-circle"></i> <b>Ekka</b>
                                            </a>
                                        </div>
                                        <h1 class="h5 mb-1">Welcome Back!</h1>
                                        <p class="text-muted mb-4">Đăng nhập trang quản lí.</p>
                                        <form class="user" method="POST" action="{{ route('admin.login.post') }}">
                                            @csrf
                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            @if ( Session::has('error') )
                                                <div class="alert alert-danger" role="alert">
                                                    {{ Session::get('error') }}
                                                </div>
                                            @endif
                                            @if ( Session::has('success') )
                                                <div class="alert alert-success" role="alert">
                                                    {{ Session::get('success') }}
                                                </div>
                                            @endif
                                            <div class="form-group">
                                                <input type="email" class="form-control form-control-user" name="email" id="exampleInputEmail" placeholder="Email">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" class="form-control form-control-user" name="password" id="exampleInputPassword" placeholder="Password">
                                            </div>
                                            <button class="btn btn-success btn-block waves-effect waves-light"> Đăng nhập </button>
                                        </form>
                                    </div>
                                </div> 
                            </div> 
                        </div> 
                    </div> 
                </div> 
            </div>
        </div>
    </div>
    <script src="{{ asset("manager/assets/js/jquery.min.js") }}"></script>
    <script src="{{ asset("manager/assets/js/bootstrap.bundle.min.js") }}"></script>
    <script src="{{ asset("manager/assets/js/metismenu.min.js") }}"></script>
    <script src="{{ asset("manager/assets/js/waves.js") }}"></script>
    <script src="{{ asset("manager/assets/js/simplebar.min.js") }}"></script>

    <!-- App js -->
    <script src="{{ asset("manager/assets/js/theme.js") }}"></script>

</body>

</html>
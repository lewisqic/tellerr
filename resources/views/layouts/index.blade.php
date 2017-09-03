<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Tellerr</title>

    <link rel="stylesheet" id="skin_css" href="{{ url('assets/css/skins/blue.css') }}" />

</head>

<body>


<div class="header mb-5">

    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <a href="{{ url('') }}" class="navbar-brand"><i class="fa fa-cloud"></i> <span>Tellerr</span></a>
                <ul class="navbar-nav ml-4 mr-auto">
                    <li class="nav-item">
                        <a href="{{ url('pricing') }}" class="nav-link">Pricing</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    @if ( $user = \Auth::check() )
                    <li class="nav-item dropdown ml-3">
                        <a href="#" class="nav-link dropdown-toggle" id="user_menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Welcome, {{ $user->first_name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right animated fadeInUp" aria-labelledby="user_menu">
                            <a href="#" class="dropdown-item"><i class="fa fa-credit-card text-primary"></i> Billing & Subscription</a>
                            <a href="{{ url('admin/settings') }}" class="dropdown-item"><i class="fa fa-cog text-primary"></i> Settings</a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ url('auth/logout') }}" class="dropdown-item text-danger"><i class="fa fa-power-off"></i> Logout</a>
                        </div>
                    </li>
                    @else
                    <li class="nav-item">
                        <a href="{{ url('auth/login') }}" class="nav-link">Login</a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

</div>

<div class="container">

    @if ( isset($errors) && count($errors) > 0 )
        <div class="alert alert-alt alert-dismissible alert-danger">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
            <strong>Oops!</strong> We encountered the following error(s) when trying to process your request:
            <ul>
                @foreach ( $errors->all() as $error )
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @else
        {!! \Msg::show() !!}
    @endif

    @yield('content')

</div>


{!! Js::config() !!}
{!! Js::msg(false) !!}
<script src="{{ url('assets/js/vendor.js') }}"></script>
<script src="{{ url('assets/js/core.js') }}"></script>
@stack('scripts')

</body>
</html>
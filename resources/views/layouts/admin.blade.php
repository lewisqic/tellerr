<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin | Tellerr</title>

    <link rel="stylesheet" id="skin_css" href="{{ url('assets/css/skins/' . $app_user->remarkSetting->primary_color . '.css') }}" />

</head>

<body class="remark sidebar-{{ $app_user->remarkSetting->sidebar_skin }} {{ $app_user->remarkSetting->sidebar_minimized ? 'sidebar-minimized' : '' }} {{ $app_user->remarkSetting->navbar_inverse ? 'navbar-inverse' : '' }}">

<div class="header">

    <nav class="navbar navbar-expand-md navbar-dark bg-light">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <a href="{{ url('admin') }}" class="navbar-brand"><i class="fa fa-cloud"></i> <span>Tellerr Admin</span></a>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="#" class="nav-link toggle-sidebar"><i class="fa fa-bars fa-lg"></i></a>
                </li>
            </ul>
            <form action="{{ url('admin/search') }}" method="post" class="validate form-inline mr-auto">
                {!! Html::hiddenInput() !!}
                <input class="form-control" type="text" placeholder="search...">
                <button type="submit" class="btn"><i class="fa fa-search"></i></button>
            </form>
            <ul class="navbar-nav">
                <li class="nav-item dropdown ml-3">
                    <a href="#" class="nav-link dropdown-toggle" id="user_menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Welcome, {{ $app_user->first_name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right animated fadeInUp" aria-labelledby="user_menu">
                        <a class="dropdown-item no-click">
                            <strong>{{ $app_user->name }}</strong><br>
                            <small class="text-muted">{{ $app_user->email }}</small>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ url('admin/profile') }}" class="dropdown-item"><i class="fa fa-user text-primary"></i> My Profile</a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ url('auth/logout') }}" class="dropdown-item text-danger"><i class="fa fa-power-off"></i> Logout</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="{{ url('auth/logout') }}" class="nav-link text-danger"><i class="fa fa-power-off fa-lg"></i></a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="configurator">
        <a href="#" class="handle"><i class="fa fa-cog"></i></a>
        <div class="inner">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a href="#style" class="nav-link active" data-toggle="tab" role="tab">Style</a>
                </li>
                <li class="nav-item">
                    <a href="#color" class="nav-link" data-toggle="tab" role="tab">Color</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="style" role="tabpanel">

                    <h6>Navbar Type</h6>
                    <div class="abc-checkbox abc-checkbox-primary">
                        <input type="checkbox" name="navbar_type" id="navbar_type" {{ $app_user->remarkSetting->navbar_inverse ? 'checked' : '' }}>
                        <label for="navbar_type">
                            Inverse
                        </label>
                    </div>

                    <h6 class="mt-3">Sidebar Skin</h6>
                    <div class="abc-radio abc-radio-primary">
                        <input type="radio" name="sidebar_skin" id="dark" value="dark" {{ $app_user->remarkSetting->sidebar_skin == 'dark' ? 'checked' : '' }}>
                        <label for="dark">
                            Dark
                        </label>
                    </div>
                    <div class="abc-radio abc-radio-primary">
                        <input type="radio" name="sidebar_skin" id="light" value="light" {{ $app_user->remarkSetting->sidebar_skin == 'light' ? 'checked' : '' }}>
                        <label for="light">
                            Light
                        </label>
                    </div>

                </div>
                <div class="tab-pane" id="color" role="tabpanel">

                    <h6>Primary Color</h6>

                    @php
                        $skins = array_diff(scandir(base_path('resources/assets/sass/skins')), array('.', '..'));
                    @endphp
                    @foreach ( $skins as $skin )
                        @php
                            $skin = preg_replace('/\.scss/', '', $skin);
                        @endphp
                        @if ( !preg_match('/2/', $skin) )
                            <div class="abc-radio {{ $skin }}">
                                <input type="radio" name="primary_color" id="{{ $skin }}" value="{{ $skin }}" {{ $app_user->remarkSetting->primary_color == $skin ? 'checked' : '' }}>
                                <label for="{{ $skin }}">{{ ucwords($skin) }}</label>
                            </div>
                         @endif
                    @endforeach


                </div>
            </div>
        </div>
    </div>

</div>

<div class="sidebar">

    <div class="sidebar-scrollbar">

        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ url('admin') }}" class="nav-link {{ nav_active('^admin$') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
            </li>
            <li class="nav-item open">
                <a href="{{ url('admin/plans') }}" class="nav-link {{ nav_active('^admin/plans') }}"><i class="fa fa-list-alt"></i> <span>Plans</span></a>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cogs"></i> <span>System</span></a>
                <div class="dropdown-menu">
                    @if ( has_access('AdminAdministratorController@index') )
                    <a href="{{ url('admin/administrators') }}" class="dropdown-item {{ nav_active('^admin/administrators') }}">Administrators</a>
                    @endif
                    @if ( has_access('AdminRoleController@index') )
                    <a href="{{ url('admin/roles') }}" class="dropdown-item {{ nav_active('^admin/roles') }}">Roles</a>
                    @endif
                    @if ( has_access('AdminSettingController@index') )
                    <a href="{{ url('admin/settings') }}" class="dropdown-item {{ nav_active('^admin/settings') }}">Settings</a>
                    @endif
                </div>
            </li>
        </ul>

    </div>

</div>

<div class="main">

    <div class="content">

        @if ( count($errors) > 0 )
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

    <div class="footer">
        &copy; Tellerr {{ date('Y') }}
    </div>

</div>


<div class="sidebar-right" id="sidebar-right">
    <div class="cssload-container"><div class="cssload-whirlpool"></div></div>
    <div class="sidebar-wrapper"></div>
</div>
<a href="#" id="open-sidebar"></a>
<a href="#" id="close-sidebar" class="close-sidebar"></a>


{!! Js::config() !!}
{!! Js::msg(false) !!}
<script src="{{ url('assets/js/vendor.js') }}"></script>
<script src="{{ url('assets/js/core.js') }}"></script>
<script src="{{ url('assets/js/modules/remark.js') }}"></script>
@stack('scripts')

</body>
</html>
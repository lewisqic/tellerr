<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Account | Tellerr</title>

    <link rel="stylesheet" id="skin_css" href="{{ url('assets/css/skins/' . $app_user->remarkSetting->primary_color . '.css') }}" />

</head>

<body class="remark sidebar-{{ $app_user->remarkSetting->sidebar_skin }} {{ $app_user->remarkSetting->sidebar_minimized ? 'sidebar-minimized' : '' }} {{ $app_user->remarkSetting->navbar_inverse ? 'navbar-inverse' : '' }} {{ $is_sandbox ? 'sandbox' : '' }}">

<div class="header">

    <nav class="navbar navbar-expand-md navbar-dark bg-light">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <a href="{{ url('account') }}" class="navbar-brand"><i class="fa fa-cloud"></i> <span>Tellerr</span></a>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="#" class="nav-link toggle-sidebar"><i class="fa fa-bars fa-lg"></i></a>
                </li>
            </ul>
            <form action="{{ url('account/search') }}" method="post" class="validate form-inline">
                {!! Html::hiddenInput() !!}
                <input class="form-control" type="text" placeholder="search...">
                <button type="submit" class="btn"><i class="fa fa-search"></i></button>
            </form>
            <ul class="navbar-nav ml-4">
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle hide-arrow" id="notification_menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bell fa-lg"></i> <span class="badge badge-danger">3</span>
                    </a>
                    <div class="dropdown-menu animated fadeInUp notifications" aria-labelledby="notification_menu">
                        <div class="notification-header">Notifications <span class="badge badge-danger">3 New</span></div>
                        <a href="#" class="dropdown-item">
                            <div class="notification-item">
                                <span class="icon primary"><i class="fa fa-calendar"></i></span>
                                <span class="title">A new event has been scheduled</span>
                                <span class="date">2 days ago</span>
                            </div>
                        </a>
                        <a href="#" class="dropdown-item">
                            <div class="notification-item">
                                <span class="icon warning"><i class="fa fa-server"></i></span>
                                <span class="title">Disk space is 90% full</span>
                                <span class="date">4 days ago</span>
                            </div>
                        </a>
                        <a href="#" class="notification-footer">View all notifications <i class="fa fa-bell"></i></a>
                    </div>
                </li>
            </ul>
            <div class="mr-auto">
                <div class="alert alert-warning alert-alt ml-5 mt-3 py-2">
                    <i class="fa fa-info-circle"></i> You must <a href="{{ url('account/verify') }}" class="text-underline text-warning">verify your account</a> before receiving payouts.
                </div>
            </div>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle hide-arrow" id="language_menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ url('assets/images/flags/english.png') }}" class="flag">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right animated fadeInUp" aria-labelledby="language_menu">
                        <a href="#" class="dropdown-item"><img src="{{ url('assets/images/flags/spanish.png') }}" class="flag"> Español</a>
                        <a href="#" class="dropdown-item"><img src="{{ url('assets/images/flags/french.png') }}" class="flag"> Français</a>
                        <a href="#" class="dropdown-item"><img src="{{ url('assets/images/flags/german.png') }}" class="flag"> Deutsche</a>
                    </div>
                </li>
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
                        <a href="{{ url('account/profile') }}" class="dropdown-item"><i class="fa fa-user text-primary"></i> My Profile</a>
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
                <a href="{{ url('account') }}" class="nav-link  {{ nav_active('^account$') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
            </li>
            <li class="nav-item open">
                <a href="{{ url('admin/forms') }}" class="nav-link {{ nav_active('^admin/forms') }}"><i class="fa fa-file-text-o"></i> <span>Forms</span></a>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cogs"></i> <span>System</span></a>
                <div class="dropdown-menu">
                    <a href="{{ url('account/billing/subscription') }}" class="dropdown-item {{ nav_active('^account/billing') }}"><i class="fa fa-credit-card"></i> Billing & Subscription</a>
                    <a href="{{ url('account/users') }}" class="dropdown-item {{ nav_active('^account/users') }}"><i class="fa fa-users"></i> Users</a>
                    <a href="{{ url('account/roles') }}" class="dropdown-item {{ nav_active('^account/roles') }}"><i class="fa fa-key"></i> Roles</a>
                    <a href="{{ url('account/settings') }}" class="dropdown-item {{ nav_active('^account/settings') }}"><i class="fa fa-wrench"></i> Account Settings</a>
                </div>
            </li>
        </ul>

    </div>

    <div class="trial-sandbox-wrapper">

        @if ( ($is_trial || $is_trial_expired || $is_canceled) )
            <div class="trial-notice {{ $is_trial_expired || $is_canceled ? 'trial-expired' : '' }}">
                @if ( $is_trial_expired || $is_canceled )
                    <i class="fa fa-exclamation-circle"></i>
                    @if ( $is_canceled )
                        <strong>Your subscription was canceled on {{ $subscription->canceled_at->toFormattedDateString() }}.</strong><br><a href="{{ url('account/billing/upgrade') }}">Resume your subscription</a>
                    @else
                        <strong>Your free trial has expired.</strong><br><a href="{{ url('account/billing/upgrade') }}">Upgrade now</a> to resume uninterrupted service.
                    @endif
                @else
                    <i class="fa fa-info-circle"></i>
                    @if ( $trial_days_left > 1 )
                        <strong>{{ $trial_days_left }} days</strong> till free trial ends.
                    @else
                        Free trial ends <strong>today</strong>.
                    @endif
                    <br><a href="{{ url('account/billing/upgrade') }}">Upgrade now</a> to enjoy uninterrupted service.
                @endif
            </div>
        @endif

        @if ( $is_sandbox )
            <div class="sandbox-notice">
                Hey! Your account is currently in <strong>sandbox</strong> mode.  <a href="#">Click here</a> to learn what that means (and how to get rid of this message).
            </div>
        @endif

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
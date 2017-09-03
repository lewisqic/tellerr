<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Auth | Tellerr</title>

    <link rel="stylesheet" href="{{ url('assets/css/skins/blue.css') }}" />
    @stack('styles')

</head>

<body>

    <div class="container-fluid">
        <div class="row auth-wrapper">
            <div class="col panel left">
                <div class="back">
                    <a href="{{ url('/') }}"><i class="fa fa-long-arrow-left"></i> Go Back Home</a>
                </div>
                <div class="panel-inner">
                    <h2 class="logo">
                        Tellerr <small>v1.0.0</small>
                    </h2>
                </div>
            </div>
            <div class="col panel right">
                <div class="panel-inner">
                    <div class="row">
                        <div class="col-sm-6 mx-auto">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{!! Js::config() !!}
{!! Js::msg() !!}
<script src="{{ url('assets/js/vendor.js') }}"></script>
<script src="{{ url('assets/js/core.js') }}"></script>
<script src="{{ url('assets/js/modules/auth.js') }}"></script>
@stack('scripts')

</body>
</html>
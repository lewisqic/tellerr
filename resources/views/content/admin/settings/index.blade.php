@extends('layouts.admin')

@section('content')

    {!! Breadcrumbs::render('admin/settings') !!}

    <h1>Settings</h1>

    <div class="page-content container-fluid">

        <form action="{{ url('admin/settings') }}" method="post" class="validate tabs labels-right" id="edit_settings_form">
            {!! Html::hiddenInput(['method' => 'post']) !!}


            <ul class="nav nav-tabs hash-tabs" role="tablist">
                @php $i = 0 @endphp
                @foreach ( $tabs as $tab )
                <li class="nav-item">
                    <a class="nav-link {{ $i == 0 ? 'active' : '' }}" data-toggle="tab" href="#{{ str_slug($tab) }}" role="tab">{{ $tab }}</a>
                </li>
                @php $i++ @endphp
                @endforeach
            </ul>

            <div class="tab-content mt-4">
                @php $i = 0 @endphp
                @foreach ( $tabs as $tab )
                <div class="tab-pane {{ $i == 0 ? 'active' : '' }}" id="{{ str_slug($tab) }}" role="tabpanel">

                    @foreach ( $settings[$tab] as $setting )
                        <div class="form-group row">
                            <label class="col-form-label col-sm-3">{{ $setting->label }}</label>
                            <div class="col-sm-9">
                                <input type="text" name="settings[{{ $setting->key }}]" class="form-control" placeholder="{{ $setting->label }}" value="{{ $setting->value }}" {!! $setting->is_required ? 'data-fv-notempty="true"' : '' !!}>
                                <small class="form-text text-muted">{{ $setting->description }}</small>
                            </div>
                        </div>
                    @endforeach

                </div>
                @php $i++ @endphp
                @endforeach
            </div>

            <div class="form-group row mt-4">
                <div class="col-sm-9 ml-auto">
                    <button type="submit" class="btn btn-primary" data-loading-text="<i class='fa fa-circle-o-notch fa-spin fa-lg'></i>"><i class="fa fa-check"></i> Save</button>
                </div>
            </div>

        </form>

    </div>

@endsection
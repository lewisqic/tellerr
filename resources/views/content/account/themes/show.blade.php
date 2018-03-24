@extends(\Request::ajax() ? 'layouts.ajax' : 'layouts.account')

@section('content')

    {!! Breadcrumbs::render('account/themes/show', $theme) !!}

    <div class="float-right">
        <a href="{{ url('account/themes/' . $theme->id . '/edit?_ajax=false&_redir=' . urlencode(url('account/themes/' . $theme->id))) }}" class="btn btn-primary open-sidebar"><i class="fa fa-edit"></i> Edit</a>
        <form action="{{ url('account/themes/' . $theme->id) }}" method="post" class="validate d-inline ml-2" id="delete_form">
            {!! \Html::hiddenInput(['method' => 'delete']) !!}
            <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
        </form>
    </div>

    <h1>{{ $theme->name }} <small>Theme</small></h1>

    <div class="page-content container-fluid">

        <div class="labels-right">

            <div class="form-group row">
                <label class="col-form-label col-sm-2">Theme Name:</label>
                <div class="col-sm-10 form-control-static">
                    {{ $theme->name }}
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">Form Title:</label>
                <div class="col-sm-10 form-control-static">
                    {{ $theme->show_title ? 'Yes' : 'No' }}
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">Logo:</label>
                <div class="col-sm-10 form-control-static">
                    {{ $theme->show_logo ? 'Yes' : 'No' }}
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">Logo Image:</label>
                <div class="col-sm-10 form-control-static">
                    @if ( $theme->logo_image )
                        <div class="row">
                            <div class="col-sm-2"><img src="{{ Storage::url($theme->logo_image) }}" class="w-100"></div>
                        </div>
                    @else
                        <em>no logo image set</em>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">Background Type:</label>
                <div class="col-sm-10 form-control-static">
                    {{ ucwords($theme->background_type) }}
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">Background Image:</label>
                <div class="col-sm-10 form-control-static">
                    @if ( $theme->background_image )
                        <div class="row">
                            <div class="col-sm-2"><img src="{{ Storage::url($theme->background_image) }}" class="w-100"></div>
                        </div>
                    @else
                        <em>no background image set</em>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">Background Color:</label>
                <div class="col-sm-10 form-control-static">
                    <i class="fa fa-square" style="color: {{ $theme->background_color }};"></i> {{ $theme->background_color }}
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">Primary Color:</label>
                <div class="col-sm-10 form-control-static">
                    <i class="fa fa-square" style="color: {{ $theme->primary_color }};"></i> {{ $theme->primary_color }}</span>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">Secondary Color:</label>
                <div class="col-sm-10 form-control-static">
                    <i class="fa fa-square" style="color: {{ $theme->secondary_color }};"></i> {{ $theme->secondary_color }}</span>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">Custom CSS:</label>
                <div class="col-sm-10 form-control-static">
                    {{ $theme->enable_custom_css ? 'Yes' : 'No' }}
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-2">CSS Rules:</label>
                <div class="col-sm-10 form-control-static">
                    @if ( $theme->custom_css )
                        <code>{{ $theme->custom_css }}</code>
                    @else
                        <em>no css rules set</em>
                    @endif
                </div>
            </div>

            <br>

            @if ( $theme->updated_at )
                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Last Updated:</label>
                    <div class="col-sm-10 form-control-static">
                        {{ $theme->updated_at->toDayDateTimeString() }}
                    </div>
                </div>
            @endif

            <div class="form-group row">
                <label class="col-form-label col-sm-2">Date Created:</label>
                <div class="col-sm-10 form-control-static">
                    {{ $theme->created_at->toDayDateTimeString() }}
                </div>
            </div>


        </div>

    </div>

@endsection
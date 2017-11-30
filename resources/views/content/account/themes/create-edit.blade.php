@extends(\Request::ajax() ? 'layouts.ajax' : 'layouts.account')

@section('content')

@if ( $title == 'Edit' )
    {!! Breadcrumbs::render('account/themes/edit', $theme) !!}
@else
    {!! Breadcrumbs::render('account/themes/create') !!}
@endif

<h1>{{ $title }} Theme <small>{{ $theme->name or '' }}</small></h1>

<div class="page-content container-fluid">

    <form action="{{ $action }}" method="post" class="validate labels-right" id="create_edit_theme_form">
        <input type="hidden" name="id" value="{{ $theme->id or '' }}">
        {!! Html::hiddenInput(['method' => $method]) !!}

        <div class="form-group row">
            <label class="col-form-label col-sm-3">Name</label>
            <div class="col-sm-9">
                <input type="text" name="name" class="form-control" placeholder="Name" value="{{ $theme->name or old('name') }}" data-fv-notempty="true" data-fv-stringlength="true" data-fv-stringlength-min="2" data-fv-stringlength-max="80" autofocus>
            </div>
        </div>

        <div class="form-group row mt-4">
            <div class="col-sm-9 ml-auto">
                <button type="submit" class="btn btn-primary" data-loading-text="<i class='fa fa-circle-o-notch fa-spin fa-lg'></i>"><i class="fa fa-check"></i> Save</button>
                <a href="#" class="btn btn-secondary close-sidebar">Cancel</a>
            </div>
        </div>

    </form>

</div>

@endsection
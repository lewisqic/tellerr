@extends(\Request::ajax() ? 'layouts.ajax' : 'layouts.account')

@section('content')

    {!! Breadcrumbs::render('admin/roles/show', $role) !!}

    <div class="float-right">
        <a href="{{ url('admin/roles/' . $role->id . '/edit?_ajax=false&_redir=' . urlencode(url('admin/roles/' . $role->id))) }}" class="btn btn-primary open-sidebar"><i class="fa fa-edit"></i> Edit</a>
        <form action="{{ url('admin/roles/' . $role->id) }}" method="post" class="validate d-inline ml-2" id="delete_form">
            {!! \Html::hiddenInput(['method' => 'delete']) !!}
            <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
        </form>
    </div>

    <h1>{{ $role->name }} <small>Theme</small></h1>

    <div class="page-content container-fluid">

        <div class="labels-right">

            <div class="form-group row">
                <label class="col-form-label col-sm-2">Name:</label>
                <div class="col-sm-10 form-control-static">
                    {{ $role->name }}
                </div>
            </div>

            <br>

            @if ( $role->updated_at )
                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Last Updated:</label>
                    <div class="col-sm-10 form-control-static">
                        {{ $role->updated_at->toDayDateTimeString() }}
                    </div>
                </div>
            @endif

            <div class="form-group row">
                <label class="col-form-label col-sm-2">Date Created:</label>
                <div class="col-sm-10 form-control-static">
                    {{ $role->created_at->toDayDateTimeString() }}
                </div>
            </div>


        </div>

    </div>

@endsection
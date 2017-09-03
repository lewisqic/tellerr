@extends(\Request::ajax() ? 'layouts.ajax' : 'layouts.account')

@section('content')

@if ( $title == 'Edit' )
    {!! Breadcrumbs::render('account/roles/edit', $role) !!}
@else
    {!! Breadcrumbs::render('account/roles/create') !!}
@endif

<h1>{{ $title }} Role <small>{{ $role->name or '' }}</small></h1>

<div class="page-content container-fluid">

    <form action="{{ $action }}" method="post" class="validate labels-right" id="create_edit_role_form">
        <input type="hidden" name="id" value="{{ $role->id or '' }}">
        {!! Html::hiddenInput(['method' => $method]) !!}

        <div class="form-group row">
            <label class="col-form-label col-sm-3">Name</label>
            <div class="col-sm-9">
                <input type="text" name="name" class="form-control" placeholder="Name" value="{{ $role->name or old('name') }}" data-fv-notempty="true" data-fv-stringlength="true" data-fv-stringlength-min="2" data-fv-stringlength-max="80" autofocus>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-sm-3">Default</label>
            <div class="col-sm-9">
                <select name="is_default" class="form-control">
                    <option value="0" {{ isset($role) && !$role->is_default ? 'selected' : '' }}>No</option>
                    <option value="1" {{ isset($role) && $role->is_default ? 'selected' : '' }}>Yes</option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-sm-3">
                <sup><a href="#" data-toggle="tooltip" title="Select the permissions to be granted to users assigned to this role."><i class="fa fa-info-circle"></i></a></sup>
                Permissions
            </label>
            <div class="col-sm-9 form-control-static">
                <div class="permissions-wrapper">
                    @foreach ( $permissions as $group )
                    <div class="permission-group-wrapper mb-3">
                        <div class="group">
                            <div class="abc-checkbox abc-checkbox-primary checkbox-inline">
                                <input type="checkbox" class="permission-group" id="{{ $group['label'] }}">
                                <label for="{{ $group['label'] }}">{{ $group['label'] }}</label>
                            </div>
                        </div>
                        <div class="functions ml-4">
                            @foreach ( $group['actions'] as $key => $value )
                            @php $id = $group['controller'] . '@' . $key @endphp
                            <div class="abc-checkbox abc-checkbox-primary checkbox-inline">
                                <input type="checkbox" class="permission-function" name="permissions[]" id="{{ $id }}" value="{{ $id }}" {{ isset($auth_role->permissions[$id]) && $auth_role->permissions[$id] ? 'checked' : '' }}>
                                <label for="{{ $id }}"><small>{{ $value }}</small></label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
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

@push('scripts')
<script src="{{ url('assets/js/modules/permissions.js') }}"></script>
@endpush
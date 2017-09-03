@extends(\Request::ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')

    {!! Breadcrumbs::render('admin/roles/show', $role) !!}

    <div class="float-right">
        <a href="{{ url('admin/roles/' . $role->id . '/edit?_ajax=false&_redir=' . urlencode(url('admin/roles/' . $role->id))) }}" class="btn btn-primary open-sidebar"><i class="fa fa-edit"></i> Edit</a>
        <form action="{{ url('admin/roles/' . $role->id) }}" method="post" class="validate d-inline ml-2" id="delete_form">
            {!! \Html::hiddenInput(['method' => 'delete']) !!}
            <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
        </form>
    </div>

    <h1>{{ $role->name }} <small>Role</small></h1>

    <div class="page-content container-fluid">

        <div class="labels-right">

            <ul class="nav nav-tabs hash-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#show_details" role="tab">Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#show_users" role="tab">Users</a>
                </li>
            </ul>

            <div class="tab-content mt-4">
                <div class="tab-pane active" id="show_details" role="tabpanel">

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">Name:</label>
                        <div class="col-sm-10 form-control-static">
                            {{ $role->name }}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">Default:</label>
                        <div class="col-sm-10 form-control-static">
                            {{ $role->is_default ? 'Yes' : 'No' }}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">Permissions:</label>
                        <div class="col-sm-10 form-control-static">
                            @foreach ( $permissions as $group )
                                @if ( preg_match('/' . $group['controller'] . '@/', $role->permissions) )
                                    <div>{{ $group['label'] }}</div>
                                @endif
                                @foreach ( $group['actions'] as $key => $value )
                                    @php $id = $group['controller'] . '@' . $key @endphp
                                    @if ( isset($auth_role->permissions[$id]) )
                                        <div class="ml-4"><small>{{ $value }}</small></div>
                                    @endif
                                @endforeach
                            @endforeach
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
                <div class="tab-pane" id="show_users" role="tabpanel">

                    <table id="list_role_users_table" class="datatable table table-striped table-hover" data-url="{{ url('admin/administrators/data') }}" data-params='{"role_id": "{{ $role->id }}"}'>
                        <thead>
                            <tr>
                                <th data-name="first_name" data-order="primary-asc">First Name</th>
                                <th data-name="last_name">Last Name</th>
                                <th data-name="email">Email</th>
                                <th data-name="last_login" data-o-sort="true">Last Login</th>
                                <th data-name="created_at" data-o-sort="true">Date Created</th>
                                {!! Html::dataTablesActionColumn(true) !!}
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>
            </div>


        </div>

    </div>

@endsection
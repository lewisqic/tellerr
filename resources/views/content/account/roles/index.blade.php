@extends('layouts.account')

@section('content')

    {!! Breadcrumbs::render('account/roles') !!}

    <div class="float-right">
        <a href="{{ url('account/roles/create') }}" class="btn btn-primary open-sidebar"><i class="fa fa-plus-circle"></i> Add Role</a>
    </div>

    <h1>User Roles</h1>

    <div class="page-content container-fluid">

        <table id="list_roles_table" class="datatable table table-striped table-hover" data-url="{{ url('account/roles/data') }}" data-params='{}'>
            <thead>
                <tr>
                    <th data-name="name" data-order="primary-asc">Name</th>
                    <th data-name="is_default">Default</th>
                    <th data-name="user_count">User Count</th>
                    <th data-name="created_at" data-o-sort="true">Date Created</th>
                    {!! Html::dataTablesActionColumn() !!}
                </tr>
            </thead>
            <tbody></tbody>
        </table>

    </div>

@endsection
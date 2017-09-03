@extends('layouts.account')

@section('content')

    {!! Breadcrumbs::render('account/users') !!}

    <div class="float-right">
        <a href="{{ url('account/users/create') }}" class="btn btn-primary open-sidebar"><i class="fa fa-plus-circle"></i> Add User</a>
    </div>

    <h1>Users</h1>

    <div class="page-content container-fluid">

        <div class="datatable-filters">
            <div class="abc-checkbox abc-checkbox-primary checkbox-inline">
                <input type="checkbox" id="with_trashed"><label for="with_trashed">Show Deleted</label>
            </div>
        </div>
        <table id="list_users_table" class="datatable table table-striped table-hover" data-url="{{ url('account/users/data') }}" data-params='{}'>
            <thead>
                <tr>
                    <th data-name="first_name" data-order="primary-asc">First Name</th>
                    <th data-name="last_name">Last Name</th>
                    <th data-name="email">Email</th>
                    <th data-name="roles">Role(s)</th>
                    <th data-name="created_at" data-o-sort="true">Date Created</th>
                    {!! Html::dataTablesActionColumn() !!}
                </tr>
            </thead>
            <tbody></tbody>
        </table>

    </div>

@endsection
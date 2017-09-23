@extends('layouts.account')

@section('content')

    {!! Breadcrumbs::render('account/forms') !!}

    <div class="float-right">
        <a href="{{ url('account/forms/create') }}" class="btn btn-primary"><i class="fa fa-wpforms"></i> Create Form</a>
    </div>

    <h1>Forms</h1>

    <div class="page-content container-fluid">

        <div class="datatable-filters">
            <div class="abc-checkbox abc-checkbox-primary checkbox-inline">
                <input type="checkbox" id="with_trashed"><label for="with_trashed">Show Deleted</label>
            </div>
        </div>
        <table id="list_forms_table" class="datatable table table-striped table-hover" data-url="{{ url('account/forms/data') }}" data-params='{}'>
            <thead>
                <tr>
                    <th data-name="title">Title</th>
                    <th data-name="created_at" data-order="primary-desc" data-o-sort="true">Date Created</th>
                    {!! Html::dataTablesActionColumn() !!}
                </tr>
            </thead>
            <tbody></tbody>
        </table>

    </div>

@endsection
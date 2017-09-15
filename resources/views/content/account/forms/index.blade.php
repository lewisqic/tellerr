@extends('layouts.account')

@section('content')

    {!! Breadcrumbs::render('account/forms') !!}

    <div class="float-right">
        <a href="{{ url('account/forms/create') }}" class="btn btn-primary"><i class="fa fa-wpforms"></i> Create Form</a>
    </div>

    <h1>Forms</h1>

    <div class="page-content container-fluid">

        <table id="list_forms_table" class="datatable table table-striped table-hover" data-url="{{ url('account/forms/data') }}" data-params='{}'>
            <thead>
                <tr>
                    <th data-name="name" data-order="primary-asc">Name</th>
                    <th data-name="created_at" data-o-sort="true">Date Created</th>
                    {!! Html::dataTablesActionColumn() !!}
                </tr>
            </thead>
            <tbody></tbody>
        </table>

    </div>

@endsection
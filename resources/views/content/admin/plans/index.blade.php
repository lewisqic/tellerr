@extends('layouts.admin')

@section('content')

    {!! Breadcrumbs::render('admin/plans') !!}

    <div class="float-right">
        <a href="{{ url('admin/plans/create') }}" class="btn btn-primary open-sidebar"><i class="fa fa-list-alt"></i> Add Plan</a>
    </div>

    <h1>Plans</h1>

    <div class="page-content container-fluid">

        <table id="list_plans_table" class="datatable table table-striped table-hover" data-url="{{ url('admin/plans/data') }}" data-params='{}'>
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
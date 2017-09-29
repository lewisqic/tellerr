@extends(\Request::ajax() ? 'layouts.ajax' : 'layouts.account')

@section('content')

    {!! Breadcrumbs::render('account/forms/show', $form) !!}

    <div class="float-right">
        <a href="{{ subdomain('f/' . $form->unique_id) }}" class="btn btn-secondary" target="_blank"><i class="fa fa-external-link"></i> Preview</a>
        <a href="{{ url('account/forms/' . $form->id . '/edit?_redir=' . urlencode(url('account/forms/' . $form->id))) }}" class="btn btn-primary ml-2"><i class="fa fa-edit"></i> Edit</a>
        <form action="{{ url('account/forms/' . $form->id) }}" method="post" class="validate d-inline ml-2" id="delete_form">
            {!! \Html::hiddenInput(['method' => 'delete']) !!}
            <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
        </form>
    </div>

    <h1>{{ $form->title }} <small>Form</small></h1>

    <div class="page-content container-fluid">

        <div class="labels-right">

            <ul class="nav nav-tabs hash-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#overview" role="tab">Overview</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#use" role="tab">Use/Embed</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#payments" role="tab">Payments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#subscriptions" role="tab">Subscriptions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#statistics" role="tab">Statistics</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#integrations" role="tab">Integrations</a>
                </li>
            </ul>

            <div class="tab-content mt-4">
                <div class="tab-pane active" id="overview" role="tabpanel">

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">Title:</label>
                        <div class="col-sm-10 form-control-static">
                            {{ $form->title }}
                        </div>
                    </div>


                    <br>

                    @if ( $form->updated_at )
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2">Last Updated:</label>
                            <div class="col-sm-10 form-control-static">
                                {{ $form->updated_at->toDayDateTimeString() }}
                            </div>
                        </div>
                    @endif

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">Date Created:</label>
                        <div class="col-sm-10 form-control-static">
                            {{ $form->created_at->toDayDateTimeString() }}
                        </div>
                    </div>

                </div>
                <div class="tab-pane" id="show_users" role="tabpanel">

                    <table id="list_role_users_table" class="datatable table table-striped table-hover" data-url="{{ url('account/forms/data') }}" data-params='{"role_id": "{{ $form->id }}"}'>
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
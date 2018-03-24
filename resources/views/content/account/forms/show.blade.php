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
                        <label class="col-form-label col-sm-2">Form ID:</label>
                        <div class="col-sm-10 form-control-static">
                            {{ $form->unique_id }}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">Title:</label>
                        <div class="col-sm-10 form-control-static">
                            {{ $form->title }}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">Show Form Description:</label>
                        <div class="col-sm-10 form-control-static">
                            {{ $form->show_description ? 'Yes' : 'No' }}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">Form Description:</label>
                        <div class="col-sm-10 form-control-static">
                            {{ $form->description }}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">Add Terms & Conditions:</label>
                        <div class="col-sm-10 form-control-static">
                            {{ $form->terms ? 'Yes' : 'No' }}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">Terms & Conditions Text:</label>
                        <div class="col-sm-10 form-control-static">
                            {{ $form->terms }}
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
            </div>


        </div>

    </div>

@endsection
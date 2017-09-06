@extends('layouts.account')

@section('content')

	@inject('company_payment_method', 'App\Services\CompanyPaymentMethodService')

	@include('partials.account.modals')

	{!! Breadcrumbs::render('account/billing/payment-methods') !!}

	<h1>Billing & Subscription</h1>

	<div class="page-content container-fluid">

		<ul class="nav nav-tabs" role="tablist">
			<li class="nav-item">
				<a href="{{ url('account/billing/subscription') }}" class="nav-link" role="tab">My Subscription</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active" role="tab">Payment Methods</a>
			</li>
			<li class="nav-item">
				<a href="{{ url('account/billing/history') }}" class="nav-link" role="tab">Billing History</a>
			</li>
		</ul>

		<div class="tab-content mt-4">
			<div class="tab-pane active" role="tabpanel">

				<div class="labels-right">

					@if ( $default_payment_method && $company_payment_method->isExpired($default_payment_method) )
						<div class="alert alert-alt alert-danger mb-4">
							<i class="fa fa-exclamation-circle"></i> Your default payment method has expired, please add a new default payment method.
						</div>
					@endif

					@foreach ( $payment_methods as $payment_method )
						<div class="form-group row">
							<label class="col-form-label col-sm-3">{{ $payment_method->cc_type }} {!! Html::ccIcon($payment_method->cc_type) !!}</label>
							<div class="col-sm-9 form-control-static">
								<div class="row">
									<div class="col-sm-3">
										XXXX-{{ $payment_method->cc_last4 }},
										{{ $payment_method->cc_expiration_month . '/' . substr($payment_method->cc_expiration_year, 2) }}
										@if ( $payment_method->is_default )
											<span class="badge badge-primary ml-3">default</span>
										@endif
										@if ( $company_payment_method->almostExpired($payment_method) )
											<br><small class="text-warning"><i class="fa fa-exclamation-triangle"></i> Card expires soon!</small>
										@endif
										@if ( $company_payment_method->isExpired($payment_method) )
											<br><small class="text-danger"><i class="fa fa-exclamation-circle"></i> Card is expired!</small>
										@endif
									</div>
									<div class="col-sm-9">
										@if ( !$payment_method->is_default )
											<form action="{{ url('account/billing/payment-method/' . $payment_method->id) }}" method="post" class="validate d-inline mr-2">
												{!! Html::hiddenInput(['method' => 'delete']) !!}
												<button class="btn btn-outline-secondary btn-sm text-muted confirm-click"><i class="fa fa-trash-o text-danger"></i> Delete</button>
											</form>
											@if ( !$company_payment_method->isExpired($payment_method) )
												<form action="{{ url('account/billing/payment-method/' . $payment_method->id) }}" method="post" class="validate d-inline">
													{!! Html::hiddenInput(['method' => 'put']) !!}
													<button class="btn btn-outline-secondary btn-sm text-muted"><i class="fa fa-check text-primary"></i> Set as Default</button>
												</form>
											@endif
										@endif
									</div>
								</div>
							</div>
						</div>
					@endforeach

					@if ( $payment_methods->isEmpty() )
						<em class="text-muted">no payment methods found</em>
					@else
						<div class="form-group row mt-5">
							<div class="col-sm-9 ml-auto">
								<button class="btn btn-sm btn-outline-success" data-toggle="modal" data-target="#add_payment_method"><i class="fa fa-credit-card"></i> Add Payment Method</button>
							</div>
						</div>
					@endif

				</div>

			</div>
		</div>

	</div>

	@push('scripts')
		{!! Js::stripeConfig() !!}
		<script src="https://js.stripe.com/v3/"></script>
		<script src="{{ url('assets/js/modules/upgrade.js') }}"></script>
	@endpush

@endsection


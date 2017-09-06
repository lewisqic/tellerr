@extends('layouts.account')

@section('content')


	{!! Breadcrumbs::render('account/billing/history') !!}

	<h1>Billing & Subscription</h1>

	<div class="page-content container-fluid">

		<ul class="nav nav-tabs" role="tablist">
			<li class="nav-item">
				<a href="{{ url('account/billing/subscription') }}" class="nav-link" role="tab">My Subscription</a>
			</li>
			<li class="nav-item">
				<a href="{{ url('account/billing/payment-methods') }}" class="nav-link" role="tab">Payment Methods</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active" role="tab">Billing History</a>
			</li>
		</ul>

		<div class="tab-content mt-4">
			<div class="tab-pane active" role="tabpanel">

				<table id="list_company_payments_table" class="datatable table table-striped table-hover" data-url="{{ url('account/billing/history/data') }}" data-params='{}'>
					<thead>
					<tr>
						<th data-name="id" data-order="false">ID</th>
						<th data-name="amount">Amount</th>
						<th data-name="status">Status</th>
						<th data-name="notes" data-order="false">Notes</th>
						<th data-name="payment_method" data-order="false">Payment Method</th>
						<th data-name="created_at" data-o-sort="true" data-order="primary-desc">Date Created</th>
					</tr>
					</thead>
					<tbody></tbody>
				</table>

			</div>
		</div>

	</div>


@endsection


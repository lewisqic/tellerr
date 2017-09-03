<div class="modal fade" id="add_payment_method" tabindex="-1" role="dialog" aria-labelledby="add_payment_method_label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Payment Method</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ url('account/billing/payment-method') }}" method="post" class="labels-right payment-form" id="add_payment_method_form">
                <input type="hidden" name="nonce" value="">
                {!! Html::hiddenInput(['method' => 'post']) !!}

                <div class="modal-body">

                    <h6 class="text-muted mt-3 mb-5">
                        <img src="{{ url('assets/images/credit-cards.jpg') }}" class="float-right">
                        Add New Credit/Debit Card
                    </h6>


                    <div class="form-group row">
                        <label class="col-form-label col-sm-3">Number</label>
                        <div class="col-sm-9">
                            <!--  Hosted Fields div container -->
                            <div class="form-group hosted-field">
                                <div class="form-control" id="card_number"></div>
                                <span class="helper-text"></span>
                            </div>
                            <span class="payment-lock"><i class="fa fa-lock text-muted"></i></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3">Expiration</label>
                        <div class="col-sm-9">
                            <div class="row">
                                <div class="col-sm-5">
                                    <!--  Hosted Fields div container -->
                                    <div class="form-group hosted-field">
                                        <div class="form-control" id="expiration_date"></div>
                                    </div>
                                    <span class="payment-lock"><i class="fa fa-lock text-muted"></i></span>
                                </div>
                                <div class="col-sm-2 text-right">
                                    <label class="col-form-label">CVV</label>
                                </div>
                                <div class="col-sm-5">
                                    <!--  Hosted Fields div container -->
                                    <div class="form-group hosted-field">
                                        <div class="form-control" id="cvv"></div>
                                    </div>
                                    <span class="payment-lock"><i class="fa fa-lock text-muted"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-9 ml-auto">
                            <div class="abc-checkbox abc-checkbox-primary checkbox-inline">
                                <input type="checkbox" name="is_default" id="is_default" value="1" checked>
                                <label for="is_default">Make this my default payment method</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-2 error-wrapper d-none">
                        <div class="alert alert-alt alert-danger">
                            <button type="button" class="close" data-hide="error-wrapper" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <div class="mb10">
                                <strong><i class="fa fa-exclamation-triangle"></i> Oh snap! Something went wrong...</strong>
                            </div>
                            <em class="error-message"></em>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i>"><i class="fa fa-check"></i> Save</button>
                </div>

            </form>

        </div>
    </div>
</div>
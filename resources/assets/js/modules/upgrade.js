/******************************************************
 * Upgrade class used on the account upgrade page
 ******************************************************/
class Upgrade {

    /**
     * Class constructor, called when instantiating new class object
     */
    constructor() {
        // declare our class properties
        this.stripe = Stripe(stripe_config.publishable_key);
        this.card = null;
        // call init
        this.init();
    }

    /**
     * We run init when our class is first instantiated
     */
    init() {
        // bind events
        this.bindEvents();
        // setup stripe
        this.setupStripe();
    }

    /**
     * bind all necessary events
     */
    bindEvents() {
        let self = this;
        $('input[name="installment"]').on('change', function(e) {
            self.handleInstallmentRadioChange();
        });
        $('select[name="plan_id"]').not('.new-plan').on('change', function(e) {
            self.handlePlanIdSelectChange();
        });
        $('select.new-plan').on('change', function(e) {
            self.handleNewPlanSelectChange();
        });
        $('button.submit').on('click', function(e) {
            self.handleSubmitClick();
        });
        $('input[name="payment_method"]').on('change', function(e) {
            self.handlePaymentMethodRadioChange();
        });
        $('.apply-coupon').on('click', function(e) {
           e.preventDefault();
           self.applyCoupon();
        });
        $('.remove-coupon').on('click', function(e) {
            e.preventDefault();
            self.removeCoupon();
        });
    }

	/*
	 handle the installment radio change event
	 */
    handleInstallmentRadioChange() {
		let installment = $('input[name="installment"]:checked').attr('data-installment');
		let price = $('input[name="installment"]:checked').attr('data-price');
		$('.installment-term').hide();
		$('.installment-term[data-installment="' + installment + '"]').show();
		$('.installment-price').html(price);
	}

	/*
	 handle the plan ID select change event
	 */
    handlePlanIdSelectChange() {
		let price_month = $('select[name="plan_id"] option:selected').attr('data-price-month');
		let price_year = $('select[name="plan_id"] option:selected').attr('data-price-year');
		$('.plan-price[data-installment="month"]').html(price_month);
		$('.plan-price[data-installment="year"]').html(price_year);
		$('input[name="installment"][data-installment="month"').attr('data-price', price_month);
		$('input[name="installment"][data-installment="year"').attr('data-price', price_year);
		this.handleInstallmentRadioChange();
	}

    /**
     * handle the new plan select change event
     */
    handleNewPlanSelectChange() {
        let current_amount = parseFloat($('.current-amount').val());
        let selected_price = $('select[name="plan_id"] option:selected').attr('data-price');

        $('.payment-required, .no-payment-required').hide();

        if ( selected_price !== undefined ) {
            let new_price = parseFloat(selected_price.replace(/\$/, ''));
            if (new_price > current_amount) {
                $('.payment-required').show();
                let total_due = new_price - current_amount;
                $('.total-due-today').html('$' + total_due.toFixed(2));
            } else {
                $('.no-payment-required').show();
            }
            $('.new-plan-price').html('$' + new_price);
        }
    }

    /**
     * handle our submit button click event
     */
    handleSubmitClick() {
		let self = this;
		$('button.submit').button('loading');
		$('.error-wrapper').hide();

		if ( $('#change_plan_form').length ) {
            $('#change_plan_form').submit();
		} else if ( $('select[name="company_payment_method_id"]').is(':visible') ) {
            self.submitPaymentForm();
        } else {
		    self.getToken();
        }
	}

    /**
     * handle our payment method radio change event
     */
    handlePaymentMethodRadioChange() {
		let val = $('input[name="payment_method"]:checked').val();
		$('.payment-methods-wrapper').hide();
		$('.payment-methods-wrapper[data-method="' + val + '"]').show();
	}

    /**
     * setup our stripe stuff
     */
    setupStripe() {

        let self = this;
        let elements = self.stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        let style = {
            base: {
                color: '#495057',
                fontSize: '16px',
                lineHeight: '24px',
                fontFamily: 'helvetica, tahoma, calibri, sans-serif'
            }
        };

        if ( $('#card_element').length ) {

            // Create an instance of the card Element
            self.card = elements.create('card', {style: style});

            // Add an instance of the card Element into the `card-element` <div>
            self.card.mount('#card_element');
            self.card.on('ready', function() {
                $('button.submit').prop('disabled', false);
            });

            // setup error listening on card element
            self.card.addEventListener('change', function(event) {
                if ( event.error ) {
                    self.setPaymentError(event.error.message);
                } else {
                    self.setPaymentError();
                }
            });

        }

    }

    /**
     * get a payment token
     */
    getToken() {
        let self = this;
        let $token = $('input[name="token"]');

        if ( $token.val() === '' ) {

            self.stripe.createToken(self.card).then(function(result) {
                if ( result.error ) {
                    self.setPaymentError(result.error.message);
                } else {
                    self.setPaymentError();
                    $token.val(result.token.id);
                    self.submitPaymentForm();
                }
            });

        }

    }

    /**
     * submit our form to complete subscription
     */
    submitPaymentForm() {
        let self = this;
        $('.payment-form').ajaxSubmit({
            beforeSubmit: function() {
            },
            error: function(jqXHR, textStatus, errorThrown) {
                let error = jqXHR.responseJSON && jqXHR.responseJSON.message ? jqXHR.responseJSON.message : ( jqXHR.responseText ? jqXHR.responseText : 'Oops, something went wrong...');
                self.setPaymentError(error);
            },
            success: function(data) {
                if ( data.route ) {
                    window.location = data.route;
                }
            }
        });
    }

    /**
     * set our error message
     */
    setPaymentError(message) {
        $('input[name="token"]').val('');
        $('.error-message').html(message);
        if ( message === undefined ) {
            $('.error-wrapper').hide();
        } else {
            $('.error-wrapper').show();
        }
        if ( message !== undefined ) {
            $('button.submit').button('reset');
        }
    }

    /**
     * apply a coupon
     */
    applyCoupon() {
        let self = this;
        let code = $.trim($('input[name="coupon"]').val());
        if ( code === '' ) {
            self.setCouponMessage('Please enter a coupon code.', 'danger');
            return false;
        }

        $.ajax({
            url: Core.url('verify-coupon'),
            method: 'POST',
            dataType: 'json',
            data: {
                code: code
            },
            error: function(jqXHR, textStatus, errorThrown) {
                let message = jqXHR.responseJSON && jqXHR.responseJSON.message ? jqXHR.responseJSON.message : 'Unknown error';
                self.setCouponStatus('danger', message);
                return false;
            },
            beforeSend: function() {
                self.setCouponStatus();
            },
            success: function(data) {

                /*let newPrices = {
                    month: data.new_prices.month,
                    year: data.new_prices.year
                };

                $('.step-wrapper .price-display').not('.price-coupon').addClass('disabled');

                $('.price-coupon[data-term="month"]').html(newPrices.month).show();
                $('.price-coupon[data-term="year"]').html(newPrices.year).show();

                $('.subscription-summary .price-display[data-term="month"]').html(newPrices.month);
                $('.subscription-summary .price-display[data-term="year"]').html(newPrices.year);

                $('.apply-coupon').hide();
                $('.remove-coupon').show();*/





                $('.apply-coupon').hide();
                $('.remove-coupon').show();
                self.setCouponMessage('Coupon applied successfully.', 'success');

            }
        });


    }

    /**
     * remove a coupon
     */
    removeCoupon() {
        let self = this;
        $('input[name="coupon"]').val('');
        $('.remove-coupon').hide();
        $('.apply-coupon').show();
        self.setCouponMessage('');

    }

    /**
     * set our coupon message
     */
    setCouponMessage(message, status) {
        $('.coupon-message').html(message).removeClass('text-success text-danger').addClass('text-' + status);
    }

}

/******************************************************
 * Instantiate new class
 ******************************************************/
$(function() {
    new Upgrade();
});
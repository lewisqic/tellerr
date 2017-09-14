var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/******************************************************
 * Upgrade class used on the account upgrade page
 ******************************************************/
var Upgrade = function () {

    /**
     * Class constructor, called when instantiating new class object
     */
    function Upgrade() {
        _classCallCheck(this, Upgrade);

        // declare our class properties
        this.stripe = Stripe(stripe_config.publishable_key);
        this.card = null;
        // call init
        this.init();
    }

    /**
     * We run init when our class is first instantiated
     */


    _createClass(Upgrade, [{
        key: 'init',
        value: function init() {
            // bind events
            this.bindEvents();
            // setup stripe
            this.setupStripe();
        }

        /**
         * bind all necessary events
         */

    }, {
        key: 'bindEvents',
        value: function bindEvents() {
            var self = this;
            $('input[name="installment"]').on('change', function (e) {
                self.handleInstallmentRadioChange();
            });
            $('select[name="plan_id"]').not('.new-plan').on('change', function (e) {
                self.handlePlanIdSelectChange();
            });
            $('select.new-plan').on('change', function (e) {
                self.handleNewPlanSelectChange();
            });
            $('button.submit').on('click', function (e) {
                self.handleSubmitClick();
            });
            $('input[name="payment_method"]').on('change', function (e) {
                self.handlePaymentMethodRadioChange();
            });
            $('.apply-coupon').on('click', function (e) {
                e.preventDefault();
                self.applyCoupon();
            });
            $('.remove-coupon').on('click', function (e) {
                e.preventDefault();
                self.removeCoupon();
            });
        }

        /*
         handle the installment radio change event
         */

    }, {
        key: 'handleInstallmentRadioChange',
        value: function handleInstallmentRadioChange() {
            var installment = $('input[name="installment"]:checked').attr('data-installment');
            var price = $('input[name="installment"]:checked').attr('data-price');
            $('.installment-term').hide();
            $('.installment-term[data-installment="' + installment + '"]').show();
            $('.installment-price').html(price);
        }

        /*
         handle the plan ID select change event
         */

    }, {
        key: 'handlePlanIdSelectChange',
        value: function handlePlanIdSelectChange() {
            var price_month = $('select[name="plan_id"] option:selected').attr('data-price-month');
            var price_year = $('select[name="plan_id"] option:selected').attr('data-price-year');
            $('.plan-price[data-installment="month"]').html(price_month);
            $('.plan-price[data-installment="year"]').html(price_year);
            $('input[name="installment"][data-installment="month"').attr('data-price', price_month);
            $('input[name="installment"][data-installment="year"').attr('data-price', price_year);
            this.handleInstallmentRadioChange();
        }

        /**
         * handle the new plan select change event
         */

    }, {
        key: 'handleNewPlanSelectChange',
        value: function handleNewPlanSelectChange() {
            var current_amount = parseFloat($('.current-amount').val());
            var selected_price = $('select[name="plan_id"] option:selected').attr('data-price');

            $('.payment-required, .no-payment-required').hide();

            if (selected_price !== undefined) {
                var new_price = parseFloat(selected_price.replace(/\$/, ''));
                if (new_price > current_amount) {
                    $('.payment-required').show();
                    var total_due = new_price - current_amount;
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

    }, {
        key: 'handleSubmitClick',
        value: function handleSubmitClick() {
            var self = this;
            $('button.submit').button('loading');
            $('.error-wrapper').hide();

            if ($('#change_plan_form').length) {
                $('#change_plan_form').submit();
            } else if ($('select[name="company_payment_method_id"]').is(':visible')) {
                self.submitPaymentForm();
            } else {
                self.getToken();
            }
        }

        /**
         * handle our payment method radio change event
         */

    }, {
        key: 'handlePaymentMethodRadioChange',
        value: function handlePaymentMethodRadioChange() {
            var val = $('input[name="payment_method"]:checked').val();
            $('.payment-methods-wrapper').hide();
            $('.payment-methods-wrapper[data-method="' + val + '"]').show();
        }

        /**
         * setup our stripe stuff
         */

    }, {
        key: 'setupStripe',
        value: function setupStripe() {

            var self = this;
            var elements = self.stripe.elements();

            // Custom styling can be passed to options when creating an Element.
            var style = {
                base: {
                    color: '#495057',
                    fontSize: '16px',
                    lineHeight: '24px',
                    fontFamily: 'helvetica, tahoma, calibri, sans-serif'
                }
            };

            if ($('#card_element').length) {

                // Create an instance of the card Element
                self.card = elements.create('card', { style: style });

                // Add an instance of the card Element into the `card-element` <div>
                self.card.mount('#card_element');
                self.card.on('ready', function () {
                    $('button.submit').prop('disabled', false);
                });

                // setup error listening on card element
                self.card.addEventListener('change', function (event) {
                    if (event.error) {
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

    }, {
        key: 'getToken',
        value: function getToken() {
            var self = this;
            var $token = $('input[name="token"]');

            if ($token.val() === '') {

                self.stripe.createToken(self.card).then(function (result) {
                    if (result.error) {
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

    }, {
        key: 'submitPaymentForm',
        value: function submitPaymentForm() {
            var self = this;
            $('.payment-form').ajaxSubmit({
                beforeSubmit: function beforeSubmit() {},
                error: function error(jqXHR, textStatus, errorThrown) {
                    var error = jqXHR.responseJSON && jqXHR.responseJSON.message ? jqXHR.responseJSON.message : jqXHR.responseText ? jqXHR.responseText : 'Oops, something went wrong...';
                    self.setPaymentError(error);
                },
                success: function success(data) {
                    if (data.route) {
                        window.location = data.route;
                    }
                }
            });
        }

        /**
         * set our error message
         */

    }, {
        key: 'setPaymentError',
        value: function setPaymentError(message) {
            $('input[name="token"]').val('');
            $('.error-message').html(message);
            if (message === undefined) {
                $('.error-wrapper').hide();
            } else {
                $('.error-wrapper').show();
            }
            if (message !== undefined) {
                $('button.submit').button('reset');
            }
        }

        /**
         * apply a coupon
         */

    }, {
        key: 'applyCoupon',
        value: function applyCoupon() {
            var self = this;
            var code = $.trim($('input[name="coupon"]').val());
            if (code === '') {
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
                error: function error(jqXHR, textStatus, errorThrown) {
                    var message = jqXHR.responseJSON && jqXHR.responseJSON.message ? jqXHR.responseJSON.message : 'Unknown error';
                    self.setCouponStatus('danger', message);
                    return false;
                },
                beforeSend: function beforeSend() {
                    self.setCouponStatus();
                },
                success: function success(data) {

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

    }, {
        key: 'removeCoupon',
        value: function removeCoupon() {
            var self = this;
            $('input[name="coupon"]').val('');
            $('.remove-coupon').hide();
            $('.apply-coupon').show();
            self.setCouponMessage('');
        }

        /**
         * set our coupon message
         */

    }, {
        key: 'setCouponMessage',
        value: function setCouponMessage(message, status) {
            $('.coupon-message').html(message).removeClass('text-success text-danger').addClass('text-' + status);
        }
    }]);

    return Upgrade;
}();

/******************************************************
 * Instantiate new class
 ******************************************************/


$(function () {
    new Upgrade();
});
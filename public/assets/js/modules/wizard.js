var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/******************************************************
 * Setup wizard class
 ******************************************************/
var Wizard = function () {

    /**
     * Class constructor, called when instantiating new class object
     */
    function Wizard() {
        _classCallCheck(this, Wizard);

        // declare our class properties

        // call init
        this.init();
    }

    /**
     * We run init when our class is first instantiated
     */


    _createClass(Wizard, [{
        key: 'init',
        value: function init() {
            // bind events
            this.bindEvents();
        }

        /**
         * bind all necessary events
         */

    }, {
        key: 'bindEvents',
        value: function bindEvents() {
            var self = this;

            $('.next-step').on('click', function (e) {
                e.preventDefault();
                if (!$(this).hasClass('activate-now')) {
                    self.changeStep('next');
                }
            });
            $('.previous-step').on('click', function (e) {
                e.preventDefault();
                self.changeStep('previous');
            });

            $('input[name="activation"]').on('change', function () {
                self.activationOption($(this));
            });

            $('body').on('click', '.activate-now', function (e) {
                e.preventDefault();
                self.createStripeAccount();
            });

            $('.finish-setup').on('click', function (e) {
                e.preventDefault();
                self.submitForm();
            });

            $('.save-settings').on('click', function (e) {
                e.preventDefault();
                self.submitForm({
                    setup_completed: 0
                }, false);
            });
        }

        /**
         * change our step
         */

    }, {
        key: 'changeStep',
        value: function changeStep(direction) {

            var currentStep = parseFloat($('.step-content:visible').attr('data-step'));
            var nextStep = direction === 'next' ? currentStep + 1 : currentStep - 1;

            // validate form first
            $('#setup_wizard_form').formValidation({
                icon: {
                    valid: 'fa fa-check text-success',
                    invalid: 'fa fa-times text-danger'
                },
                trigger: 'blur',
                framework: 'bootstrap4',
                row: {
                    valid: ''
                }
            }).formValidation('validate');
            var formValidation = $('#setup_wizard_form').data('formValidation');
            if (direction === 'next' && !formValidation.isValid()) {
                return false;
            }

            // hide all steps
            $('.step-content').hide();
            $('.step-heading').removeClass('active');
            // now show the necessary step
            $('.step-content[data-step="' + nextStep + '"]').show();
            $('.step-heading[data-step="' + nextStep + '"]').addClass('active');
        }

        /**
         * handle our activation option change
         */

    }, {
        key: 'activationOption',
        value: function activationOption($this) {
            $('.abc-radio .form-text').hide();
            $this.closest('.abc-radio').find('.form-text').show();

            if ($this.val() == 'now') {
                $('.activation-fields').show();
                $('.next-step:visible').addClass('activate-now');
            } else {
                $('.activation-fields, .activation-error').hide();
                $('.next-step:visible').removeClass('activate-now');
            }
        }

        /**
         * create our deferred stripe account
         */

    }, {
        key: 'createStripeAccount',
        value: function createStripeAccount() {

            // validate form first
            $('#setup_wizard_form').formValidation({
                icon: {
                    valid: 'fa fa-check text-success',
                    invalid: 'fa fa-times text-danger'
                },
                trigger: 'blur',
                framework: 'bootstrap4',
                row: {
                    valid: ''
                }
            }).formValidation('validate');
            var formValidation = $('#setup_wizard_form').data('formValidation');

            if (formValidation.isValid()) {

                $.ajax({
                    url: Core.url('account/create-stripe-account'),
                    method: 'POST',
                    data: {
                        country: $('select[name="stripe_country"]').val(),
                        email: $('input[name="stripe_email"]').val()
                    },
                    beforeSend: function beforeSend() {
                        $('.activation-error').hide();
                        $('.next-step.stripe').find('.fa').removeClass('fa-long-arrow-right').addClass('fa-circle-o-notch fa-spin');
                    }
                }).done(function (data, textStatus, jqXHR) {

                    // show activation success now
                    $('#activation_later').prop('disabled', true);
                    $('.activation-fields').hide();
                    $('.activation-success').show();

                    $('.next-step.stripe').find('.fa').removeClass('fa-circle-o-notch fa-spin').addClass('fa-long-arrow-right');
                    $('.next-step.stripe').removeClass('activate-now').trigger('click');
                }).fail(function (jqXHR, textStatus, errorThrown) {

                    $('.activation-error').show();
                    $('.next-step.stripe').find('.fa').removeClass('fa-circle-o-notch fa-spin').addClass('fa-long-arrow-right');
                });
            }
        }

        /**
         * submit our setup form
         */

    }, {
        key: 'submitForm',
        value: function submitForm(data, redirect) {
            var extraData = data === undefined ? {} : data;
            var doRedirect = redirect === undefined ? true : redirect;
            $('#setup_wizard_form').ajaxSubmit({
                data: extraData,
                beforeSubmit: function beforeSubmit() {
                    if (doRedirect) {
                        $('.finish-setup').addClass('disabled').find('.fa').removeClass('fa-check').addClass('fa-circle-o-notch fa-spin');
                    }
                },
                error: function error(jqXHR, textStatus, errorThrown) {
                    var error = jqXHR.responseJSON && jqXHR.responseJSON.message ? jqXHR.responseJSON.message : jqXHR.responseText ? jqXHR.responseText : 'Oops, something went wrong...';
                    alert(error);
                },
                success: function success(data) {
                    if (data.route && doRedirect) {
                        window.location = data.route;
                    }
                }
            });
        }
    }]);

    return Wizard;
}();

/******************************************************
 * Instantiate new class
 ******************************************************/


$(function () {
    new Wizard();
});
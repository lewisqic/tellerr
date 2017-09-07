/******************************************************
 * Setup wizard class
 ******************************************************/
class Wizard {

    /**
     * Class constructor, called when instantiating new class object
     */
    constructor() {
        // declare our class properties

        // call init
        this.init();
    }

    /**
     * We run init when our class is first instantiated
     */
    init() {
        // bind events
        this.bindEvents();
    }

    /**
     * bind all necessary events
     */
    bindEvents() {
        let self = this;

        $('.next-step').on('click', function(e) {
            e.preventDefault();
            if ( !$(this).hasClass('activate-now') ) {
                self.changeStep('next');
            }
        });
        $('.previous-step').on('click', function(e) {
            e.preventDefault();
            self.changeStep('previous');
        });

        $('input[name="activation"]').on('change', function() {
            self.activationOption($(this));
        });

        $('body').on('click', '.activate-now', function(e) {
            e.preventDefault();
            self.createStripeAccount();
        });

        $('.finish-setup').on('click', function(e) {
            e.preventDefault();
            self.submitForm();
        });

        $('.save-settings').on('click', function(e) {
            e.preventDefault();
            self.submitForm({
                setup_completed: 0
            }, false);
        });

    }

    /**
     * change our step
     */
    changeStep(direction) {

        let currentStep = parseFloat($('.step-content:visible').attr('data-step'));
        let nextStep = direction === 'next' ? currentStep + 1 : currentStep - 1;

        // validate form first
        $('#setup_wizard_form').formValidation({
            icon: {
                valid: 'fa fa-check text-success',
                invalid: 'fa fa-times text-danger',
            },
            trigger: 'blur',
            framework: 'bootstrap4',
            row: {
                valid: ''
            }
        }).formValidation('validate');
        let formValidation = $('#setup_wizard_form').data('formValidation');
        if ( direction === 'next' && !formValidation.isValid() ) {
            return false;
        }

        // hide all steps
        $('.step-content').addClass('d-none');
        $('.step-heading').removeClass('active');
        // now show the necessary step
        $('.step-content[data-step="' + nextStep + '"]').removeClass('d-none');
        $('.step-heading[data-step="' + nextStep + '"]').addClass('active');

    }

    /**
     * handle our activation option change
     */
    activationOption($this) {
        $('.abc-radio .form-text').addClass('d-none');
        $this.closest('.abc-radio').find('.form-text').removeClass('d-none');

        if ( $this.val() == 'now' ) {
            $('.activation-fields').removeClass('d-none');
            $('.next-step:visible').addClass('activate-now');
        } else {
            $('.activation-fields, .activation-error').addClass('d-none');
            $('.next-step:visible').removeClass('activate-now');
        }
    }

    /**
     * create our deferred stripe account
     */
    createStripeAccount() {

        // validate form first
        $('#setup_wizard_form').formValidation({
            icon: {
                valid: 'fa fa-check text-success',
                invalid: 'fa fa-times text-danger',
            },
            trigger: 'blur',
            framework: 'bootstrap4',
            row: {
                valid: ''
            }
        }).formValidation('validate');
        let formValidation = $('#setup_wizard_form').data('formValidation');

        if ( formValidation.isValid() ) {

            $.ajax({
                url: Core.url('account/create-stripe-account'),
                method: 'POST',
                data: {
                    country: $('select[name="stripe_country"]').val(),
                    email: $('input[name="stripe_email"]').val()
                },
                beforeSend: function() {
                    $('.activation-error').addClass('d-none');
                    $('.next-step.stripe').find('.fa').removeClass('fa-long-arrow-right').addClass('fa-circle-o-notch fa-spin');
                }
            }).done(function(data, textStatus, jqXHR) {

                // show activation success now
                $('#activation_later').prop('disabled', true);
                $('.activation-fields').addClass('d-none');
                $('.activation-success').removeClass('d-none');

                $('.next-step.stripe').find('.fa').removeClass('fa-circle-o-notch fa-spin').addClass('fa-long-arrow-right');
                $('.next-step.stripe').removeClass('activate-now').trigger('click');

            }).fail(function(jqXHR, textStatus, errorThrown) {

                $('.activation-error').removeClass('d-none');
                $('.next-step.stripe').find('.fa').removeClass('fa-circle-o-notch fa-spin').addClass('fa-long-arrow-right');

            });

        }

    }

    /**
     * submit our setup form
     */
    submitForm(data, redirect) {
        let extraData = data === undefined ? {} : data;
        let doRedirect = redirect === undefined ? true : redirect;
        $('#setup_wizard_form').ajaxSubmit({
            data: extraData,
            beforeSubmit: function() {
                if ( doRedirect ) {
                    $('.finish-setup').addClass('disabled').find('.fa').removeClass('fa-check').addClass('fa-circle-o-notch fa-spin');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                let error = jqXHR.responseJSON && jqXHR.responseJSON.message ? jqXHR.responseJSON.message : ( jqXHR.responseText ? jqXHR.responseText : 'Oops, something went wrong...');
                alert(error);
            },
            success: function(data) {
                if ( data.route && doRedirect ) {
                    window.location = data.route;
                }
            }
        });

    }



}

/******************************************************
 * Instantiate new class
 ******************************************************/
$(function() {
    new Wizard();
});
/******************************************************
 * Signup class used on the public sign up page
 ******************************************************/
class SignUp {

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
        // handle form validation success action
        $('#sign_up_form input[name="subdomain"]').on('blur', function(e) {
            self.handleFieldBlur();
        });
    }

    /*
     handle the ajax form validation success
     */
    handleFieldBlur(obj) {
        let self = this;
        let $field = $('#sign_up_form input[name="subdomain"]');
        let $feedback = $field.closest('.form-group').find('.fv-control-feedback');
        $('.subdomain-validation-error').hide();
        if ( $field.val() !== '' && !$field.closest('.form-group').hasClass('has-warning') ) {
            // convert field value to proper format
            $field.val($field.val().replace(/\s+/, '').toLowerCase());
            $feedback.removeClass('fa-check text-success fa-times text-danger').addClass('fa-circle-o-notch fa-spin text-info');
            // do the ajax call here
            $.ajax({
                url: Core.url('validate-subdomain'),
                method: 'POST',
                data: {
                    subdomain: $field.val()
                }
            }).done(function(data, textStatus, jqXHR) {
                self.handleValidateSubdomainSuccess(data, $feedback);
            }).fail(function(jqXHR, textStatus, errorThrown) {
                self.handleValidateSubdomainError(jqXHR, $feedback);
            });
        }
    }

    /*
     handle the ajax call success
     */
    handleValidateSubdomainSuccess(data, $feedback) {
        $('#sign_up_form').removeClass('invalid');
        $('input[name="subdomain-validation"]').val('human');
        $feedback.removeClass('fa-circle-o-notch fa-spin text-info').addClass('fa-check text-success');
    }

    /*
     handle the ajax call error
     */
    handleValidateSubdomainError(jqXHR, $feedback) {
        $('#sign_up_form').addClass('invalid');
        $feedback.removeClass('fa-circle-o-notch fa-spin text-info').addClass('fa-times text-danger');
        $('.subdomain-validation-error').html(jqXHR.responseJSON.message).show();
    }

}

/******************************************************
 * Instantiate new class
 ******************************************************/
$(function() {
    new SignUp();
});
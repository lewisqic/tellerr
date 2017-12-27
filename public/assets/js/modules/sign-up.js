var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/******************************************************
 * Signup class used on the public sign up page
 ******************************************************/
var SignUp = function () {

    /**
     * Class constructor, called when instantiating new class object
     */
    function SignUp() {
        _classCallCheck(this, SignUp);

        // declare our class properties
        // call init
        this.init();
    }

    /**
     * We run init when our class is first instantiated
     */


    _createClass(SignUp, [{
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
            // handle form validation success action
            $('#sign_up_form input[name="subdomain"]').on('blur', function (e) {
                self.handleFieldBlur();
            });
        }

        /*
         handle the ajax form validation success
         */

    }, {
        key: 'handleFieldBlur',
        value: function handleFieldBlur(obj) {
            var self = this;
            var $field = $('#sign_up_form input[name="subdomain"]');
            var $feedback = $field.closest('.form-group').find('.fv-control-feedback');
            $('.subdomain-validation-error').hide();
            if ($field.val() !== '' && !$field.closest('.form-group').hasClass('has-warning')) {
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
                }).done(function (data, textStatus, jqXHR) {
                    self.handleValidateSubdomainSuccess(data, $feedback);
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    self.handleValidateSubdomainError(jqXHR, $feedback);
                });
            }
        }

        /*
         handle the ajax call success
         */

    }, {
        key: 'handleValidateSubdomainSuccess',
        value: function handleValidateSubdomainSuccess(data, $feedback) {
            $('#sign_up_form').removeClass('invalid');
            $('input[name="subdomain-validation"]').val('human');
            $feedback.removeClass('fa-circle-o-notch fa-spin text-info').addClass('fa-check text-success');
        }

        /*
         handle the ajax call error
         */

    }, {
        key: 'handleValidateSubdomainError',
        value: function handleValidateSubdomainError(jqXHR, $feedback) {
            $('#sign_up_form').addClass('invalid');
            $feedback.removeClass('fa-circle-o-notch fa-spin text-info').addClass('fa-times text-danger');
            $('.subdomain-validation-error').html(jqXHR.responseJSON.message).show();
        }
    }]);

    return SignUp;
}();

/******************************************************
 * Instantiate new class
 ******************************************************/


$(function () {
    new SignUp();
});
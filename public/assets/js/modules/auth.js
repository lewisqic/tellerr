var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/******************************************************
 * Auth class used on our auth route form actions
 ******************************************************/
var Auth = function () {

    /**
     * Class constructor, called when instantiating new class object
     */
    function Auth() {
        _classCallCheck(this, Auth);

        // declare our class properties
        // call init
        this.init();
    }

    /**
     * We run init when our class is first instantiated
     */


    _createClass(Auth, [{
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
            // handle form success action
            $(window).on('auth_form.success', function (e, obj) {
                self.handleSuccess(obj);
            });
            // handle form beforeSubmit action
            $(window).on('auth_form.beforeSubmit', function (e, obj) {
                self.handleBeforeSubmit();
            });
        }

        /**
         * handle the ajax form submission success
         */

    }, {
        key: 'handleSuccess',
        value: function handleSuccess(obj) {
            obj.halt = true;
            obj.button.button('success');
            window.location = Core.url(obj.data.route ? obj.data.route : '');
        }

        /**
         * handle the ajax form beforeSubmit event
         */

    }, {
        key: 'handleBeforeSubmit',
        value: function handleBeforeSubmit(obj) {
            Core.clearNotify();
        }
    }]);

    return Auth;
}();

/******************************************************
 * Instantiate new class
 ******************************************************/


$(function () {
    window.Auth = new Auth();
});
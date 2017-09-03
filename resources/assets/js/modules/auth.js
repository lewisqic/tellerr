/******************************************************
 * Auth class used on our auth route form actions
 ******************************************************/
class Auth {

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
        // handle form success action
        $(window).on('auth_form.success', function(e, obj) {
            self.handleSuccess(obj);
        });
        // handle form beforeSubmit action
        $(window).on('auth_form.beforeSubmit', function(e, obj) {
            self.handleBeforeSubmit();
        });
    }

    /**
     * handle the ajax form submission success
     */
    handleSuccess(obj) {
        obj.halt = true;
        obj.button.button('success');
        window.location = Core.url(obj.data.route ? obj.data.route : '');
    }

    /**
     * handle the ajax form beforeSubmit event
     */
    handleBeforeSubmit(obj) {
        Core.clearNotify();
    }

}


/******************************************************
 * Instantiate new class
 ******************************************************/
$(function() {
    window.Auth = new Auth();
});
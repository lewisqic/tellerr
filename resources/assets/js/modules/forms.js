/******************************************************
 * Forms class
 ******************************************************/
class Forms {

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

        // change step
        $('.next-step').on('click', function(e) {
            e.preventDefault();
            self.changeStep('next');
        });
        $('.previous-step').on('click', function(e) {
            e.preventDefault();
            self.changeStep('previous');
        });
        $('.form-steps ul li').on('click', function(e) {
            e.preventDefault();
            self.changeStep($(this).attr('data-step'));
        });

        self.setupSortable();

    }

    /**
     * Change our displayed step
     */
    changeStep(direction) {

        let currentStep = parseFloat($('.form-steps ul li.active').attr('data-step'));
        let nextStep = direction == 'next' ? currentStep + 1 : ( direction == 'previous' ? currentStep - 1 : direction );

        // update the step nav
        $('.form-steps ul li').removeClass('active');
        $('.form-steps ul li[data-step="' + nextStep + '"]').addClass('active').removeClass('error');

        // show the right step content
        $('.form-step-content').hide();
        $('.form-step-content[data-step="' + nextStep + '"]').show();

    }

    /**
     * Setup our sortable fields
     */
    setupSortable() {

        $('.sortable-single').sortable({
            placeholder: 'sortable-placeholder',
            items: 'li:not(.disabled)'
        });

        $('.sortable-double').sortable({
            placeholder: 'sortable-placeholder',
            connectWith: '.sortable-double'
        });

    }





}

/******************************************************
 * Instantiate new class
 ******************************************************/
$(function() {
    new Forms();
});
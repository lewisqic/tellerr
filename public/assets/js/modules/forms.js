var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/******************************************************
 * Forms class
 ******************************************************/
var Forms = function () {

    /**
     * Class constructor, called when instantiating new class object
     */
    function Forms() {
        _classCallCheck(this, Forms);

        // declare our class properties

        // call init
        this.init();
    }

    /**
     * We run init when our class is first instantiated
     */


    _createClass(Forms, [{
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

            // change step
            $('.next-step').on('click', function (e) {
                e.preventDefault();
                self.changeStep('next');
            });
            $('.previous-step').on('click', function (e) {
                e.preventDefault();
                self.changeStep('previous');
            });
            $('.form-steps ul li').on('click', function (e) {
                e.preventDefault();
                self.changeStep($(this).attr('data-step'));
            });

            self.setupSortable();
        }

        /**
         * Change our displayed step
         */

    }, {
        key: 'changeStep',
        value: function changeStep(direction) {

            var currentStep = parseFloat($('.form-steps ul li.active').attr('data-step'));
            var nextStep = direction == 'next' ? currentStep + 1 : direction == 'previous' ? currentStep - 1 : direction;

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

    }, {
        key: 'setupSortable',
        value: function setupSortable() {

            $('.sortable-single').sortable({
                placeholder: 'sortable-placeholder',
                items: 'li:not(.disabled)'
            });

            $('.sortable-double').sortable({
                placeholder: 'sortable-placeholder',
                items: 'li:not(.disabled)',
                connectWith: '.sortable-double',
                stop: function stop(event, ui) {
                    $('.sortable-double input').each(function (index, el) {
                        var col = $(this).closest('.sortable-double').attr('data-column');
                        if (col === 'left') {
                            $(el).attr('name', $(el).attr('name').replace(/right/, col));
                        } else {
                            $(el).attr('name', $(el).attr('name').replace(/left/, col));
                        }
                    });
                }
            });
        }
    }]);

    return Forms;
}();

/******************************************************
 * Instantiate new class
 ******************************************************/


$(function () {
    new Forms();
});
/******************************************************
 * Remark template class
 ******************************************************/
class Remark {

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
        // init scrollbar
        this.initScrollbar();
        // init sidebar
        this.initSidebar();
    }

    /**
     * bind all necessary events
     */
    bindEvents() {
        let self = this;

        $('.toggle-sidebar').on('click', function(e) {
            e.preventDefault();
            self.toggleSidebar();
        });

        $('.sidebar .dropdown-toggle').on('click', function(e) {
            e.preventDefault();
            self.showDropdown(this);
        });

        $('.configurator .handle').on('click', function(e) {
            e.preventDefault();
            self.toggleConfigurator();
        });

        $('.configurator #color input[name="primary_color"]').on('change', function(e) {
            self.changePrimaryColor($(this).val());
        });

        $('.configurator #style input[name="sidebar_skin"]').on('change', function(e) {
            self.changeSidebarSkin($(this).val());
        });

        $('.configurator #style input[name="navbar_type"]').on('change', function(e) {
            self.toggleNavbarType();
        });

    }

    /**
     * Init our scrollbar for menu
     */
    initScrollbar() {
        if ( !$('body').hasClass('sidebar-minimized') ) {
            $('.sidebar-scrollbar').mCustomScrollbar({
                autoHideScrollbar: true,
                theme: $('body').hasClass('sidebar-dark') ? 'minimal-dark' : 'minimal'
            });
        }
    }

    /**
     * Init our sidebar classes
     */
    initSidebar() {
        let $active = $('.sidebar .nav-item.dropdown .dropdown-item.active:first');
        if ( $active.length ) {
            $active.closest('.nav-item.dropdown').addClass('open');
        }
    }

    /**
     * Show our dropdown item
     */
    showDropdown(element) {
        let $link = $(element);
        // reset any other open dropdowns
        $('.sidebar .dropdown.open .dropdown-menu').not($link.next('.dropdown-menu')).slideUp('fast', function() {
            $('.sidebar .dropdown').not($link.parent('.dropdown')).removeClass('open');
        });
        if ( $link.next('.dropdown-menu').is(':visible') ) {
            $link.next('.dropdown-menu').slideUp('fast', function() {
                $link.parent('.dropdown').removeClass('open');
            });
        } else {
            $link.next('.dropdown-menu').slideDown('fast');
            $link.parent('.dropdown').addClass('open');
        }
    }

    /**
     * toggle configurator
     */
    toggleConfigurator() {
        let $conf = $('.configurator');
        $conf.animate({
            right: $conf.css('right') == '0px' ? '-220px' : '0px'
        }, 'fast');
        $(this).toggleClass('open');
    }

    /**
     * toggle sidebar view
     */
    toggleSidebar() {
        let self = this;
        $('body').toggleClass('sidebar-minimized');
        if ( $('body').hasClass('sidebar-minimized') ) {
            $('.sidebar .nav-item.open .dropdown-menu').hide();
            $('.sidebar .nav-item.open').removeClass('open');
            $('.sidebar-scrollbar').mCustomScrollbar('destroy');
        } else {
            self.initScrollbar();
        }
        self.saveSetting('sidebar_minimized', $('body').hasClass('sidebar-minimized') ? 1 : 0);
    }

    /**
     * change our primary color
     */
    changePrimaryColor(color) {
        let self = this;
        let css = $('#skin_css').attr('href');
        let newCss = css.replace(/skins\/.*\.css/, 'skins/' + color + '.css');
        $('#skin_css').attr('href', newCss);
        self.saveSetting('primary_color', color);
    }

    /**
     * change our primary color
     */
    changeSidebarSkin(skin) {
        let self = this;
        $('.configurator #style input[name="sidebar_skin"]').each(function() {
            $('body').removeClass('sidebar-' + $(this).val());
        });
        $('body').addClass('sidebar-' + skin);
        $('.sidebar-scrollbar').mCustomScrollbar('destroy');
        self.initScrollbar();
        self.saveSetting('sidebar_skin', skin);
    }

    /**
     * toggle our navbar type
     */
    toggleNavbarType() {
        let self = this;
        $('body').toggleClass('navbar-inverse');
        self.saveSetting('navbar_inverse', $('body').hasClass('navbar-inverse') ? 1 : 0);
    }

    /**
     * save remark setting
     */
    saveSetting(key, value) {
        let area = $.url().attr('path').match(/^\/admin/) ? 'admin' : 'account';
        $.ajax({
            url: Core.url(area + '/remark-setting'),
            method: 'POST',
            data: { key: key, value: value }
        })
    }


}

/******************************************************
 * Instantiate new class
 ******************************************************/
$(function() {
    new Remark();
});
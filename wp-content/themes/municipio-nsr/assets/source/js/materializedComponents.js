Nsr = Nsr || {};

Nsr.Materialized = Nsr.Materialized || {};

Nsr.Materialized.MaterializedComponents = (function ($) {

    /**
     * Constructor
     */
    function MaterializedComponents() {
        this.init();

    }


    /**
     *  init
     *  Initializes all the necessary methods
     */
    MaterializedComponents.prototype.init = function () {

        this.mobileSideMenu({ element: '.button-collapse'});
        this.mobileCloseSideMenu({ element: '.side-nav .close'});
    };


    /**
     *  MobileSideMenu
     *  Main mobile menu
     *
     *  @param params.element string
     */
    MaterializedComponents.prototype.mobileSideMenu = function (params) {

        $(params.element).sideNav({
                menuWidth: 300,
                edge: 'right',
                closeOnClick: true,
                draggable: true
            }
        );
    };


    /**
     *  MobileCloseSideMenu
     *  Closing mobile menu
     *
     *  @param params.element string
     */
    MaterializedComponents.prototype.mobileCloseSideMenu = function (params) {

        $( params.element ).on( "click", function(  ) {
            $(this).sideNav('hide');
        });
    };


    /**
     *  mainMenuTabs
     *  Main top menu
     *
     *  @param params.element string
     */
    MaterializedComponents.prototype.mainMenuTabs = function (params) {

        $('ul.tabs').tabs('select_tab', params.element);
    };


    /**
     *  scrollSpy
     *  ScrollSpy for side menus
     *
     *  @param params.element string
     */
    MaterializedComponents.prototype.scrollSpy = function (params) {

        $(params.element).scrollSpy();

    };


    /**
     *  carousel
     *  Carousel
     *
     *  @param params.element string
     */
    MaterializedComponents.prototype.carousel = function (params) {

        $(params.element).carousel();

    };


    /**
     *  accordion
     *  Collapsibles are accordion elements that expand when clicked on
     *
     *  @param params.element string
     *  @param params.expandable boolean
     */
    MaterializedComponents.prototype.accordion = function (params) {

        $(params.element).collapsible({
            accordion : params.expandable
        });

    };


    /**
     *  tooltip
     *  Alerts and info
     *
     *  @param params.element string
     *  @param params.sec int
     *  @param params.pos int
     *  @param params.markup string
     *  @param params.msg string
     */
    MaterializedComponents.prototype.alerts = function (params) {

        switch(type) {

            case 'tooltip':
                $(params.element).tooltip({ delay: params.sec, position: params.pos, html: params.markup });
                break;

            case 'toast':
                Materialize.toast(params.msg, params.sec);
                break;

            case 'remove':
                $(params.element).tooltip('remove');
                break;
        }
    };


    /**
     *  modal
     *  Alerts and info etc
     *
     *  @param params.type string
     *  @param params.element string
     *  @param params.dismissible boolean
     *  @param params.opacity int .5
     *  @param params.in_duration int
     *  @param params.out_duration int
     *  @param params.starting_top string %
     *  @param params.ending_top string %
     *  @param params.callbacksReady    mixed
     *  @param params.callbacksComplete mixed
     */
    MaterializedComponents.prototype.modal = function (params) {

        switch (params.type) {

            case 'open':
                $(params.element).openModal();
                break;

            case 'openConfig':

                $(params.element).openModal({
                    dismissible: params.dismissible,
                    opacity: params.opacity,
                    in_duration: params.in_duration,
                    out_duration: params.out_duration,
                    starting_top: params.starting_top,
                    ending_top: params.ending_top,
                    ready: this.modalCallback( { callbacks: params.callbacksReady } ),
                    complete: this.modalCallback( { callbacks: params.callbacksComplete } )
                });

                break;

            case 'close':
                $(params.element).closeModal();
                break;
        }
    };


    /**
     *  modalCallback
     *  callback for open and ready modals and closed modals
     *
     *  @param params.callbacks string
     */
    MaterializedComponents.prototype.modalCallback = function (params) {

        if(params.callbacks === 'ready') {

        }
        if(params.callbacks === 'complete') {

        }
    };


    /**
     *  paralax
     *  callback for open and ready modals and closed modals
     *
     *  @param params.element string
     */
    MaterializedComponents.prototype.paralax = function (params) {

        $(params.element).parallax();
    };






    return new MaterializedComponents();

})(jQuery);


var Nsr;
Nsr = Nsr || {};

Nsr.App = Nsr.App || {};

Nsr.App.AppDefault = (function ($) {


    /**
     * Constructor
     */
    function AppDefault() {
        this.init();

    };


    /**
     *  init
     *  Initializes all the necessary methods
     */
    AppDefault.prototype.init = function () {
        this.chngColor();

    };


    /**
     *  Bypasing !important on parent css
     *
     */
    AppDefault.prototype.chngColor = function () {
        $('.main-footer .footer-title').attr('style','color:#007586 !important;');
    };

    return new AppDefault();


})(jQuery);

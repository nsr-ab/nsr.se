var Nsr;

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
        this.hackz();


    };


    /**
     *  Bypase !important on parent css
     *
     */
    AppDefault.prototype.hackz = function () {
        $('.main-footer .footer-title').css('color','#007586');
    };

    return new AppDefault();

    
})(jQuery);

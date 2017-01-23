Default = Default || {};

Default.App = Default.App || {};

Default.App.AppDefault = (function ($) {


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
     *  Bypasing !important on parent css
     *
     */
    AppDefault.prototype.hackz = function () {
        $('.main-footer .footer-title').css('color','#007586');
    };

    return new AppDefault();


})(jQuery);

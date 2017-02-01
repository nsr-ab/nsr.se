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
        window.setTimeout(this.removeActiveOnAccordions, 50);
    };


    /**
     *  init
     *  Initializes all the necessary methods
     */
    AppDefault.prototype.removeActiveOnAccordions = function () {
        $(document).ready(function() {
           if(!$('.wpforms-form div').hasClass('wpforms-error-container') || !$('.vc_tta-panel-body div').hasClass('wpforms-confirmation-container-full')) {
               $('.vc_tta-accordion .vc_tta-panel').removeClass('vc_active');
           }


        });

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

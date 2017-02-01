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
     *  removeActiveOnAccordions
     *  Closing all accordions on load (and a wpforms error hack, not a stabile on.... :-/ ).
     */
    AppDefault.prototype.removeActiveOnAccordions = function () {
        $(document).ready(function() {

            var error = false;

            if($('.wpforms-form div').hasClass('wpforms-error-container'))
               error = true;
            if($('.vc_tta-panel-body div').hasClass('wpforms-confirmation-container-full'))
                error = true;
            if(error === false)
                $('.vc_tta-accordion .vc_tta-panel').removeClass('vc_active');
        });
    };

    function validateRecaptcha() {
        var response = grecaptcha.getResponse();
        if (response.length === 0) {
            alert("not validated");
            return false;
        } else {
            alert("validated");
            return true;
        }
    }

    /**
     *  Bypasing !important on parent css
     *
     */
    AppDefault.prototype.chngColor = function () {
        $('.main-footer .footer-title').attr('style','color:#007586 !important;');
    };

    return new AppDefault();


})(jQuery);

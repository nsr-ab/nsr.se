var Nsr;
Nsr = Nsr || {};

Nsr.App = Nsr.App || {};

Nsr.App.AppDefault = (function ($) {


    /**
     * Constructor
     */
    function AppDefault() {
        this.init();

        AppDefault.prototype.limitException();

        /* searchNSR - Close full screen */
        $('body').on('click', '.showmoreExceptions', function (e) {
            AppDefault.prototype.showMoreExceptions($(this).closest('ul'));
        }).bind(this);
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



    /**
     *  Bypasing !important on parent css
     *
     */
    AppDefault.prototype.chngColor = function () {
        $('.main-footer .footer-title').attr('style','color:#007586 !important;');
    };


    /**
     * Limit exceptions
     * @return void
     */
    AppDefault.prototype.limitException = function (specificUl) {
        var int = 0;
        if(specificUl === undefined)
            var allOpenhours = $('.openhours');
        else
        {
            var allOpenhours = specificUl;
            specificUl.find('.showmoreExceptions').remove();
        }
        
        $(allOpenhours).each(function( index ) {
            var allItems = $(this).find('.collection-item');
            var loopint = 0;
            $(allItems).each(function( index ) {
                if(loopint > 7)
                $(this).addClass('hide');
                if(loopint === 8) {
                    $(this).closest('ul').append('<li class="showmoreExceptions">Visa fler</li>');
                }
                ++loopint;
            });
        });
    };


    /**
     * show hidden openhours
     * @return void
     */
    AppDefault.prototype.showMoreExceptions = function (thisul) {
        if(thisul.find('.collection-item').hasClass('hide')) {
            thisul.find('.collection-item').removeClass('hide');
            thisul.find('.showmoreExceptions').text('Visa färre');
        }
        else {
            AppDefault.prototype.limitException(thisul);
        }
    };

    return new AppDefault();


})(jQuery);

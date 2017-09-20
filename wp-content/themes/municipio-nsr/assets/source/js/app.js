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

        /* searchNSR - Close tooltip */
        $('body').on('click', '.closeTooltip', function (e) {
            AppDefault.prototype.closeTooltip();
        }).bind(this);

        $('body').on('click', '.infoSearch', function (e) {
            AppDefault.prototype.showHideTooltip();
        }).bind(this);

        AppDefault.prototype.checkCookie();

    };


    /**
     *  init
     *  Initializes all the necessary methods
     */
    AppDefault.prototype.init = function () {
        this.chngColor();
        window.setTimeout(this.removeActiveOnAccordions, 50);
    };


    AppDefault.prototype.setCookie = function (cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    };

    AppDefault.prototype.getCookie = function (cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    };

    AppDefault.prototype.checkCookie = function() {
        var user = AppDefault.prototype.getCookie("username");
        if (!user) {
            $('.cookieBased').show();
            AppDefault.prototype.setCookie("username", "nsr-visitor", 20);
        }
    };



    /**
     *  closeTooltip
     *  Hide tooltip
     */
    AppDefault.prototype.closeTooltip = function () {
           $('.tooltip-info').hide();
    };

    /**
     *  showHideTooltip
     *  show & hide tooltip
     */
    AppDefault.prototype.showHideTooltip = function () {
        if($('.static-tooltip').is(":visible")) {
            $('.static-tooltip').hide();
        }
        else {
            $('.static-tooltip').show();
        }

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

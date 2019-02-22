var Nsr;
Nsr = Nsr || {};

Nsr.App = Nsr.App || {};

Nsr.App.AppDefault = (function ($) {

    var cssClasses = ['villa','foretag','fastighet'];
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

        $('body').on('click', '.infoSearch, .infoSearchTxt', function (e) {
            AppDefault.prototype.showHideTooltip();
        }).bind(this);

        AppDefault.prototype.checkCookie();

        var current_page_item = $("#site-header .desk li.current_page_item").css('background-color');
        var deskHero = $('.heroWrapper').attr('data-bgimage');
        $( window ).resize(function() {
            AppDefault.prototype.heroBackground(deskHero, current_page_item);

        });


    };


    /**
     *  init
     *  Initializes all the necessary methods
     */
    AppDefault.prototype.init = function () {
        this.chngColor();
        this.mobileMenuLink();
        window.setTimeout(this.removeActiveOnAccordions, 50);
    };


    /**
     *  Bypasing !important on parent css
     *
     */
    AppDefault.prototype.chngColor = function () {
        AppDefault.prototype.navColors();
        $('.main-footer .footer-title').attr('style', 'color:#007586 !important;');
        var wrapperColor = $('.mob .nav li.active').css('background-color');
        $('.heroWrapper').css('background-color',wrapperColor);
    };


    /**
     *  Navigation colors
     *
     */
    AppDefault.prototype.navColors = function () {
        var url = new URL(window.location).pathname.split('/');
        $('#site-header').addClass('color-' + url[1]);
        if($( window ).width() > 1000) {
            $("#site-header ul.nav li").hover(
                function () {
                    var color = $(this).css("background-color");
                    $('#site-header').css('border-color', AppDefault.prototype.shadeColors(-0.1, color));
                }, function () {
                    $('#site-header').css('border-color', '');
                }
            );

        }
    };


    /**
     *  Navigation colors (Shade)
     *
     */
    AppDefault.prototype.shadeColors = function (p,c0,c1) {
        var n=p<0?p*-1:p,u=Math.round,w=parseInt;
        if(c0.length>7){
            var f=c0.split(","),t=(c1?c1:p<0?"rgb(0,0,0)":"rgb(255,255,255)").split(","),R=w(f[0].slice(4)),G=w(f[1]),B=w(f[2]);
            return "rgb("+(u((w(t[0].slice(4))-R)*n)+R)+","+(u((w(t[1])-G)*n)+G)+","+(u((w(t[2])-B)*n)+B)+")"
        }else{
            var f=w(c0.slice(1),16),t=w((c1?c1:p<0?"#000000":"#FFFFFF").slice(1),16),R1=f>>16,G1=f>>8&0x00FF,B1=f&0x0000FF;
            return "#"+(0x1000000+(u(((t>>16)-R1)*n)+R1)*0x10000+(u(((t>>8&0x00FF)-G1)*n)+G1)*0x100+(u(((t&0x0000FF)-B1)*n)+B1)).toString(16).slice(1).fadeIn("2000")
        }
    };


    /**
     *  Hero background
     *
     */
    AppDefault.prototype.heroBackground = function (deskHero, current_page_item) {
        var deskHero = $('.heroWrapper').attr('data-bgimage');
        if ($( window ).width() > 767){
            $('.heroWrapper').removeAttr('style');
            $('.heroWrapper').attr('style', 'background-image: url('+deskHero+') !important;');
        }
        else {
            $('.heroWrapper').removeAttr('style');
            $('.heroWrapper').attr('style', 'background-color:'+current_page_item+'!important;');
            $('.mob .sites-nav  li.current-menu-item').removeAttr('style');
        }
    };

    /**
     *  Mobile link
     *
     */
    AppDefault.prototype.mobileMenuLink = function () {
        $('.mob .nav a').each(function (index) {
            var link = $(this).attr('href');
            $(this).closest('li').attr('onclick', 'location.href=\''+link+'\';')
        });
    };


    /**
     *  setCookie
     *  set new cookie
     */
    AppDefault.prototype.setCookie = function (cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    };

    /**
     *  getCookie
     *  get Cookies
     */
    AppDefault.prototype.getCookie = function (cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
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


    /**
     *  checkCookie
     *  check if cookies is set
     */
    AppDefault.prototype.checkCookie = function () {
        var user = AppDefault.prototype.getCookie("username");
        if (!user) {
            //$('.cookieBased').show();
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
        if ($('.static-tooltip').is(":visible")) {
            $('.static-tooltip').hide();
        } else {
            $('.static-tooltip').show();
        }

    };


    /**
     *  removeActiveOnAccordions
     *  Closing all accordions on load (and a wpforms error hack, not a stabile on.... :-/ ).
     */
    AppDefault.prototype.removeActiveOnAccordions = function () {
        $(document).ready(function () {

            var error = false;

            if ($('.wpforms-form div').hasClass('wpforms-error-container'))
                error = true;
            if ($('.vc_tta-panel-body div').hasClass('wpforms-confirmation-container-full'))
                error = true;
            if (error === false)
                $('.vc_tta-accordion .vc_tta-panel').removeClass('vc_active');
        });
    };



    /**
     * Limit exceptions
     * @return void
     */
    AppDefault.prototype.limitException = function (specificUl) {
        var int = 0;
        if (specificUl === undefined)
            var allOpenhours = $('.openhours');
        else {
            var allOpenhours = specificUl;
            specificUl.find('.showmoreExceptions').remove();
        }

        $(allOpenhours).each(function (index) {
            var allItems = $(this).find('.collection-item');
            var loopint = 0;
            $(allItems).each(function (index) {
                if (loopint > 7)
                    $(this).addClass('hide');
                if (loopint === 8) {
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
        if (thisul.find('.collection-item').hasClass('hide')) {
            thisul.find('.collection-item').removeClass('hide');
            thisul.find('.showmoreExceptions').text('Visa färre');
        } else {
            AppDefault.prototype.limitException(thisul);
        }
    };



    return new AppDefault();


})(jQuery);

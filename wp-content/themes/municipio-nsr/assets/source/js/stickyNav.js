Nsr = Nsr || {};

Nsr.Sticky = Nsr.Sticky || {};

Nsr.Sticky.StickyNav = (function ($) {


    /**
     * Constructor
     */
    function StickyNav() {
        this.init();

    }



    /**
     *  init
     *  Initializes all the necessary methods
     */
    StickyNav.prototype.init = function () {

        this.sticky();
        this.winScroll();
        this.winResize();

    };



    /**
     *  AdminBar
     *  Wordpress admin bar
     */
    StickyNav.prototype.adminBar = function () {

        if($('body').hasClass('admin-bar')) {
            if($('.mob').hasClass('sticky')){
                return 30;
            }
            else {
                return 0;
            }
        }
        else {
            return 0;
        }
    };



    /**
     *  ResetSticky
     *  removing and adding classes to elements
     */
    StickyNav.prototype.resetSticky = function () {

        $('.mob').removeClass('sticky'), $('.side-nav').removeClass('sticky-side-nav'),
            $('.mobile-nav').addClass('hidden-lg hidden-md'), $('.desk-logo').addClass('animate-showLogo'),
            $('.desk-logo').removeClass('animate-hideLogo'), $('.mobile-logo').addClass('hide'), $('.sites-nav').removeClass('hide');

        if($(window).width() < 601) {
            $('.mob').removeClass('sticky-fix');
            $('.side-nav').removeClass('sticky-side-nav-fix');
        }
    };



    /**
     *  Sticky
     *  Making the menu sticky and fixed
     */
    StickyNav.prototype.sticky = function () {

        var scrollTop = $(window).scrollTop() + this.adminBar();
        var stickyNavTop = $('#site-header').offset().top;

        if (scrollTop > stickyNavTop) {
            $('.mob').addClass('sticky'), $('.side-nav').addClass('sticky-side-nav'),
                $('.mobile-nav').removeClass('hidden-lg hidden-md'), $('.desk-logo').addClass('animate-hideLogo'),
                $('.desk-logo').removeClass('animate-showLogo'), $('.mobile-logo').removeClass('hide'), $('.sites-nav').addClass('hide');

            if($(window).width() < 601) {
                $('.mob').addClass('sticky-fix');
                $('.side-nav').addClass('sticky-side-nav-fix');
            }
        }
        else {
            this.resetSticky();
        }
    };



    /**
     *  winScroll
     *  Change state when scrolling
     */
    StickyNav.prototype.winScroll = function () {

        $(window).scroll(function() {
            StickyNav.prototype.sticky();
        });
    };



    /**
     *  winResize
     *  Change state when resizing window
     */
    StickyNav.prototype.winResize = function () {

        $(window).scroll(function() {
            StickyNav.prototype.sticky();
        });
    };

    return new StickyNav();

})(jQuery);


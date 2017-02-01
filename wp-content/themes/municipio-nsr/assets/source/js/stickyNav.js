Nsr = Nsr || {};

Nsr.Sticky = Nsr.Sticky || {};

Nsr.Sticky.StickyNav = (function ($) {


    /**
     * Constructor
     */
    function StickyNav() {
        this.init();

    };



    /**
     *  init
     *  Initializes all the necessary methods
     */
    StickyNav.prototype.init = function () {

        this.sticky();
        this.winScroll();
        this.winResize();
        this.setItemAsActive();

        $('body').on('click', '.quickSearch', function (e) {
            StickyNav.prototype.quickSearch();
        }).bind(this);
    };


    StickyNav.prototype.quickSearch = function (){

        $('#searchModal').modal({
                dismissible: true,
                opacity: .5,
                inDuration: 300,
                outDuration: 200,
                startingTop: '1%',
                endingTop: '10%',
                ready: function(modal, trigger) {
                    $('#searchModal input').focus();
                    console.log(modal, trigger);
                },
                complete: function() {


                }
            }
        );


    };

    /**
     *  setItemAsActive
     *  Active menu section
     */
    StickyNav.prototype.setItemAsActive = function () {

        var path = window.location.pathname;
        var menu = false;

        path = path.replace(/\/$/, "");
        path = decodeURIComponent(path).split('/')[1] + "/";

        $('.nav-wrapper .nav li').removeClass('active');
        $('.nav-wrapper .nav a').each(function () {

            var menuSection = location.protocol +'//'+ location.hostname +'/'+ path;
            var href = $(this).attr('href');
            if (menuSection === href) {
                $(this).closest('li').addClass('active');
                menu = true;
            }
        });

        if(!menu)
            $('.nav-wrapper .nav li:first-child').addClass('active');
    }




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


/**
 * MenuCollapsible ad-don for Visual Composer
 *
 * @package NSRVCExtended
 *
 * Author: Johan Silvergrund
 * Company: HIQ
 *
 */

var VcExtended = VcExtended || {};

VcExtended.NSRExtend = VcExtended.NSRExtend || {};
VcExtended.NSRExtend.Extended = (function ($) {


    /**
     * Constructor
     */
    function Extended() {

        this.init();

    }


    /**
     *  init
     *  Initializes all the necessary methods and binding stuff to events
     */
    Extended.prototype.init = function () {

        $(function() {
            this.eventHandler();
        }.bind(this));

    };



    /**
     *  eventHandler
     *  Managing all event handlers (Silence is gold)
     */
    Extended.prototype.eventHandler = function () {
        $('body').on('click', '.showAllPosts', function () {
            event.preventDefault();

            if($(this).closest( "ul" ).find('li').hasClass('hide')) {
                $(this).closest("ul").find('.hide').addClass('show');
                $(this).closest("ul").find('li').removeClass('hide');
                var countItemsHide = $(this).closest("ul").find('li').length-6;
                $(this).closest("ul").find('.showPosts').text('Dölj ('+countItemsHide+')');
            }
            else {
                $(this).closest("ul").find('.show').addClass('hide');
                $(this).closest("ul").find('li').removeClass('show');
                var countItemsShow = $(this).closest("ul").find('li').length-1;
                $(this).closest("ul").find('.showPosts').text('Visa alla ('+countItemsShow+')');
            }
        }).bind(this);

    };


    return new Extended;

})(jQuery);


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

VcExtended.MenuCollapsible = VcExtended.MenuCollapsible || {};
VcExtended.MenuCollapsible.Collapsible = (function ($) {


    /**
     * Constructor
     */
    function Collapsible() {

        this.init();

    }


    /**
     *  init
     *  Initializes all the necessary methods and binding stuff to events
     */
    Collapsible.prototype.init = function () {

        $(function() {
            this.eventHandler();
        }.bind(this));

    };



    /**
     *  eventHandler
     *  Managing all event handlers (Silence is gold)
     */
    Collapsible.prototype.eventHandler = function () {

        // Accordion open & close and links
        $('body').on('click', '.collapsible-header', function () {

            $id = $(this).parents('ul').attr('id');

            if($(this).find("a").length === 0) {

                $($id).find('.materialIconState').text('add');
                if($(this).hasClass('active')) {
                    $(this).find('.materialIconState').text('clear');
                }
                else {
                    $(this).find('.materialIconState').text('add');
                }
            }
            else {
                window.location.href = $(this).find('a').attr('href');
            }

        }).bind(this);



    };

    return new Collapsible;

})(jQuery);


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

    };


    return new Extended;

})(jQuery);


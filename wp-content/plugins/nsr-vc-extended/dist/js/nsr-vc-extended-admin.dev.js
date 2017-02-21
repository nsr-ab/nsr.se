/**
 * ExtendedAdmin ad-don for Visual Composer
 *
 * @package NSRVCExtended
 *
 * Author: Johan Silvergrund
 * Company: HIQ
 *
 */

var VcExtended = VcExtended || {};

VcExtended.NSRExtendAdmin = VcExtended.NSRExtendAdmin || {};
VcExtended.NSRExtendAdmin.ExtendedAdmin = (function ($) {


    /**
     * Constructor
     */
    function ExtendedAdmin() {

        this.init();

    }




    /**
     *  init
     *  Initializes all the necessary methods and binding stuff to events
     */
    ExtendedAdmin.prototype.init = function () {

        this.colorPickerDefaultColors();

    };




    /**
     *  colorPickerDefaultColors
     *  Adding button functionality
     */
    ExtendedAdmin.prototype.colorPickerDefaultColors = function () {

        jQuery(document).ready(function($){

            if( typeof $.wp === 'object' && typeof $.wp.wpColorPicker === 'function' )
            $.wp.wpColorPicker.prototype.options = {
                palettes: ['#007685','#d1d2d0','#c0b939', '#7a409c', '#52439f', '#186cb6', '#0195af', '#01ab98', '#69c1bd','#186cb6','#aad54f', '#d4cc46'],
                hide: true
            };
        });

    }


    return new ExtendedAdmin;

})(jQuery);

var callbackOutsideScope = function () {
    //jQuery('.vc_location').val('Välj Stad/Ort').change();
};


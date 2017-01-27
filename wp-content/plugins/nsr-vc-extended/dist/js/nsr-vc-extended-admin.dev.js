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
                palettes: ['#75a3eb','#6d7dcc','#a27ac3', '#fe0e35', '#00aaff', '#00f2d3'],
                hide: true
            };
        });
    }


    return new ExtendedAdmin;

})(jQuery);

var callbackOutsideScope = function () {
    //jQuery('.vc_location').val('Välj Stad/Ort').change();
};


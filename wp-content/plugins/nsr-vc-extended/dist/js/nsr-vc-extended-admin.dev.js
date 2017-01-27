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

VcExtended.MenuCollapsibleAdmin = VcExtended.MenuCollapsibleAdmin || {};
VcExtended.MenuCollapsibleAdmin.CollapsibleAdmin = (function ($) {


    /**
     * Constructor
     */
    function CollapsibleAdmin() {

        this.init();

    }




    /**
     *  init
     *  Initializes all the necessary methods and binding stuff to events
     */
    CollapsibleAdmin.prototype.init = function () {

        this.colorPickerDefaultColors();

    };




    /**
     *  colorPickerDefaultColors
     *  Adding button functionality
     */
    CollapsibleAdmin.prototype.colorPickerDefaultColors = function () {

        jQuery(document).ready(function($){

            if( typeof $.wp === 'object' && typeof $.wp.wpColorPicker === 'function' )
            $.wp.wpColorPicker.prototype.options = {
                palettes: ['#75a3eb','#6d7dcc','#a27ac3', '#fe0e35', '#00aaff', '#00f2d3'],
                hide: true
            };
        });
    }


    return new CollapsibleAdmin;

})(jQuery);

var callbackOutsideScope = function () {
    //jQuery('.vc_location').val('Välj Stad/Ort').change();
};


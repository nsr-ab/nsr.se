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

        $(function() {
            this.eventHandler();
        }.bind(this));

        this.colorPickerDefaultColors();

    };



    /**
     *  eventHandler
     *  Managing all event handlers (Silence is gold)
     */
    CollapsibleAdmin.prototype.eventHandler = function () {

        var timeout = null;

        // fetch data by input
        $('body').on('keyup', '.vcext_search_pagetitle', function () {

            clearTimeout(timeout);
            var pageTitle = $(this).val();
            timeout = setTimeout(function () {
                CollapsibleAdmin.prototype.fetchDataByKeyword({ query: pageTitle });
            }, 500);

        }).bind(this);

    };



    /**
     *  fetchDataByKeyword
     *  Ajax phone-call to headquarter - Fetching page title and id.
     *  @param param.query string
     */
    CollapsibleAdmin.prototype.fetchDataByKeyword = function (param) {
        var data = {
            'action': 'fetch_data',
            'query': param.query
        };

        jQuery.post(ajax_object.ajax_url, data, function(response) {
            console.log(jQuery.parseJSON(response));
        });

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


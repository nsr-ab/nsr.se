/**
 * NSR Open Hours
 *
 * @package NSROpenHours
 *
 * Author: Johan Silvergrund
 * Company: HIQ
 *
 */

var NSROpenHours = NSROpenHours || {};

NSROpenHours.op = NSROpenHours.op || {};
NSROpenHours.op.OpenHours = (function ($) {


    function OpenHours() {
        this.init();
    }


    /**
     * Init - hides stuff and sets primary view
     * @return void
     */
    OpenHours.prototype.init = function () {

        $( document ).ready(function() {

            $('.acf-postbox').each(function (index, post) {
                if($(this).attr('id') != 'acf-group_locations' &&  $(this).attr('id') != 'acf-group_select_section')
                    $(this).addClass('hide');
            });
            if($('#acf-field_select option:selected')) {
                var $firstSection = $('#acf-field_select option:selected').val()
            }
            else {
                var $firstSection = $('#acf-field_select option:first').val();
            }

            OpenHours.prototype.showSection($firstSection);

            $('body').on('change', '#acf-field_select', function (e) {
                OpenHours.prototype.showSection($(this).val());
            }).bind(this);

        });


    };


    /**
     * Shows sections / location
     * @return void
     */
    OpenHours.prototype.showSection = function ($sectionID) {
        $('.acf-postbox').each(function (index, post) {
            if(!$(this).hasClass('hide')) {
                if ($(this).attr('id') != 'acf-group_locations' && $(this).attr('id') != 'acf-group_select_section')
                    $(this).addClass('hide');
            }
        });
        $('#acf-group_exception_'+$sectionID).removeClass('hide');
        $('#acf-group_hours_'+$sectionID).removeClass('hide');
        $('#acf-group_shortcode_'+$sectionID).removeClass('hide');

    };


    return new OpenHours;

})(jQuery);
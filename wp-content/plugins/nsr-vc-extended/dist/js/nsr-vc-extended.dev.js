/**
 *  eventHandler
 *  Managing all event handlers (Silence is gold)
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

        var timeout = null;

        $('body').on('keyup', '.vcext_search_pagetitle', function () {

            clearTimeout(timeout);
            var pageTitle = $(this).val();
            timeout = setTimeout(function () {
                Collapsible.prototype.fetchDataByKeyword({ query: pageTitle });
            }, 500);

        }).bind(this);

    };


    /**
     *  fetchDataByKeyword
     *  Ajax phone-call to headquarter - Fetching page title and id.
     *  @param param.query string
     */
    Collapsible.prototype.fetchDataByKeyword = function (param) {
        var data = {
            'action': 'fetch_data',
            'query': param.query
        };

        jQuery.post(ajax_object.ajax_url, data, function(response) {
            console.log(jQuery.parseJSON(response));
        });




    };


    return new Collapsible;

})(jQuery);


/**
 * Extended script for Visual Composer ad-dons
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

    var typingTimer = null;
    var doneTypingInterval = 300;


    /**
     * Constructor
     */
    function Extended() {

        this.init();
        $('.card-content').matchHeight();
    }


    /**
     *  init
     *  Initializes all the necessary methods and binding stuff to events
     */
    Extended.prototype.init = function () {

        /* Puff med länkar - Visa fler nyheter */
        $('body').on('click', '.showAllPosts', function () {
            Extended.prototype.displayMore(this);
        }).bind(this);


        /* searchNSR - Full screen */
        $('body').on('click', '.searchArea *', function (e) {
            Extended.prototype.fullScreen(e);
        }).bind(this);


        /* searchNSR - Close full screen */
        $('body').on('click', '.closeSearch', function (e) {
            Extended.prototype.closeScreen(this);
        }).bind(this);


        /* searchNSR - Enter key function */
        $.fn.enterKey = function (fnc) {
            Extended.prototype.enterTrigger(fnc, this);
        }

        /* searchNSR - Hiting Enter on search */
        $('.searchNSR').enterKey(function () {
            event.preventDefault();
            window.clearTimeout(typingTimer);
            Extended.prototype.doneTyping();
        });

        /* On input starting timer  */
        $('.searchNSR').on("input", function () {
            window.clearTimeout(typingTimer);
            typingTimer = window.setTimeout(Extended.prototype.doneTyping, doneTypingInterval);
        });

        /* Backspace or space clears timeout */
        $('.searchNSR').on('keydown', function (e) {
            Extended.prototype.haltTimer(e, typingTimer);
        });

    };



    /**
     *  displayMore
     *  Show more or less posts
     *  @param {object} element
     *  @return {void}
     */
    Extended.prototype.displayMore = function (element) {
        event.preventDefault();

        if ($(element).closest("ul").find('li').hasClass('hide')) {
            $(element).closest("ul").find('.hide').addClass('show');
            $(element).closest("ul").find('li').removeClass('hide');
            var countItemsHide = $(element).closest("ul").find('li').length - 6;
            $(element).closest("ul").find('.showPosts').text('Dölj (' + countItemsHide + ')');
        }
        else {
            $(element).closest("ul").find('.show').addClass('hide');
            $(element).closest("ul").find('li').removeClass('show');
            var countItemsShow = $(element).closest("ul").find('li').length - 1;
            $(element).closest("ul").find('.showPosts').text('Visa alla (' + countItemsShow + ')');
        }
        $('.card-content').matchHeight();
    };



    /**
     *  enterTrigger
     *  on enter
     *  @param {object} fnc
     *  @param {object} element
     *  @return {void}
     */
    Extended.prototype.enterTrigger = function (fnc, element) {
        return element.each(function () {
            $(element).keypress(function (ev) {
                var keycode = (ev.keyCode ? ev.keyCode : ev.which);
                if (keycode == '13') {
                    $('.search-autocomplete').remove();
                    $('.sorteringsguiden').remove();
                    fnc.call(element, ev);
                    event.preventDefault();
                    return false;
                }
            })
        })
    };



    /**
     *  closeScreen
     *  Close search screen
     *  @param {object} element
     */
    Extended.prototype.closeScreen = function (element) {

        event.stopPropagation();
        $('.searchNSR').removeClass('fullscreen');
        $(element).addClass('hide');
        $('#searchResult').html('');
        $('#searchkeyword-nsr').val('');
        $('.search-autocomplete').remove();
        $('.sorteringsguiden').remove();
    };



    /**
     *  haltTimer
     *  Space / Backspace - stops timer etc.
     *  @param {object} e
     *  @param {int} typingTimer
     *  @return {void}
     */
    Extended.prototype.haltTimer = function (e, typingTimer) {

        switch (e.which) {
            case 32:
                window.clearTimeout(typingTimer);
                break;
            case 8:
                window.clearTimeout(typingTimer);
                break;
        }
    };



    /**
     *  fullScreen
     *  open search window
     *  @param {object} element
     *  @return {void}
     */
    Extended.prototype.fullScreen = function (element) {

        if ($(element.target).is('i') || $(element.target).is('.search-autocomplete')) {
            element.preventDefault();
            return;
        }

        $('.searchNSR input').focus();
        $('.searchNSR').addClass('fullscreen');
        $('.closeSearch').removeClass('hide');
        event.stopPropagation();
    };



    /**
     *  doneTyping
     *  fires a call to autocomples
     *  @return {void}
     */
    Extended.prototype.doneTyping = function () {

        $('.searchNSR').each(function (index, element) {
            Extended.prototype.autocomplete(element);
        });
    };



    /**
     * Initializes the autocomplete functionality
     * @param  {object} element
     * @return {void}
     */
    Extended.prototype.autocomplete = function(element) {

        var $element = $(element);
        var $input = $element.find('input[type="search"]');

        if ($input.val().length < 2) {
            return;
        }

        this.autocompleteQuery(element);
    };



    /**
     * Submit autocomplete
     * @param  {object} element Autocomplete
     * @return {bool}
     */
    Extended.prototype.autocompleteSubmit = function(element) {

        var $element = $(element);
        var $autocomplete = $element.find('.search-autocomplete');
        var $selected = $autocomplete.find('.selected');

        if (!$selected.length)
            return true;

        location.href = $selected.find('a').attr('href');

        return false;
    };



    /**
     * Query for autocomplete suggestions
     * @param  {object} element Autocomplete element
     * @return {void}
     */
    Extended.prototype.autocompleteQuery = function(element) {

        var $element = $(element);
        var $input = $element.find('input[type="search"]').val();
        var $post_type = $('#post_type').val();

        var data = {
            action: 'fetch_data',
            query: $input,
            post_type: $post_type,
            level: 'ajax',
            type: 'json'
        };

        $.ajax({
            url: ajax_object.ajax_url,
            data: data,
            method: 'GET',
            dataType: 'json',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', ajax_object.nonce);
            }
        }).done(function (result) {

            $element.find('.sorteringsguiden').remove();
            $element.find('.search-autocomplete').remove();

            this.outputAutocomplete(element, result, $post_type);
        }.bind(this));

    };



    /**
     *  postIcon
     *  @param post_type string
     *  @return {array} res
     */
    Extended.prototype.metaDataStr = function (post_type) {

        var $res = new Array();

        switch (post_type) {

            case "post":
                $res['postSection'] = 'Nyheter';
                $res['icon'] = 'chat';
                break;

            case "fastighet":
                $res['postSection'] = 'Fastighetsägare & Bostadsrättsföreningar';
                $res['icon'] = 'location_city';
                break;

            case "villa":
                $res['postSection'] = 'Villa & Fritidsboende';
                $res['icon'] = 'home';
                break;

            case "foretag":
                $res['postSection'] = 'Företag & Restauranger';
                $res['icon'] = 'domain';
                break;

            case "page":
                $res['postSection'] = 'Sidor';
                $res['icon'] = 'insert_drive_file';
                break;

            case "faq":
                $res['postSection'] = 'Frågor & svar';
                $res['icon'] = 'forum';
                break;

            case "sorteringsguide":
                $res['postSection'] = 'Sorteringsguiden';
                $res['icon'] = 'delete';
                break;
        }
        if($res['icon'] === '')
            $res['icon'] = "find_in_page";
        return $res;
    };

    /**
     * Outputs the autocomplete dropdown
     * @param  {object} element Autocomplete element
     * @param  {array}  res     Autocomplete query result
     * @return {void}
     */
    Extended.prototype.outputAutocomplete = function(element, res, searchSection) {

        var $element = $(element);
        var $autocomplete = $('<div class="search-autocomplete"><h4>Sidor på nsr.se</h4></div>');
        var $content = $('<ul class="search-autocomplete-content"></ul>');
        var $sorteringsguiden = $('<div class="sorteringsguiden"><h4>Sorteringsguiden</h4></div>');
        var $sortMarkupTable = $('<table class="sorterings-guide-table striped"><tr><th></th><th>Sorteras som</th><th>Lämna nära dig</th><th>Bra att veta</th></tr></table>');

        console.log(res);

        if (typeof res.sortguide != 'undefined' && res.sortguide !== null && res.sortguide.length > 0) {

            $.each(res.sortguide, function (index, spost) {
                var $metaDataStr = Extended.prototype.metaDataStr('sorteringsguide');
                $sortMarkupTable.append('<tr><td><i class="material-icons">'+$metaDataStr['icon']+'</i> '+spost.post_title+'</td><td>'+spost.terms.fraktioner[0].name+'</td><td>'+spost.terms.inlamningsstallen[0].name+'</td><td>'+spost.post_meta.avfall_bra_att_veta+'</td></tr>');
                console.log(1);
            });
        }


        if (typeof res.content != 'undefined' && res.content !== null && res.content.length > 0) {
            $.each(res.content, function (index, post) {
                var $excerpt = post.post_excerpt.replace(/^(.{180}[^\s]*).*/, "$1");
                if($excerpt)
                    $excerpt = $excerpt+"...";
                var $metaDataStr = Extended.prototype.metaDataStr(post.post_type);

                if(!$metaDataStr['icon'])
                    $metaDataStr['icon'] = "find_in_page";

                $content.append('<li class="collapsible-header col s12 m12 l12"> <i class="material-icons"> '+$metaDataStr['icon']+'</i><a href="'+post.guid+'">'+post.post_title+'<span class="section right">'+$metaDataStr['postSection']+'</span><div class="moreinfo">'+$excerpt+'</div></a></li>');

            });
        } else {
            $content = $('');
        }

        $sortMarkupTable.appendTo($sorteringsguiden);
        $sorteringsguiden.appendTo($element);
        $content.appendTo($autocomplete);
        $autocomplete.appendTo($element).show();


        //$('.search-autocomplete-content li').matchHeight();
    };

    return new Extended;

})(jQuery);


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


        $('body').on('change', '.vc_all_locations', function (e) {
                $('.vc_location').val('Välj Stad/Ort...').change();
                $('.vc_date_size').val('Välj format...').change();
                $('.vc_type').val('Välj visningstyp...').change();
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

var callbackOutsideScope = function () {
    //jQuery('.vc_location').val('Välj Stad/Ort').change();
};


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


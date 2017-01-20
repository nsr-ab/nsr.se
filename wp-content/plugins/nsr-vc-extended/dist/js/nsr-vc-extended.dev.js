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
    var doneTypingInterval = 200;
    var cities = [];

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

        /* Puff med länkar - Visa fler nyheter */
        $('body').on('click', '.locationmap', function () {
            window.open($(this).data('url'), '_blank');
        }).bind(this);


    };



    /**
     *  displayMore
     *  Show more or less posts
     *  @param {object} element
     *  @return {void}
     */
    Extended.prototype.encodeStr = function (str) {
        return str.split("").reduce(function(a,b){a=((a<<5)-a)+b.charCodeAt(0);return a&a},0);
    }



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
        var $sorteringsguiden = $('<div class="sorteringsguiden"><h4>Sorteringsguiden</h4><div class="left badgeInfo"><span class="badge">P</span> Privat <span class="badge">F</span> Företag<br /></div></div>');
        var spinner = '<div class="preloader-wrapper small active" style="display:none;"> <div class="spinner-layer spinner-white-only"> <div class="circle-clipper left"> <div class="circle"></div> </div><div class="gap-patch"> <div class="circle"></div> </div><div class="circle-clipper right"> <div class="circle"></div> </div> </div> </div> ';
        var $sortMarkupTable = $('<table class="sorterings-guide-table"><tr class="tabDesk"><th></th><th>Sorteras som</th><th class="relative">Lämna nära dig... '+spinner+'</th><th class="exnfodispl">Bra att veta</th></tr></table>');

        if (typeof res.sortguide != 'undefined' && res.sortguide !== null && res.sortguide.length > 0) {

            var sortHTML;
            var tabMobile_frak = '';
            var tabMobile_inl = '';
            var CityItem;
            var cityInt = 0;
            $.each(res.sortguide, function (index, spost) {

                var customerCatIcons = '';
                if(spost.post_meta) {
                    if(spost.post_meta.avfall_kundkategori[0].indexOf('villa') >= 0) {
                        customerCatIcons += '<span class="badge sortSectionIcon private">P</span> ';
                    }
                    if(spost.post_meta.avfall_kundkategori[0].indexOf('foretag') >= 0) {
                        customerCatIcons += '<span class="badge sortSectionIcon company">F</span>';
                    }
                }

                sortHTML += '<tr class="tabMobile"><th>Avfall:</th><td valign="top">'+spost.post_title+' <div class="badgecontainer">'+customerCatIcons+'</div></td></tr>';
                sortHTML += '<tr class="tabDesk"><td class="preSortCell" valign="top">'+spost.post_title+' <div class="badgecontainer">'+customerCatIcons+'</div></td><td valign="top">';

                if(spost.terms) {
                    if(spost.post_meta.avfall_fraktion && spost.post_meta.avfall_fraktion.length) {
                        if(spost.post_meta.avfall_fraktion_hemma != '') {
                            sortHTML += '<li><b>Återvinningscentral:</b><ul class="sortAs meta-fraktion">';
                            tabMobile_frak += '<li><b>Sorteras som på ÅVC:</b><ul>';
                            for (int = 0; int < spost.post_meta.avfall_fraktion.length; int++) {
                                sortHTML += '<li>' + spost.post_meta.avfall_fraktion[int] + '</li>';
                                tabMobile_frak += '<li>' + spost.post_meta.avfall_fraktion[int] + "<li>";
                            }
                            sortHTML += '</ul></li>';
                            tabMobile_frak += '</ul></li>';
                        }
                    }
                    if(spost.post_meta.avfall_fraktion_hemma && spost.post_meta.avfall_fraktion_hemma.length) {
                        if(spost.post_meta.avfall_fraktion_hemma != '') {
                            sortHTML += '<li><b>Hemma:</b><ul class="meta-fraktion">';
                            tabMobile_frak += '<li><b class="sortAs">Sorteras som hemma:</b><ul>';

                            for (int = 0; int < spost.post_meta.avfall_fraktion_hemma.length; int++) {
                                sortHTML += '<li>' + spost.post_meta.avfall_fraktion_hemma[int] + '</li>';
                                tabMobile_frak += '<li>' + spost.post_meta.avfall_fraktion_hemma[int] + "<li>";
                            }
                            sortHTML += '</ul></li>';
                            tabMobile_frak += '</ul></li>';
                        }
                    }
                }

                sortHTML += '</td><td valign="top"><ul>';
                if(spost.terms) {
                    if(spost.terms.inlamningsstallen && spost.terms.inlamningsstallen.length) {
                        CityItem = [];
                        for (int = 0; int < spost.terms.inlamningsstallen.length; int++) {
                            if(int <= 5){
                                var cssClass = spost.terms.inlamningsstallen[int].term_id + "-" + int;

                                var inlineClick;
                                if(spost.terms.inlamningsstallen[int].pageurl)
                                    inlineClick = ' data-url="http://maps.google.com?q='+spost.terms.inlamningsstallen[int].lat+','+spost.terms.inlamningsstallen[int].long+'" ';
                                CityItem[int] = [spost.terms.inlamningsstallen[int].city, spost.terms.inlamningsstallen[int].lat, spost.terms.inlamningsstallen[int].long, spost.terms.inlamningsstallen[int].name, cssClass];
                                sortHTML += '<li class="cord-'+cssClass+' locationmap" '+inlineClick+'><i class="material-icons isize">location_on</i> ' + spost.terms.inlamningsstallen[int].name+'</li>';
                                tabMobile_inl += '<li class="cord-'+cssClass+' locationmap" '+inlineClick+'><i class="material-icons isize">location_on</i> ' + spost.terms.inlamningsstallen[int].name + '</li>';
                            }
                        }

                        cities[cityInt] = CityItem;
                        cityInt++;
                    }
                }

                sortHTML += '</ul></td>';
                var braAttVeta;
                if(spost.post_meta)
                    braAttVeta = spost.post_meta.avfall_bra_att_veta;
                sortHTML += '<td class="exnfodispl">'+braAttVeta+'</td>';
                sortHTML += '</tr>';
                sortHTML += '<tr class="tabMobile"><th>Sorteras:</th><td><ul class="meta-fraktion">'+tabMobile_frak+'</ul></td></tr>';
                sortHTML += '<tr class="tabMobile"><th>Lämnas:</th><td><ul>'+tabMobile_inl+'</ul></td></tr>';
                sortHTML += '<tr class="tabMobile lastchild"><td class="lastchild" colspan="2"> </td></tr>';
                tabMobile_inl = "";
            });

            $sortMarkupTable.append(sortHTML);
        }

        var $metaDataStr = Extended.prototype.metaDataStr('sorteringsguide');

        if (typeof res.content != 'undefined' && res.content !== null && res.content.length > 0) {
            $.each(res.content, function (index, post) {
                var $excerpt = post.post_excerpt.replace(/^(.{180}[^\s]*).*/, "$1");
                if($excerpt)
                    $excerpt = $excerpt+"...";
                var $metaDataStr = Extended.prototype.metaDataStr(post.post_type);

                if(!$metaDataStr['icon'])
                    $metaDataStr['icon'] = "find_in_page";

                var pageHTML = '<li class="collapsible-header col s12 m12 l12"> <i class="material-icons"> '+$metaDataStr['icon']+'</i><a href="'+post.guid+'">'+post.post_title+'<span class="section right">'+$metaDataStr['postSection']+'</span>';
                if($excerpt)
                    pageHTML += '<div class="moreinfo">'+$excerpt+'</div>';
                pageHTML += '</a></li>';
                $content.append(pageHTML);
            });
        } else {
            $content = $('');
        }

        $sortMarkupTable.appendTo($sorteringsguiden);
        $sorteringsguiden.appendTo($element);
        $content.appendTo($autocomplete);
        $autocomplete.appendTo($element).show();

        if (navigator.geolocation) {
            $('.preloader-wrapper').fadeIn("slow");
            navigator.geolocation.getCurrentPosition(Extended.prototype.UserLocation);
        }

    };

    /**
     * Callback function for asynchronous call to HTML5 geolocation
     * @param  {object} position
     * @return {void}
     */
    Extended.prototype.UserLocation = function(position) {
        Extended.prototype.NearestCity(position.coords.latitude, position.coords.longitude);
    }



    /**
     * Convert Degress to Radians
     * @param  {int} deg
     * @return degree
     */
    Extended.prototype.Deg2Rad = function(deg) {
        return deg * Math.PI / 180;
    }



    /**
     * Calculates with Pythagoras
     * @param  {int} lat long
     * @return degree
     */
    Extended.prototype.PythagorasEquirectangular = function(lat1, lon1, lat2, lon2) {
        lat1 = Extended.prototype.Deg2Rad(lat1);
        lat2 = Extended.prototype.Deg2Rad(lat2);
        lon1 = Extended.prototype.Deg2Rad(lon1);
        lon2 = Extended.prototype.Deg2Rad(lon2);
        var R = 6371; // km
        var x = (lon2 - lon1) * Math.cos((lat1 + lat2) / 2);
        var y = (lat2 - lat1);
        var d = Math.sqrt(x * x + y * y) * R;
        return d;
    }


    /**
     * Closest location
     * @param  {int} lat long
     * @return {array} cities
     */
    Extended.prototype.NearestCity = function(latitude, longitude) {


        var icon = 0;

        for (ind = 0; ind < cities.length; ++ind) {
            if(ind < cities.length+1) {
                $(cordClass).closest('ul').addClass('parent-'+ind);
                var mindif = 99999;
                var closest;
                for (index = 0; index < cities[ind].length; ++index) {
                    var dif = Extended.prototype.PythagorasEquirectangular(latitude, longitude, cities[ind][index][1], cities[ind][index][2]);
                    if (dif < mindif) {
                        closest = ind;
                        mindif = dif;
                        var cordClass = '.cord-' + cities[ind][index][4];
                    }
                }

                $(cordClass).css('font-weight','600');
                $(cordClass).css('color','#fff');
                $(cordClass).find('i').css('color','#00ffd0');
                icn = false;
                cordClass = false;
            }
        }
        $('.preloader-wrapper').fadeOut("slow");;
        return cities[closest];
    }


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


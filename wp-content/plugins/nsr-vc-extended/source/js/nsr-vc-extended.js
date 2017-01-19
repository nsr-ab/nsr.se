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
            //console.log(result);
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
        var $sortMarkupTable = $('<table class="sorterings-guide-table"><tr class="tabDesk"><th></th><th>Sorteras som</th><th>Lämna nära dig</th><th class="exnfodispl">Bra att veta</th></tr></table>');

        if (typeof res.sortguide != 'undefined' && res.sortguide !== null && res.sortguide.length > 0) {

            var sortHTML;
            var tabMobile_frak = '';
            var tabMobile_inl = '';

            $.each(res.sortguide, function (index, spost) {
                //console.log(spost);
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

                        for (int = 0; int < spost.terms.inlamningsstallen.length; int++) {
                            cities[int] = [spost.terms.inlamningsstallen[int].city, spost.terms.inlamningsstallen[int].lat, spost.terms.inlamningsstallen[int].long, spost.terms.inlamningsstallen[int].name];
                        }

                        if (navigator.geolocation) {
                            var closestCity = navigator.geolocation.getCurrentPosition(Extended.prototype.UserLocation);
                        }
                        for (int = 0; int < spost.terms.inlamningsstallen.length; int++) {

                            if(!closestCity) {
                                sortHTML += '<li>' + spost.terms.inlamningsstallen[int].name + '</li>';
                                tabMobile_inl += '<li>' + spost.terms.inlamningsstallen[int].name + '</li>';
                            }
                            else {
                                sortHTML += '<li>' + closestCity[4] + '</li>';
                                tabMobile_inl += '<li>' + closestCity[4] + '</li>';
                            }
                        }
                        console.log(closestCity[4]);
                    }
                }

                sortHTML += '</ul></td>';
                var braAttVeta;
                if(spost.post_meta)
                        braAttVeta = spost.post_meta.avfall_bra_att_veta;
                sortHTML += '<td class="exnfodispl">'+braAttVeta+'</td>';
                sortHTML += '</tr>';
                sortHTML += '<tr class="tabMobile"><th>Sorteras:</th><td><ul>'+tabMobile_frak+'</ul></td></tr>';
                sortHTML += '<tr class="tabMobile"><th>Lämnas:</th><td><ul>'+tabMobile_inl+'</ul></td></tr>';
                sortHTML += '<tr class="tabMobile lastchild"><td class="lastchild" colspan="2"> </td></tr>';
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


        //$('.search-autocomplete-content li').matchHeight();
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

    var lat = 20; // user's latitude
    var lon = 40; // user's longitude



    /**
     * Closest location
     * @param  {int} lat long
     * @return {array} cities
     */
    Extended.prototype.NearestCity = function(latitude, longitude) {

        var mindif = 99999;
        var closest;

        for (index = 0; index < cities.length; ++index) {
            var dif = Extended.prototype.PythagorasEquirectangular(latitude, longitude, cities[index][1], cities[index][2]);
            if (dif < mindif) {
                closest = index;
                mindif = dif;
            }
        }
        console.log(cities[closest]);
        return cities[closest];
    }




    return new Extended;

})(jQuery);


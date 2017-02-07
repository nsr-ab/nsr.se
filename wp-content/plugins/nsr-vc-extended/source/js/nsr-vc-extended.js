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
    }


    /**
     *  init
     *  Initializes all the necessary methods and binding stuff to events
     */
    Extended.prototype.init = function () {

        /* Default search */
        Extended.prototype.DefaultSiteSearch();

        if(!$('body').hasClass('wp-admin'))
            $('.card-content').matchHeight();

        $(function() {
            this.CollapsibleHeaders();
        }.bind(this));

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

        /* Puff med länkar - Visa fler nyheter */
        $('body').on('click', '#searchkeyword-nsr', function () {
            $('#searchkeyword-nsr').focus();
        }).bind(this);




    };

    /**
     *  DefaultSiteSearch
     *  Search via searchModal window
     */
    Extended.prototype.DefaultSiteSearch = function () {

        var query = Extended.prototype.getUrlParameter('q');

        if(query) {
            $('#searchkeyword-nsr').focus();
            $('#searchkeyword-nsr').val(query.replace(/\+/g, ' '));
            Extended.prototype.doneTyping();
        }
    }

    /**
     *  eventHandler
     *  Managing all event handlers (Silence is gold)
     */
    Extended.prototype.CollapsibleHeaders = function () {

        // Accordion open & close and links
        $('body').on('click', '.collapsible-header', function () {

            $id = $(this).parents('ul').attr('id');

            if($(this).find("a").length === 0) {
                $($id).find('.materialIconState').text('add');
                if($(this).hasClass('active')) {
                    $(this).find('.materialIconState').text('remove');
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
     *  closeScreen
     *  Close search screen
     *  @param {object} element
     */
    Extended.prototype.closeScreen = function (element) {

        event.stopPropagation();
        $('.searchNSR').removeClass('fullscreen');
        $('.searchNSR').removeClass('searchResult');

        $(element).addClass('hide');

        $('#searchkeyword-nsr').val('');
        $('.search-autocomplete').remove();
        $('.sorteringsguiden').remove();
        $('.vc_row').show();
        $('.page-footer').show();
        $('.main-container').removeAttr('style');

        if($('body').hasClass('error404'))
            $('.sidebar-footer-area').css('margin-top','0px');
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


        event.stopPropagation();

        if(!$('.searchNSR').hasClass('position-relative')) {

            $('.closeSearch').removeClass('hide');

            $('html, body').animate({ scrollTop: 0 }, 'slow');

            $('.vc_row').hide();
            $('.page-footer').hide();
            $('.searchNSR').closest('.vc_row').show();
            $('.main-container').height(317);

            if ($(window).width() < 540)
                $('.main-container').height(237);
        }

        if($('body').hasClass('error404'))
            $('.sidebar-footer-area').css('margin-top','200px');

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
            $('.searchNSR').addClass('searchResult');
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
     * find occurance in strings
     * @param  {string} haystack
     * @param  {string}  res
     * @param  {int}  offset
     * @return {int}
     */
    Extended.prototype.Strpos = function strpos (haystack, needle, offset) {
        var i = (haystack+'').indexOf(needle, (offset || 0));
        return i === -1 ? 0 : i;
    };


    /**
     * hash strings
     * @return {int}
     */
    Extended.prototype.hashCode = function(str) {
        var hash = 0;
        if (str === null)
            return hash;

        for (i = 0; i < str.length; i++) {
            char = str.charCodeAt(i);
            hash = ((hash<<5)-hash)+char;
            hash = hash & hash; // Convert to 32bit integer
        }
        return hash;
    };




    /**
     * Outputs the autocomplete dropdown
     * @param  {object} element Autocomplete element
     * @param  {array}  res     Autocomplete query result
     * @return {void}
     */
    Extended.prototype.outputAutocomplete = function(element, res, searchSection) {

        var $element = $(element);
        var $autocomplete = $('<div class="search-autocomplete"></div>');
        var $content = $('<ul class="search-autocomplete-content"></ul>');
        var $sorteringsguiden = $('<div class="sorteringsguiden"><h4>Sorteringsguiden</h4><div class="left badgeInfo"><span class="badge">P</span> Privat <span class="badge">F</span> Företag<br /></div></div>');
        var spinner = '<div class="preloader-wrapper small active" style="display:none;"> <div class="spinner-layer spinner-white-only"> <div class="circle-clipper left"> <div class="circle"></div> </div><div class="gap-patch"> <div class="circle"></div> </div><div class="circle-clipper right"> <div class="circle"></div> </div> </div> </div> ';
        var $sortMarkupTable = $('<table class="sorterings-guide-table"><tr class="tabDesk"><th></th><th>Sorteras som</th><th class="exnfodispl">Bra att veta</th><th class="relative">Lämnas nära dig</th></tr></table>');
        var nosortGuidedata = false;
        var noContent = false;


        if (typeof res.sortguide != 'undefined' && res.sortguide !== null && res.sortguide.length > 0) {
            console.log(res.sortguide );
            var sortHTML;
            var tabMobile_frak = '';
            var tabMobile_inl = '';
            var CityItem;
            var cityInt = 0;
            $.each(res.sortguide, function (index, spost) {

                var customerCatIcons = '';
                if(spost.post_meta) {

                    if(spost.post_meta.avfall_kundkategori[0].indexOf('foretag') >= 0) {
                        customerCatIcons += '<span class="badge sortSectionIcon company">F</span>';
                    }
                    if(spost.post_meta.avfall_kundkategori[0].indexOf('villa') >= 0) {
                        customerCatIcons += '<span class="badge sortSectionIcon private">P</span> ';
                    }
                }

                sortHTML += '<tr class="tabMobile"><th>Avfall:</th><td valign="top">'+spost.post_title+' <div class="badgecontainer">'+customerCatIcons+'</div></td></tr>';
                sortHTML += '<tr class="tabDesk"><td class="preSortCell" valign="top">'+spost.post_title+' <div class="badgecontainer">'+customerCatIcons+'</div></td><td valign="top">';

                if(spost.terms) {


                    if (spost.terms.fraktion_avc.name != '' && spost.terms.fraktion_avc.name != null) {
                        sortHTML += '<li><b>Återvinningscentral:</b><ul class="sortAs meta-fraktion">';
                        tabMobile_frak += '<li><b>ÅVC:</b><ul>';
                        if(spost.terms.fraktion_avc.link != '') {
                            var fraktion_avc = '<a href="'+spost.terms.fraktion_avc.link+'">'+spost.terms.fraktion_avc.name+'</a>';
                        }
                        else {
                            var fraktion_avc = spost.terms.fraktion_avc.name;
                        }
                        sortHTML += '<li class="fraktion-icon">' + fraktion_avc + '</li>';
                        tabMobile_frak += '<li class="fraktion-icon">' + fraktion_avc + "<li>";
                        sortHTML += '</ul></li>';
                        tabMobile_frak += '</ul></li>';

                    }

                    if (spost.terms.fraktion_hemma.name != '' && spost.terms.fraktion_hemma.name != null) {
                        sortHTML += '<li><b>Hemma:</b><ul class="meta-fraktion">';
                        tabMobile_frak += '<li><b class="sortAs">Hemma:</b><ul>';
                        if(spost.terms.fraktion_hemma.link != '') {
                            var fraktion_hemma = '<a href="'+spost.terms.fraktion_hemma.link+'">'+spost.terms.fraktion_hemma.name+'</a>';
                        }
                        else {
                            var fraktion_hemma = spost.terms.fraktion_hemma.name;
                        }
                        sortHTML += '<li class="fraktion-icon">' + fraktion_hemma + '</li>';
                        tabMobile_frak += '<li class="fraktion-icon">' + fraktion_hemma + "<li>";
                        sortHTML += '</ul></li>';
                        tabMobile_frak += '</ul></li>';
                    }
                }

                sortHTML += '</td>';
                var braAttVeta;
                if(spost.post_meta)
                    braAttVeta = spost.post_meta.avfall_bra_att_veta[0].replace(new RegExp('\r?\n', 'g'), '<br />');;
                sortHTML += '<td class="exnfodispl">'+braAttVeta+'</td>';

                sortHTML += '<td valign="top">'+spinner+'<ul class="inlstallen">';
                var hideStuff = '';
                if(spost.terms) {

                    if(spost.terms.inlamningsstallen && spost.terms.inlamningsstallen.length) {

                        CityItem = [];

                        for (var int = 0; int < spost.terms.inlamningsstallen.length; int++) {

                            var lint;
                            var inlineClick = '';
                            var inlLink = '';
                            var inLinkClose = '';

                            for (lint = 0; lint < spost.terms.inlamningsstallen[int].length; lint++) {

                                var cssClass = 'n'+Extended.prototype.hashCode(spost.terms.inlamningsstallen[int][lint]['city'])+ "-" + int;


                                if (spost.terms.inlamningsstallen[int][lint]['pageurl']) {

                                    if (Extended.prototype.Strpos(spost.terms.inlamningsstallen[int][lint]['pageurl'], '?page_id=') === 0) {
                                        inlLink = '<a href="' + spost.terms.inlamningsstallen[int][lint]['pageurl'] + '">';
                                        inLinkClose = '</a>';
                                    }
                                    else {
                                        if (spost.terms.inlamningsstallen[int][lint]['lat'] && spost.terms.inlamningsstallen[int][lint]['long']) {
                                            inlineClick = ' data-url="http://maps.google.com?q=' + spost.terms.inlamningsstallen[int][lint]['lat'] + ',' + spost.terms.inlamningsstallen[int][lint]['long'] + '" ';
                                        }
                                    }
                                }

                                CityItem[int] = [spost.terms.inlamningsstallen[int][lint]['city'], spost.terms.inlamningsstallen[int][lint]['lat'], spost.terms.inlamningsstallen[int][lint]['long'], spost.terms.inlamningsstallen[int][lint]['city'], cssClass];
                                if (lint > 5)
                                    hideStuff = 'hide';
                                if(spost.terms.inlamningsstallen[int][lint]['city'] != null) {
                                    sortHTML += '<li class="cord-' + cssClass + ' locationmap ' + hideStuff + '" ' + inlineClick + '> ' + inlLink + spost.terms.inlamningsstallen[int][lint]['city'] + inLinkClose + '</li>';
                                    tabMobile_inl += '<li class="cord-' + cssClass + ' locationmap ' + hideStuff + '" ' + inlineClick + '> ' + inlLink + spost.terms.inlamningsstallen[int][lint]['city'] + inLinkClose + '</li>';
                                }
                                hideStuff = '';
                            }
                        }

                        cities[cityInt] = CityItem;
                        cityInt++;
                    }
                }

                sortHTML += '</ul></td>';
                sortHTML += '</tr>';
                sortHTML += '<tr class="tabMobile"><th>Sorteras:</th><td><ul class="meta-fraktion">'+tabMobile_frak+'</ul></td></tr>';
                sortHTML += '<tr class="tabMobile"><th>Lämnas:</th><td>'+spinner+'<ul>'+tabMobile_inl+'</ul></td></tr>';
                sortHTML += '<tr class="tabMobile lastchild"><td class="lastchild" colspan="2"> </td></tr>';


                tabMobile_frak = "";
                tabMobile_inl = "";

                if(spost.post_title.length > 0)
                    nosortGuidedata = true;

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
                noContent = true;
            });
            $('.search-autocomplete').prepend('<h4>Sidor på nsr.se</h4>');
        }

        if(nosortGuidedata) {
            $sortMarkupTable.appendTo($sorteringsguiden);
            $sorteringsguiden.appendTo($element);
        }
        if(noContent)
            $content.appendTo($autocomplete);
        $autocomplete.appendTo($element).show();

        $('.fraktion-icon').each(function () {
            if($(this).find('span').hasClass('nofraktionlink'))
                $(this).removeClass('fraktion-icon');
        });


        if(noContent)
            $('.search-autocomplete').prepend('<h4>Sidor på nsr.se</h4>');

        if(!noContent && !nosortGuidedata){
            $('#searchkeyword-nsr').addClass('invalid');
            $('#searchkeyword-nsr').removeClass('valid');
        }


        if (navigator.geolocation) {
            $('.preloader-wrapper').fadeIn("slow");
            navigator.geolocation.getCurrentPosition(Extended.prototype.UserLocation, Extended.prototype.GeoError);
        }

    };



    /**
     * GEo Error
     * @param  {object} error
     * @return {void}
     */
    Extended.prototype.GeoError = function(error) {
        switch(error.code) {
            case error.PERMISSION_DENIED:
                constant = "PERMISSION_DENIED";
                $('.spinner-layer').hide();
                console.log('geoLocation: '+constant);
                break;
            case error.POSITION_UNAVAILABLE:
                constant = "POSITION_UNAVAILABLE";
                $('.spinner-layer').hide();
                console.log('geoLocation: '+constant);
                break;
            case error.TIMEOUT:
                constant = "TIMEOUT";
                $('.spinner-layer').hide();
                console.log('geoLocation: '+constant);
                break;
            default:
                constant = "Unrecognized error";
                $('.spinner-layer').hide();
                console.log('geoLocation: '+constant);
                break;
        }

    }



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
                        var cordClass = 'cord-' + cities[ind][index][4];
                    }
                    //console.log(cities[ind][index][1]+ " : " + cities[ind][index][2]);
                }

                $('.'+cordClass).addClass('geoLink');
                $('.'+cordClass).removeClass('hide');

                var int = 0;
                $('.inlstallen li ').each(function() {
                    if(int > 4)
                        $(this).addClass('hide');
                    if($(this).hasClass(cordClass)) {
                        var putMeInTheTopOfTheList = $(this).clone();
                        $(this).parent().prepend(putMeInTheTopOfTheList);
                        $(this).remove();
                    }
                });

                icn = false;
                cordClass = false;
                $(cordClass).parent().css('background','red');
            }
        }

        $('.preloader-wrapper').fadeOut("slow");;
        return cities[closest];
    }


    Extended.prototype.getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : sParameterName[1];
            }
        }
    };


    return new Extended;

})(jQuery);





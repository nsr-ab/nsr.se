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
    var timerFetchplanner;
    var timerElastic;
    var hxrLoader = 0;
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

        if (!$('body').hasClass('wp-admin'))
            $('.card-content').matchHeight();

        $(function () {
            this.CollapsibleHeaders();
        }.bind(this));

        /* Puff med länkar - Visa fler nyheter */
        $('body').on('click', '.showAllPosts', function () {
            Extended.prototype.displayMore(this);
        }).bind(this);


        /* CardClick */
        $('body').on('click', '.card', function (e) {
            if ($(this).data('link'))
                window.location.href = $(this).data('link');
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
            if ($(this).data('url'))
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

        if (query) {
            $('#searchkeyword-nsr').focus(), $('#searchkeyword-nsr').val(query.replace(/\+/g, ' '));
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

            if ($(this).find("a").length === 0) {
                $($id).find('.materialIconState').text('add');
                if ($(this).hasClass('active')) {
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
        return str.split("").reduce(function (a, b) {
            a = ((a << 5) - a) + b.charCodeAt(0);
            return a & a
        }, 0);
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
            $(element).closest("ul").find('.hide').addClass('show'), $(element).closest("ul").find('li').removeClass('hide');
            var countItemsHide = $(element).closest("ul").find('li').length - 6;
            $(element).closest("ul").find('.showPosts').text('Dölj (' + countItemsHide + ')');
        }
        else {
            $(element).closest("ul").find('.show').addClass('hide'), $(element).closest("ul").find('li').removeClass('show');
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
                    $('.search-autocomplete').remove(), $('.sorteringsguiden').remove();
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
        $('.searchNSR').removeClass('fullscreen'), $('.searchNSR').removeClass('searchResult');
        $(element).addClass('hide'), $('#searchkeyword-nsr').val(''), $('.search-autocomplete').remove(), $('.sorteringsguiden').remove();
        $('.vc_row').show(), $('.page-footer').show(), $('.main-container').removeAttr('style'), $('.search-fetchPlanner').html('');

        if ($('body').hasClass('error404'))
            $('.sidebar-footer-area').css('margin-top', '0px');
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

        $('.searchNSR input').focus(), $('.searchNSR').addClass('fullscreen');
        event.stopPropagation();

        if (!$('.searchNSR').hasClass('position-relative')) {

            $('.closeSearch').removeClass('hide'), $('html, body').animate({scrollTop: 0}, 'slow');
            $('.vc_row').hide(), $('.page-footer').hide(), $('.searchNSR').closest('.vc_row').show(), $('.main-container').height(317);

            if ($(window).width() < 540)
                $('.main-container').height(237);
        }

        if ($('body').hasClass('error404'))
            $('.sidebar-footer-area').css('margin-top', '200px');

    };


    /**
     *  timer FetchPlanner
     *  fires a call to fetchplannerQuery
     *  @return {void}
     */

    Extended.prototype.fpTimer = function () {
        Extended.prototype.fetchPlannerQuery($('.searchNSR'))
    };


    /**
     *  timer FetchPlanner
     *  fires a call to fetchplannerQuery
     *  @return {void}
     */
    Extended.prototype.ElasticTimer = function (element) {
        Extended.prototype.autocomplete(element);
    };


    /**
     *  doneTyping
     *  fires a call to autocomples and fetchplanner
     *  @return {void}
     */
    Extended.prototype.doneTyping = function () {

        $('.searchNSR').each(function (index, element) {
            clearTimeout(timerElastic), clearTimeout(timerFetchplanner);
            timerElastic = setTimeout(function () {
                Extended.prototype.ElasticTimer(element);
            }, 300);
        });
        timerFetchplanner = setTimeout(Extended.prototype.fpTimer, 1100);
    };


    /**
     * Initializes the autocomplete functionality
     * @param  {object} element
     * @return {void}
     */
    Extended.prototype.autocomplete = function (element) {

        var $element = $(element);
        var $input = $element.find('input[type="search"]');

        if ($input.val().length < 2) {
            return;
        }

        this.autocompleteQuery(element);
    };


    /**
     * Submit autocompleteSubmit
     * @param  {object} element Autocomplete
     * @return {bool}
     */
    Extended.prototype.autocompleteSubmit = function (element) {

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
    Extended.prototype.autocompleteQuery = function (element) {

        var $element = $(element);
        var $input = $element.find('input[type="search"]').val();
        var $post_type = $('#post_type').val();

        var data = {
            action: 'fetchDataFromElasticSearch',
            query: $input,
            post_type: $post_type,
            level: 'ajax',
            type: 'json'
        };

        Extended.prototype.getJsonData($element, data, $post_type);


    };


    /**
     * Query for fetchplanner
     * @param  {object} element fetchplanner element
     * @return {void}
     */
    Extended.prototype.fetchPlannerQuery = function (element) {


        var $element = $(element);
        var $input = $('#searchkeyword-nsr').val();
        var fdata = {
            action: 'fetchDataFromFetchPlanner',
            query: $input,
            level: 'ajax',
            type: 'json'
        };

        Extended.prototype.getJsonData($element, fdata, null);
    };


    /**
     * Query for autocomplete suggestions and fetchplanner
     * @param  {object} element Autocomplete element
     * @return {void}
     */
    Extended.prototype.spinner = function () {

        var id = arguments[0];
        var size = arguments[1];
        var display = (!arguments[2]) ? 'none' : 'block';

        if (!size)
            size = 'small';
        return '<div class="' + id + ' preloader-wrapper ' + size + ' active" style="display:' + display + ';"> <div class="spinner-layer spinner-white-only"> <div class="circle-clipper left"> <div class="circle"></div> </div><div class="gap-patch"> <div class="circle"></div> </div><div class="circle-clipper right"> <div class="circle"></div> </div> </div> </div> ';
    };


    /**
     * Query for autocomplete suggestions and fetchplanner
     * @param  {object} element Autocomplete element
     * @return {void}
     */
    Extended.prototype.getJsonData = function ($element, data, $post_type) {

        var progressbar = Extended.prototype.spinner(Extended.prototype.hashCode(data.action), 'big', true);

        $.ajax({
            url: ajax_object.ajax_url,
            data: data,
            method: 'GET',
            dataType: 'json',
            beforeSend: function (xhr) {

                xhr.setRequestHeader('X-WP-Nonce', ajax_object.nonce);
                if ($('.searchNSR form .preloader-wrapper').length < 1) {
                    $('.searchNSR .searchArea').append(progressbar);
                }

                $('#searchkeyword-nsr').removeClass('valid'), $('#searchkeyword-nsr').removeClass('invalid'), $('#searchkeyword-nsr').addClass('waitingForConnection');
            }

        }).done(function (result) {

            if (data.action === 'fetchDataFromElasticSearch') {

                $('.searchNSR').addClass('searchResult'), $element.find('.sorteringsguiden').remove(), $element.find('.search-autocomplete').remove();
                this.outputAutocomplete($element, result, $post_type);
            }
            else {

                $('.search-fetchPlanner').html('');
                this.outputFetchPlanner(result, false);
                $('.searchArea .preloader-wrapper').remove();
                if ($('#searchkeyword-nsr').hasClass('valid'))
                    $('#searchkeyword-nsr').addClass('valid');

            }
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
                $res['postSection'] = 'Villa & Privat';
                $res['icon'] = 'home';
                break;

            case "foretag":
                $res['postSection'] = 'Företag & Verksamheter';
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
        if ($res['icon'] === '')
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
    Extended.prototype.Strpos = function strpos(haystack, needle, offset) {
        var i = (haystack + '').indexOf(needle, (offset || 0));
        return i === -1 ? 0 : i;
    };


    /**
     * hash strings
     * @return {string}
     */
    Extended.prototype.hashCode = function (str) {
        var hash = 0;
        if (str === null)
            return hash;

        for (i = 0; i < str.length; i++) {
            char = str.charCodeAt(i);
            hash = ((hash << 5) - hash) + char;
            hash = hash & hash; // Convert to 32bit integer
        }
        return hash;
    };


    /**
     * Outputs Fetchplanner
     * @param  {object} element fetchplanner element
     * @param  {array}  result  fetchplanner query result
     * @return {void}
     */
    Extended.prototype.outputFetchPlanner = function (result) {

        var $fprow = '';
        var $fpMobRow = '';

        if (typeof result.fp != 'undefined' && result.fp !== null && result.fp.length > 0) {

            $fprow += '<h4>Tömningsdagar</h4><table class="fp-table"><tr class="tabDesk"><th colspan="2">Adress</th><th>Nästa tömning</th></tr>';
            $fpMobRow += '<table class="fp-table-mobile">';

            var jsdate = new Date().toISOString().slice(0, 10);
            var dateExp = false;

            $.each(result.fp, function (index, post) {

                var $dub = [];
                var $avfall = '';
                var $weeks = '';
                var $nextDate = '';

                $('#searchkeyword-nsr').removeClass('invalid'), $('#searchkeyword-nsr').addClass('valid');

                if (post.hasOwnProperty('Exec')) {

                    if (post.Exec.AvfallsTyp[0] || post.Exec.AvfallsTyp[1]) {

                        if ($.inArray(false, post.Exec.AvfallsTyp) < 0) {

                            $fprow += '<tr id="' + post.id + '" class="tabDesk">';
                            $fprow += '<td class="streetCiy"><strong>' + post.Adress + '</strong>';
                            $fprow += '<div><b class="">' + post.Ort + '</b></div>';
                            $fprow += '</td><td>';

                            for (var avint = 0; avint < post.Exec.AvfallsTyp.length; avint++) {
                                if (post.Exec.Datum[avint] >= jsdate) {
                                    if (!$dub.includes(post.Exec.AvfallsTyp[avint] + ' ' + post.Exec.Datum[avint])) {
                                        $dub['avfall'] = post.Exec.AvfallsTyp[avint];
                                        $avfall += '<span class="badge">' + post.Exec.AvfallsTyp[avint] + '</span><br /> ';
                                        //$weeks += post.Exec.DatumWeek[avint] + '<br />';
                                        $dub['nDate'] = post.Exec.AvfallsTyp[avint];
                                        $nextDate += post.Exec.DatumFormaterat[avint] + '<br />';
                                        $dub[avint] = post.Exec.AvfallsTyp[avint] + ' ' + post.Exec.Datum[avint];
                                    }
                                }
                            }

                            $fprow += $avfall + '</td><td>' + $nextDate;
                            $fpMobRow += '<tr class="fpthmob"><th colspan="2"><i class="material-icons">date_range</i> <span><strong> ' + post.Adress + '</span>, <span>' + post.Ort + '</span></strong></th></tr>';
                            $fpMobRow += '<tr><th>Kärl</th><th>Nästa tömning</th></tr>';
                            $fpMobRow += '<tr><td>' + $avfall + '</td><td>' + $nextDate + '</td></tr>';
                        }

                        $fprow += '</td></tr>';
                    }
                }

                dateExp = false;

            });
            $fprow += '</table>';
            $fpMobRow += '</table>';
        }
        $('.search-fetchPlanner').append($fprow);
        $('.search-fetchPlanner').append($fpMobRow);

    };


    /**
     * Outputs the autocomplete dropdown
     * @param  {object} element Autocomplete element
     * @param  {array}  res     Autocomplete query result
     * @return {void}
     */
    Extended.prototype.outputAutocomplete = function (element, res, searchSection) {

        var $element = $(element);
        var $autocomplete = $('<div class="search-autocomplete"></div>');
        var $content = $('<ul class="search-autocomplete-content"></ul>');
        var $sorteringsguiden = $('<div class="sorteringsguiden"><h4>Sorteringsguiden</h4><div class="left badgeInfo"><span class="badge">P</span> Privat <span class="badge">F</span> Företag<br /></div></div>');
        var spinner = Extended.prototype.spinner(Extended.prototype.hashCode('elasticCords'));
        var $sortMarkupTable = $('<table class="sorterings-guide-table"><tr class="tabDesk"><th></th><th>Sorteras som</th><th class="exnfodispl">Bra att veta</th><th class="relative">Lämnas nära dig</th></tr></table>');
        var nosortGuidedata = false;
        var noContent = false;

        if (typeof res.sortguide != 'undefined' && res.sortguide !== null && res.sortguide.length > 0) {

            var sortHTML;
            var tabMobile_frak = '';
            var tabMobile_inl = '';
            var CityItem;
            var cityInt = 0;

            $.each(res.sortguide, function (index, spost) {

                var customerCatIcons = '';
                if (spost.post_meta) {

                    if (spost.post_meta.avfall_kundkategori[0].indexOf('foretag') >= 0) {
                        customerCatIcons += '<span class="badge sortSectionIcon company">F</span>';
                    }
                    if (spost.post_meta.avfall_kundkategori[0].indexOf('villa') >= 0) {
                        customerCatIcons += '<span class="badge sortSectionIcon private">P</span> ';
                    }
                }

                sortHTML += '<tr class="tabMobile"><th class="col s12">Avfall:</th><td valign="top col s12">' + spost.post_title + ' <div class="badgecontainer">' + customerCatIcons + '</div></td></tr>';
                sortHTML += '<tr class="tabDesk"><td class="preSortCell" valign="top">' + spost.post_title + ' <div class="badgecontainer">' + customerCatIcons + '</div></td><td valign="top">';

                if (spost.post_meta) {


                    if (spost.post_meta.fraktion_avc.name != '' && spost.post_meta.fraktion_avc.name != null) {
                        sortHTML += '<li><b>Återvinningscentral:</b><ul class="sortAs meta-fraktion">';
                        tabMobile_frak += '<li><b>Återvinningscentral:<br /></b><ul>';
                        if (spost.post_meta.fraktion_avc.link != '') {
                            var fraktion_avc = '<a href="' + spost.post_meta.fraktion_avc.link + '">' + spost.post_meta.fraktion_avc.name + '</a>';
                        }
                        else {
                            var fraktion_avc = spost.post_meta.fraktion_avc.name;
                        }
                        sortHTML += '<li class="fraktion-icon">' + fraktion_avc + '</li>';
                        tabMobile_frak += '<li class="fraktion-icon">' + fraktion_avc + "<li>";
                        sortHTML += '</ul></li>';
                        tabMobile_frak += '</ul></li>';
                    }

                    if (spost.post_meta.fraktion_hemma.name != '' && spost.post_meta.fraktion_hemma.name != null) {
                        sortHTML += '<li><b>Hemma:</b><ul class="meta-fraktion">';
                        tabMobile_frak += '<li><b class="sortAs">Hemma:</b><ul>';
                        if (spost.post_meta.fraktion_hemma.link != '') {
                            var fraktion_hemma = '<a href="' + spost.post_meta.fraktion_hemma.link + '">' + spost.post_meta.fraktion_hemma.name + '</a>';
                        }
                        else {
                            var fraktion_hemma = spost.post_meta.fraktion_hemma.name;
                        }
                        sortHTML += '<li class="fraktion-icon">' + fraktion_hemma + '</li>';
                        tabMobile_frak += '<li class="fraktion-icon">' + fraktion_hemma + "<li>";
                        sortHTML += '</ul></li>';
                        tabMobile_frak += '</ul></li>';
                    }
                }

                sortHTML += '</td>';
                var braAttVeta;
                if (spost.post_meta)
                    braAttVeta = spost.post_meta.avfall_bra_att_veta[0].replace(new RegExp('\r?\n', 'g'), '<br />');

                sortHTML += '<td class="exnfodispl">' + braAttVeta + '</td>';

                sortHTML += '<td valign="top">' + spinner + '<ul class="inlstallen">';
                var hideStuff = '';
                if (spost.post_meta) {

                    if (spost.post_meta.inlamningsstallen && spost.post_meta.inlamningsstallen.length) {

                        CityItem = [];
                        for (var int = 0; int < spost.post_meta.inlamningsstallen.length; int++) {

                            var lint;
                            var inlineClick = '';
                            var inlLink = '';
                            var inLinkClose = '';
                            var latlong = '';
                            var latlongID = '';
                            var searchID = '';
                            var locationmap;
                            var setNonLink = '';
                            for (lint = 0; lint < spost.post_meta.inlamningsstallen[int].length; lint++) {

                                if (spost.post_meta.inlamningsstallen[int][lint]['pageurl']) {

                                    inlLink = '';
                                    inLinkClose = '';
                                    locationmap = '';

                                    if (Extended.prototype.Strpos(spost.post_meta.inlamningsstallen[int][lint]['pageurl'], '?page_id=')) {
                                        spost.post_meta.inlamningsstallen[int][lint]['pageurl'] = '';
                                        inlLink = '';
                                        inLinkClose = '';
                                        inlineClick = '';
                                        locationmap = '';
                                    }

                                    if (spost.post_meta.inlamningsstallen[int][lint]['pageurl'] != '') {
                                        inlLink = '<a href="' + spost.post_meta.inlamningsstallen[int][lint]['pageurl'] + '">';
                                        inLinkClose = '</a>';
                                        locationmap = 'locationmap';
                                    }

                                    if (spost.post_meta.inlamningsstallen[int][lint]['lat'] && spost.post_meta.inlamningsstallen[int][lint]['long']) {
                                        if (!spost.post_meta.inlamningsstallen[int][lint]['pageurl']) {
                                            inlineClick = ' data-url="http://maps.google.com?q=' + spost.post_meta.inlamningsstallen[int][lint]['lat'] + ',' + spost.post_meta.inlamningsstallen[int][lint]['long'] + '" ';
                                            locationmap = 'locationmap';
                                        }
                                    }
                                }

                                if (spost.post_meta.inlamningsstallen[int][lint]['lat'] && spost.post_meta.inlamningsstallen[int][lint]['long']) {
                                    latlong = 'data-lat="' + spost.post_meta.inlamningsstallen[int][lint]['lat'] + '" data-long="' + spost.post_meta.inlamningsstallen[int][lint]['long'] + '"';
                                    latlongID = Extended.prototype.hashCode('id_' + int + lint + '_' + spost.post_meta.inlamningsstallen[int][lint]['lat'] + spost.post_meta.inlamningsstallen[int][lint]['long']);
                                }

                                if (spost.post_meta.inlamningsstallen[int][lint]['lat'] && spost.post_meta.inlamningsstallen[int][lint]['long'])
                                    CityItem[lint] = [spost.post_meta.inlamningsstallen[int][lint]['city'], spost.post_meta.inlamningsstallen[int][lint]['lat'], spost.post_meta.inlamningsstallen[int][lint]['long'], spost.post_meta.inlamningsstallen[int][lint]['city'], latlongID];

                                searchID = latlongID;
                                if (latlongID)
                                    latlongID = 'id="' + latlongID + '"';

                                latlongID = '';

                                if (lint > 5)
                                    hideStuff = 'hide';
                                if (spost.post_meta.inlamningsstallen[int][lint]['city'] != null) {
                                    if (!inlLink)
                                        setNonLink = 'nullLink';
                                    sortHTML += '<li searchid="' + searchID + '" ' + latlongID + ' ' + latlong + ' class="' + setNonLink + ' ' + locationmap + ' ' + hideStuff + '" ' + inlineClick + '> ' + inlLink + spost.post_meta.inlamningsstallen[int][lint]['city'] + inLinkClose + '</li>';
                                    tabMobile_inl += '<li searchid="' + searchID + '" ' + latlongID + ' ' + latlong + ' class="' + setNonLink + ' ' + locationmap + ' ' + hideStuff + '" ' + inlineClick + '> ' + inlLink + spost.post_meta.inlamningsstallen[int][lint]['city'] + inLinkClose + '</li>';
                                }
                                nullLink = '';
                                locationmap = '';
                                hideStuff = '';
                                inlineClick = '';
                                latlong = '';
                                latlongID = '';
                            }
                        }

                        cities[cityInt] = CityItem;
                        cityInt++;
                    }


                }

                sortHTML += '<li class="viewAllInlamning"><a href="/alla-inlamningsstallen/">Visa alla</a></li></ul></td>';
                sortHTML += '</tr>';
                sortHTML += '<tr class="tabMobile"><th class="col s12">Sorteras som:</th><td><ul class="meta-fraktion">' + tabMobile_frak + '</ul></td></tr>';
                sortHTML += '<tr class="tabMobile lastchild"><th class="col s12">Lämnas:</th><td>' + spinner + '<ul class="inlstallen">' + tabMobile_inl + '<li class="viewAllInlamning"><a href="/alla-inlamningsstallen/">Visa alla</a></li></ul></td></tr>';

                tabMobile_frak = "";
                tabMobile_inl = "";

                if (spost.post_title.length > 0)
                    nosortGuidedata = true;

            });

            $sortMarkupTable.append(sortHTML);
        }

        var $metaDataStr = Extended.prototype.metaDataStr('sorteringsguide');

        if (typeof res.content != 'undefined' && res.content !== null && res.content.length > 0) {
            $.each(res.content, function (index, post) {
                var $excerpt = post.post_excerpt.replace(/^(.{180}[^\s]*).*/, "$1");
                if ($excerpt)
                    $excerpt = $excerpt + "...";
                var $metaDataStr = Extended.prototype.metaDataStr(post.post_type);

                if (!$metaDataStr['icon'])
                    $metaDataStr['icon'] = "find_in_page";

                var pageHTML = '<li class="collapsible-header col s12 m12 l12"> <i class="material-icons"> ' + $metaDataStr['icon'] + '</i><a href="' + post.guid + '">' + post.post_title + '<span class="section right">' + $metaDataStr['postSection'] + '</span>';
                if ($excerpt)
                    pageHTML += '<div class="moreinfo">' + $excerpt + '</div>';
                pageHTML += '</a></li>';
                $content.append(pageHTML);
                noContent = true;
            });
            $('.search-autocomplete').prepend('<h4>Sidor på nsr.se</h4>');
        }

        if (nosortGuidedata) {
            $sortMarkupTable.appendTo($sorteringsguiden);
            $sorteringsguiden.appendTo($element);
        }
        if (noContent)
            $content.appendTo($autocomplete);
        $autocomplete.appendTo($element).show();

        $('.fraktion-icon').each(function () {
            if ($(this).find('span').hasClass('nofraktionlink'))
                $(this).removeClass('fraktion-icon');
        });

        if (noContent)
            $('.search-autocomplete').prepend('<h4>Sidor på nsr.se</h4>');

        if (!noContent && !nosortGuidedata) {
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
    Extended.prototype.GeoError = function (error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                constant = "PERMISSION_DENIED";
                $('.search-autocomplete .preloader-wrapper').hide();
                console.log('geoLocation: ' + constant);
                break;
            case error.POSITION_UNAVAILABLE:
                constant = "POSITION_UNAVAILABLE";
                $('.search-autocomplete .preloader-wrapper').hide();
                console.log('geoLocation: ' + constant);
                break;
            case error.TIMEOUT:
                constant = "TIMEOUT";
                $('.search-autocomplete .preloader-wrapper').hide();
                console.log('geoLocation: ' + constant);
                break;
            default:
                constant = "Unrecognized error";
                $('.search-autocomplete .preloader-wrapper').hide();
                console.log('geoLocation: ' + constant);
                break;
        }

    };


    /**
     * Callback function for asynchronous call to HTML5 geolocation
     * @param  {object} position
     * @return {void}
     */
    Extended.prototype.UserLocation = function (position) {
        Extended.prototype.NearestCity(position.coords.latitude, position.coords.longitude);
    };


    /**
     * Convert Degress to Radians
     * @param  {int} deg
     * @return degree
     */
    Extended.prototype.Deg2Rad = function (deg) {
        return deg * Math.PI / 180;
    };


    /**
     * Calculates with Pythagoras
     * @param  {int} lat long
     * @return degree
     */
    Extended.prototype.PythagorasEquirectangular = function (lat1, lon1, lat2, lon2) {
        lat1 = Extended.prototype.Deg2Rad(lat1);
        lat2 = Extended.prototype.Deg2Rad(lat2);
        lon1 = Extended.prototype.Deg2Rad(lon1);
        lon2 = Extended.prototype.Deg2Rad(lon2);
        var R = 6371; // Radius of the earth in km
        var x = (lon2 - lon1) * Math.cos((lat1 + lat2) / 2);
        var y = (lat2 - lat1);
        var d = Math.sqrt(x * x + y * y) * R;
        return d;
    };


    /**
     * Calculates with  Haversine formula
     * Calculates great-circle distances between the two points – that is, the shortest distance over the earth’s surface – using the ‘Haversine’ formula.
     * @param  {int} lat long
     * @return degree
     */
    Extended.prototype.getDistanceFromLatLonInKm = function (lat1, lon1, lat2, lon2) {
        var R = 6371; // Radius of the earth in km
        var dLat = Extended.prototype.Deg2Rad(lat2 - lat1);
        var dLon = Extended.prototype.Deg2Rad(lon2 - lon1);
        var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) + Math.cos(Extended.prototype.Deg2Rad(lat1)) * Math.cos(Extended.prototype.Deg2Rad(lat2)) * Math.sin(dLon / 2) * Math.sin(dLon / 2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        var d = R * c; // Distance in km
        return d;
    };

    Extended.prototype.findClosest = function () {
        if (arguments[0] instanceof Array)
            arguments = arguments[0];

        return Math.min.apply(Math, arguments);
    };

    /**
     * Closest location
     * @param  {int} lat long
     * @return {array} cities
     */
    Extended.prototype.NearestCity = function (latitude, longitude) {
        console.log(latitude + " : " +longitude)
        var icon = 0;
        var cordID = null;
        for (ind = 0; ind < cities.length; ++ind) {
            if (ind < cities.length + 1) {
                //$(cordID).closest('ul').addClass('parent-' + ind);
                var mindif = 99999;
                var closest;
                var winners = [];
                for (index = 0; index < cities[ind].length; ++index) {
                    //var dif = Extended.prototype.PythagorasEquirectangular(latitude, longitude, cities[ind][index][1], cities[ind][index][2]);
                    if(cities[ind][index]) {
                        var dif = Extended.prototype.getDistanceFromLatLonInKm(latitude, longitude, cities[ind][index][1], cities[ind][index][2]);
                        winners[index] = dif;
                        cities[ind][index][3] = dif;
                    }
                }

                for (index = 0; index < cities[ind].length; ++index) {

                    var thewinner = Extended.prototype.findClosest(winners);
                    if (thewinner === cities[ind][index][3]) {
                        var cordID = cities[ind][index][4];
                        var deskAndMobileLi = $('[searchid="' + cordID + '"]');
                        $(deskAndMobileLi).each(function () {
                            $(this).addClass('closeToHome');
                        });
                        //$('#'+cordID).addClass('closeToHome');
                    }
                    thewinner = null;
                }

                icn = false;
            }
        }

        $('.inlstallen').each(function () {
            var closestCity = false;
            $(this).find('li').each(function () {
                if (closestCity) {
                    if ($(this).hasClass('closeToHome')) {
                        $(this).removeClass('closeToHome');
                    }
                    return;
                }
                if ($(this).hasClass('closeToHome')) {
                    $(this).addClass('geoLink');
                    $(this).removeClass('hide');
                    var putMeInTheTopOfTheList = $(this).clone();
                    $(this).parent().prepend(putMeInTheTopOfTheList);
                    $(this).remove();
                    closestCity = true;
                }
            });
        });

        $('.preloader-wrapper').fadeOut("slow");

        return cities[closest];
    };


    /**
     * Get URL param
     * @param  {string} sparam
     * @return {array} param value
     */
    Extended.prototype.getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)), sURLVariables = sPageURL.split('&'),
            sParameterName, i;
        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : sParameterName[1];
            }
        }
    };


    return new Extended;

})(jQuery);



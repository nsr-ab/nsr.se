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
    var _gaq = _gaq || [];
    var wouldBeTimer = false;
    var contentDataAmount = [];
    var $relevant = [];
    var relevantCount = null;

    /**
     * Constructor
     */
    function Extended() {
        Extended.prototype.init();
    }


    /**
     *  init
     *  Initializes all the necessary methods and binding stuff to events
     */
    Extended.prototype.init = function () {

        //_gaq.push(['_setAccount', 'UA-92267061-1']); // your ID/profile
        //_gaq.push(['_trackPageview']);


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

        /* searchNSR - Submit means search */
        $('.searchNSR form').on('submit', function (e) {
            e.preventDefault();
            window.clearTimeout(typingTimer);
            Extended.prototype.doneTyping();
            return false;
        }).bind(this);


        /* On input starting timer  */
        $('.searchNSR').on("input", function () {
            console.log('init 101');
            window.clearTimeout(typingTimer);
            if (!wouldBeTimer) {
                $('.searchNSR .search-button').show();
                wouldBeTimer = true;
            }
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
        console.log('DefaultSiteSearch 136');
        var query = Extended.prototype.getUrlParameter('q');

        if (query) {
            //$('.searchNSR .search-button-mobile').hide();
            $('.searchNSR .search-button').show();
            //if ($(window).width() < 767)
            //  $('.searchNSR .search-button-mobile').show();
            //else
            //$('.searchNSR .search-button').show();
            $('#searchkeyword-nsr').focus(), $('#searchkeyword-nsr').val(query.replace(/\+/g, ' '));
            Extended.prototype.doneTyping();
        }
    }

    /**
     *  eventHandler
     *  Managing all event handlers (Silence is gold)
     */
    Extended.prototype.CollapsibleHeaders = function () {
        console.log('CollapsibleHeader 156');
        // Accordion open & close and links
        $('body').on('click', '.collapsible-header', function () {

            $id = $(this).parents('ul').attr('id');

            if ($(this).find("a").length === 0) {
                $($id).find('.materialIconState').text('add');
                if ($(this).hasClass('active')) {
                    $(this).find('.materialIconState').text('remove');
                } else {
                    $(this).find('.materialIconState').text('add');
                }
            } else {
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
        console.log('display more 199');
        event.preventDefault();

        if ($(element).closest("ul").find('li').hasClass('hide')) {
            $(element).closest("ul").find('.hide').addClass('show'), $(element).closest("ul").find('li').removeClass('hide');
            var countItemsHide = $(element).closest("ul").find('li').length - 6;
            $(element).closest("ul").find('.showPosts').text('Dölj (' + countItemsHide + ')');
        } else {
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
        console.log('enterTrigger');
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


        if ($('body').hasClass('error404'))
            $('.sidebar-footer-area').css('margin-top', '200px');

    };


    /**
     *  timer FetchPlanner
     *  fires a call to fetchplannerQuery
     *  @return {void}
     */

    Extended.prototype.fpTimer = function () {
        Extended.prototype.fetchPlannerQuery($('.searchNSR'));
    };


    /**
     *  timer FetchPlanner
     *  fires a call to fetchplannerQuery
     *  @return {void}
     */
    Extended.prototype.ElasticTimer = function (element) {
        console.log('elasticTimer 342');
        Extended.prototype.autocomplete(element);
    };


    /**
     *  doneTyping
     *  fires a call to autocomples and fetchplanner
     *  @return {void}
     */
    Extended.prototype.doneTyping = function () {
        console.log('doneTyping 354');
        $('.searchNSR').each(function (index, element) {
            clearTimeout(timerElastic), clearTimeout(timerFetchplanner);
            timerElastic = setTimeout(function () {
                Extended.prototype.ElasticTimer(element);
            }, 300);
        });
        timerFetchplanner = setTimeout(Extended.prototype.fpTimer, 1100);
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
     *
     * @returns {string}
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
     * Initializes the autocomplete functionality
     * @param  {object} element
     * @return {void}
     */
    Extended.prototype.autocomplete = function (element) {
        console.log('autoccomplete 371');
        var $element = $(element);
        var $input = $element.find('input[type="search"]');

        if ($input.val().length < 2) {
            return;
        }

        this.autocompleteQuery(element);
    };


    /**
     * A search has taken place. Push the query GET parameter on to the browser stack
     * @param  {object} element Autocomplete element
     * @param  {string} query The query string
     * @return {void}
     */
    Extended.prototype.pushQueryUrl = function (element, query, post_type) {
        console.log('pushQueryURL 410');
        /*OBS !!!!!! sätt igpng när du e klar if (typeof history != 'undefined') {
            var $currentState = history.state;
            var $state = {query: query, post_type: post_type};
            var $title = 'S&ouml;k - NSR AB';
            var $url = '/sok/?q=' + encodeURIComponent(query);
            if (post_type != '' && post_type != 'all')
                $url += '&post_type=' + encodeURIComponent(post_type);
            history.replaceState($state, $title, $url);
        }*/
    }


    /**
     * Query for autocomplete suggestions
     * @param  {object} element Autocomplete element
     * @return {void}
     */
    Extended.prototype.autocompleteQuery = function (element) {

        var $element = $(element);
        var $input = $element.find('input[type="search"]').val();
        var $post_type = $('#post_type', $element.find('input[type="search"]').parent()).val();

        Extended.prototype.pushQueryUrl($element, $input, $post_type);

        var data = {
            action: 'fetchDataFromElasticSearch',
            query: $input,
            post_type: $post_type,
            level: 'ajax',
            type: 'json'
        };

        Extended.prototype.getJsonData('elastic', $element, data, $post_type);
    };


    /**
     * Query for fetchplanner
     * @param  {object} element fetchplanner element
     * @return {void}
     */
    Extended.prototype.fetchPlannerQuery = function (element) {


        var $element = $(element);
        var $input = $('#searchkeyword-nsr').val();
        var $post_type = $('#post_type', $('#searchkeyword-nsr').parent()).val();
        var fdata = {
            action: 'fetchDataFromFetchPlannerCombined',
            query: $input,
            post_type: $post_type,
            level: 'ajax',
            type: 'json'
        };

        Extended.prototype.getJsonData('fetchplanner', $element, fdata, null);
    };

    /**
     *
     * @param data_type
     * @param $element
     * @param data
     * @param $post_type
     */
    Extended.prototype.getJsonData = function (data_type, $element, data, $post_type) {

        $.ajax({
            url: ajax_object.ajax_url,
            data: data,
            method: 'GET',
            dataType: 'json',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', ajax_object.nonce);
                if ($('.nsr-searchResult .preloader-wrapper').length < 1) {
                    $('.prefix').addClass('nsr-origamiLoader');
                }

                $('#searchkeyword-nsr').removeClass('valid'), $('#searchkeyword-nsr').removeClass('invalid'), $('#searchkeyword-nsr').addClass('waitingForConnection');
            }

        }).complete(function () {
            if (data.action === 'fetchDataFromFetchPlanner' || data.action === 'fetchDataFromFetchPlannerCombined') {
                $('.nsr-searchResult .preloader-wrapper').remove();
            }
        }).done(function (result) {
            this.dataFromSource(data_type, $element, data, $post_type, result);
        }.bind(this));
    };

    /**
     *
     * @param data_type
     * @param $element
     * @param data
     * @param $post_type
     * @param result
     */
    Extended.prototype.dataFromSource = function (data_type, $element, data, $post_type, result) {

        $('#nsr-searchResult').css('display', 'block');
        if (!$('.hero-search').hasClass('searchMenu')) {
            (!$('.searchMenu').find('ul').hasClass('search-nav')) ? $('.searchMenu').append('<ul class="search-nav"><li class="vc_col-sm-3 nsr-elasticSearch-nav active">Sorteringsguiden</li><li class="vc_col-sm-3 nsr-page-nav">Sidor</li><li class="vc_col-sm-3 nsr-fetchplanner-nav">Tömmingsdagar</li></ul>') : '';
        }

        switch (data_type) {
            case 'elastic':
                $relevant['sortguide'] = result.sortguide.length;
                $relevant['content'] = result.content.length;
                relevantCount = 1;
                break;
            case 'fetchplanner':
                $relevant['fetchplanner'] = result.fp.length;
                relevantCount = 2;
                break;
        }

        if (relevantCount == 2) {
            $mostRelevance = $relevant.indexOf(Math.max.apply(window, $relevant));
        }


        if (data.action === 'fetchDataFromElasticSearch') {

            (typeof result.sortguide != 'undefined' && result.sortguide !== null && typeof parent.ga != 'undefined') ? parent.ga('send', 'event', 'SiteSearch', data.action, data.query, result.sortguide.length) : '';
            (typeof result.content != 'undefined' && result.content !== null && typeof parent.ga != 'undefined') ? parent.ga('send', 'event', 'SiteSearch', data.action, data.query, result.content.length) : '';
            this.outputAutocomplete($element, result);

        } else {

            $('.search-fetchPlanner').html('');
            (typeof result.fp != 'undefined' && result.fp !== null && typeof parent.ga != 'undefined') ? parent.ga('send', 'event', 'SiteSearch', data.action, data.query, result.fp.length) : '';
            this.outputFetchPlanner($element, result);
            if ($('#searchkeyword-nsr').hasClass('valid'))
                $('#searchkeyword-nsr').addClass('valid');
        }

        $('.prefix').removeClass('nsr-origamiLoader');

        /* Relevance */
        switch ($mostRelevance) {
            case 'sortguide':
                $('sorteringsguiden-data').addClass('show');
                break;
            case 'content':
                $('.search-autocomplete').addClass('show');
                break;
            case 'fetchplanner':
                $('.earch-fetchPlanner').addClass('show');
                break;
        }
    };


    /**
     * Outputs the autocomplete dropdown
     * @param  {object} element Autocomplete element
     * @param  {array}  res     Autocomplete query result
     * @return {void}
     */
    Extended.prototype.outputAutocomplete = function (element, res) {

        this.sortGuideResult(element, res);
        this.sortPagesResult(element, res);

<<<<<<< HEAD
        if (window.navigator.geolocation) {
            $('.search-autocomplete-data .preloader-wrapper').fadeIn("slow");
            window.navigator.geolocation.getCurrentPosition(Extended.prototype.UserLocation, Extended.prototype.GeoError);
=======
            $fprow += '<h4>Tömningsdagar</h4><table class="fp-table"><tr class="tabDesk"><th colspan="2">Adress</th><th>Nästa tömning</th></tr>';
            $fpMobRow += '<table class="fp-table-mobile">';

            var jsdate = new Date().toISOString().slice(0, 10);
            var dateExp = false;

            $.each(result.fp, function(index, post) {

                var $dub = [];
                var $avfall = '';
                var $weeks = '';
                var $nextDate = '';

                $('#searchkeyword-nsr').removeClass('invalid'), $('#searchkeyword-nsr').addClass('valid');

                if (post.hasOwnProperty('Exec')) {

                    if (post.Exec.AvfallsTyp[0] || post.Exec.AvfallsTyp[1]) {

                        if ($.inArray(false, post.Exec.AvfallsTyp) < 0) {
                            foundRows = true;

                            for (var avint = 0; avint < post.Exec.AvfallsTyp.length; avint++) {
                                if (post.Exec.Datum[avint] >= jsdate) {
                                    if (!$dub.indexOf(post.Exec.AvfallsTyp[avint] + ' ' + post.Exec.Datum[avint]) > -1) {
                                        $dub['avfall'] = post.Exec.AvfallsTyp[avint];
                                        $avfall += '<span class="badge">' + post.Exec.AvfallsTyp[avint] + '</span><br /> ';
                                        //$weeks += post.Exec.DatumWeek[avint] + '<br />';
                                        $dub['nDate'] = post.Exec.AvfallsTyp[avint];
                                        $nextDate += post.Exec.DatumFormaterat[avint] + '<br />';
                                        $dub[avint] = post.Exec.AvfallsTyp[avint] + ' ' + post.Exec.Datum[avint];
                                    }
                                }
                            }

                            $fprow += '<tr id="' + post.id + '" class="tabDesk">';
                            $fprow += '<td class="streetCiy"><strong>' + post.Adress + '</strong>';
                            $fprow += '<div><b class="">' + post.Ort + '</b></div>';

                            // This is how you call iCalendar and PDF generators
                            $fprow += ' <a target="_blank" href="/wp-admin/admin-ajax.php?action=fetchDataFromFetchPlannerCalendar&query='+encodeURIComponent(result.q)+'&level=ajax&type=json&calendar_type=ical&id='+encodeURIComponent(post.id)+'"><h4>ical</h4></a>';
                            $fprow += ' <a target="_blank" href="/wp-admin/admin-ajax.php?action=fetchDataFromFetchPlannerCalendar&query='+encodeURIComponent(result.q)+'&level=ajax&type=json&calendar_type=pdf&id='+encodeURIComponent(post.id)+'"><h4>pdf</h4></a>';

                            $fprow += '</td><td style="padding-top:15px;">';
                            $fprow += $avfall + '</td><td>' + $nextDate;

                            $fpMobRow += '<tr class="fpthmob"><th colspan="2"><i class="material-icons">date_range</i> <span><strong> ' + post.Adress + '</span>, <span>' + post.Ort + '</span></strong></th></tr>';
                            $fpMobRow += '<tr><th>Kärl</th><th>Nästa tömning</th></tr>';
                            $fpMobRow += '<tr><td style="padding-top:15px;">' + $avfall + '</td><td>' + $nextDate + '</td></tr>';
                        }

                        $fprow += '</td></tr>';
                    }
                }

                dateExp = false;

            });
            $fprow += '</table>';
            $fpMobRow += '</table>';
        }

        /* No result ..... */
        if (typeof result.fp != 'undefined' && result.fp !== null) {
            if (result.fp.length === 0 || !foundRows) {
                $fprow = '<h4>Tömningsdagar</h4><br /><p class="noResult">Det blev ingen träff på "' + $('#searchkeyword-nsr').val() + '". Tömningsdagar finns även på <a style="color:#ffffff!important;" href="https://minasidor.nsr.se">minasidor.nsr.se</a></p>';
                $('.search-fetchPlanner').detach().insertAfter(".errorSortguide");
            } else {
                $('.search-fetchPlanner').detach().insertBefore(".errorSortguide");
            }
        } else {
            $('.search-fetchPlanner').detach().insertAfter(".errorSortguide");
>>>>>>> e1e3a9bb640a88f930f16c687f582afef917dc28
        }
        $('#nsr-searchResult').removeClass('hide');
    };


    /**
     *
     * @param element
     * @param res
     */
    Extended.prototype.sortGuideResult = function (element, res) {

        var spinner = Extended.prototype.spinner(Extended.prototype.hashCode('elasticCords'));

        $('#nsr-searchResult').html('<div class="sorteringsguiden-data"></div>');
        $('.sorteringsguiden-data').append('<h4>Sorteringsguiden</h4><div class="left badgeInfo"><span class="badge">P</span> Privat <span class="badge">F</span> Företag<br /></div>');

        if (typeof res.sortguide != 'undefined' && res.sortguide !== null && res.sortguide.length > 0) {

            var tabMobile_frak = null;
            var tabMobile_inl = null;
            var CityItem;
            var cityInt = 0;
            var sortHTML = null;
            if ($('.errorSortguide').is(':visible'))
                $('.errorSortguide').addClass('hide');


            $.each(res.sortguide, function (index, spost) {

                var customerCatIcons = '';
                if (spost.post_meta && spost.post_meta.avfall_kundkategori && spost.post_meta.avfall_kundkategori.length >= 1) {

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
                        } else {
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
                        } else {
                            var fraktion_hemma = spost.post_meta.fraktion_hemma.name;
                        }
                        sortHTML += '<li class="fraktion-icon">' + fraktion_hemma + '</li>';
                        tabMobile_frak += '<li class="fraktion-icon">' + fraktion_hemma + "<li>";
                        sortHTML += '</ul></li>';
                        tabMobile_frak += '</ul></li>';
                    }
                }

                sortHTML += '</td>';
                var braAttVeta = '';
                if (spost.post_meta && spost.post_meta.avfall_bra_att_veta &&
                    spost.post_meta.avfall_bra_att_veta.length >= 1 && typeof spost.post_meta.avfall_bra_att_veta[0] != 'undefined') {
                    braAttVeta = spost.post_meta.avfall_bra_att_veta[0].replace(new RegExp('\r?\n', 'g'), '<br />');
                }

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
                                if (lint > 5)
                                    hideStuff = 'hide';

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
                        sortHTML += '<li class="viewAllInlamning"><a href="/alla-inlamningsstallen/">Visa alla</a></li>';
                    }


                }


                sortHTML += '</ul></td>';
                sortHTML += '</tr>';
                sortHTML += '<tr class="tabMobile"><th class="col s12">Sorteras som:</th><td><ul class="meta-fraktion">' + tabMobile_frak + '</ul></td></tr>';
                sortHTML += '<tr class="tabMobile lastchild"><th class="col s12">Lämnas:</th><td>' + spinner + '<ul class="inlstallen">' + tabMobile_inl + '<li class="viewAllInlamning"><a href="/alla-inlamningsstallen/">Visa alla</a></li></ul></td></tr>';

                tabMobile_frak = "";
                tabMobile_inl = "";

                /*if (spost.post_title.length > 0)
                    nosortGuidedata = true;*/

            });

            $('.sorteringsguiden-data').append(sortHTML);
        }

        /* No result ..... */
        if (typeof res.sortguide != 'undefined' && res.sortguide !== null) {
            if (res.sortguide.length === 0) {
                var sHTML = "";
                sHTML += '<h4>Sorteringsguide</h4><br /><p class="noResult">Det blev ingen träff på "' + $('#searchkeyword-nsr').val() + '". Tipsa oss om avfall som vi kan lägga till här  (<a style="color:#ffffff!important;" href="https://nsr.se/sorteringsguiden">nsr.se/sorteringsguiden</a>)</p>';
                $('.errorSortguide').html(sHTML).removeClass('hide');
            }
        }

        var $metaDataStr = Extended.prototype.metaDataStr('sorteringsguide');

    }


    Extended.prototype.sortPagesResult = function (res) {
        var spinner = Extended.prototype.spinner(Extended.prototype.hashCode('elasticCords'));
        if (typeof res.content != 'undefined' && res.content !== null && res.content.length > 0) {

            if ($('.errorPages').is(':visible'))
                $('.errorPages').addClass('hide');

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
            $('.search-autocomplete-data').prepend('<h4>Sidor på nsr.se</h4>');
        }


        /* No result ..... */
        if (typeof res.content != 'undefined' && res.content !== null) {
            if (res.content.length === 0) {
                var sHTML = "";
                sHTML += '<h4>Sidor på nsr.se</h4><br /><p class="noResult">Ingen träff på "' + $('#searchkeyword-nsr').val() + '".</p>';
                $('.errorPages').html(sHTML).removeClass('hide');
            }
        }
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
        var foundRows = false;

        $('.search-fetchPlanner-data').append('<h4>Tömmningsdagar</h4><table class="fp-table-mobile"></table><table class="fp-table"><tr class="tabDesk"><th colspan="2">Adress</th><th>Nästa tömning</th></tr></table>');

        if (typeof result.fp != 'undefined' && result.fp !== null && result.fp.length > 0) {


            var jsdate = new Date().toISOString().slice(0, 10);
            var dateExp = false;

            $.each(result.fp, function (index, post) {

                var $dub = [];
                var $avfall = '';
                var $weeks = '';
                var $nextDate = '';

                // OUTPUT
                $('#searchkeyword-nsr').removeClass('invalid'), $('#searchkeyword-nsr').addClass('valid');

                if (post.hasOwnProperty('Exec')) {

                    if (post.Exec.AvfallsTyp[0] || post.Exec.AvfallsTyp[1]) {

                        if ($.inArray(false, post.Exec.AvfallsTyp) < 0) {
                            foundRows = true;

                            for (var avint = 0; avint < post.Exec.AvfallsTyp.length; avint++) {
                                if (post.Exec.Datum[avint] >= jsdate) {
                                    if (!$dub.indexOf(post.Exec.AvfallsTyp[avint] + ' ' + post.Exec.Datum[avint]) > -1) {
                                        $dub['avfall'] = post.Exec.AvfallsTyp[avint];
                                        $avfall += '<span class="badge">' + post.Exec.AvfallsTyp[avint] + '</span><br /> ';
                                        //$weeks += post.Exec.DatumWeek[avint] + '<br />';
                                        $dub['nDate'] = post.Exec.AvfallsTyp[avint];
                                        $nextDate += post.Exec.DatumFormaterat[avint] + '<br />';
                                        $dub[avint] = post.Exec.AvfallsTyp[avint] + ' ' + post.Exec.Datum[avint];
                                    }
                                }
                            }

                            $fprow += '<tr id="' + post.id + '" class="tabDesk">';
                            $fprow += '<td class="streetCiy"><strong>' + post.Adress + '</strong>';
                            $fprow += '<div><b class="">' + post.Ort + '</b></div>';
                            $fprow += '</td><td style="padding-top:15px;">';
                            $fprow += $avfall + '</td><td>' + $nextDate;

                            $fpMobRow += '<tr class="fpthmob"><th colspan="2"><i class="material-icons">date_range</i> <span><strong> ' + post.Adress + '</span>, <span>' + post.Ort + '</span></strong></th></tr>';
                            $fpMobRow += '<tr><th>Kärl</th><th>Nästa tömning</th></tr>';
                            $fpMobRow += '<tr><td style="padding-top:15px;">' + $avfall + '</td><td>' + $nextDate + '</td></tr>';
                        }

                        $fprow += '</td></tr>';
                    }
                }

                dateExp = false;

            });
            $fprow += '</table>';
            $fpMobRow += '</table>';
        }

        /* No result ..... */
        if (typeof result.fp != 'undefined' && result.fp !== null) {
            if (result.fp.length === 0 || !foundRows) {
                $fprow = '<h4>Tömningsdagar</h4><br /><p class="noResult">Det blev ingen träff på "' + $('#searchkeyword-nsr').val() + '". Tömningsdagar finns även på <a style="color:#ffffff!important;" href="https://minasidor.nsr.se">minasidor.nsr.se</a></p>';
                $('.search-fetchPlanner').detach().insertAfter(".errorSortguide");
            } else {
                $('.search-fetchPlanner').detach().insertBefore(".errorSortguide");
            }
        } else {
            $('.search-fetchPlanner').detach().insertAfter(".errorSortguide");
        }
        $('.fp-table').append($fprow);
        $('.fp-table-mobile').append($fpMobRow);

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
                $('.search-autocomplete-data .preloader-wrapper').hide();
                console.log('geoLocation: ' + constant);
                break;
            case error.POSITION_UNAVAILABLE:
                constant = "POSITION_UNAVAILABLE";
                $('.search-autocomplete-data .preloader-wrapper').hide();
                console.log('geoLocation: ' + constant);
                break;
            case error.TIMEOUT:
                constant = "TIMEOUT";
                $('.search-autocomplete-data .preloader-wrapper').hide();
                console.log('geoLocation: ' + constant);
                break;
            default:
                constant = "Unrecognized error";
                $('.search-autocomplete-data .preloader-wrapper').hide();
                //console.log('geoLocation: ' + constant);
                break;
        }

    };


    /**
     * Callback function for asynchronous call to HTML5 geolocation
     * @param  {object} position
     * @return {void}
     */
    Extended.prototype.UserLocation = function (position) {
        //console.log(position.coords.latitude + ' : ' + position.coords.longitude);
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
        //console.log(latitude + " : " +longitude)
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
                    if (cities[ind][index]) {
                        var dif = Extended.prototype.getDistanceFromLatLonInKm(latitude, longitude, cities[ind][index][1], cities[ind][index][2]);
                        winners[index] = dif;
                        cities[ind][index][3] = dif;
                    }
                }

                for (index = 0; index < cities[ind].length; ++index) {
                    if (cities[ind][index]) {
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

        $('.search-autocomplete-data .preloader-wrapper').fadeOut("slow");

        return cities[closest];
    };


    /**
     * Get URL param
     * @param  {string} sparam
     * @return {array} param value
     */
    Extended.prototype.getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'),
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
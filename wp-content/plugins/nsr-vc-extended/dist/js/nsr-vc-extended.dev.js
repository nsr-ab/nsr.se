/**
 * Extended script for Visual Composer ad-dons
 *
 * @package NSRVCExtended
 *
 */

/**
 * Monster file handling Search API calls
 * @type {{}|{}}
 */
var VcExtended = VcExtended || {};

/**
 * Extend
 * @type {*|{}}
 */
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
    $relevant['count'] = 0;
    var relevantCount = null;
    var emptyRes = false;
    var searchHits = 0;

    /**
     * Constructor
     */
    function Extended() {
        Extended.prototype.init();
    }

    /**
     *  init
     *  Initializes all the necessary methods and binding stuff to events
     *  Sloppy, yes!
     */
    Extended.prototype.init = function () {

        Extended.prototype.DefaultSiteSearch();
        Extended.prototype.actionAndBindings();

    };

    /**
     * Search navigation
     */
    Extended.prototype.searchNav = function () {
        $('.search-hits').addClass('hide');
        if ($('.searchMenu').hasClass('sortguideMenu')) {
            $('.searchMenu').html('<ul class="search-nav"><li class="vc_col-sm-3 show-ao a-o-trigger">A-Ö</li><li class="vc_col-sm-3 sortguide-nsr-elasticSearch-nav active">Sökresultat <span></span></li></ul>');
        }
        if (!$('.searchMenu').hasClass('sortguideMenu')) {
            $('.searchMenu').html('<ul class="search-nav"><li class="vc_col-sm-3 nsr-elasticSearch-nav active">Sorteringsguiden <span></span></li><li class="vc_col-sm-3 nsr-page-nav">Sidor <span></span></li><li class="vc_col-sm-3 nsr-fetchplanner-nav">Tömningsdagar <span></span></li></ul>');
        }
    };

    /**
     *  DefaultSiteSearch
     *  Search via searchModal window
     */
    Extended.prototype.DefaultSiteSearch = function () {

        var query = Extended.prototype.getUrlParameter('q');

        if (query) {
            $('.breadcrumbs').hide();
            $('#searchkeyword-nsr').focus(), $('#searchkeyword-nsr').val(query.replace(/\+/g, ' '));
            $('.sorteringsguiden-data').html('');
            $('.search-autocomplete-data').html('');
            $('.search-fetchPlanner-data').html('');

            window.clearTimeout(typingTimer);
            Extended.prototype.doneTyping();
            $('.searchWrapper').addClass('searching');
            $('.search-nav li').removeClass('active');
            Extended.prototype.searchNav();
            $relevant['count'] = 0;

        }
    };

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

        return element.each(function () {
            $(element).keypress(function (ev) {
                var keycode = (ev.keyCode ? ev.keyCode : ev.which);
                if (keycode == '13') {
                    //$('.search-autocomplete').remove(), $('.sorteringsguiden').remove();
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
     * Spinner/Loader
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
        if (!$('.searchMenu').hasClass('sortguideMenu')) {
            if (typeof history != 'undefined') {
                var $currentState = history.state;
                var $state = {query: query, post_type: post_type};
                var $title = 'S&ouml;k - NSR AB';
                var $url = '/sok/?q=' + encodeURIComponent(query);
                /*if (post_type != '' && post_type != 'all')
                    $url += '&post_type=' + encodeURIComponent(post_type);*/
                history.replaceState($state, $title, $url);
            }
        }
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
        var $post_type = ''; //$('#post_type', $('#searchkeyword-nsr').parent()).val();
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
     * Special - A-Ö
     * @param letter
     * @constructor
     */
    Extended.prototype.AOQuery = function (param) {

        var $post_type = 'sorteringsguide';
        var alphabet = [];
        switch (param) {
            case 'a-c':
                alphabet = ['a', 'b', 'c'];
                break;
            case 'd-f':
                alphabet = ['d', 'e', 'f'];
                break;
            case 'g-i':
                alphabet = ['g', 'h', 'i'];
                break;
            case 'j-l':
                alphabet = ['j', 'k', 'l'];
                break;
            case 'm-o':
                alphabet = ['m', 'n', 'o'];
                break;
            case 'p-r':
                alphabet = ['p', 'r'];
                break;
            case 's-u':
                alphabet = ['s', 't', 'u'];
                break;
            case 'v-w':
                alphabet = ['v', 'w'];
                break;

            case 'y-ö':
                alphabet = ['y', 'ä', 'ö'];
                break;

        }

        alphabet = alphabet.sort();

        var markup = [];
        for (var int = 0; int < alphabet.length; int++) {

            var data = {
                action: 'fetchDataFromElasticSearch',
                query: alphabet[int] + '*',
		limit: 1000,
                post_type: $post_type,
                level: 'ajax',
                type: 'json'
            };

            var done = false;

            if (int == alphabet.length - 1) {
                done = true;
            }

            var json = this.getJsonDataAO(data, done);
            /*console.log('-- Start debugging');
            console.log(json);
            console.log('-- Stop debugging');*/
            if (json) {
                markup += (typeof (json.responseJSON.sortguide[0].post_title) !== '' && typeof (json.responseJSON.sortguide[0].post_title) !== 'undefined') ?
                    '<h5>' + json.responseJSON.sortguide[0].post_title.charAt(0) + '</h5>' : '';

            }

            markup += '<ul>';

            for (var listInt = 0; listInt < json.responseJSON.sortguide.length; listInt++) {
                if (typeof (json.responseJSON.sortguide[listInt].post_title) !== '' && typeof (json.responseJSON.sortguide[listInt].post_title !== 'undefined')) {
                    markup += '<li>';
		    markup += json.responseJSON.sortguide[listInt].post_title;
		    if (typeof (json.responseJSON.sortguide[listInt].post_meta) !== '' && typeof (json.responseJSON.sortguide[listInt].post_meta) !== 'undefined' &&
			typeof (json.responseJSON.sortguide[listInt].post_meta.fraktion_hemma) !== '' && typeof (json.responseJSON.sortguide[listInt].post_meta.fraktion_hemma) !== 'undefined' &&
			typeof (json.responseJSON.sortguide[listInt].post_meta.fraktion_hemma.name) !== '' && typeof (json.responseJSON.sortguide[listInt].post_meta.fraktion_hemma.name) !== 'undefined' && 
  		        json.responseJSON.sortguide[listInt].post_meta.fraktion_hemma.name) {
			markup += ' - <b>Hemma:</b> ' + json.responseJSON.sortguide[listInt].post_meta.fraktion_hemma.name;
		    }
		    if (typeof (json.responseJSON.sortguide[listInt].post_meta) !== '' && typeof (json.responseJSON.sortguide[listInt].post_meta) !== 'undefined' &&
			typeof (json.responseJSON.sortguide[listInt].post_meta.fraktion_avc) !== '' && typeof (json.responseJSON.sortguide[listInt].post_meta.fraktion_avc) !== 'undefined' &&
			typeof (json.responseJSON.sortguide[listInt].post_meta.fraktion_avc.name) !== '' && typeof (json.responseJSON.sortguide[listInt].post_meta.fraktion_avc.name) !== 'undefined' &&
	 	        json.responseJSON.sortguide[listInt].post_meta.fraktion_avc.name) {
			markup += ' - <b>ÅVC:</b> ' + json.responseJSON.sortguide[listInt].post_meta.fraktion_avc.name;
		    }
		    markup += '</li>';
                }
            }
            markup += '</ul>';
        }

        $('.search-ao-data').html(markup);
        $('.sorteringsguiden').addClass('hide');
        $('.search-autocomplete').addClass('hide');
        $('.search-fetchPlanner').addClass('hide');
        $('.search-ao').removeClass('hide');

    };

    /**
     *  Get data from APi's (A-Ö)
     * @param data
     * @param done
     * @returns {*}
     */
    Extended.prototype.getJsonDataAO = function (data, done) {

        return $.ajax({
            url: ajax_object.ajax_url,
            data: data,
            method: 'GET',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', ajax_object.nonce);

            }
        }).done(function (result) {
            if (done) {
                $('.prefix').removeClass('nsr-origamiLoader');
            }

        }.bind(this));
    };

    /**
     *  Get data from API's
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
                if ($(window).width() > 500) {
                    if ($('.nsr-searchResult .preloader-wrapper').length < 1) {
                        $('.prefix').addClass('nsr-origamiLoader');
                    }
                } else {
                    $('.search-button').addClass('nsr-origamiLoader');
                }

                $('#searchkeyword-nsr').removeClass('valid'), $('#searchkeyword-nsr').removeClass('invalid'), $('#searchkeyword-nsr').addClass('waitingForConnection');
            }

        }).done(function (result) {
            this.dataFromSource(data_type, $element, data, $post_type, result);
        }.bind(this));
    };

    /**
     * Collect data find relevance etc
     * @param data_type
     * @param $element
     * @param data
     * @param $post_type
     * @param result
     */
    Extended.prototype.dataFromSource = function (data_type, $element, data, $post_type, result) {

        if (data.action === 'fetchDataFromElasticSearch') {

            $relevant['sortguide'] = (result.sortguide.length > 0) ? result.sortguide.length : 0;

            if (!$('.searchMenu').hasClass('sortguideMenu')) {
                $relevant['page'] = (result.content.length > 0) ? result.content.length : 0;
            }

            (typeof result.sortguide != 'undefined' && result.sortguide !== null && typeof parent.ga != 'undefined') ? parent.ga('send', 'event', 'SiteSearch', data.action, data.query, result.sortguide.length) : '';
            (typeof result.content != 'undefined' && result.content !== null && typeof parent.ga != 'undefined') ? parent.ga('send', 'event', 'SiteSearch', data.action, data.query, result.content.length) : '';

            this.outputAutocomplete($element, result);
            $relevant['count'] = $relevant['count'] + 2;

            if (!$('.searchMenu').hasClass('sortguideMenu')) {
                if ($relevant['sortguide'] == 0) {
                    $('.search-nav li').removeClass('active');
                    $('.nsr-fetchplanner-nav').addClass('active');
                    $('#nsr-searchResult .sorteringsguiden').addClass('hide');
                    $('#nsr-searchResult .search-fetchPlanner').removeClass('hide');
                } else {
                    $('#nsr-searchResult .search-fetchPlanner').addClass('hide');
                }
            }

            if (typeof result.sortguide != 'undefined' && result.sortguide !== null) {
                $('.sortguideMenu .sortguide-nsr-elasticSearch-nav span').html('('+$relevant['sortguide']+')');
                $('.nsr-elasticSearch-nav span').html('(' + $relevant['sortguide'] + ')');
            }
            if (!$('.searchMenu').hasClass('sortguideMenu')) {
                if (typeof result.content != 'undefined' && result.content !== null)
                    $('.nsr-page-nav span').html('(' + $relevant['page'] + ')');
            }


        } else {
            if (!$('.searchMenu').hasClass('sortguideMenu')) {
                (typeof result.fp != 'undefined' && result.fp !== null && typeof parent.ga != 'undefined') ? parent.ga('send', 'event', 'SiteSearch', data.action, data.query, result.fp.length) : '';

                this.outputFetchPlanner($element, result);
                if (typeof result.fp != 'undefined' && result.fp !== null) {
                    $relevant['fetchplanner'] = (result.fp.length > 0) ? result.fp.length : 0;
                    $('.search-nav .nsr-fetchplanner-nav span').html('(' + $relevant['fetchplanner'] + ')');
                }
                $relevant['count'] = $relevant['count'] + 1;
            }
        }

        if ($('#searchkeyword-nsr').hasClass('valid'))
            $('#searchkeyword-nsr').addClass('valid');

        $('#nsr-searchResult').css('display', 'block');

        if (!$('.searchMenu').hasClass('sortguideMenu')) {
            this.relevance($relevant, $post_type);
        }

        setTimeout(function () {
            $('.prefix').removeClass('nsr-origamiLoader');
            $('.search-button').removeClass('nsr-origamiLoader');
        }, 2000);
    };

    /**
     * relevance
     * @param $relevant
     * @param $post_type
     */
    Extended.prototype.relevance = function ($relevant, $post_type) {
        if ($relevant['count'] === 3) {
            var rel = Math.max($relevant['sortguide'], $relevant['page'], $relevant['fetchplanner']);

            $('.searchView').addClass('hide');

            if ($relevant['sortguide'] === rel) {
                $('#nsr-searchResult').removeClass('transparent-background');
                $('.search-nav li').removeClass('active');
                $('.nsr-elasticSearch-nav').addClass('active');
                $('.sorteringsguiden').removeClass('hide');
                $('.a-o-qview').removeClass('hide');
            }

            if ($relevant['page'] !== $relevant['sortguide']) {
                if ($relevant['page'] === rel) {
                    $('#nsr-searchResult').addClass('transparent-background');
                    $('.search-nav li').removeClass('active');
                    $('.nsr-page-nav').addClass('active');
                    $('.search-autocomplete').removeClass('hide');
                    $('.a-o-qview').addClass('hide');
                }
            }

            if ($relevant['fetchplanner'] !== $relevant['sortguide']) {
                if ($relevant['fetchplanner'] === rel) {
                    $('#nsr-searchResult').removeClass('transparent-background');
                    $('.search-nav li').removeClass('active');
                    $('.nsr-fetchplanner-nav').addClass('active');
                    $('.search-fetchPlanner').removeClass('hide');
                    $('.a-o-qview').addClass('hide');
                }
            }

            if($relevant['sortguide'] === 0 && $relevant['fetchplanner'] === 0 && $relevant['page'] === 0) {
                if($post_type !== null) {
                    console.log($post_type);
                    if ($post_type === 'faq') {
                        $('.nsr-elasticSearch-nav').removeClass('active');
                        $('.sorteringsguiden').addClass('hide');
                        $('.nsr-page-nav').addClass('active');
                        $('.search-autocomplete').removeClass('hide');
                    }
                }
                else {
                    $('.nsr-elasticSearch-nav').removeClass('active');
                    $('.sorteringsguiden').addClass('hide');
                    $('.nsr-fetchplanner-nav').addClass('active');
                    $('.search-fetchPlanner').removeClass('hide');
                }
            }
        }
    };

    /**
     * Sorteringsguide & Pages
     * @param element
     * @param res
     */
    Extended.prototype.outputAutocomplete = function (element, res) {

        var sortGuideData = this.sortGuideResult(element, res);
        if (!$('.searchMenu').hasClass('sortguideMenu')) {
            var sortPagesData = this.sortPagesResult(element, res);
        }

        if (window.navigator.geolocation) {
            $('.search-autocomplete-data .preloader-wrapper').fadeIn("slow");
            window.navigator.geolocation.getCurrentPosition(Extended.prototype.UserLocation, Extended.prototype.GeoError);
        }

        if (sortGuideData) {
            $('.sorteringsguiden-data').append(sortGuideData);
            $('.sorteringsguiden').removeClass('hide');
        }
        if (!$('.searchMenu').hasClass('sortguideMenu')) {
            if (sortPagesData) {
                $('.search-autocomplete-data').append(sortPagesData);
            }
        }

        //Search result menu
        $('#nsr-searchResult').removeClass('hide');

        //Hits
        $('.search-hits').html('Träffar på "' + $('#searchkeyword-nsr').val() + '" <span data-letter="a-c" class="a-o-trigger right-align right hide mobHide a-o-qview">Sorteringsguiden A till Ö</span>');

        if ($('.nsr-elasticSearch-nav').hasClass('active'))
        {
            $('.a-o-qview').removeClass('hide');
            $('.search-hits').removeClass('hide');
        }
    };

    /**
     * Sorteringsguide - Build result
     * @param element
     * @param res
     * @returns {string}
     */
    Extended.prototype.sortGuideResult = function (element, res) {

        var cityInt = 0;

        if (typeof res.sortguide != 'undefined' && res.sortguide !== null && res.sortguide.length > 0) {

            var sortHTML = '';
            var CityItem = [];
            var minMobHack = false;
            var minMob = $(window).width();
            if (minMob < 1005) {
                minMobHack = true;
            }

            if ($('.errorSortguide').is(':visible'))
                $('.errorSortguide').addClass('hide');

            sortHTML += '<table class="sortGuideCompleteInfo">';

            $.each(res.sortguide, function (index, spost) {
                    sortHTML += '<tr class="tabDesk">';
                    sortHTML += '<td class="preSortCell" valign="top">';
                    sortHTML += spost.post_title + ' <i class="hide expand-sortCell material-icons expand-less deskHide">expand_less</i> <i class="expand-sortCell material-icons expand-more deskHide">expand_more</i> </td><td valign="top" class="preSort-frakt">';

                    if (spost.post_meta) {

                        // Fraktioner
                        if (spost.post_meta.fraktion_avc.name != '' && spost.post_meta.fraktion_avc.name != null) {
                            sortHTML += '<ul class="sortAs meta-fraktion">';
                            sortHTML += '<li class="fraktion-icon-avc">';
                            sortHTML += '<b class="deskHideFraction">Återvinningscentral:</b>';

                            if (spost.post_meta.fraktion_avc.link != '') {
                                var fraktion_avc = '<a href="' + spost.post_meta.fraktion_avc.link + '">' +
                                    spost.post_meta.fraktion_avc.name + '</a>';
                            } else {
                                var fraktion_avc = spost.post_meta.fraktion_avc.name;
                            }
                            if (minMobHack)
                                sortHTML += '<div>';
                            sortHTML += fraktion_avc;
                            if (minMobHack)
                                sortHTML += '</div>';
                            sortHTML += '</li></ul>';
                        }

                        if (spost.post_meta.fraktion_hemma.name != '' && spost.post_meta.fraktion_hemma.name != null) {
                            sortHTML += '<ul class="sortAs meta-fraktion">';

                            sortHTML += '<li class="fraktion-icon-home">';
                            sortHTML += '<b class="deskHideFraction">Hemma:</b>';
                            if (spost.post_meta.fraktion_hemma.link != '') {
                                var fraktion_hemma = '<a href="' + spost.post_meta.fraktion_hemma.link + '">' +
                                    spost.post_meta.fraktion_hemma.name + '</a>';
                            } else {
                                var fraktion_hemma = spost.post_meta.fraktion_hemma.name;
                            }
                            if (minMobHack)
                                sortHTML += '<div>';
                            sortHTML += fraktion_hemma;
                            if (minMobHack)
                                sortHTML += '</div>';
                            sortHTML += '</li>';

                            var braAttVeta = '';
                            if (spost.post_meta && spost.post_meta.avfall_bra_att_veta &&
                                spost.post_meta.avfall_bra_att_veta.length >= 1 &&
                                typeof spost.post_meta.avfall_bra_att_veta[0] != 'undefined') {
                                braAttVeta = spost.post_meta.avfall_bra_att_veta[0].replace(new RegExp('\r?\n', 'g'),
                                    '<br />');

                                (braAttVeta) ? sortHTML += '<li class="exnfodispl "><b>Bra att veta: </b><div>' + braAttVeta + '</div></li>' : '';
                            }
                            braAttVeta = '';
                            sortHTML += '</ul>';
                        }
                    }

                    sortHTML += '</td>';
                    sortHTML += Extended.prototype.inStallen(spost.post_meta, CityItem, minMobHack);

                    cities[cityInt] = CityItem;
                    cityInt++;

                    sortHTML += '</ul>';
                    sortHTML += '</td>';
                    sortHTML += '</tr>';

                }
            );

            sortHTML += '</table>';
            return sortHTML;
        }

        /* No result ..... */
        // Needs to be double checked when commit containing new searchbox is pushed to sandbox. 
        if (typeof res.sortguide != 'undefined' && res.sortguide !== null) {
            if (res.sortguide.length === 0) {
                var sHTML = "";
                sHTML += '<div class="no-result"><h4>Sorteringsguide</h4><br /><p class="noResult">Det blev ingen träff på "' + $('#searchkeyword-nsr').val()
                    + '". Tipsa oss om avfall som vi kan lägga till här  (<a href="https://nsr.se/sorteringsguiden">nsr.se/sorteringsguiden</a>)</p></div>';

                $('.sorteringsguiden-data').html(sHTML);

            }
        }
    };

    /**
     * Build markup for inlämningsställen
     * @param postMeta
     * @param CityItem
     * @param view
     * @returns {string}
     */
    Extended.prototype.inStallen = function (postMeta, CityItem, view) {

        var hideStuff = '';
        var sortHTML = '';
        var CityItem = [];
        var minMobHack = false;
        var minMob = $(window).width();

        if (minMob < 1005) {
            minMobHack = true;
        }

        sortHTML += '<td valign="top" class="preSort-inl">';
        sortHTML += '<i class="expand-inl material-icons expand-more">expand_more</i>';
        sortHTML += '<ul class="inlstallen">';

        if (postMeta) {
            if (postMeta.inlamningsstallen && postMeta.inlamningsstallen.length) {

                if (minMobHack)
                    sortHTML += '<li class="deskHide"><h4>Lämnas nära dig:</h4></li>';
                for (var int = 0; int < postMeta.inlamningsstallen.length; int++) {

                    var lint;
                    var inlineClick = '';
                    var inlLink = '';
                    var inLinkClose = '';
                    var latlong = '';
                    var latlongID = '';
                    var searchID = '';
                    var locationmap;
                    var setNonLink = '';

                    for (lint = 0; lint < postMeta.inlamningsstallen[int].length; lint++) {
                        if (lint > 5)
                            hideStuff = 'hide';

                        if (postMeta.inlamningsstallen[int][lint]['pageurl']) {

                            inlLink = '';
                            inLinkClose = '';
                            locationmap = '';

                            if (Extended.prototype.Strpos(postMeta.inlamningsstallen[int][lint]['pageurl'], '?page_id=')) {
                                postMeta.inlamningsstallen[int][lint]['pageurl'] = '';
                                inlLink = '';
                                inLinkClose = '';
                                inlineClick = '';
                                locationmap = '';
                            }

                            if (postMeta.inlamningsstallen[int][lint]['pageurl'] != '') {
                                inlLink = '<a href="' + postMeta.inlamningsstallen[int][lint]['pageurl'] + '">';
                                inLinkClose = '</a>';
                                locationmap = 'locationmap';
                            }

                            if (postMeta.inlamningsstallen[int][lint]['lat'] && postMeta.inlamningsstallen[int][lint]['long']) {
                                if (!postMeta.inlamningsstallen[int][lint]['pageurl']) {
                                    inlineClick = ' data-url="http://maps.google.com?q=' + postMeta.inlamningsstallen[int][lint]['lat'] + ',' + postMeta.inlamningsstallen[int][lint]['long'] + '" ';
                                    locationmap = 'locationmap';
                                }
                            }
                        }

                        if (postMeta.inlamningsstallen[int][lint]['lat'] && postMeta.inlamningsstallen[int][lint]['long']) {
                            latlong = 'data-lat="' + postMeta.inlamningsstallen[int][lint]['lat'] + '" data-long="' + postMeta.inlamningsstallen[int][lint]['long'] + '"';
                            latlongID = Extended.prototype.hashCode('id_' + int + lint + '_' + postMeta.inlamningsstallen[int][lint]['lat'] + postMeta.inlamningsstallen[int][lint]['long']);
                        }

                        if (postMeta.inlamningsstallen[int][lint]['lat'] && postMeta.inlamningsstallen[int][lint]['long'])
                            CityItem[lint] = [postMeta.inlamningsstallen[int][lint]['city'], postMeta.inlamningsstallen[int][lint]['lat'], postMeta.inlamningsstallen[int][lint]['long'], postMeta.inlamningsstallen[int][lint]['city'], latlongID];

                        searchID = latlongID;
                        if (latlongID)
                            latlongID = 'id="' + latlongID + '"';

                        latlongID = '';

                        if (postMeta.inlamningsstallen[int][lint]['city'] != null) {
                            if (!inlLink)
                                setNonLink = 'nullLink';
                            sortHTML += '<li searchid="' + searchID + '" ' + latlongID + ' ' + latlong + ' class="' + setNonLink + ' ' + locationmap + ' ' + hideStuff + '" ' + inlineClick + '> ';
                            sortHTML += inlLink + postMeta.inlamningsstallen[int][lint]['city'] + inLinkClose + '</li>';
                        }
                        nullLink = '';
                        locationmap = '';
                        hideStuff = '';
                        inlineClick = '';
                        latlong = '';
                        latlongID = '';
                    }
                }

                sortHTML += '<li class="viewAllInlamning"><a href="/alla-inlamningsstallen/">Visa alla</a></li>';
            }
            sortHTML += '</td>';
        }
        return sortHTML;
    };

    /**
     *  Build result for pages
     * @param element
     * @param res
     * @returns {string}
     */
    Extended.prototype.sortPagesResult = function (element, res) {

        var pageHTML = '';

        if (typeof res.content != 'undefined' && res.content !== null && res.content.length > 0) {

            $.each(res.content, function (index, post) {
                var $excerpt = post.post_excerpt.replace(/^(.{180}[^\s]*).*/, "$1");
                if ($excerpt)
                    $excerpt = $excerpt + "...";
                var $metaDataStr = Extended.prototype.metaDataStr(post.post_type);

                if (!$metaDataStr['icon'])
                    $metaDataStr['icon'] = "find_in_page";

                pageHTML += '<div class="search-page-result collection cursor-pointer" onClick="window.location.href=\'' + post.guid + '\';"><div class="cursor-pointer site-section">' +
                    $metaDataStr['postSection'] + '</div><a href="' + post.guid + '">' + post.post_title;
                if ($excerpt)
                    pageHTML += '<div class="moreinfo">' + $excerpt + '</div>';
                pageHTML += '</a></div>';

                noContent = true;
            });

            return pageHTML;
        }


        /* No result ..... */
        if (typeof res.content != 'undefined' && res.content !== null) {
            if (res.content.length === 0) {
                var sHTML = "";
                sHTML += '<div class="no-result"><h4>Sidor på nsr.se</h4><br /><p class="noResult">Ingen träff på "' + $('#searchkeyword-nsr').val() + '".</p></div>';
                $('.search-autocomplete-data').html(sHTML).removeClass('hide');
                $('#nsr-searchResult').removeClass('transparent-background');
            }
        }
    };

    /**
     *  Build result for fetchplanner
     * @param element
     * @param result
     */
    Extended.prototype.outputFetchPlanner = function (element, result) {


        var $fprow = '<div class="fp grid ">';
        var foundRows = false;

        if (typeof result.fp != 'undefined' && result.fp !== null && result.fp.length > 0) {

            var jsdate = new Date().toISOString().slice(0, 10);
            var dateExp = false;

            $.each(result.fp, function (index, post) {

                var $dub = [];
                var $avfall = '';
                var $weeks = '';
                var $nextDate = '';
		var $hasAndrad = false;

                $('#searchkeyword-nsr').removeClass('invalid'), $('#searchkeyword-nsr').addClass('valid');

                if (post.hasOwnProperty('Exec')) {

                    var foundRowsInThis = false;
                    for (var avint = 0; avint < post.Exec.AvfallsTyp.length; avint++) {
                        if (avint <= 5) {
                            if (post.Exec.Datum[avint] >= jsdate) {
                                if (!$dub.indexOf(post.Exec.AvfallsTyp[avint] + ' ' + post.Exec.Datum[avint]) > -1) {
                                    foundRows = true;
                                    foundRowsInThis = true;
                                    $dub['avfall'] = post.Exec.AvfallsTyp[avint];
                                    if (post.Exec.AvfallsTyp[avint]) {
                                        var avtyp = post.Exec.AvfallsTyp[avint].toLowerCase();
                                        $avfall += '<span>' + avtyp.charAt(0).toUpperCase() + avtyp.slice(1) + '</span>';
                                    }
                                    $avfall += ' <br /> ';
                                    //$weeks += post.Exec.DatumWeek[avint] + '<br />';
                                    $dub['nDate'] = post.Exec.AvfallsTyp[avint];
                                    $nextDate += '<span>' + post.Exec.DatumFormaterat[avint] + '<span>';
				    if (post.Exec.Andrad[avint]) {
					$hasAndrad = true;
					$nextDate += ' <i class="fas fa-exclamation-circle" style="color: #fd516c;"></i>';
				    }
				    $nextDate += '<br />';
                                    $dub[avint] = post.Exec.AvfallsTyp[avint] + ' ' + post.Exec.Datum[avint];
                                }
                            }
                        }
                    }

                    if (foundRowsInThis) {
                        $fprow += '<div id="' + post.id + '" class="vc_col-sm-12 tabDesk">';

                        $fprow += '<div class="vc_col-sm-8 fp-address">';
                        $fprow += '<i class="expand-avfall material-icons expand-more">expand_more</i>';
                        $fprow += '<span class="fpTopic">' + post.Adress + '</span><br />';
                        $fprow += '<b class="">' + post.Ort + '</b><br /><br />';
                        $fprow += ' <a class="avfall-files-desk" target="_blank" href="/wp-admin/admin-ajax.php?action=fetchDataFromFetchPlannerCalendar&query=' + encodeURIComponent(result.q) + '&level=ajax&type=json&calendar_type=ical&id=' + encodeURIComponent(post.id) + '">Lägg till tömningsdagar i din kalender (1 år)</a>';
                        $fprow += ' <a class="avfall-files-desk" target="_blank" href="/wp-admin/admin-ajax.php?action=fetchDataFromFetchPlannerCalendar&query=' + encodeURIComponent(result.q) + '&level=ajax&type=json&calendar_type=pdf&id=' + encodeURIComponent(post.id) + '">Skriv ut tömningsdagar (1 år)</a>';
			if ($hasAndrad) {
				$fprow += '<div class="ftinfo"><i class="fas fa-exclamation-circle" style="color: #fd516c;"></i> = förändrad tömningsdag. Tömning kan ske en dag tidigare eller senare på grund av helgdag. Ställ ut kärl en dag före ordinarie tömningsdag och låt stå tills kärl är tömt.</div>';
			}
			$fprow += '</div>';
                        $fprow += '<div  class="vc_col-sm-4 avfall hideDetails">';
                        // This is how you call iCalendar and PDF generators
                        $fprow += ' <a class="avfall-files-mob" target="_blank" href="/wp-admin/admin-ajax.php?action=fetchDataFromFetchPlannerCalendar&query=' + encodeURIComponent(result.q) + '&level=ajax&type=json&calendar_type=ical&id=' + encodeURIComponent(post.id) + '">Lägg till tömningsdagar i din kalender (1 år)</a>';
                        $fprow += ' <a class="avfall-files-mob" target="_blank" href="/wp-admin/admin-ajax.php?action=fetchDataFromFetchPlannerCalendar&query=' + encodeURIComponent(result.q) + '&level=ajax&type=json&calendar_type=pdf&id=' + encodeURIComponent(post.id) + '">Skriv ut tömningsdagar (1 år)</a>';
                        $fprow += '<div class="vc_col-sm-6 align-right vc_col-xs-6 bold">' + $avfall + '</div><div class="vc_col-sm-6 vc_col-xs-6">' + $nextDate + '</div>';
                        $fprow += '</div>';

                        $fprow += '</div>';
                    }
                }

                dateExp = false;

            });
        }
        $fprow += '</div>';

        /* No result ..... */
        if (result.fp.length === 0) {
            $fprow = '<div class="no-result"><h4>Tömningsdagar</h4><br /><p class="noResult">Det blev ingen träff på "' + $('#searchkeyword-nsr').val() + '". Tömningsdagar finns även på <a href="https://minasidor.nsr.se">minasidor.nsr.se</a></p></div>';
        }

        $('.search-fetchPlanner-data').html($fprow);
    };

    /**
     * Geo error
     * @param error
     * @constructor
     */
    Extended.prototype.GeoError = function (error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                constant = "PERMISSION_DENIED";
                $('.search-autocomplete-data .preloader-wrapper').hide();
                break;
            case error.POSITION_UNAVAILABLE:
                constant = "POSITION_UNAVAILABLE";
                $('.search-autocomplete-data .preloader-wrapper').hide();
                break;
            case error.TIMEOUT:
                constant = "TIMEOUT";
                $('.search-autocomplete-data .preloader-wrapper').hide();
                break;
            default:
                constant = "Unrecognized error";
                $('.search-autocomplete-data .preloader-wrapper').hide();
                break;
        }
    };

    /**
     * Callback function for asynchronous call to HTML5 geolocation
     * @param position
     * @constructor
     */
    Extended.prototype.UserLocation = function (position) {
        Extended.prototype.NearestCity(position.coords.latitude, position.coords.longitude);
    };

    /**
     * Convert Degress to Radians
     * @param deg
     * @returns {number}
     * @constructor
     */
    Extended.prototype.Deg2Rad = function (deg) {
        return deg * Math.PI / 180;
    };

    /**
     * Calculates with Pythagoras
     * @param lat1
     * @param lon1
     * @param lat2
     * @param lon2
     * @returns {number}
     * @constructor
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
     * Calculates great-circle distances between the two points – that is, the shortest distance over
     * the earth’s surface – using the ‘Haversine’ formula.
     * @param lat1
     * @param lon1
     * @param lat2
     * @param lon2
     * @returns {number}
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

    /**
     * Find closets city
     * @returns {number}
     */
    Extended.prototype.findClosest = function () {
        if (arguments[0] instanceof Array)
            arguments = arguments[0];

        return Math.min.apply(Math, arguments);
    };

    /**
     * Closest location
     * @param latitude
     * @param longitude
     * @returns {*}
     * @constructor
     */
    Extended.prototype.NearestCity = function (latitude, longitude) {

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
     * @param sParam
     * @returns {*}
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


    /**
     * Bindings and different actions - Its a jquery mess !
     * Not the proudest moment...
     */
    Extended.prototype.actionAndBindings = function () {

        if (!$('body').hasClass('wp-admin'))
            $('.card-content').matchHeight();


        $(function () {
            this.CollapsibleHeaders();
        }.bind(this));


        $('body').on('click', '.showAllPosts', function () {
            Extended.prototype.displayMore(this);
        }).bind(this);


        $('body').on('click', '.card', function (e) {
            if ($(this).data('link'))
                window.location.href = $(this).data('link');
        }).bind(this);


        $.fn.enterKey = function (fnc) {
            Extended.prototype.enterTrigger(fnc, this);
        };


        $('.searchNSR').enterKey(function () {

            $('.sorteringsguiden-data').html('');
            $('.search-autocomplete-data').html('');
            $('.search-fetchPlanner-data').html('');

            event.preventDefault();
            window.clearTimeout(typingTimer);
            Extended.prototype.doneTyping();
            $('.searchWrapper').addClass('searching');
            $('.search-nav li').removeClass('active');

            if (!$('.searchMenu').hasClass('sortguideMenu')) {
                $('.a-o-qview').removeClass();
            }
            Extended.prototype.searchNav();
            $relevant['count'] = 0;
        });


        $('.searchNSR form').on('submit', function (e) {

            $('.sorteringsguiden-data').html('');
            $('.search-autocomplete-data').html('');
            $('.search-fetchPlanner-data').html('');

            e.preventDefault();
            window.clearTimeout(typingTimer);
            Extended.prototype.doneTyping();
            $('.searchWrapper').addClass('searching');
            $('.search-nav li').removeClass('active');
            Extended.prototype.searchNav();
            $relevant['count'] = 0;

            if (!$('.searchMenu').hasClass('sortguideMenu')) {
                $('.a-o-qview').removeClass();
            }
            return false;
        }).bind(this);


        $('body').on('click', '.a-o-trigger', function (e) {

            e.preventDefault();
            $('.prefix').addClass('nsr-origamiLoader');
            $('.a-o-trigger').removeClass('active');

            if ($(this).hasClass('show-ao')) {
                if (!$('.searchMenu').hasClass('sortguideMenu')) {
                    $(this).removeClass('active');
                }
                $('.ao-nav li:first-child').addClass('active');
            } else {
                $(this).addClass('active');
            }

            $('.search-ao-data').html('');
            $('.searchWrapper').addClass('searching');
            if (!$('.searchMenu').hasClass('sortguideMenu')) {
                $('.search-nav li').removeClass('active');
            } else {
                $('.show-ao').addClass('active');
            }
            var aoSelect = ($(this).attr('data-letter')) ? $(this).attr('data-letter') : 'a-c';
            Extended.prototype.AOQuery(aoSelect);

        }).bind(this);


        $('.searchNSR').on("input", function () {

            window.clearTimeout(typingTimer);
            if (!wouldBeTimer) {
                $('.searchNSR .search-button').show();
                wouldBeTimer = true;
            }
        });


        $('.searchNSR').on('keydown', function (e) {
            Extended.prototype.haltTimer(e, typingTimer);
        });


        $('body').on('click', '.locationmap', function () {
            if ($(this).data('url'))
                window.open($(this).data('url'), '_blank');
        }).bind(this);


        $('body').on('click', '#searchkeyword-nsr', function () {
            $('#searchkeyword-nsr').focus();
        }).bind(this);


        $('body').on('click', '.nsr-elasticSearch-nav', function () {
            $('#nsr-searchResult').removeClass('transparent-background');
            $('.search-nav li').removeClass('active');
            $(this).addClass('active');
            $('.searchView').addClass('hide');
            $('.sorteringsguiden').removeClass('hide');
            $('.a-o-qview').removeClass('hide');
        }).bind(this);


        $('body').on('click', '.nsr-page-nav', function () {
            $('#nsr-searchResult').addClass('transparent-background');
            $('.search-nav li').removeClass('active');
            $(this).addClass('active');
            $('.searchView').addClass('hide');
            $('.search-autocomplete').removeClass('hide');
            $('.a-o-qview').addClass('hide');
        }).bind(this);


        $('body').on('click', '.nsr-fetchplanner-nav', function () {
            $('#nsr-searchResult').removeClass('transparent-background');
            $('.search-nav li').removeClass('active');
            $(this).addClass('active');
            $('.searchView').addClass('hide');
            $('.search-fetchPlanner').removeClass('hide');
            $('.a-o-qview').addClass('hide');
        }).bind(this);


        $('body').on('click', '.sortguideMenu .show-ao', function () {
            $('.search-nav li').removeClass('active');
            $(this).addClass('active');
        }).bind(this);


        $('body').on('click', '.sortguide-nsr-elasticSearch-nav', function () {
            $('.search-nav li').removeClass('active');
            $(this).addClass('active');
            $('.searchView').addClass('hide');
            $('.sorteringsguiden').removeClass('hide');
        }).bind(this);


        $('body').on('click', '.preSort-inl .material-icons', function () {

            $('.inlstallen li').hide();
            $('.sorteringsguiden-data tr').removeClass('expand-tr');

            if ($(this).hasClass('expand-more')) {

                $('.preSort-inl').find('i').text('expand_more');
                $(this).removeClass('expand-more');
                $(this).addClass('expand-less');
                $(this).text('expand_less');
                $(this).closest('.preSort-inl').find('li').show();
                $(this).closest('tr').addClass('expand-tr');
                $('.inlstallen').each(function () {
                    $(this).find('li:first').show();
                });
            } else {
                $(this).removeClass('expand-less');
                $(this).addClass('expand-more');
                $(this).text('expand_more');
                $(this).closest('.preSort-inl').find('li').hide();


                $('.inlstallen').each(function () {
                    $(this).find('li:first').show();
                });
            }
        }).bind(this);


        $('body').on('click', '.preSortCell .material-icons', function () {

            if (!$(this).closest('.tabDesk').find('.preSort-frakt').hasClass('showPreSorts')) {
                $(this).closest('.tabDesk').find('.expand-less').removeClass('hide');
                $(this).addClass('hide');
                $(this).closest('.tabDesk').find('.preSort-frakt').addClass('showPreSorts');
                $(this).closest('.tabDesk').find('.preSort-inl').addClass('showPreSorts');
                $(this).closest('tr').addClass('expand-tr');
            } else {
                $(this).closest('.tabDesk').find('.expand-more').removeClass('hide');
                $(this).addClass('hide');
                $(this).closest('.tabDesk').find('.preSort-frakt').removeClass('showPreSorts');
                $(this).closest('.tabDesk').find('.preSort-inl').removeClass('showPreSorts');
                $(this).closest('tr').removeClass('expand-tr');
            }

        }).bind(this);


        $('body').on('click', '.fp .tabDesk .material-icons', function () {

            if ($(this).closest('.tabDesk').find('.avfall').hasClass('hideDetails')) {
                $(this).closest('.tabDesk').find('.avfall').removeClass('hideDetails');
                $(this).closest('.tabDesk').addClass('expand-tr');
                $(this).closest('.tabDesk').find('.material-icons').text('expand_less');
            } else {
                $(this).closest('.tabDesk').find('.avfall').addClass('hideDetails');
                $(this).closest('.tabDesk').find('.material-icons').text('expand_more');
                $(this).closest('.tabDesk').removeClass('expand-tr');
            }

        }).bind(this);

        $('.searchDesignation').html($('.searchNSR').attr('data-searchdesignation'));

    };


    return new Extended;

})
(jQuery);

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

VcExtended.NSRExtend = VcExtended.NSRExtend || {};
VcExtended.NSRExtend.Extended = (function ($) {

    var typingTimer;

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


        /* Ad-don Puff med länkar - Visa fler nyheter */
        $('body').on('click', '.showAllPosts', function () {
            event.preventDefault();

            if($(this).closest( "ul" ).find('li').hasClass('hide')) {
                $(this).closest("ul").find('.hide').addClass('show');
                $(this).closest("ul").find('li').removeClass('hide');
                var countItemsHide = $(this).closest("ul").find('li').length-6;
                $(this).closest("ul").find('.showPosts').text('Dölj ('+countItemsHide+')');
            }
            else {
                $(this).closest("ul").find('.show').addClass('hide');
                $(this).closest("ul").find('li').removeClass('show');
                var countItemsShow = $(this).closest("ul").find('li').length-1;
                $(this).closest("ul").find('.showPosts').text('Visa alla ('+countItemsShow+')');
            }
        }).bind(this);

        var timeout = null;

        /* Ad-don searchNSR - Full screen */
        $('body').on('click', '.searchArea *', function (e) {

            if($(e.target).is('i') || $(e.target).is('.search-autocomplete') ){
                e.preventDefault();
                return;
            }
            $('.searchNSR input').focus();
            $('.searchNSR').addClass('fullscreen');
            $('.closeSearch').removeClass('hide');
            event.stopPropagation();

        }).bind(this);

        /* Ad-don searchNSR - Close full screen */
        $('body').on('click', '.closeSearch', function (e) {

            event.stopPropagation();
            $('.searchNSR').removeClass('fullscreen');
            $(this).addClass('hide');
            $('#searchResult').html('');
            $('#searchkeyword-nsr').val('');
            $('.search-autocomplete').remove();
            $('.sorteringsguiden').remove();

        }).bind(this);

        /* Ad-don searchNSR - Enter key function */
        $.fn.enterKey = function (fnc) {

            return this.each(function () {
                $(this).keypress(function (ev) {
                    var keycode = (ev.keyCode ? ev.keyCode : ev.which);
                    if (keycode == '13') {
                        $('.search-autocomplete').remove();
                        $('.sorteringsguiden').remove();
                        fnc.call(this, ev);
                        event.preventDefault();
                        return false;
                    }
                })
            })
        }

        /* Ad-don searchNSR - Hiting Enter on search */
        $('.searchNSR').enterKey(function () {

            $('.searchNSR').append('<div id="searchResult"></div>');
            clearTimeout(timeout);
            var $searchQuery = $(this).val();
            var $post_type = $('#post_type').val();
            timeout = setTimeout(function () {
                Extended.prototype.siteSearch({query: $searchQuery, post_type: $post_type});
            }, 200);

        }).bind(this);

        /* Ad-don searchNSR - Autocomplete */
        $('.search').each(function (index, element) {

            $('#searchResult').html('');
            this.autocomplete(element);

            if ($('.searchNSR').find('input[type="search"]').is(":empty")) {
                $('.search-autocomplete').remove();
                $('.sorteringsguiden').remove();
            }

        }.bind(this));

        /* Ad-don searchNSR - Read more... */
        $('body').on('click', '.read-more', function () {

            var searchQuery = $('.searchNSR').find('input[type="search"]').val();
            var $post_type = $('#post_type').val();
            Extended.prototype.siteSearch({query: searchQuery, post_type: $post_type});

        });

    };


    /**
     *  siteSearch
     *  Ajax phone-call to headquarter - Fetching data.
     *  @param param.query string
     */
    Extended.prototype.siteSearch = function (param) {

        $('#searchResult').html("");
        var query = param.query;
        var $post_type = param.post_type;

        var data = {
            action: 'fetch_data',
            query: param.query + "&post_type" + param.post_type,
            limit: 18
        };

        $.ajax({

            url: ajax_object.ajax_url,
            data: data,
            method: 'GET',
            dataType: 'json',
            beforeSend: function ( xhr ) {
                xhr.setRequestHeader('X-WP-Nonce', ajax_object.nonce);
            }
        }).done(function (result) {
            this.searchOutput(result);
        }.bind(this));


    };


    /**
     *  postIcon
     *  @param post_type string
     */
    Extended.prototype.postIcon = function (post_type) {

        switch (post_type) {

            case "post":
                $icon = 'chat';
                break;

            case "fastighet":
                $icon = 'location_city';
                break;

            case "villa":
                $icon = 'home';
                break;

            case "foretag":
                $icon = 'domain';
                break;

            case "page":
                $icon = 'insert_drive_file';
                break;

            case "faq":
                $icon = 'forum';
                break;

            case "sorteringsguide":
                $icon = 'delete';
                break;

        }
        return $icon;
    };



    /**
     *  searchOutput
     *  Printing data.
     *  @param result string
     */
    Extended.prototype.searchOutput = function (result) {

        var $content = $('<ul class="search-content"></ul>');

        if (typeof result.content != 'undefined' && result.content !== null && result.content.length > 0) {
            $.each(result.content, function (index, post) {
                if(post.post_excerpt) {
                    var $excerpt = post.post_excerpt.replace(/^(.{180}[^\s]*).*/, "$1")+"...";
                }
                else {
                    var $excerpt = '';
                }

                $icon = "find_in_page";

                switch(post.post_type){
                    case 'page':
                        $postSection = 'Sidor';
                        $icon = Extended.prototype.postIcon('page');
                        break;

                    case 'post':
                        $postSection = 'Nyheter';
                        $icon = Extended.prototype.postIcon('post');
                        break;

                    case "fastighet":
                        $postSection = 'Fastighetsägare & Bostadsrättsföreningar';
                        $icon = Extended.prototype.postIcon('fastighet');
                        break;

                    case "villa":
                        $postSection = 'Villa & Fritidsboende';
                        $icon = Extended.prototype.postIcon('villa');
                        break;

                    case "foretag":
                        $postSection = 'Företag & Restauranger';
                        $icon = Extended.prototype.postIcon('foretag');
                        break;

                    default:
                        $postSection = '';
                }

                $content.append('<li class="col s12 m6 l6"> <i class="material-icons"> ' + $icon + '</i><a href="' + post.guid + '"><h5>' + post.post_title + '</h5></a><span class="section right">'+$postSection+'</span><p>'+$excerpt+'</p></li>');
            });
        }

        $('#searchResult').html($content);
        $('.search-autocomplete').remove();
        $('.search-content li').matchHeight();
    };



    /**
     * Initializes the autocomplete functionality
     * @param  {object} element Element
     * @return {void}
     */
    Extended.prototype.autocomplete = function(element) {

        var $element = $(element);
        var $input = $element.find('input[type="search"]');

        $input.on('keydown', function (e) {
            switch (e.which) {
                case 40:
                    this.autocompleteKeyboardNavNext(element);
                    return false;

                case 38:
                    this.autocompleteKeyboardNavPrev(element);
                    return false;

                case 13:
                    return this.autocompleteSubmit(element);
            }

            clearTimeout(typingTimer);

            if ($input.val().length < 2) {
                $element.find('.sorteringsguiden').remove();
                $element.find('.search-autocomplete').remove();
                return;
            }

            typingTimer = setTimeout(function () {
                this.autocompleteQuery(element);
            }.bind(this), 300);
        }.bind(this));

        $(document).on('click', function (e) {
            if (!$(e.target).closest('.search-autocomplete').length) {
                $('.search-autocomplete').remove();
            }
        });

        $input.on('focus', function (e) {
            if ($input.val().length < 2) {
                return;
            }
            // Skapar dubbla queries.... avakta. Kolla imorgon.
            this.autocompleteQuery(element);
        }.bind(this));
    };



    /**
     * Submit autocomplete
     * @param  {object} element Autocomplete element
     * @return {bool}
     */
    Extended.prototype.autocompleteSubmit = function(element) {

        var $element = $(element);
        var $autocomplete = $element.find('.search-autocomplete');
        var $selected = $autocomplete.find('.selected');

        if (!$selected.length) {
            return true;
        }

        var url = $selected.find('a').attr('href');
        location.href = url;

        return false;
    };



    /**
     * Navigate to next autocomplete list item
     * @param  {object} element Autocomplete element
     * @return {void}
     */
    Extended.prototype.autocompleteKeyboardNavNext = function(element) {
        var $element = $(element);
        var $autocomplete = $element.find('.search-autocomplete');

        var $selected = $autocomplete.find('.selected');
        var $next = null;

        if (!$selected.length) {
            $next = $autocomplete.find('li:not(.title):first');
        } else {
            $next = $selected.next('li:not(.title):first');
        }

        if (!$next.length) {
            var $nextUl = $selected.parents('ul').next('ul');
            if ($nextUl.length) {
                $next = $nextUl.find('li:not(.title):first');
            }
        }

        $selected.removeClass('selected');
        $next.addClass('selected');
    };



    /**
     * Navigate to previous autocomplete list item
     * @param  {object} element Autocomplete element
     * @return {void}
     */
    Extended.prototype.autocompleteKeyboardNavPrev = function(element) {
        var $element = $(element);
        var $autocomplete = $element.find('.search-autocomplete');

        var $selected = $autocomplete.find('.selected');
        var $prev = null;

        if (!$selected.length) {
            $prev = $autocomplete.find('li:not(.title):last');
        } else {
            $prev = $selected.prev('li:not(.title)');
        }

        if (!$prev.length) {
            var $prevUl = $selected.parents('ul').prev('ul');
            if ($prevUl.length) {
                $prev = $prevUl.find('li:not(.title):last');
            }
        }

        $selected.removeClass('selected');
        $prev.addClass('selected');
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

        var searchSection = [$post_type, 'sorteringsguide'];

        for(var int=0; int < searchSection.length; int++){

            var logic_type = searchSection[int];

            if(logic_type === 'sorteringsguide')
                $post_type = 'sorteringsguide';

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
            }).done(function (res) {
                if(logic_type === 'sorteringsguide') {
                    $element.find('.sorteringsguiden').remove();
                }
                else {
                    $element.find('.search-autocomplete').remove();
                }

                this.outputAutocomplete(element, res, logic_type);
                $('.search-content').remove();
                logic_type = '';


            }.bind(this));


        }


    };



    /**
     * Outputs the autocomplete dropdown
     * @param  {object} element Autocomplete element
     * @param  {array}  res     Autocomplete query result
     * @return {void}
     */
    Extended.prototype.outputAutocomplete = function(element, res, searchSection) {

        console.log(searchSection); // Debug Onsdag....


        var $element = $(element);
        var $autocomplete = $('<div class="search-autocomplete"><h4>Sidor på nsr.se</h4></div>');
        var $content = $('<ul class="search-autocomplete-content"></ul>');
        var $sorteringsguiden = $('<div class="sorteringsguiden"><h4>Sorteringsguiden</h4></div>');
        var $sortMarkupTable = $('<table class="sorterings-guide-table striped"><tr><th></th><th>Sorteras som</th><th>Lämna nära dig</th><th>Bra att veta</th></tr></table>');

        if (typeof res.content != 'undefined' && res.content !== null && res.content.length > 0) {

            $.each(res.content, function (index, post) {

                var $icon = Extended.prototype.postIcon(post.post_type);
                if(!$icon)
                    var $icon = "find_in_page";
                if (post.is_file) {
                    if(searchSection != "sorteringsguide")
                        $content.append('<li class="col s12 m12 l12"><i class="material-icons"> ' + $icon + '</i><a class="link-item-before" href="' + post.guid + '" target="_blank">' + post.post_title + '</a></li>');
                }
                else {
                    if(searchSection != "sorteringsguide")
                        $content.append('<li class="col s12 m12 l12"> <i class="material-icons"> ' + $icon + '</i><a href="' + post.guid + '">' + post.post_title + '</a></li>');
                    if(searchSection === "sorteringsguide")
                        $sortMarkupTable.append('<tr><td>post.post_avfall</td><td>post.post_sorteras</td><td>post.post_inlamning</td><td>post.post_braveta</td></tr>');
                }
            });
        } else {
            $content = $('');
        }

        if (res.content === null || res.content.length === 0) {
            $autocomplete.append('<ul><li class="search-autocomplete-nothing-found">Inga träffar…</li></ul>');
            return;
        }

        if(searchSection != "sorteringsguide") {
            $content.appendTo($autocomplete);
            $autocomplete.append('<div class="extNfo"><button class="read-more block-level">' + ajax_object.searchAutocomplete.viewAll + '</a></div>');
        }

        if(searchSection === "sorteringsguide") {
            $sortMarkupTable.appendTo($sorteringsguiden);
            $sorteringsguiden.appendTo($element);
        }

        if(searchSection != "sorteringsguide")
            $autocomplete.appendTo($element).show();

        $('.search-autocomplete-content li').matchHeight();

    };

    return new Extended;

})(jQuery);


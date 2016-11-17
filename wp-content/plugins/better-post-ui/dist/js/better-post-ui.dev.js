var BetterPostUi = {};

BetterPostUi = BetterPostUi || {};
BetterPostUi.Components = BetterPostUi.Components || {};

BetterPostUi.Components.Author = (function ($) {

    function Author() {
        // Select author from list
        $('.better-post-ui-author-select li').on('click', function (e) {
            this.setSelected(e.target);
        }.bind(this));

        // Filter list of authors
        $('[name="better-post-ui-author-select-filter"]').on('input', function (e) {
            var query = $(e.target).closest(':input').val();
            this.filterList(query);
        }.bind(this));
    }

    /**
     * Sets a author as selected
     * @param {element} element The element to set selected
     */
    Author.prototype.setSelected = function (element) {
        $('.better-post-ui-author-select li.selected').removeClass('selected');
        $(element).closest('li').addClass('selected');

        $('[name="post_author_override"]').val($(element).closest('li').attr('data-user-id'));
    };

    /**
     * Filters the list of authors
     * @param  {string} query Filter query
     * @return {void}
     */
    Author.prototype.filterList = function(query) {
        if (query === '') {
            $('.better-post-ui-author-select li').show();
            return;
        }

        $('.better-post-ui-author-select li:not(:contains(' + query + '))').hide();
        $('.better-post-ui-author-select li:contains(' + query + ')').show();
    };

    return new Author();

})(jQuery);

jQuery.expr[':'].contains = function(a, i, m) {
    return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
};

BetterPostUi = BetterPostUi || {};
BetterPostUi.Components = BetterPostUi.Components || {};

BetterPostUi.Components.Order = (function ($) {

    function Order() {
        this.init();

        $('[data-action="better-post-ui-order-up"]').on('click', function (e) {
            var li = $(e.target).parents('li').first()[0];
            this.moveUp(li);
        }.bind(this));

        $('[data-action="better-post-ui-order-down"]').on('click', function (e) {
            var li = $(e.target).parents('li').first()[0];
            this.moveDown(li);
        }.bind(this));
    }

    Order.prototype.init = function () {
        $('.better-post-ui-menu-order-list').sortable({
            stop: function (e, ui) {
                BetterPostUi.Components.Order.reindex();
            }
        }).bind(this);
    };

    Order.prototype.moveUp = function(element) {
        var current = $(element);
        current.prev().before(current);
        this.reindex();
    };

    Order.prototype.moveDown = function(element) {
        var current = $(element);
        current.next().after(current);
        this.reindex();
    };

    Order.prototype.reindex = function() {
        $('.better-post-ui-menu-order-list').find('li').each(function (index, element) {
            $(this).find('[name*="menu_order"]').val(index);
        });
    };

    return new Order();

})(jQuery);

BetterPostUi = BetterPostUi || {};
BetterPostUi.Components = BetterPostUi.Components || {};

BetterPostUi.Components.Parent = (function ($) {

    var typingTimer;

    function Parent() {
        $('[data-action="better-post-ui-parent-show-all"]').on('click', function (e) {
            e.preventDefault();
            this.showList();
        }.bind(this));

        $('[data-action="better-post-ui-parent-show-search"]').on('click', function (e) {
            e.preventDefault();
            this.showSearch();
        }.bind(this));

        $('[data-action="better-post-ui-parent-search"]').on('input', function (e) {
            clearTimeout(typingTimer);

            typingTimer = setTimeout(function () {
                this.search($(e.target).closest('[data-action="better-post-ui-parent-search"]').val());
            }.bind(this), 300);
        }.bind(this));

        $(document).on('click', '[data-better-post-ui-set-parent-id]', function (e) {
            var $element = $(e.target).closest('[data-better-post-ui-set-parent-id]');
            var id = $element.attr('data-better-post-ui-set-parent-id');
            var title = $element.text();

            this.setParent(id, title);
        }.bind(this));
    }

    Parent.prototype.showList = function() {
        $('.better-post-ui-parent-search').hide();
        $('.better-post-ui-parent-list').show();
    };

    Parent.prototype.showSearch = function() {
        $('.better-post-ui-parent-list').hide();
        $('.better-post-ui-parent-search').show();
    };

    Parent.prototype.setParent = function(id, title) {
        $('[data-action="better-post-ui-parent-search"]').val('');
        $('.better-post-ui-search-parent-list').remove();

        $('.better-post-ui-parent-list #parent_id').val(id);

        this.showList();
    };

    Parent.prototype.search = function (query, postType) {
        clearTimeout(typingTimer);
        $('.better-post-ui-search-parent-list').remove();

        if (query === '') {
            return;
        }

        $.post(ajaxurl, {action: 'better_post_ui_search_parent', query: query, postType: $('[name="post_type"]').val()}, function (res) {
            clearTimeout(typingTimer);
            $('[data-action="better-post-ui-parent-search"]').after('<ul class="better-post-ui-search-parent-list"></ul>');

            $.each(res, function (index, item) {
                $('.better-post-ui-search-parent-list').append('<li data-better-post-ui-set-parent-id="' + item.ID + '">\
                    ' + item.post_title + '\
                </li>');
            });
        }, 'JSON');
    };

    return new Parent();

})(jQuery);

BetterPostUi = BetterPostUi || {};
BetterPostUi.Components = BetterPostUi.Components || {};

BetterPostUi.Components.Publish = (function ($) {

    function Publish() {
        if ($('#misc-publishing-actions').length === 0) {
            return;
        }

        this.initDatepicker();
    }

    Publish.prototype.initDatepicker = function () {
        $('#aa, #mm, #jj').hide();

        var timestamp_wrap_text = $('.misc-pub-curtime .timestamp-wrap').html();
        timestamp_wrap_text = timestamp_wrap_text.replace(/(,|@)/g, '');
        $('.misc-pub-curtime .timestamp-wrap').html(timestamp_wrap_text);

        $('#hh').before('<span class="municipio-admin-datepicker-time dashicons dashicons-clock"></span>')

        $('#timestampdiv').prepend('<div id="timestamp-datepicker" class="municipio-admin-datepicker"></div>');
        $('#timestamp-datepicker').datepicker({
            firstDay: 1,
            dateFormat: "yy-mm-dd",
            onSelect: function (selectedDate) {
                selectedDate = selectedDate.split('-');

                $('#aa').val(selectedDate[0]);
                $('#mm').val(selectedDate[1]);
                $('#jj').val(selectedDate[2]);
            }
        });

        var initialDate = $('#aa').val() + '-' + $('#mm').val() + '-' + $('#jj').val();
        $('#timestamp-datepicker').datepicker('setDate', initialDate);
    };

    return new Publish();

})(jQuery);

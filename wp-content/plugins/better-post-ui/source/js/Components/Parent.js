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

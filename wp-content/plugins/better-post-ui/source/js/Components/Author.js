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

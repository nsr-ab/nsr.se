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

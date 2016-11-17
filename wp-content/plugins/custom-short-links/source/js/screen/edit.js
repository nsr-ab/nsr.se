CustomShortLinks = CustomShortLinks || {};
CustomShortLinks.Screen = CustomShortLinks.Screen || {};

CustomShortLinks.Screen.Edit = (function ($) {

    var typingTimer = false;
    var isTyping = false;

    function Edit() {
        $(function(){
            this.handleEvents();
        }.bind(this));
    }

    Edit.prototype.handleEvents = function () {
        $('#title').on('keyup', function (e) {
            var val = $(e.target).val();

            val = val.toLowerCase().replace(/\s/g, '-')
                     .replace(/[áåä]/, 'a')
                     .replace(/[ö]/, 'o')
                     .replace(/[^a-zA-Z0-9_-]/g, '');

            $(e.target).val(val);

            if (val.length > 0) {
                $('#edit-slug-box').html('<strong>' + CustomShortLinksVars.shortlink + ':</strong> ' + CustomShortLinksVars.home_url + '/' + val);
            } else {
                $('#edit-slug-box').empty();
            }
        }.bind(this));
    };

    return new Edit();

})(jQuery);

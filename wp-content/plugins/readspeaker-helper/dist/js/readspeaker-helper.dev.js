var ReadSpeakerHelper = {};

ReadSpeakerHelper.Player = (function ($) {

    var rspkrElm;

    var playButtonSelector = '#readspeaker-play-button';
    var playerSelector = '#readspeaker-player-element';

    function Player() {
        jQuery(document).ready(function() {
            ReadSpeaker.q(function() {
                rspkrElm = $rs.get(playButtonSelector);

                rspkr.c.addEvent('onUIClosePlayer', function() {
                    rspkrElm.innerHTML = readspeakerHelper.play;
                    $rs.setAttr(rspkrElm, 'onclick', 'readpage(this.href, "' + playerSelector + '"); return false;');
                    rspkrElm.onclick = new Function('readpage(this.href, "' + playerSelector + '"); return false;');
                    $(rspkrElm).removeClass('readspeaker-is-playing');
                });

                rspkr.c.addEvent('onUIShowPlayer', function() {
                    rspkrElm.innerHTML = readspeakerHelper.stop;
                    $rs.setAttr(rspkrElm, 'onclick', 'rspkr.ui.getActivePlayer().close(); return false;');
                    rspkrElm.onclick = new Function("rspkr.ui.getActivePlayer().close(); return false;");
                    $(rspkrElm).addClass('readspeaker-is-playing');
                });
            });
        });
    }

    return new Player();

})(jQuery);

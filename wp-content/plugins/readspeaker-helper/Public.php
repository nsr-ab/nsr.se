<?php

if (!function_exists('ReadSpeakerHelper_playButton')) {
    function ReadSpeakerHelper_playButton($classes = array())
    {
        do_action('ReadSpeakerHelper/before_the_play_button');
        echo \ReadSpeakerHelper\App::getPlayButton($classes);
    }
}

if (!function_exists('ReadSpeakerHelper_player')) {
    function ReadSpeakerHelper_player()
    {
        do_action('ReadSpeakerHelper/before_the_player_element');
        echo \ReadSpeakerHelper\App::getPlayer();
    }
}

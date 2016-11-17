# ReadSpeaker Helper

**Note: This plugin requires Advanced Custom Fields to be installed.**

A helper which will help you include the ReadSpeaker.com readspeaker solution to you WordPress site.

## Multisite support
The default behaviour for multisites is that the settings is loaded from each and every site on it's own. Use the filter "ReadSpeakerHelper\multisite_load" to set multisite loading to "true" if you want to load the settings from the main blog for all blogs.

## Action reference

#### ReadSpeakerHelper/before_the_readspeaker
    Executes just before the readspeaker element is outputted (not when manually placed)

#### ReadSpeakerHelper/before_the_play_button
    Executes before the play button is outputted (only when manually placed)

#### ReadSpeakerHelper/before_the_player_element
    Executes before the player element is outputted (only when manually placed)

## Filter reference

#### ReadSpeakerPlayer/play_button
    Modify the play button markup

#### ReadSpeakerHelper/play_button_class
    Modify the play button classes

#### ReadSpeakerPlayer/player_element
    Modify the player element markup

#### ReadSpeakerHelper/readspeaker_tag
    Modify the complete readspeaker tag

<?php

namespace Nsr\Theme;

class NSRTemplates
{
    public function __construct()
    {
        add_action('after_setup_theme', array($this, 'addVCPageTemplate'));
    }

    public function addVCPageTemplate()
    {
        /*\Municipio\Helper\Template::add(
            __('Campaign', 'fredriksdal'),
            \Municipio\Helper\Template::locateTemplate('campaign.blade.php')
        );

        */
    }
}


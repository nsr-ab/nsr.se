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
        \Municipio\Helper\Template::add(
            __('Sida utan rubrik', 'NSRTemplates'),
            \Municipio\Helper\Template::locateTemplate('vc-page.blade.php')
        );

    
    }
}


<?php

namespace Municipio\Controller;

class SingleVilla extends \Municipio\Controller\BaseController
{
    public function init()
    {
        global $post;

        $singleSidebar = get_post_meta( $post->ID, 'post_showsidebar', true );
        $this->data['singleSidebar'] = $singleSidebar;

        if($singleSidebar == 0) {
            $contentGridSize = 'grid-xs-12 grid-md-12 grid-sm-12';
            $this->data['contentGridSize'] = $contentGridSize;
        }

    }
}

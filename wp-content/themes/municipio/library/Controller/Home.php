<?php

namespace Municipio\Controller;

class Home extends \Municipio\Controller\BaseController
{
    public function init()
    {
        $this->data['template'] = !empty(get_field('archive_' . sanitize_title(get_post_type()) . '_post_style', 'option')) ? get_field('archive_' . sanitize_title(get_post_type()) . '_post_style', 'option') : 'collapsed';
        $this->data['grid_size'] = !empty(get_field('archive_' . sanitize_title(get_post_type()) . '_grid_columns', 'option')) ? get_field('archive_' . sanitize_title(get_post_type()) . '_grid_columns', 'option') : 'grid-md-6';
    }
}

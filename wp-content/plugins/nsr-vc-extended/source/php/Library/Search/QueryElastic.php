<?php

namespace VcExtended\Library\Search;

class QueryElastic
{
    public static function jsonSearch($data)
    {
        $q = sanitize_text_field($data['query']);
        $limit = isset($data['limit'])  ? $data['limit'] : 9;
        $postStatuses = array('publish', 'inherit');

        $query = new \WP_Query(array(
            'ep_integrate' => true,
            's' => $q,
            //'orderby'       => 'relevance',
            'orderby' => 'post_title',
            'posts_per_page' => $limit,
            'post_status' => $postStatuses,
            'post_type' => \VcExtended\Library\Helper\PostType::getPublic(),
            'cache_results' => false
        ));


        return array(
            'content' => array_slice($query->posts, 0, 10)
        );
    }



}

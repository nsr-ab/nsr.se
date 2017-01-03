<?php

namespace VcExtended\Library\Search;

class QueryElastic
{

    public static function jsonSearch($data)
    {
        $q = sanitize_text_field($data['query']);
        $post_type = sanitize_text_field($data['post_type']);
        $limit = isset($data['limit'])  ? $data['limit'] : 9;
        $postStatuses = array('publish', 'inherit');

        $q =  \VcExtended\Library\Search\ElasticSearch::filterQuery(
            trim($q)
        );

        if($post_type === "" || $post_type === "all") {
            $post_types = \VcExtended\Library\Helper\PostType::getPublic();
        }
        else {
            $post_types = array(0 => $post_type);
        }

        $query = new \WP_Query(array(
            'ep_integrate' => true,
            's' => $q,

            'simple_query_string' => array(
                    'fields' => array('post_title^7', 'post_content^3'),
                    'query' => $q . '~'.\VcExtended\Library\Search\ElasticSearch::fuzzynessSize($q),
                    'analyzer' => 'elasticpress_synonyms'
            ),

            'orderby'       => 'relevance',
            'posts_per_page' => $limit,
            'post_status' => $postStatuses,
            'post_type' => $post_types,
            'cache_results' => false
        ));


        return array(
            'content' => array_slice($query->posts, 0, 10)
        );
    }
}

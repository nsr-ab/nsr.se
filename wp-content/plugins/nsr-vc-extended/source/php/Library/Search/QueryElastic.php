<?php

namespace VcExtended\Library\Search;

class QueryElastic
{

    public static function jsonSearch($data)
    {
        $q = sanitize_text_field($data['query']);
        $limit = isset($data['limit'])  ? $data['limit'] : 9;
        $postStatuses = array('publish', 'inherit');

        $q =  \VcExtended\Library\Search\ElasticSearch::filterQuery(
            trim($q)
        );

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
            'post_type' => \VcExtended\Library\Helper\PostType::getPublic(),
            'cache_results' => false
        ));


        return array(
            'content' => array_slice($query->posts, 0, 10)
        );
    }

}

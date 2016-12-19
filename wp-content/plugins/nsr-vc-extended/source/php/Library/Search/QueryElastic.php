<?php

namespace VcExtended\Library\Search;

class QueryElastic
{
    public static function jsonSearch($data)
    {
        $q = sanitize_text_field($data['query']);
        $limit = isset($data['limit'])  ? $data['limit'] : 9;
        $postStatuses = array('publish', 'inherit');

        $q =  self::filterQuery(
            trim($q)
        );

        $query = new \WP_Query(array(
            'ep_integrate' => true,
            's' => $q,

            'simple_query_string' => array(
                    'fields' => array('post_title^7', 'post_content^3'),
                    'query' => $q . '~'.self::fuzzynessSize($q),
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


    /**
     * Fuzzy search
     * @param  string query
     * @return $max_fuzzyness, $min_fuzzyness, $string_lengt
     */
    public function fuzzynessSize($query = '')
    {
        $max_fuzzyness = 4;
        $min_fuzzyness = 1;
        $division_by = 3;

        if (strlen($query) === $division_by) {
            return (string) '0';
        }

        if ($string_lengt = floor(strlen($query)/$division_by)) {
            if ($string_lengt >= $max_fuzzyness) {
                return (string) $max_fuzzyness;
            }

            if ($string_lengt <= $min_fuzzyness) {
                return (string) $min_fuzzyness;
            }

            return (string) $string_lengt;
        }

        return '0';
    }


    /**
     * Filter
     * @param $query
     * @return $q
     */
    public function filterQuery($query = "")
    {
        $q = explode(" ", $query);

        if (is_array($q) && !empty($q)) {
            foreach ($q as $key => $value) {
                if (mb_strlen($value) <= 2) {
                    unset($q[$key]);
                }
            }
        }

        if (is_array($q)) {
            return trim(implode(" ", $q));
        }

        return $q;
    }

}

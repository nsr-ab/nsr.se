<?php

namespace VcExtended\Library\Search;

class QueryElastic
{


    /**
     * Setup synonym mapping for elasticpress
     * @param  array $mapping
     * @return array
     */
     /* FREDRIK: This should not go here
    public function elasticPressSynonymMapping($mapping)
    {
        if (!$mapping) {
            return $mapping;
        }

        if (!isset($mapping['settings']['analysis']['filter']) || !isset($mapping['settings']['analysis']['analyzer'])) {
            return $mapping;
        }

        $synonyms = (array)get_field('elasticpress_synonyms', 'options');

        if (!$synonyms || empty($synonyms)) {
            return $mapping;
        }

        $synonymData = array();
        foreach ($synonyms as $synonym) {
            $data = explode(',', $synonym['words']);
            $data = array_map('trim', $data);
            $data = implode(',', $data);

            $synonymData[] = $data;
        }

        $mapping['settings']['analysis']['filter']['elasticpress_synonyms_filter'] = array(
            'type' => 'synonym',
            'synonyms' => $synonymData
        );

        $mapping['settings']['analysis']['analyzer']['elasticpress_synonyms'] = array(
            'tokenizer' => 'standard',
            'filter' => array(
                'lowercase',
                'elasticpress_synonyms_filter'
            )
        );

        print_r($mapping);exit;

        return $mapping;
    }
    */

  

    public static function jsonSearch($data)
    {
        /*
        FREDRIK: This will not be called anyway and can be removed
        function themeslug_deactivate_ep_fuzziness( $fuzz ) {
            return 0;
        }
        add_filter('ep_fuzziness_arg', 'themeslug_deactivate_ep_fuzziness');
        add_filter('ep_config_mapping', array($this, 'elasticPressSynonymMapping'));
        */
        $q = sanitize_text_field($data['query']);
        $post_type = sanitize_text_field($data['post_type']);
        //$post_section = sanitize_text_field($data['post_section']);
        $limit = isset($data['limit']) ? $data['limit'] : 9;
        $postStatuses = array('publish', 'inherit');

        // If only searching for calendar, return empty response
        if ($post_type == "tomningskalender") {
            return array('sortguide'=>array(), 'content'=>array());
        }

        $q = \VcExtended\Library\Search\ElasticSearch::filterQuery(trim($q));

        if ($post_type === "" || $post_type === "all") {
            $post_types = \VcExtended\Library\Helper\PostType::getPublic();
        } else {
            $post_types = array(0 => $post_type);
        }

        $q = mb_strtolower(trim($q));
        $q = preg_replace("/\s+/", " ", $q);
        $titlesort = false;

        if (mb_strlen($q) == 2 && mb_substr($q, 1, 1) == "*") {
            $rq = "";
            $titlesort = true;
        }
        else if (mb_strlen($q) <= 3) {
            $rq = $q;
        }
        else {
            $words = explode(" ", $q);
            $rq = array();
            foreach ($words as $w) {
                if (!preg_match("/^[0-9\.,_\-%]+\$/", $w) && mb_strlen($w) > 2)
                    $rq[] = $w;
            }
            $rq = implode(" ", $rq);
        }

        $querySortGuide = new \WP_Query(array(
            'ep_integrate' => true,
            's' => $q,
            'fuzziness'=>0,
            'posts_per_page' => $titlesort ? 100 : 20,
            'post_type' => 'sorteringsguide',
            'cache_results' => false
        ));

        $sortGuidePosts = $querySortGuide->posts;

        if (count($querySortGuide) < 8 && !$titlesort) {
            $querySortGuideFuzzy = new \WP_Query(array(
                'ep_integrate' => true,
                's' => $q,
                'fuzziness'=>4,
                'posts_per_page' => 20,
                'post_type' => 'sorteringsguide',
                'cache_results' => false
            ));

            $sortGuidePosts = \VcExtended\Library\Search\ElasticSearch::mergeResults($sortGuidePosts, $querySortGuideFuzzy->posts);
        }

        if ($titlesort) {
            $sortGuidePosts = \VcExtended\Library\Search\ElasticSearch::sortByTitle($sortGuidePosts, $q);
        }

        $contentPosts = array();
        if ($post_type != "sorteringsguide") {
            $query = new \WP_Query(array(
                'ep_integrate' => true,
                's' => $q,
                'fuzziness'=>4,
                'orderby' => 'relevance',
                'posts_per_page' => $limit,
                'post_status' => $postStatuses,
                'post_type' => str_replace("sorteringsguide", "", $post_types),
                'cache_results' => false
            ));
            $contentPosts = $query->posts;
        }

        if ($rq != "") {
            $rquery = new \WP_Query(array(
                'ep_integrate' => true,
                's' => $rq,
                'fuzziness'=>1,
                'orderby' => 'relevance',
                'posts_per_page' => 5,
                'post_status' => $postStatuses,
                'cache_results' => false
            ));

            if (!count($rquery->posts)) {
                return array('sortguide'=>array(), 'content'=>array());
            }
        }
        
        if ($post_type == "faq") {
            return array(
                //'rq'=>$rq,
                //'rcontent' => array_slice($rquery->posts, 0, 10),
                'content' => array_slice($contentPosts, 0, $limit),
                'sortguide' => array(),
            );
        }

        return array(
            //'rq'=>$rq,
            //'rcontent' => array_slice($rquery->posts, 0, 10),
            'content' => array_slice($contentPosts, 0, $limit),
            'sortguide' => array_slice($sortGuidePosts, 0, $limit),
         );


    }
}

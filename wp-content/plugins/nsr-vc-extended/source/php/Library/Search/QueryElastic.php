<?php

namespace VcExtended\Library\Search;

class QueryElastic
{


    /**
     * Setup synonym mapping for elasticpress
     * @param  array $mapping
     * @return array
     */
    public function elasticPressSynonymMapping($mapping)
    {
        if (!$mapping) {
            return $mapping;
        }

        if (!isset($mapping['settings']['analysis']['filter']) || !isset($mapping['settings']['analysis']['analyzer'])) {
            return $mapping;
        }

        $synonyms = (array) get_field('elasticpress_synonyms', 'options');

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

        return $mapping;
    }




    public static function jsonSearch($data)
    {

        $q = sanitize_text_field($data['query']);
        $post_type = sanitize_text_field($data['post_type']);
        //$post_section = sanitize_text_field($data['post_section']);
        $limit = isset($data['limit'])  ? $data['limit'] : 9;
        $postStatuses = array('publish', 'inherit');

        $q =  \VcExtended\Library\Search\ElasticSearch::filterQuery(trim($q));

        if($post_type === "" || $post_type === "all") {
            $post_types = \VcExtended\Library\Helper\PostType::getPublic();
        }
        else {
            $post_types = array(0 => $post_type);
        }


        $querySortGuide = new \WP_Query(array(
            'ep_integrate' => true,
            's' => $q,
            /*
            'search_fields'  => array(
                'post_title',
                'meta' => array(
                    'avfall_synonymer',
                    'avfall_synonymer_0',
                    'avfall_synonymer',
                    'avfall_synonymer_1',
                    'avfall_synonymer',
                    'avfall_synonymer_1',
                    'avfall_synonymer',
                    'avfall_synonymer_2',
                    'avfall_synonymer_0_avfall_synonym',
                    'avfall_synonymer_1_avfall_synonym',
                    'avfall_synonymer_2_avfall_synonym',
                    'avfall_synonymer_3_avfall_synonym',
                    'avfall_synonymer_4_avfall_synonym',
                    'avfall_synonymer_5_avfall_synonym',
                    'avfall_synonymer_6_avfall_synonym',
                    'avfall_synonymer_7_avfall_synonym',
                ),
            ),
            */

            'simple_query_string' => array(
                'search_fields' => array('post_title^7','post_meta' => array( 'avfall_synonymer','avfall_bra_att_veta') ),
                'query' => $q . '~' . \VcExtended\Library\Search\ElasticSearch::fuzzynessSize($q),
                'analyzer' => 'elasticpress_synonyms'
            ),

            'orderby' => 'relevance',
            'posts_per_page' => 15,
            'post_type' => 'sorteringsguide',
            'cache_results' => false
        ));


        $query = new \WP_Query(array(
            'ep_integrate' => true,
            's' => $q,

            'simple_query_string' => array(
                'fields' => array('post_title^7', 'post_content^3', 'taxonomies^3'),
                'query' => $q . '~' . \VcExtended\Library\Search\ElasticSearch::fuzzynessSize($q),
                'analyzer' => 'elasticpress_synonyms'
            ),

            'orderby' => 'relevance',
            'posts_per_page' => $limit,
            'post_status' => $postStatuses,
            'post_type' => str_replace("sorteringsguide","",$post_types),
            'cache_results' => false
        ));

        return array(
            'content' => array_slice($query->posts, 0, 10),
            'sortguide' => array_slice($querySortGuide->posts, 0, 10),
        );



    }
}

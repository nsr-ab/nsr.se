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

        $q = \VcExtended\Library\Search\ElasticSearch::filterQuery(trim($q));

        if ($post_type === "" || $post_type === "all") {
            $post_types = \VcExtended\Library\Helper\PostType::getPublic();
        } else {
            $post_types = array(0 => $post_type);
        }

        $querySortGuide = new \WP_Query(array(
            'ep_integrate' => true,
            's' => $q,
            'fuzziness'=>0,
            'posts_per_page' => 20,
            'post_type' => 'sorteringsguide',
            'cache_results' => false
        ));

        $sortGuidePosts = $querySortGuide->posts;
        if (count($querySortGuide) < 8) {
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

        /*
        $querySortGuide = new \WP_Query(array(
            'ep_integrate' => true,
            's' => $q,
            'query' => array(
                'bool' => array(
                    // Match keywords
                    'must' => array(
                        array(
                            'multi_match' => array(
                                'query' => $q,
                                'fuzziness' => \VcExtended\Library\Search\ElasticSearch::fuzzynessSize($q, 12),
                                'fields' => array(
                                    'post_title^7',
                                    'post_content^3',
                                    'terms.post_tag.name^4'
                                )
                            )
                        )
                    ),
                    // Match full query
                    'should' => array(
                        array(
                            'multi_match' => array(
                                'query' => $q,
                                'fields' => array(
                                    'post_title^7',
                                    'post_content^3'
                                ),
                                'type' => 'phrase'
                            )
                        ),
                    )
                )
            ),
            'fuzziness' => \VcExtended\Library\Search\ElasticSearch::fuzzynessSize($q, 12),
            'fields' => array(
                'post_title^7',
                'postmeta.meta_value',
                'meta' => array('synonym', '_synonym'),
                'analyzer' => 'elasticpress_synonyms'
            ),

            'posts_per_page' => 100,
            'post_type' => 'sorteringsguide',
            'cache_results' => false
        ));
        */


        //print_r($querySortGuide);
        //exit;
/*
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
            'post_type' => str_replace("sorteringsguide", "", $post_types),
            'cache_results' => false
        ));
*/

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

        return array(
            'content' => array_slice($query->posts, 0, 10),
            'sortguide' => array_slice($sortGuidePosts, 0, 10),
         );


    }
}

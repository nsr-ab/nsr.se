<?php

/**
 * MasterVCExtended ad-don for Visual Composer
 *
 * @package NSRVCExtended
 *
 * Author: Johan Silvergrund
 * Company: HIQ

 * -- MasterVCExtended --
 * A Visual composer ad-don to create lists of news topics etc.
 *
 */

namespace VcExtended\Library;

class MasterVCExtended
{

    /**
     * Merge arrays
     * @param array
     * @return array
     */
    public function mergeParams($params){

        $term_cat = explode(',', $params[0]);
        $term_tax = explode(',', $params[1]);
        $term_prepare = array_merge($term_cat, $term_tax);
        $term_data = ltrim(rtrim(implode(',', $term_prepare), ','),',');

        return $term_data;
    }



    /**
     * Query Database and fetch data
     * @param array
     * @return string
     */
    public function fetchDataFromDB(array $params){

        global $wpdb;

        if(isset($params['categories']) && isset($params['tags'])) {
            $term_data = $this->mergeParams($termtax = array( $params['categories'], $params['tags'] ) );
        }
        else {
            $term_data = (isset($params['categories'])) ? $params['categories'] : '';
            $term_data .=  (isset($params['tags'])) ? $params['tags'] : '';
        }


        /* Query */
        $sql = "SELECT $wpdb->posts.*
                FROM $wpdb->posts  ";

        if($term_data) {
            $sql .= "INNER JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id) 
                         WHERE $wpdb->posts.post_status = 'publish'             
                         AND ( $wpdb->term_relationships.term_taxonomy_id IN (" . $term_data . ") ) ";
        }
        else {
            $sql .= " WHERE $wpdb->posts.post_status = 'publish'  ";
        }

        $sql .= isset($params['author']) ? "AND $wpdb->posts.post_author = ".$params['authors']." " : null;

        if($params['post_type'] === "post,page"){
            $sql .= isset($params['post_type']) ? " AND ( $wpdb->posts.post_type = 'post' OR  $wpdb->posts.post_type = 'page' )" : null;
        }
        else {
            $sql .= isset($params['post_type']) ? " AND $wpdb->posts.post_type = '" . $params['post_type'] . "' " : null;
        }

        $sql .= isset($params['order_by']) ? " ORDER BY '$wpdb->posts.post_" . $params['order_by'] . "' " : null;
        $sql .= isset($params['order']) ? " ".$params['order'] : null;

        if ( isset($params['size']) ) {
            $sql .= isset($params['size']) ? " LIMIT " : null;
            $sql .= isset($params['vc_startfrom']) ? " " . $params['vc_startfrom'] . ", " : null;
            $sql .= isset($params['size']) ? " " . $params['size'] . "" : null;
        }

        return $this->renderMarkup($wpdb->get_results($sql), $param = (object) $params);

    }



    /**
     * Preparing filter for DB Query
     * @param array
     * @return string
     */
    public function pairParams($query)
    {

        $pairs = explode('|', $query);
        foreach ($pairs as $pair) {

            $pair = explode(':', $pair, 2);

            if($pair[0] === 'order_by')
                $params['order_by'] = $pair[1];

            if($pair[0] === 'order')
                $params['order'] = $pair[1];

            if($pair[0] === 'size')
                $params['size'] = $pair[1];

            if($pair[0] === 'post_type')
                $params['post_type'] = $pair[1];

            if($pair[0] === 'author')
                $params['author'] = $pair[1];

            if($pair[0] === 'categories')
                $params['categories'] = $pair[1];

            if($pair[0] === 'tags')
                $params['tags'] = $pair[1];

        }

        return $params;

    }


}
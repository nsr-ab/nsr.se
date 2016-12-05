<?php

/**
 * ListPagesWithThumbnail ad-don for Visual Composer
 *
 * @package NSRVCExtended
 *
 * Author: Johan Silvergrund
 * Company: HIQ

 * -- ListPagesWithThumbnail --
 * A Visual composer ad-don to create lists of news topics etc.
 *
 */

namespace VcExtended\Library;

class ListLinksWithThumbnail
{

    function __construct()
    {
        add_action('init', array($this, 'integrateWithVC'));
        add_shortcode('nsr_list_with_thumb', array($this, 'renderExtend'));

        add_image_size( 'nsr-rect-front-size', 991, 264 );

    }


    /**
     * Available parameters
     * @return array
     */
    static function params()
    {
        return array(

            /** @Param Designation parameter */
            array(
                'admin_label' => true,
                'type'      => 'textfield',
                'heading' => __('Designation', 'nsr-vc-extended'),
                'param_name' => 'vc_designation',
                'edit_field_class' => 'vc_col-sm-9 vc_col-md-9',
                'description' => __('Visible in admin (only!)', 'nsr-vc-extended'),

            ),

            /** @Param Image component parameter */
            array(

                'admin_label' => false,
                'type'      => 'attach_image',
                'heading' => __('Image', 'nsr-vc-extended'),
                'param_name' => 'vc_image',
                'edit_field_class' => 'vc_col-sm-4 vc_col-md-4',
                'description' => __('Bilder som är 991px x 264px.', 'nsr-vc-extended'),

            ),


            /** @Param Color picker component for border color */
            array(
                'admin_label' => false,
                'type' => 'colorpicker',
                'holder' => 'div',
                'class' => 'vc_extend_border_colors',
                'edit_field_class' => 'vc_col-sm-2 vc_col-md-2',
                'heading' => __('Top border color', 'nsr-vc-extended'),
                'param_name' => 'vc_border_colors',
                'dependency' => array('element' => 'color'),

            ),


            array(
                'admin_label' => false,
                'type' => 'vc_link',
                'holder' => 'div',
                'class' => 'vc_pagelink',
                'edit_field_class' => 'vc_col-sm-8 vc_col-md-8',
                'heading' => __('Heading with link', 'nsr-vc-extended'),
                'param_name' => 'vc_pagelink',

            ),

            /** @Param Post loop parameter */
            array(
                'admin_label' => false,
                'type' => 'loop',
                'heading' => __('Select your post & categories', 'nsr-vc-extended'),
                'param_name' => 'vc_loop',
                'edit_field_class' => 'vc_col-sm-12 vc_col-md-12',
            ),
        );
    }


    /**
     * Mapping Ad-don
     * @return void
     */
    public function integrateWithVC()
    {

        vc_map(array(

                'name' => __('List links with thumbnail', 'nsr-vc-extended'),
                'description' => __('List links, post, pages and add Thumbnail', 'nsr-vc-extended'),
                'base' => 'nsr_list_with_thumb',
                "content_element" => true,
                'class' => 'vc_extended ',
                'show_settings_on_create' => true,
                "is_container" => true,
                'admin_label' => false,
                'controls' => 'full',
                'icon' => 'vc_general vc_element-icon icon-wpb-ui-accordion',
                'category' => __('NSR', 'js_composer'),
                'admin_enqueue_css' => array( plugins_url( 'nsr-vc-extended/dist/css/nsr-vc-extended-admin.min.css' ) ),
                'params' => $this->params()
            )
        );
    }



    /**
     * Logic behind Ad-don output
     * @param array
     * @return string
     */
    public function renderExtend(array $atts)
    {

        $params['vc_image'] = isset($atts['vc_image']) ? $postdate = $atts['vc_image'] : null;
        $params['vc_pagelink'] = isset($atts['vc_pagelink']) ? $atts['vc_pagelink'] : null;
        $params['vc_border_colors'] = isset($atts['vc_border_colors']) ? $atts['vc_border_colors'] : null;

        if ( isset( $atts['vc_loop'] ) && ! empty( $atts['vc_loop'] ) ) {
            $query = $atts['vc_loop'];
        }

        if ( isset( $query ) ) {

            $pairs = explode('|', $query);

            foreach ($pairs as $pair) {

                $pair = explode(':', $pair, 2);
                $params['order_by'] = ($pair[0] === 'order_by') ? $pair[1] : null;
                $params['size'] = ($pair[0] === 'size') ? $pair[1] : null;
                $params['order'] = ($pair[0] === 'order') ? $pair[1] : null;
                $params['post_type'] = ($pair[0] === 'post_type') ? $pair[1] : null;
                $params['author'] = ($pair[0] === 'author') ? $pair[1] : null;
                $params['categories'] = ($pair[0] === 'categories') ? $pair[1] : null;
                $params['tags'] = ($pair[0] === 'tags') ? $pair[1] : null;
                $params['tax_query'] = ($pair[0] === 'tax_query') ? $pair[1] : null;
            }

            return $this->fetchPostData($params);
        }
    }


    /**
     * Query db and posts get result
     * @param array
     * @return string
     */
    private function fetchPostData(array $params)
    {

        $term_data = $this->mergeParams($ermtax = array($params['categories'], $params['tags']));

        global $wpdb;

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

        $sql .= $params['author'] ? "AND $wpdb->posts.post_author = ".$params['authors']." " : null;
        $sql .= $params['post_type'] ? " AND $wpdb->posts.post_type = '" . $params['post_type'] . "' " : null;
        $sql .= $params['order_by'] ? " ORDER BY '$wpdb->posts.post_" . $params['order_by'] . "' " : null;
        $sql .= $params['order'] ? $params['order'] : null;
        $sql .= $params['size'] ? " LIMIT " . $params['size'] . " " : null;

        return $this->renderMarkup($wpdb->get_results($sql), $param = (object) $params);

    }



    /**
     * Merge arrays
     * @param array
     * @return array
     */
    public function mergeParams($params)
    {

        $term_cat = explode(',', $params[0]);
        $term_tax = explode(',', $params[1]);
        $term_prepare = array_merge($term_cat, $term_tax);
        $term_data = ltrim(rtrim(implode(',', $term_prepare), ','),',');

        return $term_data;
    }



    /**
     * loop object and render markup
     * @param object $get_results
     * @param array $params
     * @return string
     */
    public function renderMarkup($get_results, $params)
    {

        $vc_border_colors = isset($params->vc_border_colors)  ? " style=\"border-top:2px solid ".$params->vc_border_colors .";\" " : null;

        $output = "<div id=\"vc_id_".md5(date('YmdHis').rand(0,9999999))."\" ". $vc_border_colors ." class=\"card hoverable small\" >";
        $output .= "<div class=\"card-image\">";
        $output .= wp_get_attachment_image( $params->vc_image, $size = 'nsr-rect-front-size', $icon = false, $attr = '' );
        $output .= "</div>";
        $output .= "<div class=\"card-content\">";

        if(isset($params->vc_pagelink)) {
            $href = vc_build_link($params->vc_pagelink);
            $output .= "<h4><a  href=\"".$href['url']."\">".$href['title']."</a></h4>";
        }

        $output .= "<ul id=\"vc_ulId_".md5(date('YmdHis').rand(0,9999999))."\">";

        $int = 0;
        $countRows = count($get_results);
        foreach( $get_results as $result ) {

            $hideRest = ($int > 4)  ? " class=\"hide\" " : null;
            $output .= "<li ".$hideRest."><a href=\"".get_permalink($result->ID)."\">";
            $output .= "<span>".$result->post_title."</span>";
            $output .= "</a></li>";

            $int++;
        }

        if($countRows>5)
            $output .= "<li><br /><a href=\"\" class=\"showAllPosts showPosts\">Visa alla (".$countRows.")</a></li>";
        $output .= "</ul>";
        $output .= "</div></div> ";

        return $output;
    }
}





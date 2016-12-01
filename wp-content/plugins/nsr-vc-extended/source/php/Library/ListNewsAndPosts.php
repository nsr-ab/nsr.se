<?php

/**
 * ListNewsAndPosts ad-don for Visual Composer
 *
 * @package NSRVCExtended
 *
 * Author: Johan Silvergrund
 * Company: HIQ

 * -- ListNewsAndPosts --
 * A Visual composer ad-don to create lists of news topics etc.
 *
 */

namespace VcExtended\Library;

class ListNewsAndPosts
{

    function __construct()
    {
        add_action('init', array($this, 'integrateWithVC'));
        add_shortcode('nsr_collection', array($this, 'renderExtend'));

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

                'type'      => 'textfield',
                'heading' => __('Designation', 'nsr-vc-extended'),
                'param_name' => 'vc_designation',
                'edit_field_class' => 'vc_col-sm-8 vc_col-md-8',
                'admin_label' => true
            ),


            /** @Param Show date parameter*/
            array(
                'type' => 'dropdown',
                'heading' => __('Post date', 'nsr-vc-extended'),
                'param_name' => 'vc_postdate',
                'edit_field_class' => 'vc_col-sm-4 vc_col-md-4',
                'value' => array(
                    __('Include', 'nsr-vc-extended')   => 1,
                    __('Exclude', 'nsr-vc-extended')   => 2
                ),
            ),


            /** @Param Icon component parameter */
            array(

                'type' => 'iconpicker',
                'param_name' => 'vc_extend_material_list',
                'edit_field_class' => 'vc_col-sm-8 vc_col-md-8',
                'heading' => __('Icon', 'nsr-vc-extended'),
                'settings' => array(
                    'emptyIcon' => true,
                    'type' => 'material',
                    'iconsPerPage' => 26,
                ),
                'description' => __('Select icon from library.', 'nsr-vc-extended'),
            ),


            /** @Param Color picker component for background color */
            array(

                'type' => 'colorpicker',
                'holder' => 'div',
                'class' => 'vc_extend_colors',
                'edit_field_class' => 'vc_col-sm-4 vc_col-md-4',
                'heading' => __('Icon color', 'nsr-vc-extended'),
                'param_name' => 'vc_extend_colors',
                'dependency' => array('element' => 'color'),
            ),

            /** @Param Post loop parameter */
            array(

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

        if (!defined('WPB_VC_VERSION')) {
            add_action('admin_notices', array($this, 'showVcVersionNotice'));
            return;
        }

        vc_map(array(

                'name' => __('List news & posts', 'nsr-vc-extended'),
                'description' => __('List news and posts', 'nsr-vc-extended'),
                'base' => 'nsr_collection',
                "content_element" => true,
                'class' => 'vc_extended ',
                'show_settings_on_create' => true,
                "is_container" => true,
                'admin_label' => true,
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

        $params['date'] = isset($atts['vc_postdate']) ? $postdate = $atts['vc_postdate'] : null;
        $params['vc_extend_material'] = isset($atts['vc_extend_material_list']) ? $atts['vc_extend_material_list'] : null;
        $params['vc_extend_colors'] = isset($atts['vc_extend_colors']) ? $atts['vc_extend_colors'] : null;

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
    private function fetchPostData(array $params){

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
    public function mergeParams($params){

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
    public function renderMarkup($get_results, $params){

        $vc_icon_colors = isset($params->vc_extend_colors)  ? " style=\"color:".isset($params->vc_extend_colors) .";\" " : null;
        $output = "<ul id=\"vc_id_".md5(date('YmdHis').rand(0,9999999))."\" class=\"collection\">";

        foreach( $get_results as $result ) {

            $output .= "<a class=\"collection-item\" href=\"".get_permalink($result->ID)."\">";
            $output .= isset($params->vc_extend_material) ? "<i ".$vc_icon_colors." class=\"listIcons material-icons ".isset($params->vc_extend_material)."\"></i>" : null;
            $output .= "<span>".$result->post_title."</span>";
            $output .= (isset($params->date) == 1) ? "<span class=\"right date\">".date('Y-m-d', strtotime($result->post_date))."</span>" : null;
            $output .= "</a>";
        }

        $output .= "</ul> ";

        return $output;
    }



    /**
     * Show Notice if Visual Composer is activated or not.
     * @return string
     */
    public function showVcVersionNotice()
    {
        $plugin_data = get_plugin_data(__FILE__);

        return '
        <div class="updated notice notice-success is-dismissible">
          <p>' . sprintf(_e('<strong>%s</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'nsr-vc-extended'), $plugin_data['Name']) . '</p>
        </div>';
    }


}





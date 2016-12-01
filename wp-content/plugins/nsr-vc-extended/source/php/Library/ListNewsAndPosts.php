<?php

/**
 * MenuCollapsible ad-don for Visual Composer
 *
 * @package NSRVCExtended
 *
 * Author: Johan Silvergrund
 * Company: HIQ

 * -- ListNewsAndPosts --
 * A Visual composer ad-don to create and manage menus and accordions
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
     * Mapping Add extra params
     * @return void
     */
    public function addParams()
    {
        vc_add_params('vc_extend', $this->params());
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
     * @return string
     */
    public function renderExtend($atts, $content = null)
    {

        /** @var  $post_date */
        $post_date = isset($atts['vc_postdate']) ? $postdate = $atts['vc_postdate'] : "";

        /** @var  $vc_extend_material */
        $vc_extend_material = (isset($atts['vc_extend_material_list'])) ? $atts['vc_extend_material_list'] : '';

        /** @var  $vc_extend_material, $vc_icon_colors*/
        $vc_extend_colors = (isset($atts['vc_extend_colors'])) ? $atts['vc_extend_colors'] : '';
        $vc_icon_colors = (isset($vc_extend_colors)) ? " style=\"color:".$vc_extend_colors.";\" " : '';

        if ( isset( $atts['vc_loop'] ) && ! empty( $atts['vc_loop'] ) ) {
            $query = $atts['vc_loop'];
        }

        if ( isset( $query ) ) {

            $pairs = explode('|', $query);
            $params = "";

            foreach ($pairs as $pair) {

                $pair = explode(':', $pair, 2);
                $params['order_by'] = ($pair[0] === 'order_by') ? $key['order_by'] = $pair[1] : '';
                $params['size'] = ($pair[0] === 'size') ? $key['size'] = $pair[1] : '';
                $params['order'] = ($pair[0] === 'order') ? $key['order'] = $pair[1] : '';
                $params['post_type'] = ($pair[0] === 'post_type') ? $key['post_type'] = $pair[1] : '';
                $params['author'] = ($pair[0] === 'author') ? $key['author'] = $pair[1] : '';
                $params['categories'] = ($pair[0] === 'categories') ? $key['categories'] = $pair[1] : '';
                $params['tags'] = ($pair[0] === 'tags') ? $key['tags'] = $pair[1] : '';
                $params['tax_query'] = ($pair[0] === 'tax_query') ? $key['tax_query'] = $pair[1] : '';
            }

            $term_cat = explode(',', $params['categories']);
            $term_tax = explode(',', $params['tags']);
            $term_prepare = array_merge($term_cat, $term_tax);
            $term_data = ltrim(rtrim(implode(',', $term_prepare), ','),',');


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

            /**  Filter */
            $sql .= $params['author'] ? "AND $wpdb->posts.post_author = ".$params['authors']." " : "";
            $sql .= $params['post_type'] ? " AND $wpdb->posts.post_type = '" . $params['post_type'] . "' " : "";
            $sql .= $params['order_by'] ? " ORDER BY '$wpdb->posts.post_" . $params['order_by'] . "'  " : "";
            $sql .= $params['order'] ? $params['order'] : "";
            $sql .= $params['size'] ? " LIMIT " . $params['size'] . " " : "";

            /** Markup */
            $output = "<ul id=\"vc_id_".md5(date('YmdHis').rand(0,9999999))."\" class=\"collection\">";

            foreach( $wpdb->get_results($sql) as $result ) {

                $output .= "<a class=\"collection-item\" href=\"".get_permalink($result->ID)."\">";
                $output .= ($vc_extend_material) ? "<i ".$vc_icon_colors." class=\"listIcons material-icons ".$vc_extend_material."\"></i>" : "";
                $output .= "<span>".$result->post_title."</span>";
                $output .= ($post_date == 1) ? "<span class=\"right date\">".date('Y-m-d', strtotime($result->post_date))."</span>" : "";
                $output .= "</a>";
            }

            $output .= "</ul> ";

            return $output;
        }
    }


    /**
     * Show Notice if Visual Composer is activated or not.
     * @return string
     */
    public function showVcVersionNotice()
    {
        $plugin_data = get_plugin_data(__FILE__);

        echo '
        <div class="updated">
          <p>' . sprintf(__('<strong>%s</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'nsr-vc-extended'), $plugin_data['Name']) . '</p>
        </div>';
    }


}





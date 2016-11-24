<?php

namespace VcExtended\Library;

class MenuCollapsible
{

    function __construct() {

        add_action( 'init', array( $this, 'integrateWithVC' ) );
        add_shortcode( 'nsr_menu-collapsible', array( $this, 'renderExtend' ) );
        add_action( 'wp_ajax_fetch_data', array( $this, 'fetchData' ) );

    }

    /**
     * Mapping Ad-don
     * @return void
     */
    public function integrateWithVC()
    {

        if ( ! defined( 'WPB_VC_VERSION' ) ) {
            add_action('admin_notices', array( $this, 'showVcVersionNotice' ));
            return;
        }


        vc_map( array(

            "name" => __('Menu Collapsible', 'vc_extend'),
            "description" => __('Menu Accordion', 'vc_extend'),
            "base" => 'nsr_menu-collapsible',
            "class" => '',
            "controls" => 'full',
            "icon" => 'vc_general vc_element-icon icon-wpb-ui-accordion',
            "category" => __('NSR', 'js_composer'),

            //"admin_enqueue_js" => array(plugins_url('nsr-vc-extended/dist/js/nsr-vc-extended.min.js' )),
            //"admin_enqueue_css" => array(plugins_url('nsr-vc-extended/dist/css/nsr-vc-extended.min.css' )),

            "params" => array(

                array(
                    "type" => 'textfield',
                    "holder" => 'div',
                    "class" => '',
                    "heading" => __('Sök ', 'vc_extend'),
                    "param_name" => 'vcext_search_pagetitle',
                    "value" => __('', 'vc_extend'),
                    "description" => __('Sök sida/inlägg.', 'vc_extend')
                ),


            )
        ) );
    }



    public function addParam(){
        $attributes = array(
            'type' => 'dropdown',
            'heading' => "Style",
            'param_name' => 'style',
            'value' => array( "one", "two", "three" ),
            'description' => __( "New style attribute", "my-text-domain" )
        );
        vc_add_param( 'vc_extend', $attributes ); // Note: 'vc_message' was used as a base for "Message box" element

    }


    /**
     * Logic behind Ad-don output
     * @return string
     */
    public function renderExtend( $atts, $content = null )
    {

        extract( shortcode_atts( array(
                'title' => 'Title',
                'link' => 'Link to post'), $atts ));

        $content = wpb_js_remove_wpautop($content, true);
        

        //echo NSR_VC_EXTENDED_TEMPLATE_PATH;
        //include NSR_VC_EXTENDED_TEMPLATE_PATH . 'menucollapsible.php';
        //return $output;
    }

    /**
     * Fetch page titles and id.
     * @return object

     */
    public function fetchData(){

        global $wpdb;

        $sql = "SELECT * 
                FROM $wpdb->posts 
                WHERE post_type = 'page' 
                AND post_title 
                LIKE '%".sanitize_text_field($_POST['query'])."%'";

        $result = $wpdb->get_results($sql);

        wp_send_json(json_encode($result));


        wp_die();

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
          <p>'.sprintf(__('<strong>%s</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'vc_extend'), $plugin_data['Name']).'</p>
        </div>';
    }
}





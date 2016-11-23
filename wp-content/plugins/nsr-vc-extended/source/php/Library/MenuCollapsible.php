<?php

namespace VcExtended\Library;

class MenuCollapsible
{

    function __construct() {

        add_action( 'init', array( $this, 'integrateWithVC' ) );
        add_shortcode( 'nsr_menu-collapsible', array( $this, 'renderExtend' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );

    }




    /**
     * Mapping Ad-don
     * @return void
     */
    public function integrateWithVC() {

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

            "admin_enqueue_js" => array(plugins_url('dist/nsr-vc-extended.min.js', __FILE__)),
            "admin_enqueue_css" => array(plugins_url('dist/nsr-vc-extended-admin.min.css', __FILE__)),

            "params" => array(

                array(
                    "type" => 'textfield',
                    "holder" => 'div',
                    "class" => '',
                    "heading" => __('Sök ', 'vc_extend'),
                    "param_name" => 'title',
                    "value" => __('', 'vc_extend'),
                    "description" => __('Sök sida/inlägg.', 'vc_extend')
                ),


            )
        ) );
    }



    /**
     * Logic behind Ad-don output
     * @return string
     */
    public function renderExtend( $atts, $content = null ) {

        extract( shortcode_atts( array(
                'title' => 'Title',
                'link' => 'Link to post'), $atts ));

        $content = wpb_js_remove_wpautop($content, true);
        

        //echo NSR_VC_EXTENDED_TEMPLATE_PATH;
        //include NSR_VC_EXTENDED_TEMPLATE_PATH . 'menucollapsible.php';
        //return $output;
    }



    /**
     * Enque Scripts & CSS
     * @return string
     */
    public function enqueueScripts() {

        wp_register_style( 'vc_extend_style', plugins_url('dist/nsr-vc-extended.min.css', __FILE__) );
        wp_enqueue_style( 'vc_extend_style' );
        wp_enqueue_script( 'vc_extend_js', plugins_url('dist/nsr-vc-extended.min.js', __FILE__), array('jquery') );

    }



    /**
     * Show Notice if Visual Composer is activated or not.
     * @return string
     */
    public function showVcVersionNotice() {
        $plugin_data = get_plugin_data(__FILE__);
        echo '
        <div class="updated">
          <p>'.sprintf(__('<strong>%s</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'vc_extend'), $plugin_data['Name']).'</p>
        </div>';
    }
}





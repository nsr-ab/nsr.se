<?php

/**
 * WMenuCollapsible ad-don for Visual Composer
 *
 * @package NSRVCExtended
 *
 * Author: Johan Silvergrund
 * Company: HIQ
 *
 */

namespace VcExtended\Library;

class MenuCollapsible
{

    function __construct()
    {
        add_action('init', array($this, 'integrateWithVC'));
        add_shortcode('nsr_menu-collapsible', array($this, 'renderExtend'));

    }


    /**
     * Available parameters
     * @return array
     */
    public function params()
    {
        return array(

            /** @Array Designation parameter */
            array(

                'type' => 'textfield',
                'heading' => __('Designation', 'nsr-vc-extended'),
                'param_name' => 'designation',
                'admin_label' => true
            ),

            /** @Array Parameter Group */
            array(

                'type' => 'param_group',
                'param_name' => 'vc_extend_rows',
                'params' => array(

                    /** @Array Title parameter */
                    array(

                        'type' => 'textfield',
                        'holder' => 'div',
                        'class' => 'vc_extend_text_pagetitle',
                        'edit_field_class' => 'vc_col-sm-8 vc_col-md-8',
                        'heading' => __('Title', 'nsr-vc-extended'),
                        'param_name' => 'vc_extend_text_pagetitle',
                        'description' => __('Title, prefered maxchars 160', 'nsr-vc-extended'),
                        'admin_label' => true
                    ),

                    /** @Array Colorpicker component parameter for left border color */
                    array(

                        'type' => 'colorpicker',
                        'holder' => 'div',
                        'class' => 'vc_extend_bordercolor',
                        'edit_field_class' => 'vc_col-sm-4 vc_col-md-4',
                        'heading' => __('Left border color (Menu only)', 'nsr-vc-extended'),
                        'param_name' => 'vc_extend_bordercolor',
                        'description' => __('Menu left border color', 'nsr-vc-extended'),
                        'dependency' => array('element' => 'color')
                    ),

                    /** @Array Link component parameter */
                    array(

                        'type' => 'vc_link',
                        'holder' => 'div',
                        'class' => 'vc_extend_text_pagelink',
                        'edit_field_class' => 'vc_col-sm-8 vc_col-md-8',
                        'heading' => __('Link title to page', 'nsr-vc-extended'),
                        'param_name' => 'vc_extend_text_pagelink',
                        'description' => __('If selected the title is going to be linked, if link title is selected, the main link title will be active.', 'nsr-vc-extended')
                    ),

                    /** @Array Margin parameter (only for menus)*/
                    array(

                        'type' => 'dropdown',
                        'holder' => 'div',
                        'class' => 'vc_extend_row_margin_bottom',
                        'edit_field_class' => 'vc_col-sm-4 vc_col-md-4',
                        'heading' => __('Margin bottom (Menus only)', 'nsr-vc-extended'),
                        'param_name' => 'vc_extend_row_margin_bottom',
                        'value' => array(__('No margins', 'nsr-vc-extended') => '', __('Margins', 'nsr-vc-extended') => '1' ),
                        'description' => __('Margin bottom', 'nsr-vc-extended')
                    ),

                    /** @Array Text parameter */
                    array(

                        'type' => 'textarea',
                        'holder' => 'div',
                        'class' => 'vc_extend_description',
                        'edit_field_class' => 'vc_col-sm-6 vc_col-md-8',
                        'heading' => __('Your text', 'nsr-vc-extended'),
                        'param_name' => 'vc_extend_description',
                        'description' => __('<table>
                                                <tr col>
                                                    <th class="col-md-5">Description</th><th>HTML</th>
                                                </tr>
                                                <tr>
                                                    <td class="col-md-5">Linebreak</td><td><b>&#60;br /&#62;</b></td>                             
                                                </tr>                                             
                                                <tr>
                                                    <td>Paragraph</td><td><b>&#60;p&#62;</b>Your text<b>&#60;/p&#62;</b></td>                                             
                                                </tr>
                                                <tr>
                                                    <td>Link</td><td><b>&#60;a href="http://link.se"&#62;</b>Read more...<b>&#60;/a&#62;</b> </td>
                                                    
                                                </tr>
                                                <tr>
                                                    <td class="col-md-4">Bold text</td><td><b>&#60;b&#62;</b>Your text<b>&#60;/b&#62;</b></td>
                                                </tr>
                                                <tr>
                                                    <td>Italic text</td><td><b>&#60;i&#62;</b>Your text<b>&#60;/i&#62;</b></td>
                                                </tr>
                                                <tr>
                                                    <td>Headings (H1,H2,H3...)</td><td><b>&#60;h3&#62;</b>Your text<b>&#60;/h3&#62;</b></td>
                                                </tr>
                                            </table>
                        ', 'nsr-vc-extended')
                    ),

                    /** @Array Color picker component for background color */
                    array(

                        'type' => 'colorpicker',
                        'holder' => 'div',
                        'class' => 'vc_extend_colors',
                        'edit_field_class' => 'vc_col-sm-6 vc_col-md-4',
                        'heading' => __('Background color', 'nsr-vc-extended'),
                        'param_name' => 'vc_extend_colors',
                        'description' => __('Select row background color.', 'nsr-vc-extended'),
                        'dependency' => array('element' => 'color'),
                    ),

                    /** @Array Icon component parameter */
                    array(

                        'type' => 'iconpicker',
                        'holder' => 'div',
                        'class' => 'vc_extend_icon',
                        'edit_field_class' => 'vc_col-sm-12 vc_col-md-12',
                        'heading' => __('Icon', 'nsr-vc-extended'),
                        'param_name' => 'vc_extend_material',
                        'settings' => array(

                            'emptyIcon' => true,
                            'type' => 'material',
                            'iconsPerPage' => 4000,
                        ),

                        'dependency' => array(

                            'element' => 'icon_type',
                            'value' => 'material',
                        ),

                        'description' => __('Select icon from library.', 'nsr-vc-extended'),

                    )
                )
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

                'name' => __('Menu accordion', 'nsr-vc-extended'),
                'description' => __('Menu Accordion', 'nsr-vc-extended'),
                'base' => 'nsr_menu-collapsible',
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

        $vc_extend_rows = vc_param_group_parse_atts( $atts['vc_extend_rows'] );
        $content = wpb_js_remove_wpautop($content, true);
        $output = "<ul id=\"vc_id_".md5(date('YmdHis').rand(0,9999999))."\" class=\"collapsible\" data-collapsible=\"accordion\">";

        $int = 0;
        $countRows = count($vc_extend_rows);

        foreach($vc_extend_rows as $row) {

            /** @var  $vc_extend_colors */
            $vc_extend_colors = (isset($row['vc_extend_colors'])) ? $row['vc_extend_colors'] : '';

            /** @var  $vc_extend_bordercolor */
            $vc_extend_bordercolor = (isset($row['vc_extend_bordercolor'])) ? $row['vc_extend_bordercolor'] : '';

            /** @var  $vc_extend_material */
            $vc_extend_material = (isset($row['vc_extend_material'])) ? $row['vc_extend_material'] : '';

            /** @var  $vc_extend_description */
            $vc_extend_description = (isset($row['vc_extend_description'])) ? $row['vc_extend_description'] : '';

            /** @var  $vc_extend_text_pagetitle */
            $vc_extend_text_pagetitle = (isset($row['vc_extend_text_pagetitle'])) ? $row['vc_extend_text_pagetitle'] : '';

            /** @var  $vc_extend_text_pagelink */
            $vc_extend_text_pagelink = (isset($row['vc_extend_text_pagelink'])) ? $row['vc_extend_text_pagelink'] : '';

            /** @var  $vc_extend_row_margin_bottom */
            $vc_extend_row_margin_bottom = (isset($row['vc_extend_row_margin_bottom'])) ? $row['vc_extend_row_margin_bottom'] : '';

            /** @var  $colors */
            $colors = ($vc_extend_colors) ? $colors = " color:#fff; background-color:" . $vc_extend_colors . ";" : "";

            /** @var  $forceLinkColor */
            $forceLinkColor = ($vc_extend_colors) ? $forceLinkColor = " style=\"color:#fff; \"" : " style=\"color:#007586; \"";

            /** @var  $colorClass */
            $colorClass = ($vc_extend_colors) ? $colorClass = "wlink" : "";

            /** @var  $hasMargin */
            $hasMargin = ($vc_extend_row_margin_bottom) ? $hasMargin = "hasMargin" : "";

            /** @var  $menuBorderColor */
            $menuBorderColor = ($vc_extend_bordercolor) ? "border-left: 8px solid ".$vc_extend_bordercolor.";" : "";

            /** @var  $liCSS */
            $liCSS = "style=\"".$colors." ".$menuBorderColor." \"";


            /** @var  $output */
            $output .= "<li><div class=\"collapsible-header ".$colorClass." ".$hasMargin."\"  ". $liCSS ."\">";
            $output .= ($vc_extend_material) ? "<i class=\"material-icons ".$vc_extend_material."\"></i>" : "";


            /** Custom Title & Link */
            if(!$vc_extend_text_pagelink) {
                $output .= $vc_extend_text_pagetitle;
            }
            else {
                $href = vc_build_link($vc_extend_text_pagelink);
                $output .= "<a ".$forceLinkColor." href=\"".$href['url']."\">".$href['title']."</a>";
                $output .= "<i class=\"right material-icons materialIconState\">chevron_right</i></div>";
            }

            /** Custom Description */
            if($vc_extend_description)
                $output .= (!$vc_extend_text_pagelink) ?  " <i class=\"right material-icons materialIconState\">add</i></div>" : "";
                $output .= (!$vc_extend_text_pagelink) ?  "<div class=\"collapsible-body\"><p>".$vc_extend_description."</p></div>" : "";

            $output .= "</li>";
            $int++;

            /** Custom margins - splits up the ul */
            if($vc_extend_row_margin_bottom) {
                $output .= "</ul>";
                $output .= ($countRows == $int ) ?  "" : "<ul class=\"collapsible\" data-collapsible=\"accordion\">";
            }
            else {
                $menuMargin = null;
            }
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

        echo '
        <div class="updated">
          <p>' . sprintf(__('<strong>%s</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'nsr-vc-extended'), $plugin_data['Name']) . '</p>
        </div>';
    }


}





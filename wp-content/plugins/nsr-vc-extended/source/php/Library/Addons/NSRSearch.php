<?php

/**
 * Puff ad-don for Visual Composer
 *
 * @package NSRVCExtended
 *
 * Author: Johan Silvergrund
 * Company: HIQ

 * -- Search --
 * NSR search widget.
 *
 */

namespace VcExtended\Library\Addons;

class NSRSearch
{

    function __construct()
    {
        add_action('init', array($this, 'integrateWithVC'));
        add_shortcode('nsr_search', array($this, 'renderExtend'));

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
                'heading' => __('Title', 'nsr-vc-extended'),
                'param_name' => 'vc_designation',
                'edit_field_class' => 'vc_col-sm-9 vc_col-md-9',

            )

        );
    }


    /**
     * Mapping Ad-don
     * @return void
     */
    public function integrateWithVC()
    {

        vc_map(array(

                'name' => __('Search', 'nsr-vc-extended'),
                'description' => __('NSR Search', 'nsr-vc-extended'),
                'base' => 'nsr_search',
                "content_element" => true,
                'class' => 'vc_extended ',
                'show_settings_on_create' => true,
                "is_container" => true,
                'admin_label' => false,
                'controls' => 'full',
                'icon' => 'vc_general vc_element-icon icon-wpb-ui-accordion',
                'category' => __('NSR', 'js_composer'),
                'icon' => plugins_url( 'nsr-vc-extended/dist/img/icn_search.svg' ),
                'admin_enqueue_css' => array( plugins_url( 'nsr-vc-extended/dist/css/nsr-vc-extended-admin.min.css' ) ),
                'params' => $this->params()
            )
        );
    }



    /**
     * Logic behind Ad-don output
     * @param array $atts
     * @param string $content
     * @return string
     */
    public function renderExtend(array $atts, $content = null)
    {

        $params['vc_title'] = isset($atts['vc_title']) ? $postdate = $atts['vc_title'] : null;
        return $this->renderMarkup($param = (object) $params);

    }



    /**
     * loop object and render markup
     * @param array $params
     * @return string
     */
    public function renderMarkup($params)
    {

       $designation = isset($params->vc_designation)  ? " style=\"border-top:2px solid ". $params->vc_designation .";\" " : null;

            $output = "<div class=\"row search searchNSR\" itemscope=\"\" itemtype=\"http://schema.org/WebSite\">
                        
                        <div class=\"col s12\">
                          <div class=\"row\">
                        <form itemprop=\"potentialAction\" itemscope=\"\" itemtype=\"http://schema.org/SearchAction\">
                            <h4>".__('Tömningskalender eller sorteringsguiden', 'nsr-vc-extended')."</h4>
                            <div class=\"input-field col s12 searchArea\">
                                <i class=\"material-icons prefix\">search</i>
                                <input class=\"form-control form-control-lg validated input-field s12\" itemprop=\"query-input\" required=\"\" id=\"searchkeyword-nsr\" autocomplete=\"off\"  type=\"search\" name=\"searchQ\" value=\"\" aria-invalid=\"true\">
                                <label for=\"searchQ-input\">".__('Where do you live? What stuff do you want to sort? Are you looking for something else?', 'nsr-vc-extended')."</label>
                            </div>
                           
                        </form>
                    </div></div>  
                     <i class=\"hide closeSearch material-icons\">cancel</i>
                     
                     <div id=\"searchResult\"></div>
                     </div>";

            return $output;



    }
}





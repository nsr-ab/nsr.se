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
                'type' => 'textfield',
                'heading' => __('Title', 'nsr-vc-extended'),
                'param_name' => 'vc_designation',
                'edit_field_class' => 'vc_col-sm-7 vc_col-md-7',

            ),

            /** @Param post types parameter */
            array(
                'admin_label' => true,
                'type' => 'dropdown',
                'heading' => __('Search section', 'nsr-vc-extended'),
                'param_name' => 'vc_search_sections',
                'edit_field_class' => 'vc_col-sm-7 vc_col-md-7',
                'value' => array(
                    __('Choose section...', 'nsr-vc-extended') => '',
                    __('All sections', 'nsr-vc-extended') => 'all',
                    __('Villa & Fritidsboende', 'nsr-vc-extended') => 'villa',
                    __('Fastighetsägare & Bostadsrättsföreningar', 'nsr-vc-extended') => 'fastighet',
                    __('Företag & Restauranger', 'nsr-vc-extended') => 'foretag',
                    __('Vanliga frågor', 'nsr-vc-extended') => 'faq',

                ),
            ),
            /** @Param post types parameter */
            array(
                'admin_label' => true,
                'type' => 'dropdown',
                'heading' => __('Page position', 'nsr-vc-extended'),
                'param_name' => 'vc_search_position',
                'edit_field_class' => 'vc_col-sm-7 vc_col-md-7',
                'value' => array(
                    __('Choose Type...', 'nsr-vc-extended') => '',
                    __('Searchbox mixed with other content', 'nsr-vc-extended') => 'content',
                    __('Searchbox alone in row', 'nsr-vc-extended') => 'top',
                ),
            ),
            /** @Param Designation parameter */
            array(
                'admin_label' => true,
                'type' => 'textfield',
                'heading' => __('Tooltip title', 'vc_tooltip_title'),
                'param_name' => 'vc_tooltip_title',
                'edit_field_class' => 'vc_col-sm-7 vc_col-md-7',

            ),

            /** @Param Designation parameter */
            array(
                'admin_label' => true,
                'type' => 'textfield',
                'heading' => __('Tooltip', 'vc_tooltip'),
                'param_name' => 'vc_tooltip',
                'edit_field_class' => 'vc_col-sm-7 vc_col-md-7',

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
                'icon' => plugins_url('nsr-vc-extended/dist/img/icn_search.svg'),
                'admin_enqueue_css' => array(plugins_url('nsr-vc-extended/dist/css/nsr-vc-extended-admin.min.css')),
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

        $params['vc_designation'] = isset($atts['vc_designation']) ? $atts['vc_designation'] : null;
        $params['vc_search_sections'] = isset($atts['vc_search_sections']) ? $atts['vc_search_sections'] : null;
        $params['vc_search_position'] = isset($atts['vc_search_position']) ? $atts['vc_search_position'] : null;
        $params['vc_tooltip'] = isset($atts['vc_tooltip']) ? $atts['vc_tooltip'] : null;
        $params['vc_tooltip_title'] = isset($atts['vc_tooltip_title']) ? $atts['vc_tooltip_title'] : null;

        return $this->renderMarkup($param = (object)$params);

    }


    /**
     * loop object and render markup
     * @param array $params
     * @return string
     */
    public function renderMarkup($params)
    {

        if ($params->vc_search_position === 'content') {
            $positionFixed = 'position-relative';
        } else {
            $positionFixed = 'position-absolute';
        }
        if ($params->vc_search_position === '' || $params->vc_search_position === false) {
            $positionFixed = 'position-relative';
        }
        $output = "<div class=\"row search searchNSR " . $positionFixed . "\" itemscope=\"\" itemtype=\"http://schema.org/WebSite\">
                        
                        <div class=\"col s12\">
                          <div class=\"row\">
                          
                                                          
                            <div class=\"tooltip-info cookieBased\">
                                <div class=\"tooltip-inner\">
                                    <i class=\"material-icons closeTooltip\">close</i>
                                    <b>" . $params->vc_tooltip_title . "</b><br />
                                   " . $params->vc_tooltip . "
                                </div>
                            </div>                               
                           
                          
                        <form itemprop=\"potentialAction\" itemscope=\"\" itemtype=\"http://schema.org/SearchAction\">
                            <h4 class=\"search-title\">" . $params->vc_designation . "</h4>
                            <div class=\"input-field col s12 searchArea\">
                                <i class=\"material-icons prefix notranslate\">&#xE8B6;</i>
                                <input class=\"form-control form-control-lg validated input-field s12\" itemprop=\"query-input\" required=\"\" id=\"searchkeyword-nsr\" autocomplete=\"off\"  type=\"search\" name=\"searchQ\" value=\"\" aria-invalid=\"true\">
                                <label for=\"searchQ-input\">" . __('Where do you live? What stuff do you want to sort? Are you looking for something else?', 'nsr-vc-extended') . "</label>
                                <input type=\"hidden\" id=\"post_type\" value=\"" . $params->vc_search_sections . "\">
                            </div>
                           
                        </form>
                    </div></div>  
                     
                     <div class=\"searchbuttons\"> 
                     <i class=\"infoSearch material-icons\">info</i> <i class=\"hide closeSearch material-icons\">cancel</i>
                     </div> 
                     
                      <div class=\"tooltip-info static-tooltip\">
                        <div class=\"tooltip-inner\">
                            <i class=\"material-icons closeTooltip\">close</i>
                               <b>" . $params->vc_tooltip_title . "</b><br />
                                   " . $params->vc_tooltip . "
                         </div>
                      </div> 
                     
                     <div class=\"search-autocomplete\"></div>
                     <div class=\"search-fetchPlanner\"></div>
                     <div id=\"searchResult\"></div>
                     
                   
                      <!-- Search - No result -->
                     <div class=\"errorNoresult\">
                     
                        <div class=\"errorSortguide hide\"></div>
                        <div class=\"errorPages hide\"></div>
                        <div class=\"errorFetchPlanner hide\"></div>
                        
                     </div>
                   
                   
                     </div>
                     
                     
                     
                     ";

        return $output;


    }
}





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
                    __('Sorteringsguide', 'nsr-vc-extended') => 'sorteringsguide',
                    __('Tömningskalender', 'nsr-vc-extended') => 'tomningskalender',
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
                    __('Searchbox in Hero', 'nsr-vc-extended') => 'hero',
                    __('Searchbox mixed with other content', 'nsr-vc-extended') => 'content',
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
        $hero = '';
        $positionFixed = '';

        if ($params->vc_search_position === 'content') {
            $positionFixed = 'position-relative';
        } else {
            if ($params->vc_search_position === 'hero') {
                $hero = 'nsrHero';
            } else {
                $positionFixed = 'position-absolute';
            }
        }

        if ($params->vc_search_position === '' || $params->vc_search_position === false) {
            $positionFixed = 'position-relative';
        }

        $output = "<div class=\"row search searchNSR " . $hero . " " . $positionFixed . "\" data-searchDesignation=\"".get_the_title()."\" itemscope=\"\" itemtype=\"http://schema.org/WebSite\">
                        <div class=\"searchDesignation deskHide\"></div>   
                        <div class=\"col s12\">
                         ";
                        if ($params->vc_search_position === 'content') {
                                $output .= "<h2>Sök i Sorteringsguiden</h2>";
                        }
                        $output .= " 
                        <form itemprop=\"potentialAction\" itemscope=\"\" itemtype=\"http://schema.org/SearchAction\">
                          <div class=\"row\">                       
                                                
                            <div class=\"tooltip-info\">
                                <div class=\"tooltip-inner\">
                                    <i class=\"material-icons closeTooltip\">close</i>
                                    <b>" . $params->vc_tooltip_title . "</b><br />
                                   " . $params->vc_tooltip . "
                                </div>
                            </div>               
                          
                            <!-- <h4 class=\"search-title\">" . $params->vc_designation . "</h4> -->
                            <div class=\"input-field col s12 searchArea\">
                                <div class=\"searchWrapper\">
                                   <i class=\"material-icons prefix notranslate\">&#xE8B6;</i>
                                    <input class=\"form-control form-control-lg validated input-field s12\" itemprop=\"query-input\"  id=\"searchkeyword-nsr\" autocomplete=\"off\"  type=\"search\" name=\"searchQ\" value=\"\" aria-invalid=\"true\">
                                    <label for=\"searchkeyword-nsr\">" . __('Where do you live? What stuff do you want to sort? Are you looking for something else?',
                'nsr-vc-extended') . "</label>
                                    <input type=\"hidden\" id=\"post_type\" value=\"" . $params->vc_search_sections . "\">
                                     <!-- <b>" . $params->vc_tooltip_title . "</b><br /> -->
                                     <div class=\"searchTooltip\">" . $params->vc_tooltip . "</div>
                                     <div class=\"searchbuttons\"> 
                                        <i class=\"infoSearch material-icons\">info</i> <i class=\"hide closeSearch material-icons\">cancel</i><span class=\"infoSearchTxt\">Tips från coachen</span>
                                     </div> 
                     
                                      <div class=\"tooltip-info static-tooltip\">
                                        <div class=\"tooltip-inner\">
                                            <i class=\"material-icons closeTooltip\">close</i>
                                               <b>" . $params->vc_tooltip_title . "</b><br />
                                                   " . $params->vc_tooltip . "
                                         </div>
                                      </div> 
                                    <!-- <input type=\"submit\" class=\"btn btn-large waves-effect waves-light notranslate search-button\" style=\"display:none;\" value=\"SÖK\"> -->
                                    <!-- <input type=\"submit\" class=\"btn btn-large notranslate search-button\" style=\"display:none;\" value=\"SÖK\">  -->
                                    <button type=\"submit\" class=\"btn btn-large notranslate search-button\"><span>SÖK</span></button>        
                                </div>                         
                            </div>
                           
         
                          </div>
                        </form> ";
        if ($params->vc_search_position === 'content') {
            $output .= " <div class=\"searchMenu sortguideMenu\"></div> ";
        }
            $output .= "</div>
                    
                     </div>
                     ";


        //if (empty(get_field('visualComposerACFHero'))) {
        if ($params->vc_search_position === 'content') {
            $output .= "
           
                        <div id=\"nsr-searchResult\" class=\"hide\">
                            <div class=\"search-hits hide\"></div>
                            <div class=\"sorteringsguiden hide searchView\"><div class=\"sorteringsguiden-data\"></div></div>
                            <div class=\"search-autocomplete hide searchView\"><div class=\"search-autocomplete-data\"></div></div>
                            <div class=\"search-fetchPlanner hide searchView\"><div class=\"search-fetchPlanner-data\"></div></div>
                            <div class=\"search-ao hide searchView\">
                                <ul class=\"ao-nav vc_col-sm-10\">
                                    <li class=\"a-o-trigger vc_col-sm-1 active\" data-letter=\"a-c\">A-C</li>
                                    <li class=\"a-o-trigger vc_col-sm-1\" data-letter=\"d-f\">D-F</li>
                                    <li class=\"a-o-trigger vc_col-sm-1\" data-letter=\"g-i\">G-I</li>
                                    <li class=\"a-o-trigger vc_col-sm-1\" data-letter=\"j-l\">J-L</li>
                                    <li class=\"a-o-trigger vc_col-sm-1\" data-letter=\"m-o\">M-O</li>
                                    <li class=\"a-o-trigger vc_col-sm-1\" data-letter=\"p-r\">P-R</li>
                                    <li class=\"a-o-trigger vc_col-sm-1\" data-letter=\"s-u\">S-U</li>
                                    <li class=\"a-o-trigger vc_col-sm-1\" data-letter=\"v-w\">V-W</li>
                                    <li class=\"a-o-trigger vc_col-sm-1\" data-letter=\"y-ö\">Y-Ö</li>
                                </ul>
                                <div class=\"search-ao-data\"></div>
                            </div>
                            <div class=\"errorSortguide hide\"></div>
                            <div class=\"errorPages hide\"></div>
                        </div>
                        
            ";
        }

        return $output;
    }
}

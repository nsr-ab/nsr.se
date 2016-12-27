<?php

namespace openhours;

class App
{
    public function __construct()
    {
        add_action('admin_notices', array($this, 'sendRequirementsWarning'));
        add_shortcode('opening-hours', array($this, 'getOpeningHours'));

        /**
         * Check if Visual composer is activated
         */
        if (!defined('WPB_VC_VERSION')) {
            add_action('admin_notices', array($this, 'showVcVersionNotice'));
            return;
        }

        add_action('widgets_init', function () {
            register_widget('openhours\OpenHoursWidget');
        });


        add_action('admin_enqueue_scripts', array($this, 'enqueueStylesAdmin'));
        add_action('after_setup_theme', array($this, 'after_nsr_theme_setup'));
    }

    /**
     * Enqueue required scripts
     * @return void
     */
    public function enqueueScriptsAdmin()
    {

        if (is_admin()) {
            wp_register_script('nsr-openHours-admin', plugins_url('nsr-open-hours/dist/js/nsr-open-hours.min.js'));
            wp_enqueue_script('nsr-openHours-admin');
        }
    }


    /**
     * Enqueue required style Admin
     * @return void
     */
    public function enqueueStylesAdmin()
    {
        wp_register_style('nsr-openHours-admin-style', plugins_url('nsr-open-hours/dist/css/nsr-open-hours.min.css'));
        wp_enqueue_style('nsr-openHours-admin-style');
    }


    /**
     * Enqueue after everything else
     * @return void
     */
    public function after_nsr_theme_setup()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueueScriptsAdmin'));

    }


    /**
     * Requirement warning (Plugin ACF PRO to work)
     * @return void
     */
    public function sendRequirementsWarning()
    {
        if (!function_exists('get_field')) {
            echo '<div class="notice notice-error">';
            echo '<p>';
            _e("Open hours require ACF PRO to function. Please make shure that this is installed and enabled.", 'nsr-open-hours');
            echo '</p>';
            echo '</div>';
        }
    }


    /**
     * Macthing stuff in vector
     * @return boolean
     */
    public function in_array_r($item, $array)
    {
        return preg_match('/"' . $item . '"/i', json_encode($array));
    }


    /**
     * Creating openingHour markup for shortcode
     * @return shortcode hook
     */
    public function getOpeningHours($atts)
    {

        $section = isset($atts['section']) ? $section = $atts['section'] : null;
        $type = isset($atts['type']) ? $type = $atts['type'] : null;
        $city = isset($atts['city']) ? $city = $atts['city'] : null;
        $align = isset($atts['align']) ? $align = $atts['align'] : null;
        $dsize = isset($atts['datesize']) ? $atts['datesize'] : null;
        $dateformat = ($dsize === 'full' ) ? 'l' : 'D';
        $fulldateCSS = ($dsize === 'full' ) ? 'fulldate' : '';

        switch ($type) {

            case "today":

                $unique_exception = false;
                $exception_info = get_field('oph_exeptions_' . $section, 'option');

                if (is_array($exception_info) && !empty($exception_info)) {

                    foreach ($exception_info as $exception_data) {

                        if ($exception_data['date_' . $section] == date("Y-m-d")) {
                            $unique_exception = $exception_data;
                            break;
                        }
                    }
                }

                if (!is_null($unique_exception) && is_array($unique_exception)) {

                    $return_value = $unique_exception['ex_title_' . $section] . " ";
                    $return_value .= $unique_exception['ex_info_' . $section];
                    $filter_is_exception = true;

                }
                else {

                    $return_value = get_field($this->getMetaKeyByDayId(date("w"), $section), 'option');
                    $filter_is_exception = true;
                }

                break;

            case "week":

                $datetime = new \DateTime();
                $datetime->modify('-1 day');

                if($align)
                    $align = "text-align-".$align;

                $openLiStart = isset($atts['markup']) ? $openLiStart = '<li class="collection-item '.$align.'">' : null;
                $closeLiItemStart = isset($atts['markup']) ? $closeLiItemStart = '</li>' : null;
                $openLiWeekend = isset($atts['markup']) ? $openLiWeekend = '<li class="collection-item weekender '.$align.'">' : null;
                $openLiToday = isset($atts['markup']) ? $openLiToday = '<li class="collection-item today '.$align.'">' : null;
                $listItem = array($openLiStart, $closeLiItemStart, $openLiWeekend, $openLiToday);
                $return_value = "";
                $i = 0;

                $return_value .= "<li class=\"collection-header\"><i class=\"material-icons\">access_time</i> " . $city . "</li>";

                while (true) {

                    if ($i === 7)
                        break;
                    if ($datetime->format('N') === '8' && $i === 0) {

                        $datetime->add(new \DateInterval('P1D'));
                        continue;
                    }

                    $exception_info = get_field('oph_exeptions_' . $section, 'option');

                    if ($this->in_array_r($datetime->format('Y-m-d'), $exception_info)) {

                        foreach ($exception_info as $exc) {

                            if ($exc['date_' . $section] === $datetime->format('Y-m-d')) {
                                $ex_title = $exc['ex_title_' . $section];
                                $ex_info = $exc['ex_info_' . $section];
                                $return_value .= $listItem[2] . $ex_title . " " . $ex_info . $listItem[1];
                            }
                        }
                    }
                    else {

                        $openSpan = isset($atts['markup']) ? $openLiToday = '<span class="date left text-align-left  '.$fulldateCSS.'">' : null;
                        $closeSpan = isset($atts['markup']) ? $closeLiItemToday = '</span>' : null;
                        if ($datetime->format('Y-m-d') != date('Y-m-d')) {
                            $return_value .= $listItem[0] . $openSpan . ucfirst(date_i18n($dateformat, strtotime($datetime->format($dateformat)))) . $closeSpan . " " . get_field($this->getMetaKeyByDayId($datetime->format('N'), $section), 'option') . $listItem[1];
                        } else {
                            $return_value .= $listItem[3] . $openSpan . ucfirst(date_i18n($dateformat, strtotime($datetime->format($dateformat)))) . $closeSpan . " " . get_field($this->getMetaKeyByDayId($datetime->format('N'), $section), 'option') . $listItem[1];
                        }
                    }

                    $return_value .= isset($atts['markup']) ? '' : '<br />';
                    $filter_is_exception = false;
                    $datetime->add(new \DateInterval('P1D'));
                    $i++;
                }
                break;

            case "weekends":

                $datetime = new \DateTime();

                $datetime->modify('-1 day');
                $openLiStart = isset($atts['markup']) ? $openLiStart = '<li class="collection-item">' : null;
                $closeLiItemStart = isset($atts['markup']) ? $closeLiItemStart = '</li>' : null;
                $openLiToday = isset($atts['markup']) ? $openLiToday = '<li class="collection-item today">' : null;
                $listItem = array($openLiStart, $closeLiItemStart, $openLiToday);
                $return_value = "";
                $return_value .= "<li class=\"collection-header\"><i class=\"material-icons\">access_time</i> " . $city . "</li>";
                $exception_info = get_field('oph_exeptions_' . $section, 'option');
                $openSpan = isset($atts['markup']) ? $openLiToday = '<span class="date-day">' : null;
                $closeSpan = isset($atts['markup']) ? $closeLiItemToday = '</span>' : null;

                foreach ($exception_info as $exc) {

                    $ex_title = $exc['ex_title_' . $section];
                    $ex_info = $exc['ex_info_' . $section];
                    $ex_date = $exc['date_' . $section];
                    $return_value .= $listItem[0] . $openSpan . substr($ex_date, strrpos($ex_date, '-') + 1) . " " . ucfirst(date_i18n('M', strtotime($ex_date))) . $closeSpan . " " . $ex_title . " " . $ex_info . $listItem[1];

                }

                $return_value .= isset($atts['markup']) ? '' : '<br />';
                $filter_is_exception = false;
                $datetime->add(new \DateInterval('P1D'));

                break;
        }

        $openUl = isset($atts['markup']) ? $openUl = '<ul class="collection openhours with-header">' : null;
        $closeUl = isset($atts['markup']) ? $closeUl = '</ul>' : null;

        return apply_filters('openhours/shortcode', $openUl . $return_value . $closeUl, $filter_is_exception);

    }


    /**
     * OptionsDay
     * @return string
     */
    public function getMetaKeyByDayId($day_id, $section)
    {
        switch ($day_id) {
            case 1:
                return 'oph_mon_' . $section;
                break;

            case 2:
                return 'oph_tue_' . $section;
                break;

            case 3:
                return 'oph_wed_' . $section;
                break;

            case 4:
                return 'oph_thu_' . $section;
                break;

            case 5:
                return 'oph_fri_' . $section;
                break;

            case 6:
                return 'oph_sat_' . $section;
                break;

            case 0:
            case 7:
                return 'oph_sun_' . $section;
                break;

            default:
                return false;
        }
    }


}

<?php

namespace openhours;

class App
{
    public function __construct()
    {
        add_action('admin_notices', array($this, 'sendRequirementsWarning'));
        add_shortcode('opening-hours', array($this, 'getTodaysOpeningHours'));
    }

    public function sendRequirementsWarning()
    {
        if (!function_exists('get_field')) {
            echo '<div class="notice notice-error">';
                echo '<p>';
                    _e("Open hours require ACF PRO to function. Please make shure that this is installed and enabled.", 'opening-hours-slug');
                echo '</p>';
            echo '</div>';
        }
    }

    public function getTodaysOpeningHours($atts)
    {

        //Default data
        $section = $atts['section'];
        $unique_exception   = null;
        $exception_info     = get_field('oph_exeptions_'.$section, 'option');

        //Get current day exception
        if (is_array($exception_info) && !empty($exception_info)) {
            foreach ($exception_info as $exception_data) {
                if ($exception_data['date_'.$section] == date("Y-m-d")) {
                    $unique_exception = $exception_data;
                    break;
                }
            }
        }

        //Get exception for this day
        if (!is_null($unique_exception) && is_array($unique_exception)) {
            $return_value = $unique_exception['ex_info_'.$section];
            $filter_is_exception = true;
        } else {
            $return_value = get_field($this->getMetaKeyByDayId(date("w"), $section), 'option');
            $filter_is_exception = false;
        }

        return apply_filters('openhours/shortcode', $return_value, $filter_is_exception);

    }

    public function getMetaKeyByDayId($day_id,$section)
    {
        switch ($day_id) {
            case 1:
                return 'oph_mon_'.$section;
                break;
            case 2:
                return 'oph_tue_'.$section;
                break;
            case 3:
                return 'oph_wed_'.$section;
                break;
            case 4:
                return 'oph_thu_'.$section;
                break;
            case 5:
                return 'oph_fri_'.$section;
                break;
            case 6:
                return 'oph_sat_'.$section;
                break;
            case 0:
            case 7:
                return 'oph_sun_'.$section;
                break;
            default:
                return false;
        }
    }
}

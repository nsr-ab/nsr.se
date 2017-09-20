<?php
/**
 * NSR Visual Composer Extended
 *
 * @package NSRVCExtended
 *
 * Author: Johan Silvergrund
 * Company: HIQ
 *
 */

namespace VcExtended;
class App
{
    public $collection;

    public function __construct()
    {
        /**
         * Check if Visual composer is activated
         */
        if (!defined('WPB_VC_VERSION')) {
            add_action('admin_notices', array($this, 'showVcVersionNotice'));
            return;
        }
        /**
         * Master methods
         */
        if (!class_exists('MasterVCExtended')) {
            new \VcExtended\Library\Addons\MasterVCExtended();
        }
        /**
         * Ad-dons Collapsible menus
         */
        if (!class_exists('MenuCollapsible')) {
            new \VcExtended\Library\Addons\MenuCollapsible();
        }
        /**
         * Ad-dons List News & Posts
         */
        if (!class_exists('ListNewsAndPosts')) {
            new \VcExtended\Library\Addons\ListNewsAndPosts();
        }
        /**
         * Ad-dons List links with a thumbnail
         */
        if (!class_exists('ListLinksWithThumbnail')) {
            new \VcExtended\Library\Addons\ListLinksWithThumbnail();
        }
        /**
         * Ad-dons Thumbnail, link heading and description
         */
        if (!class_exists('ThumbnailAndTextarea')) {
            new \VcExtended\Library\Addons\ThumbnailAndTextarea();
        }
        /**
         * Ad-dons Thumbnail, link heading and description
         */
        if (!class_exists('Puff')) {
            new \VcExtended\Library\Addons\Puff();
        }
        /**
         * Ad-dons NSR openHour
         */
        if (!class_exists('OpenHours')) {
            new \VcExtended\Library\Addons\OpenHours();
        }
        /**
         * Ad-dons Elastic Site Search
         */
        if (!class_exists('NSRSearch')) {
            new \VcExtended\Library\Addons\NSRSearch();
        }
        /**
         *  Helper - PostType
         */
        if (!class_exists('PostType')) {
            new \VcExtended\Library\Helper\PostType();
        }
        /**
         * Elastic
         */
        if (!class_exists('Elasticsearch')) {
            new \VcExtended\Library\Search\ElasticSearch();
        }
        /**
         * Elastic search Query
         */
        if (!class_exists('QueryElastic')) {
            new \VcExtended\Library\Search\QueryElastic();
        }
        /**
         * Enqueue Scripts
         */
        if (!class_exists('Enqueue')) {
            new \VcExtended\Library\Enqueue();
        }
        add_action('wp_ajax_nopriv_fetchDataFromElasticSearch', array($this, 'fetchDataFromElasticSearch'));
        add_action('wp_ajax_fetchDataFromElasticSearch', array($this, 'fetchDataFromElasticSearch'));
        add_action('wp_ajax_nopriv_fetchDataFromFetchPlanner', array($this, 'fetchDataFromFetchPlanner'));
        add_action('wp_ajax_fetchDataFromFetchPlanner', array($this, 'fetchDataFromFetchPlanner'));
    }

    /**
     * Show Notice if Visual Composer is activated or not.
     * @return string
     */
    public function showVcVersionNotice()
    {
        echo '
        <div class="notice notice-error is-dismissible">
          <p>' . __('<strong>NSR Visual Composer Extended</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'nsr-vc-extended') . '</p>
        </div>';
    }

    /**
     * Unique result
     * @param array
     * @param string
     * @return string
     */
    public function citiesUnique($array, $key)
    {
        $temp_array = array();
        foreach ($array as &$v) {
            if (!isset($temp_array[$v[$key]]))
                $temp_array[$v[$key]] =& $v;
        }
        return array_values($temp_array);
    }

    /**
     * Unique id
     * @param int
     * @return string
     */
    private static function gen_uid($l = 10)
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $l);
    }

    /**
     * Get data from fetchplanner
     * @param string
     * @return array
     */
    private static function fetchPlansByCurl($curl)
    {
        $fetchplanner_curl = curl_init();
        curl_setopt_array($fetchplanner_curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => NSR_FetchPlanner . $curl
        ));
        $response = curl_exec($fetchplanner_curl);
        curl_close($fetchplanner_curl);
        return json_decode($response);
    }

    /**
     * Set correct date format
     * @param string
     * @return array
     */
    public static function setDateFormat($fpdate)
    {
        $date = str_replace(")/", "", str_replace("/Date(", "", $fpdate));
        $date = ($date / 1000);
        return date("Y-m-d", strtotime(substr(strtok(date("Y-m-d H:m", $date), ":"), 0, -2) . '+1 day'));
    }

    /**
     * Set correct date format
     * @param string
     * @return string
     */
    public static function getFpDefenitions($defenition)
    {
        $retVal = array('KÄRL 1', 'KÄRL 2', 'TVÅDELAT KÄRL', 'TRÄDGÅRDSAVFALL', 'RESTAVFALL', false);
        switch ($defenition) {
            // Bjuv/Åstorp
            case 'Mat+Rest+Hp+Fg':
                //(Töms normalt varannan vecka)
                $type = $retVal[0];
                break;
            case 'Ti+Pf+Me+Og':
                //(Töms normalt var fjärde vecka)
                $type = $retVal[1];
                break;
            // Båstad/Ängelholm
            case 'Mat+Rest+Me+Hp':
                //(Töms normalt varannan vecka)
                $type = $retVal[0];
                break;
            case 'Ti+Pf+Fg+Og':
                //(Töms normalt var fjärde vecka)
                $type = $retVal[1];
                break;
            // Helsingborg
            case 'Mat+Rest+Pf+Fg':
                //(Töms normalt varannan vecka)
                $type = $retVal[0];
                break;
            case 'Me+Og+Ti+Plast':
                //(Töms normalt var fjärde vecka)
                $type = $retVal[1];
                break;
            case 'Mat+Rest':
                //(Töms normalt varannan vecka)
                $type = $retVal[2];
                break;
            case 'Trädgårdsavfall':
                //(Töms normalt varannan vecka)
                $type = $retVal[3];
                break;
            case "Restavfall":
                $type = $retVal[4];
                break;
            default:
                $type = $retVal[5];
        }
        return $type;
    }

    /**
     *  fetchDataFromFetchPlanner
     *  Get data from Fetchplanners API
     */
    public function fetchDataFromFetchPlanner()
    {
        $collection = new \VcExtended\Library\Helper\Collection();
        $data = self::fetchPlansByCurl('/GetPickupDatabyName?pickupName=' . trim(urlencode($_GET['query'])) . '&maxCount=50');

        $executeDates['fp'] = array();
        $colData['fp'] = array();

        $int = 0;
        $todaysDate = date('Y-m-d');
        $stopDate = date("Y-m-d", strtotime("$todaysDate +26 days"));
        $countCities = 0;
        $checkCityDupes = array();

        foreach ($data->d as $item) {
            if (!in_array($item->PickupCity, $checkCityDupes))
                array_push($checkCityDupes, $item->PickupCity);
            $countCities++;
        }

        foreach ($data->d as $item) {
            if (in_array($item->PickupCity, $checkCityDupes)) {
                $fpId = self::gen_uid($item->PickupId);
                $collect = $collection->getItem($fpId);

                if ($collect === false) {

                    $fpData = self::fetchPlansByCurl('/GetCalendarData?pickupId=' . $item->PickupId . '&maxCount=40&DateEnd=' . $stopDate);
                    $containerData = self::fetchPlansByCurl('/GetContainerData?pickupId=' . $item->PickupId);

                    $colData['fp'][$int]['id'] = $fpId;
                    $colData['fp'][$int]['Adress'] = $item->PickupName;
                    $colData['fp'][$int]['Ort'] = ucfirst(mb_strtolower($item->PickupCity));
                    $fInt = 0;

                    foreach ($fpData->d as $fpItem) {
                        foreach ($containerData->d as $contInfo) {
                            if ($contInfo->ContainerId === $fpItem->ContainerId) {
                                $date = self::setDateFormat($fpItem->ExecutionDate);
                                $datetime = new \DateTime($date);
                                $colData['fp'][$int]['Exec']['Datum'][$fInt] = $date;
                                $colData['fp'][$int]['Exec']['DatumFormaterat'][$fInt] = ucfirst(date_i18n('l j M', strtotime($datetime->format('F jS, Y'))));
                                $colData['fp'][$int]['Exec']['DatumKontroll'][$fInt] = $fpItem->ExecutionDate;
                                if ($datetime->format("W") % 2 == 1) {
                                    $colData['fp'][$int]['Exec']['DatumWeek'][$fInt] = "Udda veckor";
                                } else {
                                    $colData['fp'][$int]['Exec']['DatumWeek'][$fInt] = "Jämna veckor";
                                }
                                $colData['fp'][$int]['Exec']['AvfallsTyp'][$fInt] = self::getFpDefenitions($contInfo->ContentTypeCode);
                                $colData['fp'][$int]['Exec']['AvfallsTypFormaterat'][$fInt] = $contInfo->ContentTypeCode;
                            }
                        }
                        $fInt++;
                    }
                    $collection->addItem(json_decode(json_encode($colData), FALSE), $fpId);
                    $cityRemove = array_search($item->PickupCity, $checkCityDupes);
                    unset($checkCityDupes[$cityRemove]);
                }
                $collect = $collection->getItem($fpId);
                $executeDates['fp'][$int]['id'] = $collect->fp[$int]->id;
                $executeDates['fp'][$int]['Adress'] = $collect->fp[$int]->Adress;
                $executeDates['fp'][$int]['Ort'] = $collect->fp[$int]->Ort;

                for ($fpInt = 0; $fpInt < count($collect->fp[$int]->Exec->Datum); $fpInt++) {
                    $executeDates['fp'][$int]['Exec']['Datum'][$fpInt] = $collect->fp[$int]->Exec->Datum[$fpInt];
                    $executeDates['fp'][$int]['Exec']['DatumFormaterat'][$fpInt] = $collect->fp[$int]->Exec->DatumFormaterat[$fpInt];
                    $executeDates['fp'][$int]['Exec']['DatumKontroll'][$fpInt] = $collect->fp[$int]->Exec->DatumKontroll[$fpInt];
                    $executeDates['fp'][$int]['Exec']['DatumWeek'][$fpInt] = $collect->fp[$int]->Exec->DatumWeek[$fpInt];
                    $executeDates['fp'][$int]['Exec']['AvfallsTyp'][$fpInt] = $collect->fp[$int]->Exec->AvfallsTyp[$fpInt];
                    $executeDates['fp'][$int]['Exec']['AvfallsTypFormaterat'][$fpInt] = $collect->fp[$int]->Exec->AvfallsTypFormaterat[$fpInt];
                }
                $int++;
            }
        }
        wp_send_json($executeDates);
        exit;
    }

    /**
     *  fetch_data
     *  Get data from Elastic Search
     */
    public function fetchDataFromElasticSearch()
    {
        $result = \VcExtended\Library\Search\QueryElastic::jsonSearch(array('query' => $_GET['query'], 'limit' => $_GET['limit'], 'post_type' => $_GET['post_type'], 'section' => $_GET['post_section']));

        $int = 0;
        if ($result['content']) {
            foreach ($result['content'] as $property) {
                $result['content'][$int]->guid = str_replace(get_site_url(), "", get_permalink($property->ID));
                $int++;
            }
        }
        if ($result['sortguide']) {
            for ($metaInt = 0; $metaInt < count($result['sortguide']); $metaInt++) {

                $result['sortguide'][$metaInt]->post_meta = get_post_meta($result['sortguide'][$metaInt]->ID);

                for ($int1 = 0; $int1 < count($result['sortguide'][$metaInt]->post_meta['avfall_fraktion']); $int1++) {

                    $termId = maybe_unserialize($result['sortguide'][$metaInt]->post_meta['avfall_fraktion'][$int1]);
                    $getTerm = get_term(intval($termId[$int1]));
                    $termlink = get_term_meta(intval($termId[$int1]));
                    $termPageLink = get_page_link($termlink['fraktion_page_link'][0]);

                    if ($result['sortguide'][$metaInt]->post_meta['avfall_fraktion'][0]) {
                        if (strpos($termPageLink, '?page_id=') !== false)
                            $termPageLink = false;
                        if ($termPageLink) {
                            $termName = "<a href='" . $termPageLink . "'>" . $getTerm->name . "</a>";
                        } else {
                            $termName = "<span class='nofraktionlink'>" . $getTerm->name . "</span>";
                        }
                        $result['sortguide'][$metaInt]->post_meta['avfall_fraktion'][$int1] = $termName;
                    }
                    unset($termName);
                    unset($termPageLink);
                }
            }

            for ($metaInt = 0; $metaInt < count($result['sortguide']); $metaInt++) {

                $result['sortguide'][$metaInt]->post_meta = get_post_meta($result['sortguide'][$metaInt]->ID);

                $frakt = array(array('avc', $result['sortguide'][$metaInt]->post_meta['avfall_fraktion_avc'][0]), array('hemma', $result['sortguide'][$metaInt]->post_meta['avfall_fraktion_hemma'][0]));

                foreach ($frakt as $fraktion) {

                    $getFraktionTerm = get_term(intval($fraktion[1]));
                    $fraktionTermlink = get_term_meta(intval($fraktion[1]));


                    $extLink = $fraktionTermlink['fraktion_extern_link'][0];

                    if ($extLink) {
                        $fraktionTermPageLink = $extLink;
                    } else {
                        $fraktionTermPageLink = get_page_link($fraktionTermlink['fraktion_page_link'][0]);
                    }

                    if (strpos($fraktionTermPageLink, '?page_id=') !== false)
                        $fraktionTermPageLink = false;

                    if ($fraktionTermPageLink) {
                        $termName = "<a href='" . $fraktionTermPageLink . "'>" . $getFraktionTerm->name . "</a>";
                    } else {
                        $termName = "<span class='nofraktionlink'>" . $getFraktionTerm->name . "</span>";
                    }



                    if ($fraktion[0] === 'avc')
                        $result['sortguide'][$metaInt]->post_meta['fraktion_avc']['name'] = $termName;

                    if ($fraktion[0] === 'hemma')
                        $result['sortguide'][$metaInt]->post_meta['fraktion_hemma']['name'] = $termName;

                    if ($fraktion[0] === 'avc') {
                        $result['sortguide'][$metaInt]->post_meta['fraktion_avc']['name'] = $getFraktionTerm->name;
                        $result['sortguide'][$metaInt]->post_meta['fraktion_avc']['link'] = $fraktionTermPageLink;
                    }

                    if ($fraktion[0] === 'hemma') {
                        $result['sortguide'][$metaInt]->post_meta['fraktion_hemma']['name'] = $getFraktionTerm->name;
                        $result['sortguide'][$metaInt]->post_meta['fraktion_hemma']['link'] = $fraktionTermPageLink;
                    }

                }
                $fraktionsInt = 0;
                $checkDupes = array();

                $terms = get_the_terms($result['sortguide'][$metaInt]->ID,'fraktioner');

                foreach ($terms as $termsFraktion) {

                    $fraktId = $termsFraktion->term_id;
                    $termObject = get_field('fraktion_inlamningsstallen', 'fraktioner_' . $fraktId);
                    $lint = 0;

                    foreach ($termObject as $termLocID) {

                        $termInlamningsstalle = get_term($termLocID, 'inlamningsstallen');
                        $getTerm = get_term_meta($termLocID);
                        if ($getTerm['fraktion_extern_page_link'][0]) {
                            $termPageLink = $getTerm['fraktion_extern_page_link'][0];
                        } else {
                            $termPageLink = get_page_link(intval($getTerm['fraktion_page_link'][0]));
                        }

                        if (!in_array($termInlamningsstalle->term_id, $checkDupes)) {
                            $result['sortguide'][$metaInt]->post_meta['inlamningsstallen'][$fraktionsInt][$lint]['termId'] = $termInlamningsstalle->term_id;
                            $result['sortguide'][$metaInt]->post_meta['inlamningsstallen'][$fraktionsInt][$lint]['city'] = $termInlamningsstalle->name;
                            $result['sortguide'][$metaInt]->post_meta['inlamningsstallen'][$fraktionsInt][$lint]['lat'] = $getTerm['inlamningsstalle_latitude'][0];
                            $result['sortguide'][$metaInt]->post_meta['inlamningsstallen'][$fraktionsInt][$lint]['long'] = $getTerm['inlamningsstalle_longitude'][0];
                            $result['sortguide'][$metaInt]->post_meta['inlamningsstallen'][$fraktionsInt][$lint]['pageurl'] = $termPageLink;
                            $lint++;
                            array_push($checkDupes, $termInlamningsstalle->term_id);
                        }

                    }
                    $fraktionsInt++;
                }

            }

        }




        wp_send_json($result);
        exit;
    }
}
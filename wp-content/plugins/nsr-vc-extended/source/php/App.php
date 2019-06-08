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

define('ANDRADTOMNING', '2019-07-22', '2019-12-23, 2019-12-24, 2019-12-25, 2019-12-26, 2019-12-27, 2019-12-28, 2019-12-29, 2019-12-30, 2019-12-31');


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

        // ZapCal classes
        if (!class_exists('ZDateHelper')) {
            new \VcExtended\Library\ZapCal\ZDateHelper();
        }
        if (!class_exists('ZCiCalDataNode')) {
            new \VcExtended\Library\ZapCal\ZCiCalDataNode();
        }
        if (!class_exists('ZCiCalNode')) {
            new \VcExtended\Library\ZapCal\ZCiCalNode();
        }
        if (!class_exists('ZCRecurringDate')) {
            new \VcExtended\Library\ZapCal\ZCRecurringDate();
        }
        if (!class_exists('ZCTimeZoneHelper')) {
            new \VcExtended\Library\ZapCal\ZCTimeZoneHelper();
        }
        if (!class_exists('ZCiCal')) {
            new \VcExtended\Library\ZapCal\ZCiCal();
        }

        // FPDF
        if (!class_exists('FPDF')) {
            new \VcExtended\Library\FPDF\FPDF();
        }
        if (!class_exists('FPDFCalendar')) {
            new \VcExtended\Library\FPDF\FPDFCalendar();
        }
        

        add_action('wp_ajax_nopriv_fetchDataFromElasticSearch', array($this, 'fetchDataFromElasticSearch'));
        add_action('wp_ajax_fetchDataFromElasticSearch', array($this, 'fetchDataFromElasticSearch'));
        add_action('wp_ajax_nopriv_fetchDataFromFetchPlanner', array($this, 'fetchDataFromFetchPlanner'));
        add_action('wp_ajax_fetchDataFromFetchPlanner', array($this, 'fetchDataFromFetchPlanner'));
        add_action('wp_ajax_nopriv_fetchDataFromFetchPlannerCombined', array($this, 'fetchDataFromFetchPlannerCombined'));
        add_action('wp_ajax_fetchDataFromFetchPlannerCombined', array($this, 'fetchDataFromFetchPlannerCombined'));
        add_action('wp_ajax_nopriv_fetchDataFromFetchPlannerCalendar', array($this, 'fetchDataFromFetchPlannerCalendar'));
        add_action('wp_ajax_fetchDataFromFetchPlannerCalendar', array($this, 'fetchDataFromFetchPlannerCalendar'));
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
    public static function getFpDefenitions($defenition, $jobtemplate)
    {
        if ($jobtemplate == "Extra tömning")
            $jobtemplate = "Tömning";

        // Bjuv/Åstorp
        if ($defenition == 'Mat+Rest+Hp+Fg' && $jobtemplate == "Tömning")    //(Töms normalt varannan vecka)
            return 'KÄRL 1';
        if ($defenition == 'Ti+Pf+Me+Og' && $jobtemplate == "Tömning")    //(Töms normalt var fjärde vecka)
            return 'KÄRL 2';

        // Båstad/Ängelholm
        if ($defenition == 'Mat+Rest+Me+Hp' && $jobtemplate == "Tömning")    //(Töms normalt varannan vecka)
            return 'KÄRL 1';
        if ($defenition == 'Ti+Pf+Fg+Og' && $jobtemplate == "Tömning")    //(Töms normalt var fjärde vecka)
            return 'KÄRL 2';

        // Helsingborg
        if ($defenition == 'Mat+Rest+Pf+Fg' && $jobtemplate == "Tömning")    //(Töms normalt varannan vecka)
            return 'KÄRL 1';
        if ($defenition == 'Me+Og+Ti+Plast' && $jobtemplate == "Tömning")    //(Töms normalt var fjärde vecka)
            return 'KÄRL 2';
        if ($defenition == 'Ti+Me+Og+Plast' && $jobtemplate == "Tömning")    //(Töms normalt var fjärde vecka)
            return 'KÄRL 2';

        if ($defenition == 'Matavfall' && $jobtemplate == "Tömning")
            return 'MATAVFALL';
        if ($defenition == 'Mat+Rest' && $jobtemplate == "Tömning")
            return 'TVÅDELAT KÄRL';
        if ($defenition == 'Trädgårdsavfall' && $jobtemplate == "Tömning")
            return 'TRÄDGÅRDSAVFALL';
        if ($defenition == 'Restavfall' && $jobtemplate == "Tömning")
            return 'RESTAVFALL';
        if ($defenition == 'Slam' && $jobtemplate == "Tömning")
            return 'SLAM';
        if ($defenition == 'Slam' && $jobtemplate == "Fast 7 dagars")
            return 'SLAM';
        if ($defenition == 'Slam' && $jobtemplate == "Kampanjtömning")
            return 'SLAM';
        if ($defenition == 'Slam')
            return 'SLAM';

        if ($jobtemplate == "Mjukplast")
            return 'MJUKPLAST';
        if ($jobtemplate == "Grovsopor")
            return 'GROVSOPOR';
        if ($jobtemplate == "Röd box")
            return 'RÖD BOX';

        if ($defenition == 'Slam kampanj' || $jobtemplate == "Slam kampanj")
            return 'SLAM';
        if ($defenition == 'Slam fast 7 dagars' || $jobtemplate == "Slam fast 7 dagars")
            return 'SLAM';
        if ($defenition == 'Slam budad 7 dagar' || $jobtemplate == "Slam budad 7 dagar")
            return 'SLAM';
        if ($defenition == 'Slam budad 2 dagar' || $jobtemplate == "Slam budad 2 dagar")
            return 'SLAM';
        if ($defenition == 'Slam akut' || $jobtemplate == "Slam akut")
            return 'SLAM';
        if ($defenition == 'Slam akut nästa arbetsdag' || $jobtemplate == "Slam akut nästa arbetsdag")
            return 'SLAM';

        // Tillagt 190608
        if ($defenition == 'Tidningar' && $jobtemplate == "Tömning")
            return 'TIDNINGAR';
        if ($defenition == 'Hårdplast' && $jobtemplate == "Tömning")
            return 'HÅRDPLAST';
        if ($defenition == 'Mjukplast' && $jobtemplate == "Tömning")
            return 'MJUKPLAST';
        if ($defenition == 'Ofärgat glas' && $jobtemplate == "Tömning")
            return 'OFÄRGAT GLAS';
        if ($defenition == 'Färgat glas' && $jobtemplate == "Tömning")
            return 'FÄRGAT GLAS';
        if ($defenition == 'Pappersförpackningar' && $jobtemplate == "Tömning")
            return 'PAPPERSFÖRPACK.';
        if ($defenition == 'Metallförpackningar' && $jobtemplate == "Tömning")
            return 'METALLFÖRPACK.';

       // return "$jobtemplate $defenition";
        return false;

            
        /*
        $retVal = array('KÄRL 1', 'KÄRL 2', 'TVÅDELAT KÄRL', 'TRÄDGÅRDSAVFALL', 'RESTAVFALL', 'SLAM', false);
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
            case "Slam":
                $type = $retVal[5];
                break;
            default:
                $type = $retVal[6];
        }
        return $type;
        */


        //return false;
    }

    /**
     *  fetchDataFromFetchPlannerInternal
     *  Get data from Fetchplanners API
     */
     private function fetchDataFromFetchPlannerInternal($from, $to, $q, $post_type, $maxcount=10)
     {
        // Turn 3B into 3 B
        $qp = explode(" ", $q);
        if (count($qp) > 1 && preg_match('!^([0-9]+)([A-Za-z])$!', $qp[count($qp)-1], $m) && count($m) == 3) {
            array_pop($qp);
            array_push($qp, $m[1]);
            array_push($qp, $m[2]);
            $q = implode(" ", $qp);
        }

        // Only call on empty post_type or tomningskalender
        if (!($post_type == "" || $post_type == "all" || $post_type == "tomningskalender")) {
            return array('fp'=>array(), 'q'=>$q);
            exit;
        }
        
        // Ändrade tömningsdagar
        $andrtomn = array_map(function ($d) { return trim($d); }, explode(",", ANDRADTOMNING));

        $data = self::fetchPlansByCurl('/GetContainerCalendarDataByPickupName?pickupName=' .
            trim(urlencode($q)) . '&dateStart=' . $todaysDate . '&dateEnd=' . $stopDate . '&maxCountCalendarPerContainer=' . $maxcount);

        $int = 0;
        $colData['fp'] = array();
        $colData['q'] = $q;
        $idToIndex = array();
        $dupIdTypDate = array();

        foreach ($data->d as $item) {
            //$fpId = md5('PICKUPID' . $item->PickupId);
            //$fpId = $item->PickupId;
            $fpId = md5('CUSTOMERID' . $item->CustomerId);

            if (isset($idToIndex[$fpId]))
                $i = $idToIndex[$fpId];
            else {
                $i = $idToIndex[$fpId] = $int++;           
                $colData['fp'][$i]['id'] = $fpId;
                $colData['fp'][$i]['Adress'] = $item->PickupName;
                $colData['fp'][$i]['Ort'] = ucfirst(mb_strtolower($item->PickupCity));
                $colData['fp'][$i]['Exec'] = array(
                    'Datum'=>array(),
                    'DatumFormaterat'=>array(),
                    'DatumKontroll'=>array(),
                    'DatumWeek'=>array(),
                    'AvfallsTyp'=>array(),
                    'AvfallsTypFormaterat'=>array(),
                    'Andrad'=>array()
                );
            }    

            foreach ($item->Calendars as $cal) {
                $date = self::setDateFormat($cal->ExecutionDate);
                $datetime = new \DateTime($date);
                $typ = self::getFpDefenitions($item->ContentTypeCode, $cal->JobTemplate);

//                echo $item->CustomerId . " $date $typ " .$item->ContentTypeCode ." " . $cal->JobTemplate."\n";
                if (isset($dupIdTypDate[$fpId . $typ . $date]))
                    continue;
                $dupIdTypDate[$fpId . $typ . $date] = 1;

                for ($j=0 ; $j<count($colData['fp'][$i]['Exec']['Datum']) ; $j++) {
                    if ($colData['fp'][$i]['Exec']['Datum'][$j] > $date)
                        break;
                }

                array_splice($colData['fp'][$i]['Exec']['Datum'], $j, 0, $date);
                array_splice($colData['fp'][$i]['Exec']['DatumFormaterat'], $j, 0, ucfirst(date_i18n('l j M', strtotime($datetime->format('F jS, Y')))));
                array_splice($colData['fp'][$i]['Exec']['DatumKontroll'], $j, 0, $cal->ExecutionDate);
                array_splice($colData['fp'][$i]['Exec']['DatumWeek'], $j, 0, ($datetime->format("W") % 2 == 1) ? 'Udda veckor' : 'Jämna veckor');
                array_splice($colData['fp'][$i]['Exec']['AvfallsTyp'], $j, 0, $typ);
                array_splice($colData['fp'][$i]['Exec']['AvfallsTypFormaterat'], $j, 0, $item->ContentTypeCode);
                array_splice($colData['fp'][$i]['Exec']['Andrad'], $j, 0, in_array($date, $andrtomn) ? 1 : 0);
            }
        }

        usort($colData['fp'], function($v, $w) use ($q) {
            $len = strlen($q);
            $a = $v['Adress'];
            $b = $w['Adress'];
            if (!strcasecmp($a, $q))
                return -1;
            if (!strcasecmp($b, $q))
                return 1;
            if (!strncasecmp($a, $q, $len) && strncasecmp($b, $q, $len))
                return -1;
            if (strncasecmp($a, $q, $len) && !strncasecmp($b, $q, $len))
                return 1;
            return strcasecmp($a, $b);                
        });

        return $colData;
     }

     
    public function fetchDataFromFetchPlannerCombined() {
        $todaysDate = date('Y-m-d');
        $stopDate = date("Y-m-d", strtotime("$todaysDate +26 days"));
        $q = $_GET['query'];
        $post_type = $_GET['post_type'];

        $colData  = $this->fetchDataFromFetchPlannerInternal($todaysDate, $stopDate, $q, $post_type);
        return wp_send_json($colData);
    }

    private function sendEmptyCalendar($calendar_type) {
        if ($calendar_type == "ical") {
            $icalobj = new \VcExtended\Library\ZapCal\ZCiCal("Tömningskalender");          
            header('Content-Disposition: filename="tomningskalender.ics"');
            header("Content-Type: Text/Calendar");
            echo $icalobj->export();
            exit;    
        }

        echo "<h4>Ingen kalender kunde genereras för denna tömningsplats.</h4>";
        exit;
    }

    public function fetchDataFromFetchPlannerCalendar() {
        $todaysDate = date('Y-m-d');
        $stopDate = date("Y-m-d", strtotime("$todaysDate +365 days"));
        $q = $_GET['query'];
        $post_type = $_GET['post_type'];
        $calendar_type = ($_GET['calendar_type'] == "ical") ? "ical" : "pdf";
        $id = isset($_GET['id']) ? $_GET['id'] : "";

        $colData  = $this->fetchDataFromFetchPlannerInternal($todaysDate, $stopDate, $q, $post_type, 100);

        if (!$colData || !isset($colData['fp']) || !count($colData['fp'])) {
            $this->sendEmptyCalendar($calendar_type);
            exit;
        }

        $exec = null;
        $title = "";
        do {
            $post = @array_shift($colData['fp']);
            if ($post && isset($post['Exec']) && ($id == "" || $id == $post['id'])) {
                for ($i=0 ; $i<count($post['Exec']['AvfallsTyp']) ; $i++) {
                    if (strlen($post['Exec']['AvfallsTyp'][$i]) >= 1) {
                        $exec = $post['Exec'];
                        $title = $post['Adress'] . ', ' . $post['Ort'];
                        break;
                    }
                }
            }
        } while ($exec == null && count($colData['fp']) > 0);

        if (!$exec) {
            $this->sendEmptyCalendar($calendar_type);
            exit;
        }

        $results = array();
        $dub = array();
        for ($avint = 0; $avint < count($exec['AvfallsTyp']); $avint++) {
          //  echo "<pre>$avint " . $exec['Datum'][$avint] . " ". $exec['AvfallsTyp'][$avint] ."</pre>";

            if (strtotime($exec['Datum'][$avint]) >= strtotime($todaysDate) && $exec['AvfallsTyp'][$avint] != "") {
                $k = $exec['AvfallsTyp'][$avint] . ' ' . $exec['Datum'][$avint];
                if (!isset($dub[$k])) {
                    $dub[$k] = 1;
                    $results[] = array('Datum'=>$exec['Datum'][$avint], 'AvfallsTyp'=>$exec['AvfallsTyp'][$avint]);
                }
            }
        }

        if (!count($results)) {
            $this->sendEmptyCalendar($calendar_type);
            exit;
        }

        // Ändrade tömningsdagar
        $andrtomn = array_map(function ($d) { return trim($d); }, explode(",", ANDRADTOMNING));

        //
        // ICAL
        //
        if ($calendar_type == "ical") {
            $icalobj = new \VcExtended\Library\ZapCal\ZCiCal("Tömningskalender");
            $index = 1;

            foreach ($results as $result) {
                if (in_array($result['Datum'], $andrtomn)) {
                    $eventobj = new \VcExtended\Library\ZapCal\ZCiCalNode("VEVENT", $icalobj->curnode);
                    $eventobj->addNode(new \VcExtended\Library\ZapCal\ZCiCalDataNode("SUMMARY:" . $result['AvfallsTyp']));
                    $eventobj->addNode(new \VcExtended\Library\ZapCal\ZCiCalDataNode("DTSTART:" . \VcExtended\Library\ZapCal\ZCiCal::fromSqlDateTime($result['Datum'] . " 00:00:00")));
                    $eventobj->addNode(new \VcExtended\Library\ZapCal\ZCiCalDataNode("DTEND:" . \VcExtended\Library\ZapCal\ZCiCal::fromSqlDateTime($result['Datum'] . " 23:59:59")));
                    $eventobj->addNode(new \VcExtended\Library\ZapCal\ZCiCalDataNode("UID:" . date('Y-m-d-H-i-s') . "@nsr.se" . "#" . ($index++)));
                    $eventobj->addNode(new \VcExtended\Library\ZapCal\ZCiCalDataNode("DTSTAMP:" . \VcExtended\Library\ZapCal\ZCiCal::fromSqlDateTime()));
                    $eventobj->addNode(new \VcExtended\Library\ZapCal\ZCiCalDataNode("Description:" . \VcExtended\Library\ZapCal\ZCiCal::formatContent("Ändrad tömningsdag - ställ ut kärl en dag tidigare")));
                }
                $text = $result['AvfallsTyp'] . " - " . $title;
                $eventobj = new \VcExtended\Library\ZapCal\ZCiCalNode("VEVENT", $icalobj->curnode);
                $eventobj->addNode(new \VcExtended\Library\ZapCal\ZCiCalDataNode("SUMMARY:" . $result['AvfallsTyp']));
                $eventobj->addNode(new \VcExtended\Library\ZapCal\ZCiCalDataNode("DTSTART:" . \VcExtended\Library\ZapCal\ZCiCal::fromSqlDateTime($result['Datum'] . " 00:00:00")));
                $eventobj->addNode(new \VcExtended\Library\ZapCal\ZCiCalDataNode("DTEND:" . \VcExtended\Library\ZapCal\ZCiCal::fromSqlDateTime($result['Datum'] . " 23:59:59")));
                $eventobj->addNode(new \VcExtended\Library\ZapCal\ZCiCalDataNode("UID:" . date('Y-m-d-H-i-s') . "@nsr.se" . "#" . ($index++)));
                $eventobj->addNode(new \VcExtended\Library\ZapCal\ZCiCalDataNode("DTSTAMP:" . \VcExtended\Library\ZapCal\ZCiCal::fromSqlDateTime()));
                $eventobj->addNode(new \VcExtended\Library\ZapCal\ZCiCalDataNode("Description:" . \VcExtended\Library\ZapCal\ZCiCal::formatContent($text)));
            }
        
            header('Content-Disposition: filename="tomningskalender.ics"');
            header("Content-Type: Text/Calendar");
            echo $icalobj->export();
            exit;
        }

        //
        // PDF
        //
        $bydate = array();
        $lastyear = 0;
        $lastmonth = 0;
        foreach ($results as $v) {
            $d = $v['Datum'];
            $a = $v['AvfallsTyp'];
            if (!isset($bydate[$d]))
                $bydate[$d] = "";
            $bydate[$d] .= $a . "\n";

            $y = (int)substr($d, 0, 4);
            $m = (int)substr($d, 5, 2);
            if (!$lastyear || !$lastmonth || $lastyear < $y || ($lastyear == $y && $lastmonth < $m)) {
                $lastyear = $y;
                $lastmonth = $m;
            }
        }

        $pdf = new \VcExtended\Library\FPDF\FPDFCalendar("L", "A4");
        $pdf->SetMargins(7,7);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->SetFillColor(190,190,250);
        $year = (int)gmdate("Y");
        $month = (int)gmdate("m");
        for ($monthi = 0; $monthi <= 12; $monthi++) {
            $date = $pdf->MDYtoJD($month, 1, $year);
            $pdf->printMonth($title, $date, $bydate, $andrtomn);
            if ($year == $lastyear && $month == $lastmonth)
                break;
            if ($month == 12) {
                $month = 1;
                $year++;
            }
            else {
                $month++;
            }
        }

        header('Content-Disposition: filename="tomningskalender.pdf"');
        $pdf->Output();

        exit;  
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


                    //$fpData = self::fetchPlansByCurl('/GetCalendarData?customerId=' . $item->CustomerId . '&maxCount=40&DateEnd=' . $stopDate);
                    //$fpData = self::fetchPlansByCurl('/GetCalendarData?customerId=1025636&maxCount=40&DateEnd=' . $stopDate);
                    $containerData = self::fetchPlansByCurl('/GetContainerData?pickupId=' . $item->PickupId);
                    //$containerData = self::fetchPlansByCurl('/GetContainerData?customerId=' . $item->CustomerId);
                    //$containerData = self::fetchPlansByCurl('/GetContainerData?customerId=1025636');

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

                $frakt = array(array('avc', $result['sortguide'][$metaInt]->post_meta['avfall_fraktion_avc'][0]),
                               array('hemma', $result['sortguide'][$metaInt]->post_meta['avfall_fraktion_hemma'][0]));

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
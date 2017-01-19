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
        if ( !class_exists( 'MasterVCExtended' ) ) {
            new \VcExtended\Library\Addons\MasterVCExtended();
        }


        /**
         * Ad-dons Collapsible menus
         */
        if ( !class_exists( 'MenuCollapsible' ) ) {
            new \VcExtended\Library\Addons\MenuCollapsible();
        }

        /**
         * Ad-dons List News & Posts
         */
        if ( !class_exists( 'ListNewsAndPosts' ) ) {
            new \VcExtended\Library\Addons\ListNewsAndPosts();
        }

        /**
         * Ad-dons List links with a thumbnail
         */
        if ( !class_exists( 'ListLinksWithThumbnail' ) ) {
            new \VcExtended\Library\Addons\ListLinksWithThumbnail();
        }

        /**
         * Ad-dons Thumbnail, link heading and description
         */
        if ( !class_exists( 'ThumbnailAndTextarea' ) ) {
            new \VcExtended\Library\Addons\ThumbnailAndTextarea();
        }

        /**
         * Ad-dons Thumbnail, link heading and description
         */
        if ( !class_exists( 'Puff' ) ) {
            new \VcExtended\Library\Addons\Puff();
        }


        /**
         * Ad-dons NSR openHour
         */
        if ( !class_exists( 'OpenHours' ) ) {
            new \VcExtended\Library\Addons\OpenHours();
        }


        /**
         * Ad-dons Elastic Site Search
         */
        if ( !class_exists( 'NSRSearch' ) ) {
            new \VcExtended\Library\Addons\NSRSearch();
        }

        /**
         *  Helper - PostType
         */
        if ( !class_exists( 'PostType' ) ) {
            new \VcExtended\Library\Helper\PostType();
        }


        /**
         * Elastic
         */
        if ( !class_exists( 'Elasticsearch' ) ) {
            new \VcExtended\Library\Search\ElasticSearch();
        }


        /**
         * Elastic search Query
         */
        if ( !class_exists( 'QueryElastic' ) ) {
            new \VcExtended\Library\Search\QueryElastic();
        }


        /**
         * Enqueue Scripts
         */
        if ( !class_exists( 'Enqueue' ) ) {
            new \VcExtended\Library\Enqueue();
        }

        add_action( 'wp_ajax_nopriv_fetch_data', array( $this, 'fetch_data' ) );
        add_action( 'wp_ajax_fetch_data', array( $this, 'fetch_data' ) );

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
     *  fetch_data
     *  Get data from Elastic Search
     */
    public function fetch_data()
    {

        $result = \VcExtended\Library\Search\QueryElastic::jsonSearch(array( 'query' => $_GET['query'],'limit'=>$_GET['limit'], 'post_type' => $_GET['post_type'], 'section' => $_GET['post_section'] ));
        $int = 0;

        if ($result['content']){
            foreach ($result['content'] as $property) {
                $result['content'][$int]->guid = str_replace(get_site_url(), "", get_permalink($property->ID));
                $int++;
            }
        }

        if ($result['sortguide']) {

            for($metaInt=0;$metaInt < count($result['sortguide']); $metaInt++) {
                for ($int1 = 0; $int1 < count($result['sortguide'][$metaInt]->post_meta['avfall_fraktion']); $int1++) {
                    $termId = maybe_unserialize($result['sortguide'][$metaInt]->post_meta['avfall_fraktion'][$int1]);
                    $getTerm = get_term(intval($termId[$int1]));
                    $termlink = get_term_meta(intval($termId[$int1]));
                    $termPageLink = get_page_link($termlink['fraktion_page_link'][0]);
                    if ($result['sortguide'][$metaInt]->post_meta['avfall_fraktion'][0]) {

                        if ($termPageLink) {
                            $termName = "<i class='material-icons'>description</i> <a href='" . $termPageLink . "'>" . $getTerm->name . "</a>";
                        } else {
                            $termName = $getTerm->name;
                        }
                        $result['sortguide'][$metaInt]->post_meta['avfall_fraktion'][$int1] = $termName;
                    }
                }
            }

            for($metaInt=0;$metaInt < count($result['sortguide']); $metaInt++) {
                for ($int2 = 0; $int2 < count($result['sortguide'][$metaInt]->post_meta['avfall_fraktion_hemma']); $int2++) {
                    $termId = maybe_unserialize($result['sortguide'][$metaInt]->post_meta['avfall_fraktion_hemma'][$int2]);
                    $getTerm = get_term(intval($termId[$int2]));
                    $termlink = get_term_meta( intval($termId[$int2]) );
                    $termPageLink = get_page_link($termlink['fraktion_page_link'][0]);
                    if($result['sortguide'][$metaInt]->post_meta['avfall_fraktion_hemma'][0]) {

                        if($termPageLink) {
                            $termName = "<i class='material-icons'>description</i> <a href='" . $termPageLink . "'>" . $getTerm->name . "</a>";
                        }
                        else {
                            $termName = $getTerm->name;
                        }

                        $result['sortguide'][$metaInt]->post_meta['avfall_fraktion_hemma'][$int2] = $termName;
                    }
                }

                $lint=0;

                foreach($result['sortguide'][$metaInt]->terms['inlamningsstallen'] as $term){
                    $getTerm = get_term_meta($term['term_id']);
                    $getTermLong = $getTerm['inlamningsstalle_longitude'];
                    $getTermLat = $getTerm['inlamningsstalle_latitude'];
                    $getTermCity = $getTerm['inlamningsstalle_stadort'];
                    $pageurl = $getTerm['tax_pageurl'];
                    $result['sortguide'][$metaInt]->terms['inlamningsstallen'][$lint]['lat'] = $getTermLat[0];
                    $result['sortguide'][$metaInt]->terms['inlamningsstallen'][$lint]['long'] = $getTermLong[0];
                    $result['sortguide'][$metaInt]->terms['inlamningsstallen'][$lint]['city'] = $getTermCity[0];
                    $result['sortguide'][$metaInt]->terms['inlamningsstallen'][$lint]['pageurl'] = $pageurl[0];
                    $lint++;
                }
            }
        }


        wp_send_json($result);
        exit;
    }

}



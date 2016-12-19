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
    public function fetch_data() {
        $result = \VcExtended\Library\Search\QueryElastic::jsonSearch(array( 'query' => $_GET['query'],'limit'=>$_GET['limit'] ));
        $int = 0;

        if ($result['content']){
            foreach ($result['content'] as $property) {
                $result['content'][$int]->guid = str_replace(get_site_url(), "", get_permalink($property->ID));
                $int++;
            }
        }

        wp_send_json($result);
        exit;
    }

}



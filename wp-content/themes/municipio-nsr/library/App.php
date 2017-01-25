<?php
namespace Nsr;

class App
{
    public function __construct()
    {

        /**
         * Template
         */

        new \Nsr\Theme\CustomPostTypes();
        new \Nsr\Theme\Enqueue();
        new \Nsr\Theme\SidebarsExtended();
        new \Nsr\Theme\NSRTemplates();
        new \Nsr\Theme\CustomPostTypesMetaSidebar();


        add_action( 'after_setup_theme', array( $this, 'nsr_theme_setup' ) );
        add_action( 'init', array( $this,'add_excerpts_to_pages' ) );

        add_action( 'admin_menu', array( $this,'nsr_change_post_label' ) );
        add_action( 'init', array( $this,'nsr_change_post_object' ) );

        add_action( 'init', array($this,'add_taxonomies_to_pages') );
        if ( ! is_admin() ) {
            add_action( 'pre_get_posts', array($this,'category_and_tag_archives') );
        }

        $this->customRedirects();
        $this->image_size();
        add_filter( 'image_size_names_choose', array($this,'nsr_image_sizes') );
        add_action('after_setup_theme', array( $this,'create_404_page') );

        if ( is_admin() ) {
            define('ALLOW_UNFILTERED_UPLOADS', true);
        }

        add_action( 'login_enqueue_scripts', array( $this, 'admin_login_logo') );
        add_filter( 'login_headerurl', array( $this,'login_logo_url') );
    }

    /**
     *  Image size
     *  Adding sizes
     *  @return void
     */
    public function image_size()
    {
        add_image_size( 'Bild-till-puff', 757, 267, true );
    }



    function fix_svg_thumb_display() {
        echo '
            td.media-icon img[src$=".svg"], img[src$=".svg"].attachment-post-thumbnail { 
              width: 100% !important; 
              height: auto !important; 
            }
          ';
    }


    /**
     *  nsr_theme_setup
     *  Adding language file
     *  @return void
     */
    public function nsr_theme_setup()
    {
        load_child_theme_textdomain( 'nsr', get_stylesheet_directory() . '/languages' );
    }


    /**
     *  add_excerpts_to_pages
     *  Adding Excerpts to page
     *  @return void
     */
    public function add_excerpts_to_pages()
    {
        add_post_type_support( 'page', 'excerpt' );
    }



    /**
     *  nsr_change_post_label
     *  Change designation post to news
     *  @return void
     */
    function nsr_change_post_label()
    {

        global $menu;
        global $submenu;

        $menu[5][0] = __('News', 'nsr');
        $submenu['edit.php'][5][0] = __('News', 'nsr');
        $submenu['edit.php'][10][0] = __('Add News', 'nsr');
        $submenu['edit.php'][16][0] = __('News Tags', 'nsr');
    }


    /**
     *  nsr_change_post_object
     *  Change designation post to news
     *  @return void
     */
    function nsr_change_post_object()
    {
        global $wp_post_types;
        $labels = &$wp_post_types['post']->labels;
        $labels->name = __('News', 'nsr');
        $labels->singular_name = __('News', 'nsr');
        $labels->add_new = __('Add News', 'nsr');
        $labels->add_new_item = __('Add News', 'nsr');
        $labels->edit_item = __('Edit News', 'nsr');
        $labels->new_item = __('Add', 'nsr');
        $labels->view_item = __('View News', 'nsr');
        $labels->search_items = __('Search News', 'nsr');
        $labels->not_found = __('No news found', 'nsr');
        $labels->not_found_in_trash = __('No news found in the trash', 'nsr');
        $labels->all_items = __('All News', 'nsr');
        $labels->menu_name = __('News', 'nsr');
        $labels->name_admin_bar = __('News', 'nsr');
    }


    /**
     *  add_taxonomies_to_pages
     *  Adding categories and tags to pages
     *  @return void
     */
    function add_taxonomies_to_pages()
    {
        register_taxonomy_for_object_type( 'post_tag', 'page' );
        register_taxonomy_for_object_type( 'category', 'page' );
    }


    /**
     *  category_and_tag_archives
     *  Adding categories and tags to pages
     *  @return void
     */
    function category_and_tag_archives( $wp_query )
    {
        $my_post_array = array('post','page');

        if ( $wp_query->get( 'category_name' ) || $wp_query->get( 'cat' ) )
            $wp_query->set( 'post_type', $my_post_array );

        if ( $wp_query->get( 'tag' ) )
            $wp_query->set( 'post_type', $my_post_array );
    }


    /**
     * customRedirects
     * Fixing redirects when saving taxanomy.
     * @return location
     */
    public function customRedirects()
    {

        add_filter( 'wp_redirect',
            function( $location ){
                $args = array(
                    'public'   => true,
                    '_builtin' => true

                );
                $taxonomy = get_taxonomies($args);
                foreach ($taxonomy  as $mytaxonomy) {
                    $args = array(
                        'action'   => FILTER_SANITIZE_STRING,
                        'taxonomy' => FILTER_SANITIZE_STRING,
                        'tag_ID'   => FILTER_SANITIZE_NUMBER_INT,
                    );
                    $_inputs    = filter_input_array( INPUT_POST, $args );
                    $_post_type = filter_input( INPUT_GET, 'post_type', FILTER_SANITIZE_STRING );
                    if( 'editedtag' === $_inputs['action']
                        or $mytaxonomy === $_inputs['taxonomy']
                        or $_inputs['tag_ID'] > 0
                    ){
                        $location = add_query_arg( 'action',   'edit',               $location );
                        $location = add_query_arg( 'taxonomy', $_inputs['taxonomy'], $location );
                        $location = add_query_arg( 'tag_ID',   $_inputs['tag_ID'],   $location );
                        if( $_post_type )
                            $location = add_query_arg( 'post_type', $_post_type, $location );
                    }
                    return $location;
                }
            }
        );
    }



    /**
     * create_404_page
     * Insert a privately published page we can query for our 404 page
     * @return location
     */
    function create_404_page() {

        // Check if the 404 page exists
        $page_exists = get_page_by_title( '404' );

        if (!isset($page_exists->ID)) {

            // Page array
            $page = array(
                'post_author' => 1,
                'post_content' => '',
                'post_name' =>  '404',
                'post_status' => 'private',
                'post_title' => '404',
                'post_type' => 'page',
                'post_parent' => 0,
                'menu_order' => 0,
                'to_ping' =>  '',
                'pinged' => '',
            );

            $insert = wp_insert_post($page);

            // The insert was successful
            if ($insert) {
                // Store the value of our 404 page
                update_option( '404pageid', (int) $insert );
            }
        }

    }

    function login_logo_url() {
        return home_url();
    }




}

<?php
namespace Nsr;

class App
{
    public function __construct()
    {
        new \Nsr\Theme\Enqueue();
        new \Nsr\Theme\SidebarsExtended();
        new \Nsr\Theme\NSRTemplates();
        new \Nsr\Theme\CustomNSRPages();

        add_action( 'after_setup_theme', array( $this, 'nsr_theme_setup' ) );
        add_action( 'init', array( $this,'add_excerpts_to_pages' ) );

        add_action( 'admin_menu', array( $this,'nsr_change_post_label' ) );
        add_action( 'init', array( $this,'nsr_change_post_object' ) );

        add_action( 'init', array($this,'add_taxonomies_to_pages') );
        if ( ! is_admin() ) {
            add_action( 'pre_get_posts', array($this,'category_and_tag_archives') );
        }


    }


    /**
     *  nsr_theme_setup
     *  Adding language file
     */
    public function nsr_theme_setup()
    {
        load_child_theme_textdomain( 'nsr', get_stylesheet_directory() . '/languages' );
    }


    /**
     *  add_excerpts_to_pages
     *  Adding Excerpts to page
     */
    public function add_excerpts_to_pages()
    {
        add_post_type_support( 'page', 'excerpt' );
    }



    /**
     *  nsr_change_post_label
     *  Change designation post to news
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
     */
    function add_taxonomies_to_pages()
    {
        register_taxonomy_for_object_type( 'post_tag', 'page' );
        register_taxonomy_for_object_type( 'category', 'page' );
    }


    /**
     *  category_and_tag_archives
     *  Adding categories and tags to pages
     */
    function category_and_tag_archives( $wp_query )
    {
        $my_post_array = array('post','page');

        if ( $wp_query->get( 'category_name' ) || $wp_query->get( 'cat' ) )
            $wp_query->set( 'post_type', $my_post_array );

        if ( $wp_query->get( 'tag' ) )
            $wp_query->set( 'post_type', $my_post_array );
    }




}

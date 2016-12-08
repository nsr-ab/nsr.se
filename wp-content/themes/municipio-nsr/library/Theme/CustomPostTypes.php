<?php

namespace Nsr\Theme;

class CustomPostTypes
{
    public function __construct()
    {

        add_action('init', array($this, 'register_custom_post_types'));
    }


    /**
     * Register static custom posttypes for Företag & Restauranger, Fastighetsägare & Bostadsrättsföreningar
     * @return void
     */
    function register_custom_post_types()
    {


        /* Villa & Fritidsboende */

        $labels = array(

                "name" => __( 'Pages Villa', 'nsr' ),
                "singular_name" => __( 'Pages Villa', 'nsr' ),
                "menu_name" => __( 'Pages Villa', 'nsr' ),
                "all_items" => __( 'All Items', 'nsr' ),
                "add_new" => __( 'Add New', 'nsr' ),
                "add_new_item" => __( 'Add New Item', 'nsr' ),
                "edit_item" => __( 'Edit Item', 'nsr' ),
                "new_item" => __( 'New Item', 'nsr' ),
                "view_item" => __( 'View Item', 'nsr' ),
                "search_items" => __( 'Search Item', 'nsr' ),
                "not_found" => __( 'Not Found', 'nsr' ),
                "not_found_in_trash" => __( 'Not Found in Trash', 'nsr' ),
                "parent_item_colon" => __( 'Parent', 'nsr' ),
                "featured_image" => __( 'Featured Image', 'nsr' ),
                "set_featured_image" => __( 'Set Featured Image', 'nsr' ),
                "remove_featured_image" => __( 'Remove Featured Image', 'nsr' ),
                "use_featured_image" => __( 'Use Featured Image', 'nsr' ),
                "archives" => __( 'Use Featured Image', 'nsr' ),
                "insert_into_item" => __( 'Insert into item', 'nsr' ),
                "uploaded_to_this_item" => __( 'Uploaded to this Item', 'nsr' ),
                "filter_items_list" => __( 'Filter Items List', 'nsr' ),
                "items_list_navigation" => __( 'Items List Navigation', 'nsr' ),
                "items_list" => __( 'Items List', 'nsr' ),
                "parent_item_colon" => __( 'Parent', 'nsr' ),
        );

        $args = array(
                "label" => __( 'Villa & Fritidsboende', 'nsr' ),
                "labels" => $labels,
                "description" => "Villa & Fritidsboendes pages",
                "public" => true,
                "publicly_queryable" => true,
                "show_ui" => true,
                "show_in_rest" => false,
                "rest_base" => "",
                "has_archive" => false,
                "show_in_menu" => true,
                "exclude_from_search" => false,
                "capability_type" => "post",
                "map_meta_cap" => true,
                "hierarchical" => false,
                "rewrite" => array( "slug" => "villa", "with_front" => true ),
                "query_var" => true,
                "menu_position" => 21,
                "menu_icon" => "dashicons-admin-page",
                "supports" => array( "title", "editor", "thumbnail", "excerpt", "trackbacks", "custom-fields", "comments", "revisions", "author", "page-attributes", "post-formats" ),
                "taxonomies" => array( "category", "post_tag" ),
        );
        register_post_type( "villa", $args );



        /* Företag & Restauranger */

        $labels = array(

            "name" => __( 'Pages Företag', 'nsr' ),
            "singular_name" => __( 'Pages Företag', 'nsr' ),
            "menu_name" => __( 'Pages Företag', 'nsr' ),
            "all_items" => __( 'All Items', 'nsr' ),
            "add_new" => __( 'Add New', 'nsr' ),
            "add_new_item" => __( 'Add New Item', 'nsr' ),
            "edit_item" => __( 'Edit Item', 'nsr' ),
            "new_item" => __( 'New Item', 'nsr' ),
            "view_item" => __( 'View Item', 'nsr' ),
            "search_items" => __( 'Search Item', 'nsr' ),
            "not_found" => __( 'Not Found', 'nsr' ),
            "not_found_in_trash" => __( 'Not Found in Trash', 'nsr' ),
            "parent_item_colon" => __( 'Parent', 'nsr' ),
            "featured_image" => __( 'Featured Image', 'nsr' ),
            "set_featured_image" => __( 'Set Featured Image', 'nsr' ),
            "remove_featured_image" => __( 'Remove Featured Image', 'nsr' ),
            "use_featured_image" => __( 'Use Featured Image', 'nsr' ),
            "archives" => __( 'Use Featured Image', 'nsr' ),
            "insert_into_item" => __( 'Insert into item', 'nsr' ),
            "uploaded_to_this_item" => __( 'Uploaded to this Item', 'nsr' ),
            "filter_items_list" => __( 'Filter Items List', 'nsr' ),
            "items_list_navigation" => __( 'Items List Navigation', 'nsr' ),
            "items_list" => __( 'Items List', 'nsr' ),
            "parent_item_colon" => __( 'Parent', 'nsr' ),
        );

        $args = array(
            "label" => __( 'Företag & Restauranger', 'nsr' ),
            "labels" => $labels,
            "description" => "Företag & Restaurangers pages",
            "public" => true,
            "publicly_queryable" => true,
            "show_ui" => true,
            "show_in_rest" => false,
            "rest_base" => "",
            "has_archive" => false,
            "show_in_menu" => true,
            "exclude_from_search" => false,
            "capability_type" => "post",
            "map_meta_cap" => true,
            "hierarchical" => false,
            "rewrite" => array( "slug" => "foretag", "with_front" => true ),
            "query_var" => true,
            "menu_position" => 21,
            "menu_icon" => "dashicons-admin-page",
            "supports" => array( "title", "editor", "thumbnail", "excerpt", "trackbacks", "custom-fields", "comments", "revisions", "author", "page-attributes", "post-formats" ),
            "taxonomies" => array( "category", "post_tag" ),
        );
        register_post_type( "foretag", $args );


        /* Fastighetsägare & Bostadsrättsföreningar */

        $labels = array(

            "name" => __( 'Pages Fastighet', 'nsr' ),
            "singular_name" => __( 'Pages Fastighet', 'nsr' ),
            "menu_name" => __( 'Pages Fastighet', 'nsr' ),
            "all_items" => __( 'All Items', 'nsr' ),
            "add_new" => __( 'Add New', 'nsr' ),
            "add_new_item" => __( 'Add New Item', 'nsr' ),
            "edit_item" => __( 'Edit Item', 'nsr' ),
            "new_item" => __( 'New Item', 'nsr' ),
            "view_item" => __( 'View Item', 'nsr' ),
            "search_items" => __( 'Search Item', 'nsr' ),
            "not_found" => __( 'Not Found', 'nsr' ),
            "not_found_in_trash" => __( 'Not Found in Trash', 'nsr' ),
            "parent_item_colon" => __( 'Parent', 'nsr' ),
            "featured_image" => __( 'Featured Image', 'nsr' ),
            "set_featured_image" => __( 'Set Featured Image', 'nsr' ),
            "remove_featured_image" => __( 'Remove Featured Image', 'nsr' ),
            "use_featured_image" => __( 'Use Featured Image', 'nsr' ),
            "archives" => __( 'Use Featured Image', 'nsr' ),
            "insert_into_item" => __( 'Insert into item', 'nsr' ),
            "uploaded_to_this_item" => __( 'Uploaded to this Item', 'nsr' ),
            "filter_items_list" => __( 'Filter Items List', 'nsr' ),
            "items_list_navigation" => __( 'Items List Navigation', 'nsr' ),
            "items_list" => __( 'Items List', 'nsr' ),
            "parent_item_colon" => __( 'Parent', 'nsr' ),
        );

        $args = array(
            "label" => __( 'Fastighetsägare & Bostadsrättsföreningar', 'nsr' ),
            "labels" => $labels,
            "description" => "Fastighetsägare & Bostadsrättsföreningars pages",
            "public" => true,
            "publicly_queryable" => true,
            "show_ui" => true,
            "show_in_rest" => false,
            "rest_base" => "",
            "has_archive" => false,
            "show_in_menu" => true,
            "exclude_from_search" => false,
            "capability_type" => "post",
            "map_meta_cap" => true,
            "hierarchical" => false,
            "rewrite" => array( "slug" => "fastighet", "with_front" => true ),
            "query_var" => true,
            "menu_position" => 21,
            "menu_icon" => "dashicons-admin-page",
            "supports" => array( "title", "editor", "thumbnail", "excerpt", "trackbacks", "custom-fields", "comments", "revisions", "author", "page-attributes", "post-formats" ),
            "taxonomies" => array( "category", "post_tag" ),
        );
        register_post_type( "fastighet", $args );






    }







}
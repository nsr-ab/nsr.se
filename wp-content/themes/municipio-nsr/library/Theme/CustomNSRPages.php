<?php

namespace Nsr\Theme;

class CustomNSRPages
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


        $labels = array(
            "name" => __('Sidor Företag', 'nsr'),
            "singular_name" => __('Företag & Restauranger', 'nsr'),
            "menu_name" => __('Sidor Företag', 'nsr'),
            "all_items" => __('Visa alla sidor', 'nsr'),
            "add_new" => __('Lägg till ny', 'nsr'),
            "add_new_item" => __('Lägg till ny sida', 'nsr'),
            "edit_item" => __('Redigera sida', 'nsr'),
            "new_item" => __('Ny sida', 'nsr'),
            "view_item" => __('Visa sida', 'nsr'),
            "search_items" => __('Sök i företag & restauranger', 'nsr'),
            "not_found" => __('Inga sidor hittades', 'nsr'),
            "not_found_in_trash" => __('Kunde inte hitta sida i papperskorgen', 'nsr'),
            "parent_item_colon" => __('Företag & Restauranger huvudsida:', 'nsr'),
            "featured_image" => __('Utvald bild', 'nsr'),
            "set_featured_image" => __('Lägg till utvald bild', 'nsr'),
            "remove_featured_image" => __('Radera utvald bild', 'nsr'),
            "use_featured_image" => __('Använd utvald bild', 'nsr'),
            "archives" => __('Arkiv', 'nsr'),
            "insert_into_item" => __('Lägg till objekt på sidan', 'nsr'),
            "uploaded_to_this_item" => __('Ladda upp objekt till sida', 'nsr'),
            "filter_items_list" => __('Filtrera lista', 'nsr'),
            "items_list_navigation" => __('Navigera i listan', 'nsr'),
            "items_list" => __('Lista', 'nsr'),
            "parent_item_colon" => __('Företag & Restauranger huvudsida:', 'nsr'),
        );

        $args = array(
            "label" => __('Företag & Restauranger', 'nsr'),
            "labels" => $labels,
            "description" => "Alla sidor som ligger under företag & restauranger",
            "public" => true,
            "publicly_queryable" => true,
            "show_ui" => true,
            "show_in_rest" => false,
            "rest_base" => "",
            "has_archive" => false,
            "show_in_menu" => true,
            "menu_position" => 20,
            "exclude_from_search" => false,
            "capability_type" => "post",
            "map_meta_cap" => true,
            "hierarchical" => false,
            "rewrite" => array("slug" => "foretag", "with_front" => true),
            "query_var" => true,
            "menu_icon" => "dashicons-admin-page",
            "supports" => array("title", "editor", "thumbnail", "excerpt", "custom-fields", "revisions", "author", "page-attributes", "post-formats"),
            "taxonomies" => array("category", "post_tag"),
        );
        register_post_type("foretag", $args);


        $labels = array(
            "name" => __('Sidor Fastighet', 'nsr'),
            "singular_name" => __('Fastighetsägare & Bostadsrättsföreningar', 'nsr'),
            "menu_name" => __('Sidor Fastighet', 'nsr'),
            "all_items" => __('Visa alla sidor', 'nsr'),
            "add_new" => __('Lägg till ny', 'nsr'),
            "add_new_item" => __('Lägg till ny sida', 'nsr'),
            "edit_item" => __('Redigera sida', 'nsr'),
            "new_item" => __('Ny sida', 'nsr'),
            "view_item" => __('Visa sida', 'nsr'),
            "search_items" => __('Sök i fastighetsägare & bostadsrättsföreningar', 'nsr'),
            "not_found" => __('Inga sidor hittades', 'nsr'),
            "not_found_in_trash" => __('Kunde inte hitta sida i papperskorgen', 'nsr'),
            "parent_item_colon" => __('Fastighetsägare & Bostadsrättsföreningar huvudsida:', 'nsr'),
            "featured_image" => __('Utvald bild', 'nsr'),
            "set_featured_image" => __('Lägg till utvald bild', 'nsr'),
            "remove_featured_image" => __('Radera utvald bild', 'nsr'),
            "use_featured_image" => __('Använd utvald bild', 'nsr'),
            "archives" => __('Arkiv', 'nsr'),
            "insert_into_item" => __('Lägg till objekt på sidan', 'nsr'),
            "uploaded_to_this_item" => __('Ladda upp objekt till sida', 'nsr'),
            "filter_items_list" => __('Filtrera lista', 'nsr'),
            "items_list_navigation" => __('Navigera i listan', 'nsr'),
            "items_list" => __('Lista', 'nsr'),
            "parent_item_colon" => __('Företag & Restauranger huvudsida:', 'nsr'),
        );

        $args = array(
            "label" => __('Fastighetsägare & Bostadsrättsföreningar', 'nsr'),
            "labels" => $labels,
            "description" => "Alla sidor som ligger under fastighetsägare & Bbostadsrättsföreningar",
            "public" => true,
            "publicly_queryable" => true,
            "show_ui" => true,
            "show_in_rest" => false,
            "rest_base" => "",
            "has_archive" => false,
            "show_in_menu" => true,
            "menu_position" => 21,
            "exclude_from_search" => false,
            "capability_type" => "post",
            "map_meta_cap" => true,
            "hierarchical" => false,
            "rewrite" => array("slug" => "fastighet", "with_front" => true),
            "query_var" => true,
            "menu_icon" => "dashicons-admin-page",
            "supports" => array("title", "editor", "thumbnail", "excerpt", "custom-fields", "revisions", "author", "page-attributes", "post-formats"),
            "taxonomies" => array("fastighet", "post_tag"),
        );
        register_post_type("fastighet", $args);

    }


}
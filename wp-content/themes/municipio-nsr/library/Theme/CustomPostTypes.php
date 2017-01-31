<?php

namespace Nsr\Theme;

class CustomPostTypes
{
    public function __construct()
    {

        add_action( 'init', array($this, 'cptui_register_taxes_sorteringsguide') );
        add_action('init', array($this, 'register_custom_post_types'));
    }


    /**
     * Register custom posttypes for Företag & Restauranger, Fastighetsägare & Bostadsrättsföreningar, FAQ, Sorteringsguide
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
                "description" => "Villa & Fritidsboendes",
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
                "hierarchical" => true,
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
            "description" => "Företag & Restaurangers",
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
            "hierarchical" => true,
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
            "description" => "Fastighetsägare & Bostadsrättsföreningars",
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
            "hierarchical" => true,
            "rewrite" => array( "slug" => "fastighet", "with_front" => true ),
            "query_var" => true,
            "menu_position" => 21,
            "menu_icon" => "dashicons-admin-page",
            "supports" => array( "title", "editor", "thumbnail", "excerpt", "trackbacks", "custom-fields", "comments", "revisions", "author", "page-attributes", "post-formats" ),
            "taxonomies" => array( "category", "post_tag" ),
        );
        register_post_type( "fastighet", $args );



        /* FAQ */

        $labels = array(

            "name" => __( 'F.A.Q', 'nsr' ),
            "singular_name" => __( 'Pages F.A.Q', 'nsr' ),
            "menu_name" => __( 'Pages F.A.Q', 'nsr' ),
            "all_items" => __( 'All Items', 'nsr' ),
            "add_new" => __( 'Add New question', 'nsr' ),
            "add_new_item" => __( 'Add New Item', 'nsr' ),
            "edit_item" => __( 'Edit Item', 'nsr' ),
            "new_item" => __( 'New Item', 'nsr' ),
            "view_item" => __( 'View Item', 'nsr' ),
            "search_items" => __( 'Search Item', 'nsr' ),
            "not_found" => __( 'Not Found', 'nsr' ),
            "not_found_in_trash" => __( 'Not Found in FAQ trash', 'nsr' ),
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
            "label" => __( 'F.A.Q', 'nsr' ),
            "labels" => $labels,
            "description" => "Frequently asked questions & answers",
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
            "hierarchical" => true,
            "rewrite" => array( "slug" => "faq", "with_front" => true ),
            "query_var" => true,
            "menu_position" => 21,
            "menu_icon" => "dashicons-welcome-learn-more",
            "supports" => array( "title", "editor", "excerpt"),
            "taxonomies" => array( "category", "post_tag" ),
        );
        register_post_type( "faq", $args );




        /* Sorteringsguiden */

        $labels = array(

            "name" => __( 'Sorting guide', 'nsr' ),
            "singular_name" => __( 'Sorting guide', 'nsr' ),
            "menu_name" => __( 'Sorting guide', 'nsr' ),
            "all_items" => __( 'All waste', 'nsr' ),
            "add_new" => __( 'Add New waste', 'nsr' ),
            "add_new_item" => __( 'Add New Waste', 'nsr' ),
            "edit_item" => __( 'Edit Waste', 'nsr' ),
            "new_item" => __( 'New Waste', 'nsr' ),
            "view_item" => __( 'View Waste', 'nsr' ),
            "search_items" => __( 'Search Waste', 'nsr' ),
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
            "items_list" => __( 'Waste List', 'nsr' ),
            "parent_item_colon" => __( 'Parent', 'nsr' ),
        );

        $args = array(
            "label" => __( 'Sorting guide', 'nsr' ),
            "labels" => $labels,
            "description" => "Managing waste for Sorting guide",
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
            "hierarchical" => true,
            "rewrite" => array( "slug" => "sorteringsguide", "with_front" => true ),
            "query_var" => true,
            "menu_position" => 21,
            "menu_icon" => "dashicons-trash",
            "supports" => array( "title", "editor"),
            "taxonomies" => array("fraktioner", "inlamningsstallen")
        );
        register_post_type( "sorteringsguide", $args );


    }



    /**
     * Register taxonomies.
     * @return void
     */
    public function cptui_register_taxes_sorteringsguide() {

        $labels = array(
            "name" => __( 'Fraktioner', 'nsr' ),
            "singular_name" => __( 'Fraktion', 'nsr' ),
        );
        $args = array(
            "label" => __( 'Fraktioner', 'nsr' ),
            "labels" => $labels,
            "public" => true,
            "hierarchical" => false,
            "label" => "Fraktioner",
            "show_ui" => true,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "query_var" => true,
            "rewrite" => array( 'slug' => 'fraktioner', 'with_front' => true ),
            "show_admin_column" => false,
            "show_in_rest" => false,
            "rest_base" => "",
            "show_in_quick_edit" => true,
        );
        register_taxonomy( "fraktioner", array( "fraktioner" ), $args );

        $labels = array(
            "name" => __( 'Inlämningsställen', 'nsr' ),
            "singular_name" => __( 'Inlämingsställe', 'nsr' ),
        );
        $args = array(
            "label" => __( 'Inlämningsställen', 'nsr' ),
            "labels" => $labels,
            "public" => true,
            "hierarchical" => false,
            "label" => "Inlämningsställen",
            "show_ui" => true,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "query_var" => true,
            "rewrite" => array( 'slug' => 'inlamningsstallen', 'with_front' => true ),
            "show_admin_column" => false,
            "show_in_rest" => false,
            "rest_base" => "",
            "show_in_quick_edit" => true,
        );
        register_taxonomy( "inlamningsstallen", array( "inlamningsstallen" ), $args );



        if( function_exists('acf_add_local_field_group') ):

            acf_add_local_field_group(array (
                'key' => 'group_586cd2a01195f',
                'title' => 'Fraktioner - Koppling inlämningsställen',
                'fields' => array (
                    array (
                        'taxonomy' => 'inlamningsstallen',
                        'field_type' => 'multi_select',
                        'multiple' => 0,
                        'allow_null' => 1,
                        'return_format' => 'id',
                        'add_term' => 1,
                        'load_terms' => 0,
                        'save_terms' => 0,
                        'key' => 'field_586cd2ac7119c',
                        'label' => 'Inlämningsställen',
                        'name' => 'fraktion_inlamningsstallen',
                        'type' => 'taxonomy',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                    ),
                    array (
                        'post_type' => array (
                        ),
                        'taxonomy' => array (
                        ),
                        'allow_null' => 0,
                        'multiple' => 0,
                        'allow_archives' => 1,
                        'key' => 'field_587f45101d1d5',
                        'label' => 'Länk till sida',
                        'name' => 'fraktion_page_link',
                        'type' => 'page_link',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                    ),
                ),

                'location' => array (
                    array (
                        array (
                            'param' => 'taxonomy',
                            'operator' => '==',
                            'value' => 'fraktioner',
                        ),
                    ),
                ),

                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => '',
            ));

            acf_add_local_field_group(array (
                'key' => 'group_586ccede33e92',
                'title' => 'Inlämningsställen - Koppling Fraktioner',
                'fields' => array (
                    array (
                        'taxonomy' => 'fraktioner',
                        'field_type' => 'multi_select',
                        'multiple' => 0,
                        'allow_null' => 0,
                        'return_format' => 'object',
                        'add_term' => 1,
                        'load_terms' => 0,
                        'save_terms' => 1,
                        'key' => 'field_586d08616a7b7',
                        'label' => 'Sorteras som på ÅVC',
                        'name' => 'avfall_fraktion',
                        'type' => 'taxonomy',
                        'instructions' => 'Välj vilken fraktion avfallet sorteras under',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                    ),
                    array (
                        'default_value' => '',
                        'maxlength' => '',
                        'placeholder' => '56.046467',
                        'prepend' => '',
                        'append' => '',
                        'key' => 'field_586e1d5759269',
                        'label' => 'Latitude',
                        'name' => 'inlamningsstalle_latitude',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                    ),
                    array (
                        'default_value' => '',
                        'maxlength' => '',
                        'placeholder' => '12.694512',
                        'prepend' => '',
                        'append' => '',
                        'key' => 'field_586e1df95926a',
                        'label' => 'Longitude',
                        'name' => 'inlamningsstalle_longitude',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                    ),
                    array (
                        'default_value' => '',
                        'maxlength' => '',
                        'placeholder' => 'Storgatan 5b',
                        'prepend' => '',
                        'append' => '',
                        'key' => 'field_58749b3d11fb1',
                        'label' => 'Adress',
                        'name' => 'inlamningsstalle_adress',
                        'type' => 'text',
                        'instructions' => 'Skriv in inlämningsställets adress',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                    ),
                    array (
                        'default_value' => '',
                        'maxlength' => '',
                        'placeholder' => 'Helsingborg',
                        'prepend' => '',
                        'append' => '',
                        'key' => 'field_58749b8ca6b78',
                        'label' => 'Stad/Ort',
                        'name' => 'inlamningsstalle_stadort',
                        'type' => 'text',
                        'instructions' => 'Skriv in Ort/Stad',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                    ),
                    array (
                        'post_type' => array (
                            0 => 'sorteringsguide',
                        ),
                        'taxonomy' => array (
                        ),
                        'allow_null' => 0,
                        'multiple' => 0,
                        'return_format' => 'object',
                        'ui' => 1,
                        'key' => 'field_586e2225ccf51',
                        'label' => 'Länk till sida',
                        'name' => 'tax_pageurl',
                        'type' => 'page_link',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                    ),

                ),
                'location' => array (
                    array (
                        array (
                            'param' => 'taxonomy',
                            'operator' => '==',
                            'value' => 'inlamningsstallen',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => '',
            ));

            //Avfall - Extra information

            acf_add_local_field_group(array (
                'key' => 'group_586d03401cd10',
                'title' => 'Avfall - Extra information',
                'fields' => array (
                    array (
                        'layout' => 'vertical',
                        'choices' => array (
                            'villa' => 'Villa & Fritidsboende',
                            'fastighet' => 'Fastighetsägare & Bostadsrättsföreningar',
                            'foretag' => 'Företag & Restauranger',
                        ),
                        'default_value' => array (
                        ),
                        'allow_custom' => 0,
                        'save_custom' => 0,
                        'toggle' => 1,
                        'return_format' => 'value',
                        'key' => 'field_586d08e9db9e1',
                        'label' => 'Kundkategori',
                        'name' => 'avfall_kundkategori',
                        'type' => 'checkbox',
                        'instructions' => 'Välj vilken kundkategori avfallet ska visas för',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                    ),
                    array (
                        'taxonomy' => 'fraktioner',
                        'field_type' => 'multi_select',
                        'multiple' => 0,
                        'allow_null' => 0,
                        'return_format' => 'object',
                        'add_term' => 1,
                        'load_terms' => 0,
                        'save_terms' => 1,
                        'key' => 'field_586d08616a7b7',
                        'label' => 'Sorteras som på ÅVC',
                        'name' => 'avfall_fraktion',
                        'type' => 'taxonomy',
                        'instructions' => 'Välj vilken fraktion avfallet sorteras under',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                    ),
                    array (
                        'taxonomy' => 'fraktioner',
                        'field_type' => 'multi_select',
                        'multiple' => 0,
                        'allow_null' => 0,
                        'return_format' => 'object',
                        'add_term' => 1,
                        'load_terms' => 0,
                        'save_terms' => 1,
                        'key' => 'field_587e175490745',
                        'label' => 'Sorteras som hemma',
                        'name' => 'avfall_fraktion_hemma',
                        'type' => 'taxonomy',
                        'instructions' => 'Välj vilken fraktion avfallet sorteras under',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                    ),
                    array (
                        'taxonomy' => 'inlamningsstallen',
                        'field_type' => 'multi_select',
                        'multiple' => 0,
                        'allow_null' => 0,
                        'return_format' => 'id',
                        'add_term' => 1,
                        'load_terms' => 0,
                        'save_terms' => 1,
                        'key' => 'field_586d088d95b08',
                        'label' => 'Inlämningsställen',
                        'name' => 'avfall_inlamningsstallen',
                        'type' => 'taxonomy',
                        'instructions' => 'Välj var man kan lämna in avfallet',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                    ),
                    array (
                        'tabs' => 'all',
                        'toolbar' => 'full',
                        'media_upload' => 1,
                        'default_value' => '',
                        'delay' => 1,
                        'key' => 'field_586d034e3dd38',
                        'label' => 'Bra att veta',
                        'name' => 'avfall_bra_att_veta',
                        'type' => 'wysiwyg',
                        'instructions' => 'Extra information angående detta avfall',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                    ),
                    array (
                        'sub_fields' => array (
                            array (
                                'default_value' => '',
                                'maxlength' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'key' => 'field_586d0767fc7fe',
                                'label' => 'Synonym',
                                'name' => 'avfall_synonym',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                            ),
                        ),
                        'min' => 0,
                        'max' => 0,
                        'layout' => 'block',
                        'button_label' => '',
                        'collapsed' => '',
                        'key' => 'field_586d03e89d980',
                        'label' => 'Synonymer',
                        'name' => 'avfall_synonymer',
                        'type' => 'repeater',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                    ),
                ),
                'location' => array (
                    array (
                        array (
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'sorteringsguide',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => '',
            ));

        endif;



    }


}
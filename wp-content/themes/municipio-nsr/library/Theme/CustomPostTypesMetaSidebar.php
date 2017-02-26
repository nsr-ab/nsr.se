<?php

namespace Nsr\Theme;


class CustomPostTypesMetaSidebar {


    public function __construct() {
        if ( is_admin() ) {
            add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
            add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
        }

    }


    /**
     * Meta box initialization.
     */
    public function init_metabox() {
        add_action( 'add_meta_boxes', array( $this, 'add_metabox'  )        );
        add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );
    }


    /**
     * Add meta box.
     */
    public function add_metabox() {

        $type_name = array('page','fastighet','villa','foretag','faq');
        foreach ( $type_name as $custom_post_type ) {
            add_meta_box(
                'sidebar-meta-box',
                __( 'Display sidebar', 'nsr' ),
                array( $this, 'render_metabox' ),
                $custom_post_type,
                'side'
            );
        }


    }


    /**
     * Render meta box.
     */
    public function render_metabox( $post ) {

        wp_nonce_field( 'showsidebar_nonce_action', 'showsidebar_nonce' );
        $select_value = get_post_meta( $post->ID, 'post_showsidebar', true );

        echo "<select type=\"text\" id=\"post_showsidebar\" name=\"post_showsidebar\" >";
        echo "<option";

        echo ($select_value == 1) ? " selected=\"selected\" " : "";

        echo " value=\"1\">" . __('Show right sidebar', 'nsr');
        echo "</option>";
        echo "<option";

        echo ($select_value == 0) ? " selected=\"selected\" " : "";


        echo " value=\"0\">" . __('Hide right sidebar', 'nsr');
        echo "</option>";
        echo "</select>";
        echo "<p class=\"howto\">" . __('Right sidebar with widgets', 'nsr') ."</p>";
    }


    /**
     * Handles saving the meta box.
     *
     * @param int     $post_id Post ID.
     * @param WP_Post $post    Post object.
     * @return null
     */
    public function save_metabox( $post ) {
        global $post;

        $nonce_name   = isset( $_POST['showsidebar_nonce'] ) ? $_POST['showsidebar_nonce'] : '';
        $nonce_action = 'showsidebar_nonce_action';

        if ( ! isset( $nonce_name ) ) {
            return;
        }

        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post->ID ) ) {
            return;
        }

        if ( wp_is_post_autosave( $post->ID ) ) {
            return;
        }

        if ( wp_is_post_revision( $post->ID ) ) {
            return;
        }

        $data = sanitize_text_field( $_POST['post_showsidebar'] );
        update_post_meta( $post->ID, 'post_showsidebar', $data );
    }

}


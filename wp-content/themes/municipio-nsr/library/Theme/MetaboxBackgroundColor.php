<?php

namespace Nsr\Theme;


class MetaboxBackgroundColor {


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

        $type_name = array('post','page','fastighet','villa','foretag','faq');
        foreach ( $type_name as $custom_post_type ) {
            add_meta_box(
                'bgcolor-meta-box',
                __( 'BackgroundColor', 'nsr' ),
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

        wp_nonce_field( 'backgroundcolor_nonce_action', 'backgroundcolor_nonce' );
        $select_value = get_post_meta( $post->ID, 'post_backgroundcolor', true );

        echo "<select type=\"text\" id=\"post_backgroundcolor\" name=\"post_backgroundcolor\" >";
        echo "<option";

        echo ($select_value == 1) ? " selected=\"selected\" " : "";

        echo " value=\"1\">" . __('Grey background & white header/footer', 'nsr');
        echo "</option>";
        echo "<option";

        echo ($select_value == 0) ? " selected=\"selected\" " : "";


        echo " value=\"0\">" . __('White background & grey header/footer', 'nsr');
        echo "</option>";
        echo "</select>";
        echo "<p class=\"howto\">" . __('Select background/header color', 'nsr') ."</p>";
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

        $nonce_name   = isset( $_POST['backgroundcolor_nonce'] ) ? $_POST['backgroundcolor_nonce'] : '';
        $nonce_action = 'backgroundcolor_nonce_action';

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

        $data = sanitize_text_field( $_POST['post_backgroundcolor'] );
        update_post_meta( $post->ID, 'post_backgroundcolor', $data );
    }

}


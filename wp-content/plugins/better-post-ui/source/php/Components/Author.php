<?php

namespace BetterPostUi\Components;

class Author
{
    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'authorDiv'));
        add_filter('default_hidden_meta_boxes', array($this, 'alwaysShowAuthorMetabox'), 10, 2);
    }

    /**
     * Changes the metabox title of the author metabox (admin)
     * @return void
     */
    public function authorDiv()
    {
        $postType = get_post_type();
        $postTypeObject = get_post_type_object($postType);

        // Checks if authordiv should exist
        if (!post_type_supports($postType, 'author') || (!is_super_admin() && !current_user_can($postTypeObject->cap->edit_others_posts))) {
            return;
        }

        // Remove the default authordiv and add our own
        remove_meta_box('authordiv', $postType, 'normal');
        add_meta_box(
            'authordiv',
            __('Author'),
            array($this, 'authorDivContent'),
            $postType,
            'normal',
            'default'
        );
    }

    public function authorDivContent()
    {
        global $post;

        $args = apply_filters('BetterPostUi/authors', array(
            'who' => 'authors'
        ));

        $authors = get_users($args);

        uasort($authors, function ($a, $b) use ($post) {
            if ($post->post_author == $a->ID) {
                return -1;
            }

            if ($post->post_author == $b->ID) {
                return 1;
            }

            return 0;
        });

        include BETTERPOSTUI_TEMPLATE_PATH . 'authordiv.php';
    }

    /**
     * Display the author metabox by default
     * @param  array $hidden Hidden metaboxes before
     * @param  array $screen Screen args
     * @return array         Hidden metaboxes after
     */
    public function alwaysShowAuthorMetabox($hidden, $screen)
    {
        if ($screen->post_type != 'page') {
            return $hidden;
        }

        $hidden = array_filter($hidden, function ($item) {
            return $item != 'authordiv';
        });

        return $hidden;
    }
}

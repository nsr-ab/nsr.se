<?php

namespace BetterPostUi\Components;

class PageAttributes
{
    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'pageAttributesDiv'));
        add_action('wp_ajax_better_post_ui_search_parent', array($this, 'searchParent'));
    }

    public function pageAttributesDiv()
    {
        $postType = get_post_type();

        // Checks if authordiv should exist
        if (!post_type_supports($postType, 'page-attributes')) {
            return;
        }

        // Remove the default pageparentdiv and add our own
        remove_meta_box('pageparentdiv', $postType, 'normal');
        add_meta_box(
            'pageparentdiv',
            'page' == $postType ? __('Page Attributes') : __('Attributes'),
            array($this, 'pageAttributesDivContent'),
            $postType,
            'side',
            'default'
        );
    }

    /**
     * The contents of the pageattributes div
     * @param  object $post Current post object
     * @return void
     */
    public function pageAttributesDivContent($post)
    {
        $postTypeObject = get_post_type_object($post->post_type);
        include BETTERPOSTUI_TEMPLATE_PATH . 'pageparentdiv.php';
    }

    /**
     * Search parent
     * @param  string $query    Search query
     * @param  string $postType Post type
     * @return array            Found posts
     */
    public function searchParent($query = null, $postType = null)
    {
        if (defined('DOING_AJAX') && DOING_AJAX) {
            $query = $_POST['query'];
            $postType = $_POST['postType'];
        }

        $search = new \WP_Query(array(
            'post_type' => $postType,
            'post_status' => array('publish', 'future', 'draft', 'pending', 'private'),
            's' => $query
        ));

        if (defined('DOING_AJAX') && DOING_AJAX) {
            echo json_encode($search->posts);
            wp_die();
        }

        return $search->posts;
    }
}

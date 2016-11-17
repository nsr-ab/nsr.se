<?php

namespace CustomShortLinks;

class Shortlinks
{
    public function __construct()
    {
        add_action('init', array($this, 'registerPostType'));
        add_action('wp', array($this, 'simpleRedirect'));
        // add_action('save_post_custom-short-link', array($this, 'writeToHtaccess'));

        add_action('edit_form_after_title', array($this, 'afterTitle'));

        add_filter('enter_title_here', array($this, 'titlePlaceholder'));
    }

    /**
     * Register shortlinks posttype
     * @return void
     */
    public function registerPostType()
    {
        $nameSingular = 'Shortlink';
        $namePlural = 'Shortlinks';
        $description = 'Create shortlinks to your posts or pages';

        $labels = array(
            'name'               => _x($nameSingular, 'post type general name', 'custom-short-links'),
            'singular_name'      => _x($nameSingular, 'post type singular name', 'custom-short-links'),
            'menu_name'          => _x($namePlural, 'admin menu', 'custom-short-links'),
            'name_admin_bar'     => _x($nameSingular, 'add new on admin bar', 'custom-short-links'),
            'add_new'            => _x('Add New', 'add new button', 'custom-short-links'),
            'add_new_item'       => sprintf(__('Add new %s', 'custom-short-links'), $nameSingular),
            'new_item'           => sprintf(__('New %s', 'custom-short-links'), $nameSingular),
            'edit_item'          => sprintf(__('Edit %s', 'custom-short-links'), $nameSingular),
            'view_item'          => sprintf(__('View %s', 'custom-short-links'), $nameSingular),
            'all_items'          => sprintf(__('All %s', 'custom-short-links'), $namePlural),
            'search_items'       => sprintf(__('Search %s', 'custom-short-links'), $namePlural),
            'parent_item_colon'  => sprintf(__('Parent %s', 'custom-short-links'), $namePlural),
            'not_found'          => sprintf(__('No %s', 'custom-short-links'), $namePlural),
            'not_found_in_trash' => sprintf(__('No %s in trash', 'custom-short-links'), $namePlural)
        );

        $args = array(
            'labels'               => $labels,
            'description'          => __($description, 'custom-short-links'),
            'public'               => false,
            'publicly_queriable'   => false,
            'show_ui'              => true,
            'show_in_nav_menus'    => false,
            'show_in_menu'         => true,
            'has_archive'          => false,
            'rewrite'              => false,
            'hierarchical'         => false,
            'menu_position'        => 100,
            'exclude_from_search'  => true,
            'menu_icon'            => 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1MTIiIGhlaWdodD0iNTEyIiB2aWV3Qm94PSIwIDAgNDguMTU1IDQ4LjE1NSI+PHBhdGggZD0iTTM2Ljg1IDI0LjY1Nmw5LjUwNC05LjUwM0wzNi44NSA1LjY1SDI0LjA4di00LjZjMC0uNTc3LS40NzMtMS4wNS0xLjA1LTEuMDVIMTkuMjRjLS41NzggMC0xLjA1LjQ3My0xLjA1IDEuMDV2NC42SDIuM2EuNS41IDAgMCAwLS41LjV2MTguMDA3YS41LjUgMCAwIDAgLjUuNWgxNS44OXYzLjczaC01Ljg4bC00Ljc1MiA0Ljc1IDQuNzUyIDQuNzUzaDUuODh2OS4yMTRjMCAuNTc3LjQ3MiAxLjA1MiAxLjA1IDEuMDUyaDMuNzg1Yy41NzggMCAxLjA1LS40NzUgMS4wNS0xLjA1MlYzNy44OWg3LjI2YS41LjUgMCAwIDAgLjUtLjV2LTguNTA0YS41LjUgMCAwIDAtLjUtLjVoLTcuMjZ2LTMuNzNoMTIuNzc0eiIgZmlsbD0iIzk5OSIvPjwvc3ZnPg==',
            'supports'             => array('title')
        );

        register_post_type('custom-short-link', $args);

        add_filter('manage_edit-custom-short-link_columns', array($this, 'listColumns'));
        add_action('manage_custom-short-link_posts_custom_column', array($this, 'listColumnsContent'), 10, 2);
        add_filter('manage_edit-custom-short-link_sortable_columns', array($this, 'listColumnsSorting'));
    }

    public function listColumns($columns)
    {
        $columns = array(
            'cb'     => '<input type="checkbox">',
            'title'  => __('Shortlink'),
            'target' => __('Target'),
            'type'   => __('Type'),
            'date'   => __('Date')
        );

        return $columns;
    }

    public function listColumnsContent($column, $postId)
    {
        if ($column == 'target') {
            $fields = get_fields($postId);

            switch ($fields['custom_short_links_redirect_url_type']) {
                case 'external':
                    echo $fields['custom_short_links_redirect_to_external'];
                    break;

                case 'internal':
                    echo $fields['custom_short_links_redirect_to_internal'];
                    break;
            }
        }

        if ($column == 'type') {
            $fields = get_fields($postId);

            switch ($fields['custom_short_links_redirect_method']) {
                case '301':
                    echo '302 Moved Permanently';
                    break;

                case '302':
                    echo '302 Moved Temporarily';
                    break;

                case 'meta':
                    echo 'Meta Refresh';
                    break;

                default:
                    echo $fields['custom_short_links_redirect_method'];
                    break;
            }
        }
    }

    public function listColumnsSorting($columns)
    {
        $columns['target'] = 'target';
        $columns['type'] = 'type';
        return $columns;
    }

    /**
     * Make a simple PHP redirect to the target page
     * @return void
     */
    public function simpleRedirect()
    {
        $currentUrl = trim($_SERVER['REQUEST_URI'], '/');
        $post = get_page_by_title($currentUrl, 'OBJECT', 'custom-short-link');

        if (!$post) {
            return;
        }

        $fields = get_fields($post->ID);
        $redirectTo = null;

        // Find redirect url
        switch ($fields['custom_short_links_redirect_url_type']) {
            case 'external':
                $redirectTo = $fields['custom_short_links_redirect_to_external'];
                break;

            case 'internal':
                $redirectTo = $fields['custom_short_links_redirect_to_internal'];
                break;
        }

        // Make redirect with selected method
        switch ($fields['custom_short_links_redirect_method']) {
            case 'meta':
                add_action('wp_head', function () use ($fields, $redirectTo) {
                    echo '<meta http-equiv="refresh" content="' . $fields['custom_short_links_timeout'] . ';URL=' . $redirectTo . '">';
                });
                break;

            // 301 or 302
            default:
                wp_redirect($redirectTo, intval($fields['custom_short_links_redirect_method']));
                break;
        }
    }

    /**
     * Write RewriteRules for redirect to the .htaccess
     *
     * TODO:
     * This function appends the rules to the bottom of the htaccess.
     * The default wordpress rules makes these rules not to read, if put above the Wordpress rules howerever, everything works.
     * This needs to be fixed somehow so that the rules is beeing read even if they're in the bottom
     *
     * @param  [type] $postId [description]
     * @return [type]         [description]
     */
    public function writeToHtaccess($postId)
    {
        if (wp_is_post_revision($postId)) {
            return;
        }

        $homeBaseUrl = preg_replace('(^https?://)', '', home_url());
        $homeBaseUrl = preg_replace('(^www.)', '', $homeBaseUrl);

        // Add Rewrite condition
        $rules = array(
            '<IfModule mod_rewrite.c>',
            'RewriteEngine on',
            'RewriteBase /',
            'RewriteCond %{HTTP_HOST} ^' . $homeBaseUrl . ' [OR]',
            'RewriteCond %{HTTP_HOST} ^www.' . $homeBaseUrl
        );

        // Get redirects
        $posts = get_posts(array(
            'posts_per_page' => -1,
            'post_type' => 'custom-short-link'
        ));

        // Add each rule
        foreach ($posts as $post) {
            $redirectTo = '';
            $post->meta = get_fields($post->ID);

            switch ($post->meta['custom_short_links_redirect_url_type']) {
                case 'internal':
                    $redirectTo = $post->meta['custom_short_links_redirect_to_internal'];
                    break;

                case 'external':
                    $redirectTo = $post->meta['custom_short_links_redirect_to_external'];
                    break;
            }

            $rules[] = 'RewriteRule ^' . $post->post_title . '$ ' . $redirectTo . ' [L,QSA,R=' . intval($fields['custom_short_links_redirect_method']) . ']';
        }

        $rules[] = '</IfModule>';

        $htaccess = get_home_path() . '.htaccess';
        insert_with_markers($htaccess, 'Custom Shortlinks for ' . $homeB§aseUrl, $rules);
    }

    /**
     * Changes the title input placeholder in WP Admin
     * @param  string $placeholder Original placeholder
     * @return string              New placeholder
     */
    public function titlePlaceholder($placeholder)
    {
        $screen = get_current_screen();

        if ($screen->post_type == 'custom-short-link') {
            $placeholder = __('Enter shortlink');
        }

        return $placeholder;
    }

    public function afterTitle()
    {
        global $post;
        $screen = get_current_screen();

        if ($screen->post_type != 'custom-short-link') {
            return;
        }

        if (isset($_GET['action']) && $_GET['action'] == 'edit') {
            echo '<div id="edit-slug-box">
                <strong>' . __('Shortlink', 'custom-short-links') . '</strong>: <a href="' . home_url() . '/' . $post->post_title . '">' . home_url() . '/' . $post->post_title . '</a>
            </div>';
        } elseif ($screen->action == 'add') {
            echo '<div id="edit-slug-box"></div>';
        }
    }
}

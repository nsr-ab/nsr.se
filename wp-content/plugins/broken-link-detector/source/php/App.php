<?php

namespace BrokenLinkDetector;

class App
{
    public static $dbTable = 'broken_links_detector';
    public static $installChecked = false;
    public static $wpdb = null;

    public static $externalDetector = false;

    public function __construct()
    {
        global $wpdb;
        self::$wpdb = $wpdb;

        self::$dbTable = $wpdb->prefix . self::$dbTable;

        add_action('admin_menu', array($this, 'addListTablePage'));

        add_action('admin_enqueue_scripts', array($this, 'enqueueStyles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));

        add_filter('wp_insert_post_data', array($this, 'checkSavedPost'), 10, 2);

        add_action('wp', array($this, 'postTypeColumns'));

        add_action('post_submitbox_misc_actions', array($this, 'rescanPost'), 100);

        $this->brokenLinksColumnSorting();

        self::$externalDetector = new \BrokenLinkDetector\ExternalDetector();
        new \BrokenLinkDetector\Editor();
    }

    public static function checkInstall()
    {
        if (self::$installChecked) {
            return;
        }

        $tableName = self::$dbTable;

        if (!empty(get_option('broken-links-detector-db-version')) && self::$wpdb->get_var("SHOW TABLES LIKE '$tableName'") == $tableName) {
            return;
        }

        self::install();
    }

    public function rescanPost()
    {
        echo '<div class="misc-pub-section">
            <label style="display:block;margin-bottom:5px;"><input type="checkbox" name="broken-link-detector-rescan" value="true"> ' . __('Rescan broken links', 'broken-link-detector') . '</label>
            <small>' . __('The rescan will be executed for this post only. The scan will execute direcly after the save is completed and may take a few minutes to complete.', 'broken-links-detector') . '</small>
        </div>';
    }

    /**
     * Sort post if sorting on broken-links column
     * @return void
     */
    public function brokenLinksColumnSorting()
    {
        add_filter('posts_fields', function ($fields, $query) {
            if ($query->get('orderby') !== 'broken-links') {
                return $fields;
            }

            global $wpdb;

            $fields .= ", (
                SELECT COUNT(*)
                FROM " . self::$dbTable . "
                WHERE post_id = {$wpdb->posts}.ID
            ) AS broken_links_count";

            return $fields;
        }, 10, 2);

        add_filter('posts_orderby', function ($orderby, $query) {
            if ($query->get('orderby') !== 'broken-links') {
                return $orderby;
            }

            $orderby = "broken_links_count {$query->get('order')}, " . $orderby;
            return $orderby;
        }, 10, 2);
    }

    /**
     * Add broken links column to post type post list table
     * @return void
     */
    public function postTypeColumns()
    {
        $postTypes = get_post_types();

        foreach ($postTypes as $postType) {
            add_filter('manage_' . $postType . '_posts_columns', function ($columns) {
                broken_link_detector_array_splice_assoc($columns, -1, 0, array(
                    'broken-links' => __('Broken links', 'broken-links-detector')
                ));

                return $columns;
            }, 50);

            add_filter('manage_edit-' . $postType . '_sortable_columns', function ($columns) {
                $columns['broken-links'] = 'broken-links';
                return $columns;
            }, 50);

            add_action('manage_' . $postType . '_posts_custom_column', function ($column, $postId) {
                if ($column !== 'broken-links') {
                    return;
                }

                $links = \BrokenLinkDetector\ListTable::getBrokenLinksCount($postId);

                if ($links > 0) {
                    echo '<span class="broken-link-detector-label">' . $links . '</span>';
                } else {
                    echo '<span aria-hidden="true">—</span>';
                }
            }, 20, 2);
        }
    }

    /**
     * Adds the list table page of broken links
     */
    public function addListTablePage()
    {
        add_menu_page(
            'Broken links',
            'Broken links',
            'edit_posts',
            'broken-links-detector',
            function () {
                \BrokenLinkDetector\App::checkInstall();

                $listTable = new \BrokenLinkDetector\ListTable();

                $offset = get_option('gmt_offset');

                if ($offset > -1) {
                    $offset = '+' . $offset;
                } else {
                    $offset = '-' . (1 * abs($offset));
                }

                $nextRun = date('Y-m-d H:i', strtotime($offset . ' hours', wp_next_scheduled('broken-links-detector-external')));

                include BROKENLINKDETECTOR_TEMPLATE_PATH . 'list-table.php';
            },
            'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA0NzIuOTUxIDQ3Mi45NTEiIHdpZHRoPSI1MTIiIGhlaWdodD0iNTEyIj48cGF0aCBkPSJNNDYyLjU1NyA3Ny42MjdjLTE0LjMyMy0yNS41NTYtNDEuNDg4LTQxLjQyMy03MC44OS00MS40MjNhODEuMDMgODEuMDMgMCAwIDAtMzkuNiAxMC4zNjRMMTk5LjUxIDEzMi4yMDNsMTcuNTUgMzEuMjggMTUyLjU2My04NS42MzZhNDUuMjQgNDUuMjQgMCAwIDEgMjIuMDctNS43OGMxNi40MjcgMCAzMS41OTUgOC44NTMgMzkuNTkzIDIzLjExIDUuOTEgMTAuNTM2IDcuMzU2IDIyLjc1MyA0LjA3MiAzNC40MS0zLjI3IDExLjY1NS0xMC44NzcgMjEuMzQ1LTIxLjQxMiAyNy4yNTRsLTEzOC4yOSA3Ny42MDZjLTIuNjM1LTE2Ljg1LTEwLjQ5NS0zMi4zOTItMjIuNzk0LTQ0LjctMjEuNjg3LTIxLjY2Mi01Ni4wNjMtMjkuMzI3LTg0LjkzNS0xOC45NjNsLTEyLjQ4NiA0LjQ5NSAxMi4xMyAzMy43MzQgMTIuNTMzLTQuNDdjMTYuMTI3LTUuODcgMzUuMzItMS40OTYgNDcuNDA2IDEwLjU2NyA2Ljk3MyA2Ljk4IDExLjM4NyAxNS44MjUgMTIuNzg1IDI1LjQxYTM2LjEzMiAzNi4xMzIgMCAwIDEtMS43My0uNDQ4Yy0xMS42ODItMy4yODQtMjEuMzU1LTEwLjktMjcuMjcyLTIxLjQ3NmwtNi41Mi0xMS41MTgtMzEuMzAyIDE3LjU5IDYuNTYgMTEuNTE4YzEwLjYgMTguOTE1IDI3LjkzIDMyLjU3IDQ4LjgzNyAzOC40NC43MjMuMTg3IDEuNDU1LjM5IDIuMTg3LjU2YTQ1Ljc1IDQ1Ljc1IDAgMCAxLTMuNTM2IDMuOTg0TDExMy4yIDM5My40OTZjLTguNTUgOC41NDMtMTkuOTMgMTMuMjQtMzIuMDM0IDEzLjI0LTEyLjExMiAwLTIzLjQ5Mi00LjY5Ny0zMi4wMjctMTMuMjQtOC41MzctOC41NDMtMTMuMjM1LTE5LjkwNy0xMy4yMzUtMzIuMDI3IDAtMTIuMTE0IDQuNjk4LTIzLjQ4NSAxMy4yMzMtMzIuMDJsOTkuMjktOTkuMjgtMjUuMzctMjUuMzctOTkuMjggOTkuMjhDOC40NDUgMzE5LjQyOCAwIDMzOS44MDcgMCAzNjEuNDdjMCAyMS42OCA4LjQ0NiA0Mi4wNSAyMy43NzYgNTcuMzk2IDE1LjM0IDE1LjM0IDM1LjcxIDIzLjc2OCA1Ny4zODggMjMuNzY4czQyLjA1LTguNDMgNTcuMzg4LTIzLjc2OGwxMTQuMzQ1LTExNC4zMmE4MS4zNTMgODEuMzUzIDAgMCAwIDE4Ljg0Mi0yOS43MWM2LjYyMy0xLjc2NCAxMi45MjItNC4zIDE4Ljc3NS03LjZsMTQwLjk5LTc5LjExNWMxOC44NzUtMTAuNjEgMzIuNTE0LTI3Ljk0OCAzOC4zNzUtNDguODMgNS44Ny0yMC44OSAzLjI2OC00Mi43OTgtNy4zMjMtNjEuNjY0ek0xNzMuMTcgMTQxLjc3YzQuNTA0LS44MjIgNy41MTItNS4xNDYgNi42OS05LjY1OGwtMTcuMS05NC45NDJjLS44Mi00LjUyLTUuMTYyLTcuNTI3LTkuNjY1LTYuNzIyLTQuNTEuODItNy41MjcgNS4xNDUtNi43MDYgOS42NzNsMTcuMTEgOTQuOTI3Yy44MTMgNC41MyA1LjE0NiA3LjUyOCA5LjY3MyA2LjcyM3pNNDMuNTYgMTkxLjc3Nmw5NC45MzUtMTcuMTJjNC41Mi0uODIgNy41MjctNS4xNDQgNi43MDYtOS42NTYtLjgyLTQuNTI4LTUuMTQzLTcuNTQzLTkuNjQ3LTYuNzIyTDQwLjYxIDE3NS40MDVjLTQuNTAzLjc5Ny03LjUyIDUuMTQ1LTYuNjk4IDkuNjU3LjgwNSA0LjUyIDUuMTQ2IDcuNTI3IDkuNjUgNi43MTR6IiBmaWxsPSIjRkZGIi8+PC9zdmc+',
            750
        );
    }

    /**
     * Setsup the database table on plugin activation (hooked in App.php)
     * @return void
     */
    public static function install()
    {
        $charsetCollation = self::$wpdb->get_charset_collate();
        $tableName = self::$dbTable;

        if (!empty(get_site_option('broken-links-detector-db-version')) && self::$wpdb->get_var("SHOW TABLES LIKE '$tableName'") == $tableName) {
            return;
        }

        $sql = "CREATE TABLE $tableName (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            post_id bigint(20) DEFAULT NULL,
            url varchar(255) DEFAULT '' NOT NULL,
            UNIQUE KEY id (id)
        ) $charsetCollation;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        update_option('broken-links-detector-db-version', '1.0.0');
    }

    /**
     * Drops the database table on plugin deactivation (hooked in App.php)
     * @return void
     */
    public static function uninstall()
    {
        $tableName = self::$dbTable;
        $sql = 'DROP TABLE ' . $tableName;

        self::$wpdb->query($sql);

        delete_option('broken-links-detector-db-version');
    }

    /**
     * Checks if a saved posts permalink is changed and updates permalinks throughout the site
     * @param  array $data     Post data
     * @param  array $postarr  $_POST data
     * @return array           Post data (do not change)
     */
    public function checkSavedPost($data, $postarr)
    {
        remove_action('wp_insert_post_data', array($this, 'checkSavedPost'), 10, 2);

        $detector = new \BrokenLinkDetector\InternalDetector($data, $postarr);
        return $data;
    }

    /**
     * Enqueue required style
     * @return void
     */
    public function enqueueStyles()
    {
        wp_enqueue_style('broken-links-detector', BROKENLINKDETECTOR_URL . '/dist/css/broken-link-detector.min.css', '', '1.0.0');
    }

    /**
     * Enqueue required scripts
     * @return void
     */
    public function enqueueScripts()
    {
    }
}

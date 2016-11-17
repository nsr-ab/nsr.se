<?php

namespace BrokenLinkDetector;

class ExternalDetector
{
    public function __construct()
    {
        add_action('wp', array($this, 'schedule'));
        add_action('broken-links-detector-external', array($this, 'lookForBrokenLinks'));

        add_action('save_post', function ($postId) {
            if (wp_is_post_revision($postId) || !isset($_POST['broken-link-detector-rescan']) || $_POST['broken-link-detector-rescan'] !== 'true') {
                return;
            }

            wp_schedule_single_event(time() + 60, 'broken-links-detector-external', array($postId));
        });

        if (isset($_GET['broken-links-detector']) && $_GET['broken-links-detector'] == 'scan') {
            do_action('broken-links-detector-external');
        }
    }

    public function schedule()
    {
        if (wp_next_scheduled('broken-links-detector-external')) {
            return;
        }

        wp_schedule_event(time(), 'daily', 'broken-links-detector-external');
    }

    /**
     * Look for broken links in post_content
     * @param integer $post_id Optional post_id to update broken links for
     * @return void
     */
    public function lookForBrokenLinks($postId = null, $url = null)
    {
        \BrokenLinkDetector\App::checkInstall();
        $foundUrls = array();

        if ($url) {
            $url = "REGEXP ('.*(href=\"{$url}\").*')";
        } else {
            $url = "RLIKE ('href=*')";
        }

        global $wpdb;
        $sql = "
            SELECT ID, post_content
            FROM $wpdb->posts
            WHERE
                post_content {$url}
                AND post_type NOT IN ('attachment', 'revision', 'acf', 'acf-field', 'acf-field-group')
                AND post_status IN ('publish', 'private', 'password')
        ";

        if (is_numeric($postId)) {
            $sql .= " AND ID = $postId";
        }

        $posts = $wpdb->get_results($sql);

        foreach ($posts as $post) {
            preg_match_all('/<a[^>]+href=([\'"])(http|https)(.+?)\1[^>]*>/i', $post->post_content, $m);

            if (!isset($m[3]) || count($m[3]) > 0) {
                foreach ($m[3] as $key => $url) {
                    $url = $m[2][$key] . $url;

                    if ($postId !== 'internal' && !$this->isBroken($url)) {
                        continue;
                    }

                    $foundUrls[] = array(
                        'post_id' => $post->ID,
                        'url' => $url
                    );
                }
            }
        }

        $this->saveBrokenLinks($foundUrls, $postId);
    }

    /**
     * Save broken links (if not already in db)
     * @param  array $data
     * @return void
     */
    public function saveBrokenLinks($data, $postId = null)
    {
        global $wpdb;

        $inserted = array();
        $tableName = \BrokenLinkDetector\App::$dbTable;

        if (is_numeric($postId)) {
            $wpdb->delete($tableName, array('post_id' => $postId), array('%d'));
        } elseif (is_null($postId)) {
            $wpdb->query("TRUNCATE $tableName");
        }

        foreach ($data as $item) {
            $exists = $wpdb->get_row("SELECT id FROM $tableName WHERE post_id = {$item['post_id']} AND url = '{$item['url']}'");

            if ($exists) {
                continue;
            }

            $inserted[] = $wpdb->insert($tableName, array(
                'post_id' => $item['post_id'],
                'url' => $item['url']
            ), array('%d', '%s'));
        }

        return true;
    }

    /**
     * Check if a link gives 404 header
     * @param  string  $url Url to check
     * @return boolean      "True" if 404 response header else "false"
     */
    public function isBroken($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return true;
        }

        $headers = @get_headers($url);

        if (strtolower($headers[0]) == 'http/1.1 404 not found') {
            return true;
        }

        return false;
    }
}

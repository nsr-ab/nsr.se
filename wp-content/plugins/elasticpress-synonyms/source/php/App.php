<?php

namespace ElasticPressSynonyms;

class App
{
    public function __construct()
    {
        add_filter('acf/settings/load_json', array($this, 'loadJson'));

        add_action('init', array($this, 'init'));
        add_action('acf/save_post', array($this, 'elasticpressReindex'), 20);
    }

    public function loadJson($paths)
    {
        $paths[] = ELASTICPRESS_SYNONYMS_PATH . 'source/acf-json';
        return $paths;
    }

    /**
     * Init
     * @return void
     */
    public function init()
    {
        if (!$this->isElasticPress()) {
            return;
        }

        if (is_multisite()) {
            add_action('acf/save_post', array($this, 'multisiteSync'));
        }

        add_action('admin_menu', array($this, 'addSynonymsOptionsPage'));
        add_filter('ep_config_mapping', array($this, 'elasticPressSynonymMapping'));
    }

    public function elasticpressReindex()
    {
        $screen = get_current_screen();

        if ($screen->id !== 'tools_page_synonyms') {
            return;
        }

        // Run cron in 10 seconds
        wp_schedule_single_event(time() + 10, 'ep_sync');
    }

    /**
     * Setup synonym mapping for elasticpress
     * @param  array $mapping
     * @return array
     */
    public function elasticPressSynonymMapping($mapping)
    {

        //Get synonyms
        $synonyms = (array) get_field('elasticpress_synonyms', 'options');

        // Validate that we have data, if not. Exit.
        if (!$synonyms || empty($synonyms)) {
            return $mapping;
        }

        // Get synonyms
        $synonymData = array();
        foreach ($synonyms as $synonym) {
            $data = explode(',', $synonym['words']);
            $data = array_map('trim', $data);
            $data = implode(',', $data);
            $synonymData[] = strtolower($data);
        }

        // Define filter for synonyms
        $mapping['settings']['analysis']['filter']['elasticpress_synonyms'] = array(
            'type' => 'synonym',
            'synonyms' => $synonymData
        );

        // FREDRIK: Must add synonym as an analyzer filter too
        $mapping['settings']['analysis']['analyzer']['elasticpress_synonyms'] = array(
            'tokenizer'=>'standard',
            'filter'=>array('elasticpress_synonyms')
        );
    
        // Tell ES to use filter above by default
        array_unshift($mapping['settings']['analysis']['analyzer']['default']['filter'], 'elasticpress_synonyms');

        // Return new mapping settings
        return $mapping;
    }

    /**
     * Adds synonyms wordlist options page
     */
    public function addSynonymsOptionsPage()
    {
        if (!class_exists('EP_Modules') || !function_exists('acf_add_options_page')) {
            return;
        }

        acf_add_options_page(array(
            'page_title' => __('Search synonyms', 'municipio'),
            'menu_slug' => 'synonyms',
            'parent_slug' => 'options-general.php',
            'icon_url' => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+Cjxzdmcgd2lkdGg9IjEwMHB4IiBoZWlnaHQ9Ijg4cHgiIHZpZXdCb3g9IjAgMCAxMDAgODgiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+CiAgICA8IS0tIEdlbmVyYXRvcjogU2tldGNoIDQwLjMgKDMzODM5KSAtIGh0dHA6Ly93d3cuYm9oZW1pYW5jb2RpbmcuY29tL3NrZXRjaCAtLT4KICAgIDx0aXRsZT5TaGFwZTwvdGl0bGU+CiAgICA8ZGVzYz5DcmVhdGVkIHdpdGggU2tldGNoLjwvZGVzYz4KICAgIDxkZWZzPjwvZGVmcz4KICAgIDxnIGlkPSJQYWdlLTEiIHN0cm9rZT0ibm9uZSIgc3Ryb2tlLXdpZHRoPSIxIiBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPgogICAgICAgIDxwYXRoIGQ9Ik05Ni4zOSwzOS42MSBMOTYuMzksMzkuNjEgTDc0LjE5LDQuOTUgQzcyLjU0MzIyMTUsMi4yOTQ3MTY4IDY5LjY4MjQxNDksMC42MzM3MjQyNzEgNjYuNTYsMC41MiBDNjQuMTI3ODIwMywwLjQ3NDM1NTAwMSA2MS43Nzk0NTg1LDEuNDA4Njk5MzEgNjAuMDQzNDMyLDMuMTEyNzUxMzUgQzU4LjMwNzQwNTUsNC44MTY4MDMzOSA1Ny4zMjk1NzE3LDcuMTQ3MzkyMDkgNTcuMzMsOS41OCBMNTcuMzMsMTUuMTIgQzU3LjMzMDAyMDUsMTUuODU2MTczOCA1Ni43MzYxNTMzLDE2LjQ1NDUwNjIgNTYsMTYuNDYgTDQ0LDE2LjQ2IEM0My4yNTk5Mzg0LDE2LjQ2IDQyLjY2LDE1Ljg2MDA2MTYgNDIuNjYsMTUuMTIgTDQyLjY2LDkuODQgQzQyLjcxNjYzNzksNS41OTA1Nzk4NyAzOS44NzA0NjA5LDEuODQ5MzAwODcgMzUuNzYsMC43NyBDMzIuMDAzODA3OSwtMC4xMzU1OTY1ODcgMjguMDg2MjczMywxLjQzNzgzOTQzIDI2LDQuNjkgTDMuNjEsMzkuNjEgTDMuNjEsMzkuNjEgQy0wLjc4MjIzNDg0Myw0Ni41MzM2NjE0IC0wLjc3OTIzNDgxNiw1NS4zNzE1NTI3IDMuNjE3Njk5NDksNjIuMjkyMjMwNiBDOC4wMTQ2MzM4LDY5LjIxMjkwODUgMTYuMDEzOTQ1Niw3Mi45NzA0Nzk5IDI0LjE0NzczNjMsNzEuOTM1OTQ1MyBDMzIuMjgxNTI3MSw3MC45MDE0MTA2IDM5LjA4NTUxMzUsNjUuMjYxMDExNiA0MS42MSw1Ny40NiBDNDEuODkwNDQ5Myw1Ni42NzEwMTE3IDQyLjYzMjcxMzEsNTYuMTQwMjUzMiA0My40Nyw1Ni4xMyBMNTYuNDcsNTYuMTMgQzU3LjMwNzI4NjksNTYuMTQwMjUzMiA1OC4wNDk1NTA3LDU2LjY3MTAxMTcgNTguMzMsNTcuNDYgQzYwLjg1NDQ4NjUsNjUuMjYxMDExNiA2Ny42NTg0NzI5LDcwLjkwMTQxMDYgNzUuNzkyMjYzNyw3MS45MzU5NDUzIEM4My45MjYwNTQ0LDcyLjk3MDQ3OTkgOTEuOTI1MzY2Miw2OS4yMTI5MDg1IDk2LjMyMjMwMDUsNjIuMjkyMjMwNiBDMTAwLjcxOTIzNSw1NS4zNzE1NTI3IDEwMC43MjIyMzUsNDYuNTMzNjYxNCA5Ni4zMywzOS42MSBMOTYuMzksMzkuNjEgWiBNMjEuNTEsNjIuOCBDMTYuNjk1OTkxMyw2Mi44MDQwNDU0IDEyLjM1Mzc1MTQsNTkuOTA3MjY5NyAxMC41MDg3MDYzLDU1LjQ2MDg2NjIgQzguNjYzNjYxMTUsNTEuMDE0NDYyNyA5LjY3OTMwNDgyLDQ1Ljg5NDQyMTIgMTMuMDgxODkzLDQyLjQ4ODk3MTMgQzE2LjQ4NDQ4MTIsMzkuMDgzNTIxMyAyMS42MDM2NjcxLDM4LjA2MzU3MzcgMjYuMDUxNjIwMSwzOS45MDQ4ODAxIEMzMC40OTk1NzMyLDQxLjc0NjE4NjUgMzMuMzk5OTk4Myw0Ni4wODU5ODk2IDMzLjQsNTAuOSBDMzMuNDAwMDAyMyw1Ny40NjgyODUzIDI4LjA3ODI4MjksNjIuNzk0NDgwNCAyMS41MSw2Mi44IEwyMS41MSw2Mi44IFogTTc4LjUxLDYyLjggQzczLjY5Njg5OTMsNjIuOCA2OS4zNTc3Mjc1LDU5LjkwMDY1ODEgNjcuNTE1ODMzNiw1NS40NTM5MzI4IEM2NS42NzM5Mzk3LDUxLjAwNzIwNzYgNjYuNjkyMDUzMiw0NS44ODg4MDU1IDcwLjA5NTQyOTMsNDIuNDg1NDI5MyBDNzMuNDk4ODA1NSwzOS4wODIwNTMyIDc4LjYxNzIwNzYsMzguMDYzOTM5NyA4My4wNjM5MzI4LDM5LjkwNTgzMzYgQzg3LjUxMDY1ODEsNDEuNzQ3NzI3NSA5MC40MSw0Ni4wODY4OTkzIDkwLjQxLDUwLjkgQzkwLjQxMDAwNDUsNTQuMDU5NTQ0NSA4OS4xNTM1MTMsNTcuMDg5MzkxIDg2LjkxNzUwMjYsNTkuMzIxNjQ5NyBDODQuNjgxNDkyMiw2MS41NTM5MDg0IDgxLjY0OTU0LDYyLjgwNTMxMDIgNzguNDksNjIuOCBMNzguNTEsNjIuOCBaIE01MCw4Ny40OSBDNDcuNDE1MzA3NCw4Ny40OSA0NS4zMiw4NS4zOTQ2OTI2IDQ1LjMyLDgyLjgxIEM0NS4zMiw4MC4yMjUzMDc0IDQ3LjQxNTMwNzQsNzguMTMgNTAsNzguMTMgQzUyLjU4NDY5MjYsNzguMTMgNTQuNjgsODAuMjI1MzA3NCA1NC42OCw4Mi44MSBDNTQuNjgsODUuMzk0NjkyNiA1Mi41ODQ2OTI2LDg3LjQ5IDUwLDg3LjQ5IEw1MCw4Ny40OSBaIE02NC4xNSw4Ny40OSBDNjEuNTY1MzA3NCw4Ny40OSA1OS40Nyw4NS4zOTQ2OTI2IDU5LjQ3LDgyLjgxIEM1OS40Nyw4MC4yMjUzMDc0IDYxLjU2NTMwNzQsNzguMTMgNjQuMTUsNzguMTMgQzY2LjczNDY5MjYsNzguMTMgNjguODMsODAuMjI1MzA3NCA2OC44Myw4Mi44MSBDNjguODMwMDIzNSw4NS4zODY5MDIyIDY2Ljc0Njg3ODcsODcuNDc4OTg3NiA2NC4xNyw4Ny40OSBMNjQuMTUsODcuNDkgWiBNMzUuODQsODcuNDkgQzMzLjI1NTMwNzQsODcuNDkgMzEuMTYsODUuMzk0NjkyNiAzMS4xNiw4Mi44MSBDMzEuMTYsODAuMjI1MzA3NCAzMy4yNTUzMDc0LDc4LjEzIDM1Ljg0LDc4LjEzIEMzOC40MjQ2OTI2LDc4LjEzIDQwLjUyLDgwLjIyNTMwNzQgNDAuNTIsODIuODEgQzQwLjUyMDAyMzUsODUuMzg2OTAyMiAzOC40MzY4Nzg3LDg3LjQ3ODk4NzYgMzUuODYsODcuNDkgTDM1Ljg0LDg3LjQ5IFoiIGlkPSJTaGFwZSIgZmlsbD0iIzAwMDAwMCI+PC9wYXRoPgogICAgPC9nPgo8L3N2Zz4='
        ));
    }

    /**
     * Checks if ElasticPress search is activated
     * @return boolean
     */
    public function isElasticPress()
    {
        if (!class_exists('EP_Modules')) {
            return false;
        }

        $modules = \EP_Modules::factory();
        $activeModules = $modules->get_active_modules();

        if (isset($activeModules['search'])) {
            return true;
        }

        return false;
    }

    /**
     * Syncs synonyms between sites in multisite
     * @param  string|int $postId Saved post
     * @return void
     */
    public function multisiteSync($postId)
    {
        if ($postId != 'options') {
            return;
        }

        $screen = get_current_screen();
        if ($screen->id != 'settings_page_synonyms') {
            return;
        }

        $sites = get_sites();
        $synonyms = get_field('elasticpress_synonyms', 'options');

        foreach ($sites as $site) {
            switch_to_blog($site->blog_id);
            update_field('field_57fcc7f8c8862', $synonyms, 'options');
        }

        restore_current_blog();
    }
}

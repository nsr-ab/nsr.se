<?php

namespace Municipio\Controller;

class Search extends \Municipio\Controller\BaseController
{
    public function init()
    {
        if (get_field('use_google_search', 'option') === true) {
            $this->googleSearch();
        } else {
            $this->wpSearch();
        }

        $this->data['template'] = is_null(get_field('search_result_layout', 'option')) ? 'default' : get_field('search_result_layout', 'option');
        $this->data['gridSize'] = get_field('search_result_grid_columns', 'option');
    }

    /**
     * Default wordpress search
     * @return void
     */
    public function wpSearch()
    {
        global $wp_query;
        $this->data['resultCount'] = $wp_query->found_posts;
        $this->data['keyword'] = get_search_query();
    }

    /**
     * Google Site Search init
     * @return void
     */
    public function googleSearch()
    {
        $search = new \Municipio\Search\Google(get_search_query(), $this->getIndex());
        $this->data['search'] = $search;
        $this->data['results'] = $search->results;
    }

    /**
     * Get pagination index (used for Google Site Search)
     * @return integer
     */
    public function getIndex()
    {
        return isset($_GET['index']) && is_numeric($_GET['index']) ? sanitize_text_field($_GET['index']) : 1;
    }
}

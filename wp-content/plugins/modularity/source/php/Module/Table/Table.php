<?php

namespace Modularity\Module\Table;

class Table extends \Modularity\Module
{
    public function __construct()
    {

        //Register acf module
        $this->register(
            'table',
            __("Table", 'modularity'),
            __("Tables", 'modularity'),
            __("Outputs a flexible table with options.", 'modularity'),
            array(), //supports
            null, //icon
            'acf-dynamic-table-field/acf-anagram_dynamic_table_field.php', //included plugin
            3600*24*7
        );

        //Register stylesheets
        add_action('Modularity/Module/mod-table/enqueue', array($this, 'modAssets'));

        add_action('save_post', array($this, 'csvImport'), 999);
    }

    public function csvImport($post_id)
    {
        if (!isset($_POST['post_type']) || $_POST['post_type'] != $this->moduleSlug) {
            return;
        }

        if (get_field('mod_table_data_type', $post_id) != 'csv') {
            return;
        }

        ini_set('auto_detect_line_endings', true);

        $file = get_field('mod_table_csv_file', $post_id);
        $file = fopen($file['url'], 'r');
        $data = array();

        if (!$file) {
            wp_die(__('There was an error opening the selected .csv-file.'));
        }

        while (!feof($file)) {
            $row = fgetcsv($file, 0, ';');

            if (count($row) === 0) {
                continue;
            }

            foreach ($row as &$value) {
                $value = mb_convert_encoding($value, 'UTF-8', 'Windows-1252');
            }

            array_push($data, $row);
        }

        fclose($file);

        $data = array_filter($data);
        $data = json_encode($data);

        update_post_meta($post_id, 'mod_table', $data);
        update_post_meta($post_id, '_mod_table', 'field_5666a2ae23643');
    }

    public function modAssets()
    {
        wp_register_script('mod-table', MODULARITY_URL . '/dist/js/Table/assets/table.js', array(), '1.1.1', true);
        wp_enqueue_script('mod-table');

        wp_register_style('mod-table', MODULARITY_URL . '/dist/css/Table/assets/table.min.css', array(), '1.1.1');
        wp_enqueue_style('mod-table');
    }

    public function script()
    {
        if (!$this->hasModule()) {
            return;
        }

        wp_enqueue_script('datatables', 'https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js', array(), '1.10.11', true);
        wp_enqueue_script('datatables-init', MODULARITY_URL . '/dist/js/Table/assets/table-init.js', array(), '1.0.0', true);
        wp_localize_script('datatables-init', 'datatablesLang', array(
            'sEmptyTable' => __('No data available in table', 'modularity'),
            'sInfo' => __('Showing _START_ to _END_ of _TOTAL_ entries', 'modularity'),
            'sInfoEmpty' => __('Showing 0 to 0 of 0 entries', 'modularity'),
            'sInfoFiltered' => __('filtered from _MAX_ total entries', 'modularity'),
            'sLengthMenu' => __('Show _MENU_ entries', 'modularity'),
            'sLoadingRecords' => __('Loading...', 'modularity'),
            'sProcessing' => __('Processing...', 'modularity'),
            'sZeroRecords' => __('No matching records found', 'modularity'),
            'sFirst' => __('First', 'modularity'),
            'sLast' => __('Last', 'modularity'),
            'sNext' => __('Next', 'modularity'),
            'sPrevious' => __('Previous', 'modularity'),
            'sSortAscending' => __('activate to sort column ascending', 'modularity'),
            'sSortDescending' => __('activate to sort column descending', 'modularity')
        ));
    }
}

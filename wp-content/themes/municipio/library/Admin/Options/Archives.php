<?php

namespace Municipio\Admin\Options;

class Archives
{
    public function __construct()
    {
        add_action('admin_init', array($this, 'addArchiveOptions'));
    }

    /**
     * Adds archive options fields
     */
    public function addArchiveOptions()
    {
        if (!function_exists('acf_add_local_field_group')) {
            return;
        }

        $postTypes = array();
        foreach (get_post_types() as $key => $postType) {
            $args = get_post_type_object($postType);

            if (!$args->public || $args->name === 'page') {
                continue;
            }

            $postTypes[$postType] = $args;
        }

        foreach ($postTypes as $posttype => $args) {
            // Get posttype taxonommies
            $taxonomies = array();
            $taxonomiesRaw = get_object_taxonomies($posttype);

            foreach ($taxonomiesRaw as $taxonomy) {
                $taxonomies[$taxonomy] = $taxonomy;
            }

            $fieldArgs = array(
                'key' => 'group_' . md5($posttype),
                'title' => __('Archive for', 'municipio') . ': ' . $args->label,
                'fields' => array(),
                'location' => array (
                    array (
                        array (
                            'param' => 'options_page',
                            'operator' => '==',
                            'value' => 'acf-options-archives',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => '',
            );

            if ($args->has_archive || $args->name === 'post') {
                // Feed display label
                $fieldArgs['fields'][] = array (
                    'key' => 'field_570e104caf1b2_' . md5($posttype),
                    'label' => 'Archive feed display settings',
                    'name' => 'archive_' . sanitize_title($posttype) . '_feed_display_settings',
                    'type' => 'message',
                    'instructions' => 'The below settings will apply for the archive feed.',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array (
                        'width' => '',
                        'class' => 'municipio-theme-options-label',
                        'id' => '',
                    ),
                    'message' => '',
                    'new_lines' => 'wpautop',
                    'esc_html' => 0,
                );

                // Post style
                $fieldArgs['fields'][] = array(
                    'key' => 'field_56f00fe21f918_' . md5($posttype),
                    'label' => 'Post style',
                    'name' => 'archive_' . sanitize_title($posttype) . '_post_style',
                    'type' => 'select',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array (
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array (
                        'full' => 'Full',
                        'collapsed' => 'Collapsed',
                        'compressed' => 'Compressed',
                        'grid' => 'Grid',
                        'cards' => 'Post cards',
                        'newsitem' => 'News items'
                    ),
                    'default_value' => array (
                        0 => 'full',
                    ),
                    'allow_null' => 0,
                    'multiple' => 0,
                    'ui' => 0,
                    'ajax' => 0,
                    'placeholder' => '',
                    'disabled' => 0,
                    'readonly' => 0,
                );

                // Grid columns
                $fieldArgs['fields'][] = array(
                    'key' => 'field_56f1045257121_' . md5($posttype),
                    'label' => 'Grid columns',
                    'name' => 'archive_' . sanitize_title($posttype) . '_grid_columns',
                    'type' => 'select',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array (
                        array (
                            array (
                                'field' => 'field_56f00fe21f918_' . md5($posttype),
                                'operator' => '==',
                                'value' => 'grid',
                            ),
                        ),
                        array (
                            array (
                                'field' => 'field_56f00fe21f918_' . md5($posttype),
                                'operator' => '==',
                                'value' => 'cards',
                            ),
                        ),
                    ),
                    'wrapper' => array (
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array (
                        'grid-md-12' => 1,
                        'grid-md-6' => 2,
                        'grid-md-4' => 3,
                        'grid-md-3' => 4,
                    ),
                    'default_value' => array (
                        0 => 'grid-md-12',
                    ),
                    'allow_null' => 0,
                    'multiple' => 0,
                    'ui' => 0,
                    'ajax' => 0,
                    'placeholder' => '',
                    'disabled' => 0,
                    'readonly' => 0,
                );

                // Post sorting
                $metaKeys = array(
                    'post_date'  => 'Date published',
                    'post_modified' => 'Date modified',
                    'post_title' => 'Title',
                );

                $metaKeysRaw = \Municipio\Helper\Post::getPosttypeMetaKeys($posttype);

                foreach ($metaKeysRaw as $metaKey) {
                    $metaKeys[$metaKey->meta_key] = $metaKey->meta_key;
                }

                $fieldArgs['fields'][] = array(
                    'key' => 'field_56f64546rref918_' . md5($posttype),
                    'label' => 'Sort on',
                    'name' => 'archive_' . sanitize_title($posttype) . '_sort_key',
                    'type' => 'select',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array (
                        'width' => '50%',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => apply_filters('Municipio/archive/sort_keys', $metaKeys, $posttype),
                    'default_value' => array (
                        0 => 'post_date',
                    ),
                    'allow_null' => 0,
                    'multiple' => 0,
                    'ui' => 0,
                    'ajax' => 0,
                    'placeholder' => '',
                    'disabled' => 0,
                    'readonly' => 0,
                );

                $fieldArgs['fields'][] = array(
                    'key' => 'field_56fwe545ergref918_' . md5($posttype),
                    'label' => 'Order',
                    'name' => 'archive_' . sanitize_title($posttype) . '_sort_order',
                    'type' => 'select',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array (
                        'width' => '50%',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'asc' => 'Ascending',
                        'desc' => 'Descending'
                    ),
                    'default_value' => array (
                        0 => 'desc',
                    ),
                    'allow_null' => 0,
                    'multiple' => 0,
                    'ui' => 0,
                    'ajax' => 0,
                    'placeholder' => '',
                    'disabled' => 0,
                    'readonly' => 0,
                );

                // Post filters
                $fieldArgs['fields'][] = array(
                    'key' => 'field_570ba0c87756c' . md5($posttype),
                    'label' => 'Post filters',
                    'name' => 'archive_' . sanitize_title($posttype) . '_post_filters_header',
                    'type' => 'checkbox',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array (
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array (
                        'text_search' => 'Text search',
                        'date_range' => 'Date range'
                    ),
                    'default_value' => array (
                    ),
                    'layout' => 'horizontal',
                    'toggle' => 0,
                );

                // Post filters sidebar
                if (count($taxonomies) > 0) {
                    $fieldArgs['fields'][] = array(
                        'key' => 'field_570ba0c8erg434' . md5($posttype),
                        'label' => 'Taxonomy filters',
                        'name' => 'archive_' . sanitize_title($posttype) . '_post_filters_sidebar',
                        'type' => 'checkbox',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => $taxonomies,
                        'default_value' => array (
                        ),
                        'layout' => 'horizontal',
                        'toggle' => 0,
                    );
                }

                // Publish date
                $fieldArgs['fields'][] = array(
                    'key' => 'field_56fcc6914296b_' . md5($posttype),
                    'label' => 'Date published',
                    'name' => 'archive_' . sanitize_title($posttype) . '_feed_date_published',
                    'type' => 'radio',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array (
                        'width' => '50%',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array (
                        'false' => 'Do not show',
                        'datetime' => 'Date and time',
                        'date' => 'Date only',
                        'time' => 'Time only',
                    ),
                    'other_choice' => 0,
                    'save_other_choice' => 0,
                    'default_value' => 'datetime',
                    'layout' => 'horizontal',
                );

                // Sidebar navigation
                $fieldArgs['fields'][] = array(
                    'key' => 'field_570ded8a47206_' . md5($posttype),
                    'label' => 'Sidebar navigation',
                    'name' => 'archive_' . sanitize_title($posttype) . '_show_sidebar_navigation',
                    'type' => 'true_false',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array (
                        'width' => '50%',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => 'Yes, show the sidebar navigation',
                    'default_value' => 0,
                );
            }

            // Post display label
            $fieldArgs['fields'][] = array (
                'key' => 'field_570e104caf1b3_' . md5($posttype),
                'label' => 'Post display settings',
                'name' => 'archive_' . sanitize_title($posttype) . '_post_display_settings',
                'type' => 'message',
                'instructions' => 'The below settings will apply for single post view.',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array (
                    'width' => '',
                    'class' => 'municipio-theme-options-label',
                    'id' => '',
                ),
                'message' => '',
                'new_lines' => 'wpautop',
                'esc_html' => 0,
            );

            $fieldArgs['fields'][] = array(
                'key' => 'field_56fcc6914296c_' . md5($posttype),
                'label' => 'Date published',
                'name' => 'archive_' . sanitize_title($posttype) . '_post_date_published',
                'type' => 'radio',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array (
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array (
                    'false' => 'Do not show',
                    'datetime' => 'Date and time',
                    'date' => 'Date only',
                    'time' => 'Time only',
                ),
                'other_choice' => 0,
                'save_other_choice' => 0,
                'default_value' => 'datetime',
                'layout' => 'horizontal',
            );

            $fieldArgs['fields'][] = array (
                'key' => 'field_56fcc62b8ab02_' . md5($posttype),
                'label' => 'Info to display',
                'name' => 'archive_' . sanitize_title($posttype) . '_post_display_info',
                'type' => 'checkbox',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array (
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array (
                    'category' => 'Show category',
                    'tags' => 'Show tags',
                    'author' => 'Show author',
                    'author_image' => 'Show autor image (only if show author)',
                ),
                'other_choice' => 0,
                'save_other_choice' => 0,
                'default_value' => '',
                'layout' => 'horizontal',
            );


            acf_add_local_field_group($fieldArgs);
        }
    }
}

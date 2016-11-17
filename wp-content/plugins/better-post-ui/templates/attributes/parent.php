<?php

$dropdown_args = array(
    'post_type'        => $post->post_type,
    'exclude_tree'     => $post->ID,
    'selected'         => $post->post_parent,
    'name'             => 'parent_id',
    'show_option_none' => __('(no parent)'),
    'sort_column'      => 'menu_order, post_title',
    'echo'             => 0,
    'post_status'      => array('publish', 'future', 'draft', 'pending', 'private')
);

$dropdown_args = apply_filters('page_attributes_dropdown_pages_args', $dropdown_args, $post);
$pages = wp_dropdown_pages($dropdown_args);

if (!empty($pages)) :
?>
<section>
    <div class="better-post-ui-parent-list">
        <strong><?php _e('Parent') ?></strong>
        <?php echo $pages; ?>

        <a href="#" class="button" data-action="better-post-ui-parent-show-search"><?php _e('Show search', 'better-post-ui'); ?></a>
    </div>

    <div class="better-post-ui-parent-search">
        <strong><?php _e('Search'); ?> <?php echo mb_strtolower(__('Parent')) ?></strong>
        <label class="screen-reader-text" for="parent_id"><?php _e('Parent') ?></label>
        <input type="search" data-action="better-post-ui-parent-search" class="widefat" placeholder="<?php _e('Search'); ?>…">

        <a href="#" class="button" data-action="better-post-ui-parent-show-all"><?php _e('Cancel'); ?></a>
    </div>
</section>
<?php endif; ?>

<?php
$orderPages = get_posts(array(
    'posts_per_page' => -1,
    'post_type' => $post->post_type,
    'post_parent' => $post->post_parent,
    'orderby' => 'menu_order title',
    'order' => 'asc'
));
?>
<section>
    <strong><?php _e('Order') ?></strong>
    <p style="margin-top:0;"><?php _e('Drag or click the arrows to reorder. The bold marked page is the post you are currently editing.', 'better-post-ui'); ?></p>
    <ul class="better-post-ui-menu-order-list">
        <?php foreach ($orderPages as $page) : ?>
        <li<?php echo $page->ID == $post->ID ? ' class="current"' : ''; ?>>
            <button type="button" data-action="better-post-ui-order-up">&uarr;</button>
            <button type="button" data-action="better-post-ui-order-down">&darr;</button>
            <span><?php echo $page->post_title; ?></span>

            <?php if ($page->ID == $post->ID) : ?>
            <input type="hidden" name="menu_order" value="<?php echo $post->menu_order; ?>">
            <?php else : ?>
            <input type="hidden" name="sibling_menu_order[<?php echo $page->ID; ?>]" value="<?php echo $page->menu_order; ?>">
            <?php endif; ?>
        </li>
        <?php endforeach; ?>
    </ul>
</section>

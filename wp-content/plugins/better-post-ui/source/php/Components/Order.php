<?php

namespace BetterPostUi\Components;

class Order
{
    public function __construct()
    {
        add_action('save_post', array($this, 'saveMenuOrder'), 10, 2);
    }

    public function saveMenuOrder($postId, $post)
    {
        if (!isset($_POST['sibling_menu_order']) || empty($_POST['sibling_menu_order'])) {
            return;
        }

        remove_action('save_post', array($this, 'saveMenuOrder'), 10, 2);

        $siblingOrder = $_POST['sibling_menu_order'];
        foreach ($siblingOrder as $postId => $menuOrder) {
            wp_update_post(array(
                'ID' => $postId,
                'menu_order' => $menuOrder
            ));
        }

        add_action('save_post', array($this, 'saveMenuOrder'), 10, 2);

        return true;
    }
}

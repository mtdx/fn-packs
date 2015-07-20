<?php
// not possible to use classes, use functions

add_shortcode('fncp_packs', 'fncp_render_packs');
function fncp_render_packs()
{
    $PACKS = FNCP_Packs_Factory::build(array_keys(FNCP_Pack::$PACKS));
    include FNCP_DIR_PATH . 'views/frontend/packs.php';
}

// should be used on the pack page, pulls the pack it from somewhere.
//add_shortcode('fncp_open_pack', 'fncp_open_pack');
function fncp_open_pack()
{
    if (!$userId = get_current_user_id()) {
        $message = 'Please <a href="/login">Sign In</a> or <a href="/register">Register</a>';
        include FNCP_DIR_PATH . 'views/frontend/restricted.php';
        return false;
    }
    $opened = false;
    $user = new FNAC_User($userId);
    $pack = $user->get_pack($_GET['pk']);
    try {
        if ($user->can_open($pack)) {
            $pack->set_cards();
            $opened = $user->open_pack($pack);
        }
    } catch (ErrorException $e) {
        FNCP_Stats::log($e->getMessage());
    }
    if ($opened) {
        $flash_card = $pack->get_expensive();
        include FNCP_DIR_PATH . 'views/frontend/open-pack.php';
    } else {
        $message = "Your don't have enough ninja points or coins for this Pack";
        include FNCP_DIR_PATH . 'views/frontend/restricted.php';
    }

    return null;
}


// plugin css // js
add_action('wp_enqueue_scripts', 'fncp_enqueue_frontend');
function fncp_enqueue_frontend()
{
    //kind of hardcoded but we can't include it globally
    if (is_page(20)) {
        wp_enqueue_style('fncp-manage-css', FNCP_DIR_URL . 'css/manage-packs.css', array(), '0.1', false);
        wp_enqueue_script('fncp-manage-js', FNCP_DIR_URL . 'js/manage-packs.js', array('jquery'), '0.1', true);
    }
}
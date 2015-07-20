<?php

//add_shortcode('fnac_user_data', 'fnac_user_data');
function fnac_user_data($type)
{
    if (!$userId = get_current_user_id()) {
        $message = 'Please <a href="/login">Sign In</a> or <a href="/register">Register</a>';
        include FNCP_DIR_PATH . 'views/frontend/restricted.php';
        return false;
    }
    $user = new FNAC_User($userId);
    $data = $user->get_user_stock();

    if ($type == 'coins') {
        return FNCP_Stats::number($data->fldCoins);
    } else if ($type == 'points') {
        return FNCP_Stats::number($data->fldPoints);
    }

    return '';
}

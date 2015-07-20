<?php
/*
 * Plugin Name:       Ninja Manage Packs
 * Description:       #
 * Version:           0.1
 * Author:            Marcus S.
 */

if (!defined('WPINC')) die;

define('FNCP_DIR_PATH', plugin_dir_path(__FILE__));
define('FNCP_DIR_URL', plugin_dir_url(__FILE__));

require FNCP_DIR_PATH . 'library/class.manager.php';
require FNCP_DIR_PATH . 'library/shortcodes.php';

$fncp = new FNCP_Manager();
$fncp->run();


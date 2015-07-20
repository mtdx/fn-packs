<?php
/*
 * Plugin Name:       Ninja Manage Accounts
 * Description:       #
 * Version:           0.1
 * Author:            Marcus S.
 */

if (!defined('WPINC')) die;

define('FNAC_DIR_PATH', plugin_dir_path(__FILE__));
define('FNAC_DIR_URL', plugin_dir_url(__FILE__));

require FNAC_DIR_PATH . 'library/class.manager.php';
require FNAC_DIR_PATH . 'library/shortcodes.php';

$fnac = new FNAC_Manager();
$fnac->run();


// todo -m, let them use coins when points finished.
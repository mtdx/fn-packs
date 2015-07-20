<?php

class FNAC_Manager
{
    const LOG = 'accounts.log';
    protected $loader;
    protected $plugin_slug;
    protected $version;
    protected $admin;

    public function __construct()
    {

        $this->plugin_slug = 'fn-users-slug';
        $this->version = '0.1';
        $this->load_dependencies();

        $this->admin = new FNAC_Admin($this->get_version());
        $this->define_admin_hooks();
        $this->define_admin_settings();
    }

    private function load_dependencies()
    {
        require FNAC_DIR_PATH . 'admin/class.admin.php';
        require FNAC_DIR_PATH . 'library/class.loader.php';
        require FNAC_DIR_PATH . 'library/class.exchange.php';
        require FNAC_DIR_PATH . 'library/class.user.php';
        $this->loader = new FNAC_Loader();
    }

    /**
     * @return string
     */
    public function get_version()
    {
        return $this->version;
    }

    private function define_admin_hooks()
    {
        $this->loader->add_action('admin_enqueue_scripts', $this->admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $this->admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $this->admin, 'set_main_pages');
        $this->loader->add_action('admin_menu', $this->admin, 'set_sub_pages');
        $this->loader->add_action('wp_ajax_fnac_admin_withdraw', $this->admin, 'withdraw');
        //wee hook woo order completed
        $this->loader->add_action('woocommerce_order_status_processing', $this->admin, 'process_order');
    }

    private function define_admin_settings()
    {
        $this->loader->add_action('admin_init', $this->admin, 'save_point_value');
    }

    public static function log($message)
    {
        $message = date("F j, Y, g:i a ") . $message;
        error_log($message . "\n", 3, FNAC_DIR_PATH . self::LOG);
    }

    public function run()
    {
        $this->loader->run();
    }
}
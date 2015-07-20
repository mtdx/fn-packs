<?php

class FNCP_Manager
{
    protected $loader;
    protected $plugin_slug;
    protected $version;
    protected $admin;

    public function __construct()
    {

        $this->plugin_slug = 'fn-manage-packs-slug';
        $this->version = '0.1';
        $this->load_dependencies();

        $this->admin = new FNCP_Admin($this->get_version());
        $this->define_admin_hooks();
        $this->define_admin_settings();
    }

    private function load_dependencies()
    {
        require FNCP_DIR_PATH . 'admin/class.admin.php';
        require FNCP_DIR_PATH . 'library/class.loader.php';
        require FNCP_DIR_PATH . 'library/class.stats.php';
        require FNCP_DIR_PATH . 'library/cards/class.card.php';
        require FNCP_DIR_PATH . 'library/cards/class.cards_factory.php';
        require FNCP_DIR_PATH . 'library/cards/class.cards_generator.php';
        require FNCP_DIR_PATH . 'library/packs/class.pack.php';
        require FNCP_DIR_PATH . 'library/packs/class.25k_pack.php';
        require FNCP_DIR_PATH . 'library/packs/class.50k_pack.php';
        require FNCP_DIR_PATH . 'library/packs/class.100k_pack.php';
        require FNCP_DIR_PATH . 'library/packs/class.tots_pack.php';
        require FNCP_DIR_PATH . 'library/packs/class.totw_pack.php';
        require FNCP_DIR_PATH . 'library/packs/class.toty_pack.php';
        require FNCP_DIR_PATH . 'library/packs/class.hero_pack.php';
        require FNCP_DIR_PATH . 'library/packs/class.motm_pack.php';
        require FNCP_DIR_PATH . 'library/packs/class.ninja_pack.php';
        require FNCP_DIR_PATH . 'library/packs/class.legend_pack.php';
        require FNCP_DIR_PATH . 'library/packs/class.packs_factory.php';
        $this->loader = new FNCP_Loader();
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
    }

    private function define_admin_settings()
    {
        foreach (FNCP_Pack::$PACKS as $slug => $name) {
            $this->loader->add_action('admin_init', $this->admin, 'save_' . str_replace('fncp_', '', $slug) . '_probs');
        }
    }

    public function run()
    {
        $this->loader->run();
    }

}
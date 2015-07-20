<?php

class FNCP_Admin
{
    private $version;
    private $pages = array(
        array(
            'pagetitle' => 'Ninja Luck Packs Settings',
            'menutitle' => 'Ninja Packs',
            'cap' => 'manage_options',
            'slug' => 'fn-manage-packs-settings.php',
            'function' => 'render_settings',
            'icon' => 'icon-settings.png',
            'position' => null
        )
    );

    private $subpages = array(
        array(
            'parent_slug' => 'fn-manage-packs-settings.php',
            'pagetitle' => 'Statistics',
            'menutitle' => 'Statistics',
            'cap' => 'manage_options',
            'slug' => 'fn-manage-packs-statistics.php',
            'function' => 'render_statistics'
        ),
        array(
            'parent_slug' => 'fn-manage-packs-settings.php',
            'pagetitle' => 'Opened Packs',
            'menutitle' => 'Opened Packs',
            'cap' => 'manage_options',
            'slug' => 'fn-manage-packs-openedpacks.php',
            'function' => 'render_openedpacks'
        )
    );

    public function __construct($version)
    {
        $this->version = $version;
    }

    public function enqueue_styles()
    {
        wp_enqueue_style('fncp-admin', FNCP_DIR_URL . 'css/admin.css', array(), $this->version, false);
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('fncp-adminjs', FNCP_DIR_URL . 'js/admin.js', array('jquery'), $this->version, true);
    }

    public function set_main_pages()
    {
        foreach ($this->pages as $page) {
            add_menu_page(
                $page['pagetitle'],
                $page['menutitle'],
                $page['cap'],
                $page['slug'],
                array($this, $page['function']),
                FNCP_DIR_URL . 'views/img/' . $page['icon'],
                $page['position']
            );
        }
    }

    public function set_sub_pages()
    {
        foreach ($this->subpages as $page) {
            add_submenu_page(
                $page['parent_slug'],
                $page['pagetitle'],
                $page['menutitle'],
                $page['cap'],
                $page['slug'],
                array($this, $page['function'])
            );
        }
    }

    public function save_25k_pack_probs()
    {
        register_setting('fncp_25k_pack_probs', 'fncp_25k_pack_probs');
    }

    public function save_50k_pack_probs()
    {
        register_setting('fncp_50k_pack_probs', 'fncp_50k_pack_probs');
    }

    public function save_100k_pack_probs()
    {
        register_setting('fncp_100k_pack_probs', 'fncp_100k_pack_probs');
    }

    public function save_totw_pack_probs()
    {
        register_setting('fncp_totw_pack_probs', 'fncp_totw_pack_probs');
    }

    public function save_toty_pack_probs()
    {
        register_setting('fncp_toty_pack_probs', 'fncp_toty_pack_probs');
    }

    public function save_tots_pack_probs()
    {
        register_setting('fncp_tots_pack_probs', 'fncp_tots_pack_probs');
    }

    public function save_hero_pack_probs()
    {
        register_setting('fncp_hero_pack_probs', 'fncp_hero_pack_probs');
    }

    public function save_motm_pack_probs()
    {
        register_setting('fncp_motm_pack_probs', 'fncp_motm_pack_probs');
    }

    public function save_ninja_pack_probs()
    {
        register_setting('fncp_ninja_pack_probs', 'fncp_ninja_pack_probs');
    }

    public function save_legend_pack_probs()
    {
        register_setting('fncp_legend_pack_probs', 'fncp_legend_pack_probs');
    }

    public function render_settings()
    {
        $PACKS = FNCP_Pack::$PACKS;
        $special_cards = FNCP_Card::$special_cards;
        $cardtypes = FNCP_Card::get_separated_types();
        $count_types = FNCP_Card::count_types($cardtypes);
        include FNCP_DIR_PATH . 'views/admin/settings.php';
    }

    public function render_statistics()
    {
        $PACKS = FNCP_Pack::$PACKS;
        $cardtypes = FNCP_Card::get_separated_types();
        $count_types = FNCP_Card::count_types($cardtypes);
        $statistics = FNCP_Stats::get_main_statistics();
        $pack_statistics = FNCP_Stats::get_pack_main_statistics();
        $pack_stats = FNCP_Stats::get_pack_stats();
        include FNCP_DIR_PATH . 'views/admin/statistics.php';
    }

    public function render_openedpacks()
    {
        $cardtypes = FNCP_Card::get_separated_types();
        $count_types = FNCP_Card::count_types($cardtypes);
        $count = FNCP_Stats::count_openpacks();
        $pagination = FNCP_Stats::pagination($_GET['pagenum'], $count);
        $openpacks = FNCP_Stats::get_openpacks($pagination['offset']);
        $page_links = $pagination['page_links'];
        include FNCP_DIR_PATH . 'views/admin/openedpacks.php';
    }
}
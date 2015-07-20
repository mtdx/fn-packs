<?php

class FNAC_Admin
{
    private $version;
    private $pages = array(
        array(
            'pagetitle' => 'Users Coins',
            'menutitle' => 'Users Coins',
            'cap' => 'manage_options',
            'slug' => 'fn-manage-user-coins.php',
            'function' => 'render_user_coins',
            'icon' => 'icon-user-coins.png',
            'position' => null
        )
    );

    private $subpages = array(
        array(
            'parent_slug' => 'fn-manage-user-coins.php',
            'pagetitle' => 'Transactions',
            'menutitle' => 'Transactions',
            'cap' => 'manage_options',
            'slug' => 'fn-user-coins-transactions.php',
            'function' => 'render_transactions'
        )
    );

    public function __construct($version)
    {
        $this->version = $version;
    }

    public function enqueue_styles()
    {
        wp_enqueue_style('fnac-admin', FNAC_DIR_URL . 'css/admin.css', array(), $this->version, false);
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('fnac-adminjs', FNAC_DIR_URL . 'js/admin.js', array('jquery'), $this->version, true);
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
                FNAC_DIR_URL . 'views/img/' . $page['icon'],
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

    /**
     * @param $order_id
     * @throws ErrorException if we have a visitor
     * @return bool
     * we handle the WOO order
     */
    public function process_order($order_id)
    {
        try {
            $order = new WC_Order($order_id);
            if ($userId = $order->customer_user){
                $user = new FNAC_User($userId);
                if ($points = $user->process_order($order->get_items())) {
                    $order->add_order_note("Customer successfully purchased: {$points} points", 0);
                    return $order->update_status('completed');
                }
            } else {
                $order->update_status('on-hold');
                $order->add_order_note("Error: Empty User ID or Order ID", 0);
            }
        } catch (Exception $e) {
            FNAC_Manager::log($e->getMessage());
        }
        return $order->update_status('on-hold');
    }

    public function withdraw()
    {
        $userId = intval($_POST['fnac_userId']);
        if (current_user_can('manage_options') && is_numeric($userId)) {
            $user = new FNAC_User($userId);
            echo $user->withdraw(intval($_POST['fnac_value']));
        }

        wp_die();
    }

    public function render_user_coins()
    {
        $count = FNAC_User::count();
        $pagination = FNCP_Stats::pagination($_GET['pagenum'], $count);
        $users = FNAC_User::get_all($pagination['offset']);
        $page_links = $pagination['page_links'];
        include FNAC_DIR_PATH . 'views/admin/user_coins.php';
    }

    public function render_transactions()
    {
        $count = FNAC_User::countrans();
        $pagination = FNCP_Stats::pagination($_GET['pagenum'], $count);
        $transactions = FNAC_User::get_trans($pagination['offset']);
        $page_links = $pagination['page_links'];
        include FNAC_DIR_PATH . 'views/admin/transactions.php';
    }

    public function save_point_value()
    {
        register_setting('fnac_point_value', 'fnac_point_value');
    }


}
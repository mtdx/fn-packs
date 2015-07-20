<div class="wrap">
    <div id="icon-options-general" class="icon32"></div>
    <h2><?php esc_attr_e('Transactions', 'wp_admin_style'); ?></h2>

    <div id="poststuff" class="fncp">
        <div id="post-body" class="metabox-holder columns-1">
            <!-- main content -->
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox">
                        <div class="inside admin-usercoins">
                            <div class="tablenav">
                                <div class="tablenav-pages">
                                    <span class="displaying-num">
                                        <?php
                                        //we save it for the footer, just stupid
                                        $count2 = $count;
                                        $pagenum = isset($_GET['pagenum']) ? absint(intval($_GET['pagenum'])) : 1;
                                        esc_attr_e(($pagenum * FNCP_Stats::LIMIT - FNCP_Stats::LIMIT) . ' - ' . ($pagenum * FNCP_Stats::LIMIT) . ' of ' . $count, 'wp_admin_style');
                                        ?>
                                    </span>
                                    <?php echo $page_links; ?>
                                </div>
                            </div>
                            <table class="widefat">
                                <thead>
                                <tr>
                                    <th class="row-title">#</th>
                                    <th>User</th>
                                    <th>Type</th>
                                    <th>Value</th>
                                    <th>Admin</th>
                                    <th>Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = 0;
                                foreach ($transactions as $transaction):
                                    if ($i % 2 == 0) echo '<tr>';
                                    else echo '<tr  class="alternate">';
                                    if ($transaction->fldUserId) {
                                        $wpuser = get_userdata($transaction->fldUserId);
                                        $username = $wpuser->user_login;
                                    }
                                    if ($transaction->fldAdmin) {
                                        $admin = get_userdata($transaction->fldAdmin);
                                        $admin = $admin->user_login;
                                    }
                                    ?>
                                        <td><?php esc_attr_e($transaction->fldId, 'wp_admin_style') ?></td>
                                        <td>
                                            <a href="/wp-admin/user-edit.php?user_id=<?php echo intval($transaction->fldUserId); ?>">
                                                <?php esc_attr_e($username, 'wp_admin_style'); ?>
                                            </a>
                                        </td>
                                        <td class="row-title <?php echo ($transaction->fldType != 'withdraw') ? 'green' : 'red' ?>">
                                             <code><?php esc_attr_e($transaction->fldType, 'wp_admin_style'); ?></code>
                                        </td>
                                        <td class="row-title"><?php esc_attr_e(FNCP_Stats::number($transaction->fldValue), 'wp_admin_style'); ?></td>
                                        <td>
                                            <a href="/wp-admin/user-edit.php?user_id=<?php echo intval($transaction->fldAdmin); ?>">
                                                <?php esc_attr_e($admin, 'wp_admin_style'); ?>
                                            </a>
                                        </td>
                                        <td><?php esc_attr_e(date("F j, Y, g:i a", strtotime($transaction->fldUpdated)), 'wp_admin_style'); ?></td>
                                    </tr>
                                    <?php
                                    $i++;
                                endforeach;
                                ?>
                                </tbody>
                            </table>
                            <div class="tablenav">
                                <div class="tablenav-pages">
                                    <span class="displaying-num">
                                        <?php
                                        $pagenum = isset($_GET['pagenum']) ? absint(intval($_GET['pagenum'])) : 1;
                                        esc_attr_e(($pagenum * FNCP_Stats::LIMIT - FNCP_Stats::LIMIT) . ' - ' . ($pagenum * FNCP_Stats::LIMIT) . ' of ' . $count2, 'wp_admin_style');
                                        ?>
                                    </span>
                                    <?php echo $page_links; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- post-body-content -->
        </div>
        <br class="clear">
    </div>
    <!-- #poststuff -->
</div> <!-- .wrap -->
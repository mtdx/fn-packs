<div class="wrap">
    <div id="icon-options-general" class="icon32"></div>
    <h2><?php esc_attr_e('Users Coins', 'wp_admin_style'); ?></h2>

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
                            <table class="widefat usercoins">
                                <thead>
                                <tr>
                                    <th class="row-title">#</th>
                                    <th>Profile</th>
                                    <th>Stock</th>
                                    <th>Points</th>
                                    <th>Withdrew</th>
                                    <th>Packs</th>
                                    <th>Withdraw</th>
                                    <th>Last Active</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = 0;
                                foreach ($users as $user):
                                    if ($i % 2 == 0) echo '<tr>';
                                    else echo '<tr  class="alternate">';
                                    if ($user->fldUserId) {
                                        $wpuser = get_userdata($user->fldUserId);
                                        $username = $wpuser->user_login;
                                    }
                                    ?>
                                    <td><?php esc_attr_e($user->fldUserId, 'wp_admin_style') ?></td>
                                    <td>
                                      <a href="/wp-admin/user-edit.php?user_id=<?php echo intval($user->fldUserId); ?>">
                                            <?php esc_attr_e($username, 'wp_admin_style'); ?>
                                        </a>
                                    </td>
                                    <td class="row-title"><?php esc_attr_e(FNCP_Stats::number($user->fldCoins), 'wp_admin_style'); ?></td>
                                    <td class="row-title"><code><?php esc_attr_e(FNCP_Stats::number($user->fldPoints), 'wp_admin_style'); ?></code></td>
                                    <td><?php esc_attr_e(FNCP_Stats::number($user->fldWithdrew), 'wp_admin_style'); ?></td>
                                    <td><?php esc_attr_e(FNCP_Stats::number($user->fldPacks), 'wp_admin_style'); ?></td>
                                    <td>
                                     <form action="" class="withdraw" method="post">
                                         <input type="number" name="fnac_value" value="<?php esc_attr_e($user->fldCoins);?>" placeholder="<?php esc_attr_e($user->fldCoins);?>">
                                         <input type="hidden" name="fnac_userId" value="<?php echo $user->fldUserId ?>">
                                         <input type="hidden" name="action" value="fnac_admin_withdraw">
                                        <input type="submit" value="Submit" class="button button-secondary" id="submit" name="submit">
                                     </form>
                                    </td>
                                    <td><?php esc_attr_e(date("F j, Y, g:i a", strtotime($user->fldUpdated)), 'wp_admin_style'); ?></td>
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
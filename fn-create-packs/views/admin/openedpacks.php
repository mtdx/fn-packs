<div class="wrap">
    <div id="icon-options-general" class="icon32"></div>
    <h2><?php esc_attr_e('Opened Packs', 'wp_admin_style'); ?></h2>

    <div id="poststuff" class="fncp">
        <div id="post-body" class="metabox-holder columns-1">
            <!-- main content -->
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox">
                        <div class="inside admin-statistics">
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
                                    <th>Pack</th>
                                    <th>Value</th>
                                    <th>Price</th>
                                    <th>User</th>
                                    <th>Cards</th>
                                    <th>Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = 0;
                                foreach ($openpacks as $openpack):
                                    if ($i % 2 == 0) echo '<tr>';
                                    else echo '<tr  class="alternate">';
                                    if ($openpack->fldUser) {
                                        $user = get_userdata($openpack->fldUser);
                                        $username = $user->user_login;
                                    } else {
                                        $username = 'Public';
                                    }
                                    $cards = array();
                                    $openpack->fldCards = unserialize(base64_decode($openpack->fldCards));
                                    foreach ($openpack->fldCards as $card) {
                                        $cards[$card[0]] += 1;
                                    }
                                    ?>
                                    <td><?php esc_attr_e($openpack->fldId, 'wp_admin_style') ?></td>
                                    <td  class="row-title"><?php esc_attr_e(FNCP_Card::format_type($openpack->fldType), 'wp_admin_style'); ?></td>
                                    <td><code><?php esc_attr_e(FNCP_Stats::number($openpack->fldValue), 'wp_admin_style'); ?></code></td>
                                    <td><?php esc_attr_e(FNCP_Stats::number($openpack->fldstdValue), 'wp_admin_style'); ?></td>
                                    <td>
                                        <a href="/wp-admin/user-edit.php?user_id=<?php echo intval($openpack->fldUser); ?>">
                                            <?php esc_attr_e($username, 'wp_admin_style'); ?>
                                        </a>
                                    </td>
                                    <td><?php foreach ($cards as $type => $count) esc_attr_e(FNCP_Card::format_type($type) . " (" . $count . ") ", 'wp_admin_style'); ?></td>
                                    <td><?php esc_attr_e(date("F j, Y, g:i a", strtotime($openpack->fldUpdated)), 'wp_admin_style'); ?></td>
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
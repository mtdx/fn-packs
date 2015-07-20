<h3><?php _e('Ninja Luck Packs', 'wp_admin_style'); ?></h3>

<div class="wrap">
    <div id="icon-options-general" class="icon32"></div>
    <h2><?php esc_attr_e('Statistics', 'wp_admin_style'); ?></h2>

    <div id="poststuff" class="fncp">
        <div id="post-body" class="metabox-holder columns-2">
            <!-- main content -->
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox statistics">
                        <div class="inside pack">
                            <table class="fncp-overall">
                                <tr>
                                    <?php if ($statistics['diff'] > 0): ?>
                                        <td class="blue1">
                                             +<?php echo $statistics['diff']; ?>
                                        </td>
                                    <?php else: ?>
                                        <td class="blue1 down">
                                            <?php echo $statistics['diff']; ?>
                                        </td>
                                    <?php endif; ?>

                                    <?php if ($statistics['won'] > $statistics['lost']): ?>
                                        <td class="blue1"><?php echo $statistics['won']; ?>% Win</td>
                                    <?php else: ?>
                                        <td class="blue1 down"><?php echo $statistics['lost']; ?>% Lose</td>
                                    <?php endif; ?>
                                </tr>
                                <tr>
                                    <td class="blue1"><?php echo $statistics['count']; ?> Packs</td>
                                    <td class="blue1"><?php echo $statistics['avgvalue']; ?> AVG Value</td>
                                </tr>
                            </table>
                        </div>
                        <?php foreach ($PACKS as $pack_slug => $pack_name): ?>
                            <div class="inside pack">
                                <h3 class="collapse closed">
                                    <span>
                                        <?php
                                        $key = str_replace('fncp_', '', $pack_slug);
                                        $pack_name = preg_replace('/\(.*\)/', "(" . $pack_statistics[$key]['total'] . ' Packs Opened)', $pack_name);
                                        esc_attr_e($pack_name, 'wp_admin_style');
                                        ?>
                                    </span>
                                </h3>

                                <div class="collapse closed"></div>

                                <table class="hide fncp-overall">
                                    <tr>
                                        <?php if ($pack_statistics[$key]['diff'] > 0): ?>
                                            <td class="blue2">
                                                +<?php echo $pack_statistics[$key]['diff']; ?>
                                            </td>
                                        <?php else: ?>
                                            <td class="blue2 down">
                                                 <?php echo $pack_statistics[$key]['diff']; ?>
                                            </td>
                                        <?php endif; ?>

                                        <?php if ($pack_statistics[$key]['won'] > $pack_statistics[$key]['lost']): ?>
                                            <td class="blue2"><?php echo $pack_statistics[$key]['won']; ?>% Win</td>
                                        <?php else: ?>
                                            <td class="blue2 down"><?php echo $pack_statistics[$key]['lost']; ?>% Lose
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                    <tr>
                                        <td class="blue2"><?php echo $pack_stats[$key]['count']; ?> Recent Packs</td>
                                        <td class="blue2"><?php echo $pack_stats[$key]['avgvalue']; ?> AVG Value</td>
                                    </tr>
                                    <tr>
                                        <td class="blue2"><?php echo $pack_stats[$key]['min']; ?> Min</td>
                                        <td class="blue2"><?php echo $pack_stats[$key]['max']; ?> Max</td>
                                    </tr>
                                </table>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <!-- post-body-content -->
            <!-- sidebar -->
            <div id="postbox-container-1" class="postbox-container">
                <div class="meta-box-sortables">
                    <div class="postbox">
                        <div class="inside">
                            <form method="post" class="ninjapoint" action="options.php">
                                <?php
                                $point_value = get_option('fnac_point_value');
                                settings_fields('fnac_point_value');
                                do_settings_sections('fnac_point_value');
                                ?>
                                <h3 style="padding-left: 0"><label>Ninja Point Value (Coins):</label></h3>
                                <input type="text" value="<?php echo $point_value; ?>" name="fnac_point_value" class=""><br/>
                                <?php submit_button(); ?>
                            </form>
                        </div>
                        <h3>
                            <?php foreach ($count_types as $type): ?>
                                <span><?php echo FNCP_Card::format_type($type->fldType) . ": " . $type->count; ?></span>
                                <br/>
                            <?php endforeach; ?>
                        </h3>

                        <div class="inside">
                            <p></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
    <!-- #poststuff -->
</div> <!-- .wrap -->
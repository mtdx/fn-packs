<h3><?php _e('Ninja Luck Packs', 'wp_admin_style'); ?></h3>

<div class="wrap">
    <div id="icon-options-general" class="icon32"></div>
    <h2><?php esc_attr_e('Settings', 'wp_admin_style'); ?></h2>

    <div id="poststuff" class="fncp">
        <div id="post-body" class="metabox-holder columns-2">
            <!-- main content -->
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox">
                        <div class="inside">

                        </div>
                        <?php foreach ($PACKS as $pack_slug => $pack_name): ?>
                            <div class="inside admin-card-types pack">
                                <h3 class="collapse closed">
                                    <span>
                                        <?php
                                        $pack = FNCP_Packs_Factory::build(array($pack_slug));
                                        $pack_name = str_replace(array('%no', '%sp'), array($pack->cards_nr['normal'], $pack->cards_nr['special']), $pack_name);
                                        esc_attr_e($pack_name, 'wp_admin_style');
                                        ?>
                                    </span>
                                </h3>

                                <div class="collapse closed"></div>
                                <form method="post" class="hide" action="options.php">
                                    <?php
                                    $settings = get_option($pack_slug . '_probs');
                                    settings_fields($pack_slug . '_probs');
                                    do_settings_sections($pack_slug . '_probs');
                                    ?>
                                    <table class="widefat probs">
                                        <?php
                                        $cardtypes['normal'] = array_values($cardtypes['normal']);
                                        $rows = count($cardtypes['normal']) / 3;
                                        $range1 = 0;
                                        $probtotal = 0;
                                        for ($i = 0; $i <= $rows; $i++):
                                            if ($i % 2 == 0) echo '<tr>';
                                            else echo '<tr  class="alternate">';
                                            for ($i2 = 0; $i2 <= 2; $i2++):
                                                $type_val = $settings['normal'][$cardtypes['normal'][$range1]->fldType];
                                                $probtotal += $type_val;
                                                $type_name = $cardtypes['normal'][$range1]->fldType;
                                                if (empty($type_name)) {
                                                    $range1++;
                                                    continue;
                                                }
                                                ?>
                                                <td class="row-title">
                                                    <label><?php esc_attr_e(ucwords(str_replace('_', ' ', $type_name)), 'wp_admin_style'); ?></label>
                                                    <input type="text"
                                                           class="small-text <?php echo ($type_val) ? 'on' : 'off'; ?>"
                                                           name="<?php echo $pack_slug ?>_probs[normal][<?php esc_attr_e($type_name); ?>]"
                                                           value="<?php esc_attr_e(($type_val) ? (float)$type_val : 0); ?>"
                                                           placeholder="%"/>
                                                </td>
                                                <?php
                                                $range1++;
                                            endfor;
                                            echo '</tr>';
                                        endfor
                                        ?>
                                    </table>
                                    <?php if ($probtotal != 100): ?>
                                        <h3 style="color: red">Probabilities must add to 100%.
                                            Current <?php echo $probtotal; ?>%</h3>
                                    <?php endif ?>
                                    <br/>
                                    <table class="widefat probs">
                                        <?php
                                        $cardtypes['special'] = array_values($cardtypes['special']);
                                        $rows = count($cardtypes['special']) / 3;
                                        $range1 = 0;
                                        $probtotal = 0;
                                        for ($i = 0; $i <= $rows; $i++):
                                            if ($i % 2 == 0) echo '<tr>';
                                            else echo '<tr  class="alternate">';
                                            for ($i2 = 0; $i2 <= 2; $i2++):
                                                $type_val = $settings['special'][$cardtypes['special'][$range1]->fldType];
                                                $type_name = $cardtypes['special'][$range1]->fldType;
                                                $probtotal += $type_val;
                                                if (empty($type_name)) {
                                                    $range1++;
                                                    continue;
                                                }
                                                ?>
                                                <td class="row-title">
                                                    <label><?php esc_attr_e(FNCP_Card::format_type($type_name), 'wp_admin_style'); ?></label>
                                                    <input type="text"
                                                           class="small-text <?php echo ($type_val) ? 'on' : 'off'; ?>"
                                                           name="<?php echo $pack_slug ?>_probs[special][<?php esc_attr_e($type_name); ?>]"
                                                           value="<?php esc_attr_e(($type_val) ? floatval($type_val) : 0); ?>"
                                                           placeholder="%"/>
                                                </td>
                                                <?php
                                                $range1++;
                                            endfor;
                                            echo '</tr>';
                                        endfor
                                        ?>
                                    </table>
                                    <?php if ($probtotal != 100): ?>
                                        <h3 style="color: red">Probabilities must add to 100%.
                                            Current <?php echo $probtotal; ?></h3>
                                    <?php endif ?>
                                    <br/>
                                    <table class="widefat settings">
                                        <tr>
                                            <td colspan="3" class="row-title">
                                                <label><?php esc_attr_e('Pack Price (Ninja Points)', 'wp_admin_style'); ?></label>
                                                <input type="text" class="small-text"
                                                       name="<?php echo $pack_slug ?>_probs[price]"
                                                       value="<?php esc_attr_e(($settings['price']) ? intval($settings['price']) : 0); ?>"/>
                                            </td>
                                        </tr>
                                        <tr class="alternate">
                                            <td colspan="3" class="row-title">
                                                <label><?php esc_attr_e('Basic Cards Number in Pack', 'wp_admin_style'); ?></label>
                                                <input type="text" class="small-text"
                                                       name="<?php echo $pack_slug ?>_probs[cards_nr][normal]"
                                                       value="<?php esc_attr_e(($settings['cards_nr']['normal']) ? intval($settings['cards_nr']['normal']) : 0); ?>"/>
                                            </td>
                                            <td class="row-title">
                                                <label><?php esc_attr_e('Special Cards Number in Pack', 'wp_admin_style'); ?></label>
                                                <input type="text" class="small-text"
                                                       name="<?php echo $pack_slug ?>_probs[cards_nr][special]"
                                                       value="<?php esc_attr_e(($settings['cards_nr']['special']) ? intval($settings['cards_nr']['special']) : 0); ?>"/>
                                            </td>
                                        </tr>
                                    </table>

                                    <?php submit_button(); ?>
                                    <span class="done">Done!</span>
                                </form>
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
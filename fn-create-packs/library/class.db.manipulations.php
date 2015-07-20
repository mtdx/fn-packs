<?php

class FNCP_DB_Manipulations
{
    const PLAYERS = 'pcklcktblPlayers';

    public function add_color_fldType()
    {
        global $wpdb;

        $rows = $wpdb->get_results("SELECT fldId, fldPlayerData FROM " . self::PLAYERS);
        if (empty($rows)) return;
        foreach ($rows as $row) {
            $ply_data = unserialize(base64_decode($row->fldPlayerData));
            $color = $ply_data['color'];
            if (empty($ply_data['color'])) {
                switch (true) {
                    case ($ply_data['rating'] <= 65):
                        $color = 'tots_bronze';
                        break;
                    case ($ply_data['rating'] > 65 && $ply_data['rating'] <= 75):
                        $color = 'tots_silver';
                        break;
                    case ($ply_data['rating'] > 75):
                        $color = 'tots_gold';
                        break;
                }
            }
            $wpdb->update(self::PLAYERS,
                array('fldType' => $color),
                array('fldId' => $row->fldId),
                array('%s'),
                array('%d')
            );
        }
    }
}
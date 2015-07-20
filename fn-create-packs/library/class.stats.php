<?php

class FNCP_Stats
{
    const STATISTICS = 'pcklckPackStatistics';
    const LOG = 'pack.log';
    const TYPE = 'fldType';
    const VALUE = 'fldValue';
    const STDVALUE = 'fldstdValue';
    const USER = 'fldUser';
    const CARDS = 'fldCards';
    const UPDATED = 'fldUpdated';
    const LIMIT = 22;

    /**
     * @param int $offset
     * @param int $limit
     * @return mixed
     */
    static function get_openpacks($offset = 0, $limit = self::LIMIT)
    {
        global $wpdb;
        $sql = $wpdb->prepare("SELECT * FROM " . self::STATISTICS . " ORDER BY " . self::UPDATED . " DESC LIMIT %d OFFSET %d", $limit, $offset);
        return $wpdb->get_results($sql);
    }

    /**
     * @return mixed
     */
    static function count_openpacks()
    {
        global $wpdb;
        return $wpdb->get_var("SELECT COUNT(*) FROM " . self::STATISTICS);
    }

    public static function save(FNCP_Pack $pack, $userId)
    {
        if (empty($pack)) return null;
        global $wpdb;

        $cards = array();
        foreach ($pack->cards as $card) {
            $cards[] = array($card->type, $card->ID);
        }
        $Id = $wpdb->insert(
            self::STATISTICS,
            array(
                self::TYPE => $pack->name,
                self::VALUE => $pack->value,
                self::STDVALUE => $pack->stdValue,
                self::USER => $userId,     //user id
                self::CARDS => base64_encode(serialize($cards)),
            ),
            array(
                '%s',
                '%d',
                '%d',
                '%d',
                '%s',
            )
        );
        return $Id;
    }

    public static function log($message)
    {
        $message = date("F j, Y, g:i a ") . $message;
        error_log($message . "\n", 3, FNCP_DIR_PATH . self::LOG);
    }

    public static function pagination($pagenum, $total, $limit = self::LIMIT)
    {
        $pagenum = isset($pagenum) ? absint(intval($pagenum)) : 1;
        $offset = ($pagenum - 1) * $limit;
        $num_of_pages = ceil($total / $limit);

        $page_links = paginate_links(array(
            'base' => add_query_arg('pagenum', '%#%'),
            'format' => '',
            'prev_text' => __('&laquo;', 'text-domain'),
            'next_text' => __('&raquo;', 'text-domain'),
            'total' => $num_of_pages,
            'current' => $pagenum
        ));

        return array('offset' => $offset, 'page_links' => $page_links);
    }

    /**
     * @return array
     */
    public static function get_main_statistics()
    {
        global $wpdb;
        $result = array();
        //ninja point to coins rate;
        $rate = FNAC_Exchange::rate();
        $result['diff'] = self::number($wpdb->get_var("SELECT SUM(" . self::STDVALUE . "*" . intval($rate) . ") - SUM(" . self::VALUE . ") FROM " . self::STATISTICS));
        $total = $wpdb->get_var("SELECT COUNT(*) FROM " . self::STATISTICS);
        $won = $wpdb->get_var("SELECT COUNT(*) FROM " . self::STATISTICS . " WHERE (" . self::STDVALUE . "*" . intval($rate) . ") > " . self::VALUE);
        $lost = $wpdb->get_var("SELECT COUNT(*) FROM " . self::STATISTICS . " WHERE (" . self::STDVALUE . "*" . intval($rate) . ") < " . self::VALUE);
        $result['avgvalue'] = self::number($wpdb->get_var("SELECT AVG(" . self::VALUE . ") FROM " . self::STATISTICS));
        $result['count'] = self::number($wpdb->get_var("SELECT COUNT(*) FROM " . self::STATISTICS));

        $result['total'] = $total;
        $result['won'] = round(($won / $total) * 100, 2);
        $result['lost'] = round(($lost / $total) * 100, 2);

        return $result;
    }

    public static function number($n, $precision = 3)
    {
        if ($n < 1000000) {
            // Anything less than a million
            $n_format = number_format($n);
        } else if ($n < 1000000000) {
            // Anything less than a billion
            $n_format = number_format($n / 1000000, $precision) . 'M';
        } else if ($n < 1000000000000) {
            // At least a billion
            $n_format = number_format($n / 1000000000, $precision) . 'B';
        } else {
            // At least a trillion
            $n_format = number_format($n / 1000000000000, $precision) . 'T';
        }

        return $n_format;
    }

    /**
     * @return array
     */
    public static function get_pack_main_statistics()
    {
        $result = array();
        global $wpdb;
        $rate = FNAC_Exchange::rate();
        foreach (FNCP_Pack::$PACKS as $pack => $name) {
            $pack = str_replace('fncp_', '', $pack);
            $sql = $wpdb->prepare("SELECT SUM(" . self::STDVALUE . "*" . intval($rate) . ") - SUM(" . self::VALUE . ") FROM " . self::STATISTICS . " WHERE " . self::TYPE . "=%s", $pack);
            $result[$pack]['diff'] = self::number($wpdb->get_var($sql));
            $sql = $wpdb->prepare("SELECT COUNT(*) FROM " . self::STATISTICS . " WHERE " . self::TYPE . "=%s", $pack);
            $total = $wpdb->get_var($sql);
            $sql = $wpdb->prepare("SELECT COUNT(*) FROM " . self::STATISTICS . " WHERE (" . self::STDVALUE . "*" . intval($rate) . ") > " . self::VALUE . " AND " . self::TYPE . "=%s", $pack);
            $won = $wpdb->get_var($sql);
            $sql = $wpdb->prepare("SELECT COUNT(*) FROM " . self::STATISTICS . " WHERE (" . self::STDVALUE . "*" . intval($rate) . ") < " . self::VALUE . " AND " . self::TYPE . "=%s", $pack);
            $lost = $wpdb->get_var($sql);

            $result[$pack]['total'] = $total;
            $result[$pack]['won'] = ($total) ? round(($won / $total) * 100, 2) : 0;
            $result[$pack]['lost'] = ($total) ? round(($lost / $total) * 100, 2) : 0;
        }
        return $result;
    }

    /**
     * @return array
     */
    public static function get_pack_stats()
    {
        global $wpdb;
        $stats = array();
        foreach (FNCP_Pack::$PACKS as $pack => $name) {
            $pack = str_replace('fncp_', '', $pack);
            $sql = $wpdb->prepare("SELECT COUNT(*) FROM " . self::STATISTICS . " WHERE " . self::TYPE . " = %s AND " . self::UPDATED . " >= DATE(NOW()) - INTERVAL 7 DAY ", $pack);
            $stats[$pack]['count'] = self::number($wpdb->get_var($sql));
            $sql = $wpdb->prepare("SELECT AVG(" . self::VALUE . ") FROM " . self::STATISTICS . " WHERE " . self::TYPE . " = %s", $pack);
            $stats[$pack]['avgvalue'] = self::number($wpdb->get_var($sql));
            $sql = $wpdb->prepare("SELECT MAX(" . self::VALUE . ") FROM " . self::STATISTICS . " WHERE " . self::TYPE . " = %s", $pack);
            $stats[$pack]['max'] = self::number($wpdb->get_var($sql));
            $sql = $wpdb->prepare("SELECT MIN(" . self::VALUE . ") FROM " . self::STATISTICS . " WHERE " . self::TYPE . " = %s", $pack);
            $stats[$pack]['min'] = self::number($wpdb->get_var($sql));
        }
        return $stats;
    }
}
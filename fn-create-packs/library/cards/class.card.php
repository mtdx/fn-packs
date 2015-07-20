<?php

class FNCP_Card
{
    const PLAYERS = 'pcklcktblPlayers';
    const TYPE = 'fldType';
    const DBID = 'fldId';
    const XBMIN = 'fldXBMin';
    const XBQS = 'fldXBQuickSell'; //quick sell
    static $special_cards = array(
        'tots_gold',
        'tots_silver',
        'tots_bronze',
        'purple',
        'motm',
        'toty',
        'totw_gold',
        'totw_silver',
        'totw_bronze',
        'easports',
        'legend',
        'green', // maybe not?
        'basic' // not a real type, used when the low chance special doesn't pull through and we need a normal type
    );
    public $type;
    public $ID;
    public $player_data;
    public $XBMin;
    public $XBQSell;
    public $PSQSell;

    public function __construct($type, $ID, $plt = 'XB')
    {
        if (empty($type) || empty($ID)) return null;
        global $wpdb;
        $db_card = $wpdb->get_row("SELECT * FROM " . self::PLAYERS . " WHERE " . self::DBID . "=" . intval($ID) . " LIMIT 0,1");
        if ($db_card->fldType == $type) {
            $this->type = $type;
            $this->ID = $ID;
            $this->XBMin = $db_card->fldXBMin;
            $this->XBQSell = $db_card->fldXBQuickSell;
            $this->PSQSell = $db_card->fldPSQuickSell;
            //we will eventually have this change to PS based on an argument
            $this->value = ($db_card->fldXBQuickSell > $db_card->fldXBMin) ? $db_card->fldXBQuickSell : $db_card->fldXBMin;
            $this->player_data = unserialize(base64_decode($db_card->fldPlayerData));
        }
    }

    /**
     * @param array $main_types
     * @return array
     */
    static function count_types($main_types = array())
    {
        if (empty($main_types)) return array();

        $types_count = array();
        global $wpdb;
        foreach ($main_types as $types) {
            foreach ($types as $type) {
                $sql = $wpdb->prepare("SELECT COUNT(*) as count," . self::TYPE . " FROM " . self::PLAYERS . " WHERE " . self::TYPE . "=%s LIMIT 0,1", $type->fldType);
                $row = $wpdb->get_row($sql);
                if (!empty($row->count)) $types_count[$type->fldType] = $wpdb->get_row($sql);
            }
        }
        usort($types_count, function ($a, $b) {
            if ((int)$a->count == (int)$b->count) return 0;
            return ((int)$a->count > (int)$b->count) ? -1 : 1;
        });
        return $types_count;
    }

    /**
     * @return array
     */
    static function get_separated_types()
    {
        $types = self::get_types();
        $special_cards = self::$special_cards;
        $normal_cards = array_filter($types, function ($var) use ($special_cards) {
            return !in_array($var->fldType, $special_cards);
        });
        $special_cards = array_filter($types, function ($var) use ($special_cards) {
            return in_array($var->fldType, $special_cards);
        });
        return array('normal' => $normal_cards, 'special' => $special_cards);
    }

    /**
     * @return mixed
     */
    static function get_types()
    {
        global $wpdb;
        $types = $wpdb->get_results("SELECT DISTINCT " . self::TYPE . " FROM " . self::PLAYERS . " ORDER BY " . self::XBMIN . " DESC");
        // we put a basic that some packs may have a only chance to get a special card. We need a row obj, we will replace its type
        $rowobj = $wpdb->get_row("SELECT " . self::TYPE . " FROM " . self::PLAYERS . " LIMIT 1");
        $rowobj->fldType = 'basic';
        array_push($types, $rowobj);
        return $types;
    }

    /**
     * @param $type_name
     * @return string
     */
    static function format_type($type_name)
    {
        if (!empty($type)) return '';
        return ucwords(str_replace('_', ' ', $type_name));
    }

    /**
     * @param $type
     * @param int $limit
     * @return null
     */
    public static function get_random($type, $limit = 1)
    {
        if (empty($type)) return null;
        global $wpdb;
        //we get the offset
        $sql = $wpdb->prepare("SELECT COUNT(*) FROM " . self::PLAYERS . " WHERE " . self::TYPE . "=%s", $type);
        $count = $wpdb->get_var($sql);
        $offset = mt_rand(0, $count - 1);

        $sql = $wpdb->prepare("SELECT fldId FROM " . self::PLAYERS . " WHERE " . self::TYPE . "=%s LIMIT %d OFFSET %d", $type, $limit, $offset);
        $cardId = $wpdb->get_var($sql);

        if (empty($cardId)) return null;
        return $cardId;
    }

    /**
     * @param array $cards
     * @return array
     */
    public static function get_cards_value(array $cards)
    {
        if (empty($cards)) return array();
        $IDs = array();
        global $wpdb;
        foreach ($cards as $card) {
            if (!empty($card['ID'])) $IDs[] = $card['ID'];
        }
        $IDs = implode(',', $IDs);
        $value = $wpdb->get_var("SELECT SUM(IF(" . self::XBQS . " > " . self::XBMIN . ", " . self::XBQS . ", " . self::XBMIN . "))
                                 FROM " . self::PLAYERS . " WHERE " . self::DBID . " IN (" . $IDs . ")");
        return $value;
    }

    /**
     * @param $type
     * @return string
     * we get a lower value type, static can't be down dynamically
     */
    public static function downgrade_type($type)
    {
        if (empty($type)) return $type;
        switch ($type) {
            case 'legend':
                $new_type = 'legend';
                break;
            case 'toty':
                $new_type = 'toty';
                break;
            case 'totw_gold':
                $new_type = 'totw_silver';
                break;
            case 'totw_silver':
                $new_type = 'totw_bronze';
                break;
            case 'totw_bronze':
                $new_type = 'totw_bronze';
                break;
            case 'motm':
                $new_type = 'motm';
                break;
            case 'purple':
                $new_type = 'purple';
                break;
            case 'green':
                $new_type = 'green';
                break;
            case 'easports':
                $new_type = 'easports';
                break;
            case 'tots_gold':
                $new_type = 'tots_silver';
                break;
            case 'tots_silver':
                $new_type = 'tots_bronze';
                break;
            case 'tots_bronze':
                $new_type = 'tots_bronze';
                break;
            case 'rare_gold':  // normal next
                $new_type = 'gold';
                break;
            case 'gold':
                $new_type = 'rare_silver';
                break;
            case 'rare_silver':
                $new_type = 'silver';
                break;
            case 'silver':
                $new_type = 'rare_bronze';
                break;
            case 'rare_bronze':
                $new_type = 'bronze';
                break;
            case 'bronze':
                $new_type = 'bronze';
                break;
            default:
                $new_type = 'bronze';
        }
        return $new_type;
    }
}
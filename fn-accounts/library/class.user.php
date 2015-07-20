<?php

class FNAC_User
{
    const USRCOINS = 'pcklckUsersCoins';
    const TRANSACTIONS = 'pcklckUserTransactions';
    const TTYPE = 'fldType';
    const TADMIN = 'fldAdmin';
    const TVALUE = 'fldValue';
    const POINTS = 'fldPoints';
    const ID = 'fldUserId';
    const COINS = 'fldCoins';
    const PACKS = 'fldPacks';
    const WITHDREW = 'fldWithdrew';
    const LIMIT = 22;
    const UPDATED = 'fldUpdated';
    public $Id;
    public $pack;

    function __construct($Id)
    {
        $this->Id = $Id;
    }

    /**
     * @return mixed
     */
    static function count()
    {
        global $wpdb;
        return $wpdb->get_var("SELECT COUNT(*) FROM " . self::USRCOINS);
    }

    /**
     * @return mixed
     */
    static function countrans()
    {
        global $wpdb;
        return $wpdb->get_var("SELECT COUNT(*) FROM " . self::TRANSACTIONS);
    }

    /**
     * @param int $offset
     * @param int $limit
     * @return mixed
     */
    static function get_all($offset = 0, $limit = self::LIMIT)
    {
        global $wpdb;
        $sql = $wpdb->prepare("SELECT * FROM " . self::USRCOINS . " ORDER BY " . self::UPDATED . " DESC LIMIT %d OFFSET %d", $limit, $offset);
        return $wpdb->get_results($sql);
    }

    /**
     * @param int $offset
     * @param int $limit
     * @return mixed
     */
    static function get_trans($offset = 0, $limit = self::LIMIT)
    {
        global $wpdb;
        $sql = $wpdb->prepare("SELECT * FROM " . self::TRANSACTIONS . " ORDER BY " . self::UPDATED . " DESC LIMIT %d OFFSET %d", $limit, $offset);
        return $wpdb->get_results($sql);
    }

    /**
     * @param array $items
     * @return bool
     */
    public function process_order(array $items)
    {
        $points = 0;
        foreach ($items as $item) {
            //we use the product ID, we won't have many anyway
            if (in_array($item['product_id'], array(58))) {
                if ($item['qty']) $points += $item['qty'];
            }
        }
        try {
            $this->add_points($points);
            $this->log_transaction('purchase', $points);
        } catch (Exception $e) {
            FNAC_Manager::log($e->getMessage());
        }
        return ($points) ? $points : false;
    }

    /**
     * @param $points
     * @throws ErrorException
     */
    public function add_points($points)
    {
        if (empty($points)) throw new ErrorException('Error: Empty Coins. User ID: ' . self::ID);
        global $wpdb;

        $sql = $wpdb->prepare("SELECT " . self::POINTS . " FROM " . self::USRCOINS . " WHERE " . self::ID . " = %d", $this->Id);
        $exs_points = $wpdb->get_var($sql);
        if (isset($exs_points)) {
            $update = $wpdb->update(
                self::USRCOINS,
                array(self::POINTS => $exs_points + $points),
                array(self::ID => $this->Id),
                array('%d'),
                array('%d')
            );
            if ($update === false) {
                throw new ErrorException('Error: Update Failed. User ID: ' . self::ID . " Points: " . $points);
            }
        } else {
            $insert = $wpdb->insert(
                self::USRCOINS,
                array(self::ID => $this->Id, self::POINTS => $points),
                array('%d', '%d')
            );
            if ($insert === false) {
                throw new ErrorException('Error: Insert Failed. User ID: ' . self::ID . " Points: " . $points);
            }
        }
    }

    /**
     * @param $type
     * @param $value
     * @throws ErrorException
     */
    private function log_transaction($type, $value)
    {
        global $wpdb;
        $adminId = ($type == 'withdraw') ? get_current_user_id() : 0;

        $insert = $wpdb->insert(
            self::TRANSACTIONS,
            array(self::ID => $this->Id, self::TTYPE => $type, self::TADMIN => $adminId, self::TVALUE => abs($value)),
            array('%d', '%s', '%d', '%d')
        );
        if ($insert === false) {
            throw new ErrorException('Error: Transaction Insert Failed. User ID: ' . self::ID . " Type: " . $type);
        }
    }

    /**
     * @return mixed
     * @param
     * pulls the type from a get var and
     * we check if the user has the coins get the pack
     */
    public function get_pack($type)
    {
        $types = array_keys(FNCP_Pack::$PACKS);
        switch ($type) {
            case '25k':
                $type = $types[0];
                break;
            case '50k':
                $type = $types[1];
                break;
            case '100k':
                $type = $types[2];
                break;
            case 'totw':
                $type = $types[3];
                break;
            case 'tots':
                $type = $types[4];
                break;
            case 'toty':
                $type = $types[5];
                break;
            case 'hero':
                $type = $types[6];
                break;
            case 'motm':
                $type = $types[7];
                break;
            case 'ninja':
                $type = $types[8];
                break;
            case 'legend':
                $type = $types[9];
                break;
            default:
                break;
        }

        //pulls the type from a get var for now is random
        $rand = mt_rand(0, count($types) - 1);
        $type = $types[$rand];
//        $type = 'fncp_toty_pack'; //for testing

        return $this->pack = FNCP_Packs_Factory::build(array($type));
    }

    /**
     * @param FNCP_Pack $pack
     * @return bool
     */
    public function can_open(FNCP_Pack $pack)
    {
        global $wpdb;

        $sql = $wpdb->prepare("SELECT " . self::COINS . ", " . self::POINTS . " FROM " . self::USRCOINS . " WHERE " . self::ID . " = %d", $this->Id);
        $row = $wpdb->get_row($sql);
        //we let them open packs with coins too
        $points2 = FNAC_Exchange::coins($row->fldCoins);

        return ($row->fldPoints >= $pack->stdValue || $points2 >= $pack->stdValue) ? true : false;
    }

    /**
     * @param FNCP_Pack $pack
     * @return bool
     * @throws ErrorException
     */
    public function open_pack(FNCP_Pack $pack)
    {
        global $wpdb;

        $sql = $wpdb->prepare("SELECT " . self::COINS . ", " . self::POINTS . ", " . self::PACKS . " FROM " . self::USRCOINS . " WHERE " . self::ID . " = %d", $this->Id);
        $row = $wpdb->get_row($sql);
        //we award the win
        $row->fldCoins = $row->fldCoins + $pack->value;

        if ($row->fldPoints >= $pack->stdValue) {
            //we try take points first
            $row->fldPoints = $row->fldPoints - $pack->stdValue;
        } else {
            //if not enough points, we take coins
            $row->fldCoins = $row->fldCoins - FNAC_Exchange::points($pack->stdValue);
        }

        $this->update($row->fldCoins, null, $row->fldPoints, ++$row->fldPacks);
        return FNCP_Stats::save($pack, $this->Id);
    }

    /**
     * @param $coins
     * @param null $withdrew
     * @param null $packs
     * @param null $points
     * @return mixed
     * @throws ErrorException
     */
    private function update($coins, $withdrew = null, $points = null, $packs = null)
    {
        global $wpdb;
        $data = array(self::COINS => $coins);
        if (isset($withdrew)) $data[self::WITHDREW] = abs($withdrew);
        if (isset($packs)) $data[self::PACKS] = $packs;
        if (isset($points)) $data[self::POINTS] = $points;

        $update = $wpdb->update(
            self::USRCOINS,
            $data,
            array(self::ID => $this->Id),
            array('%d', '%d', '%d', '%d'),
            array('%d')
        );
        if ($update === false) {
            throw new ErrorException('Error: User Coins Update Failed. User ID: ' . self::ID . " Coins: " . $coins);
        }
        return $update;
    }

    /**
     * the amount of coins
     * @param $value
     * @return bool
     */
    public function withdraw($value)
    {
        global $wpdb;
        if (!$rate = FNAC_Exchange::rate()) return false;
        if (($value % $rate) != 0 || $value < $rate) return false;

        $sql = $wpdb->prepare("SELECT " . self::COINS . "," . self::POINTS . "," . self::WITHDREW . " FROM " . self::USRCOINS . " WHERE " . self::ID . " = %d", $this->Id);
        $row = $wpdb->get_row($sql);

        if (($total = $row->fldCoins - $value) < 0) return false;
        $withdrew = $row->fldWithdrew + $value;
        $points = $row->fldPoints + FNAC_Exchange::coins($value);

        $this->update($total, null, $points);
        $this->update_user($withdrew);
        $this->log_transaction('withdraw', $value);

        return $total;
    }


    public function update_user($withdrew)
    {
        global $wpdb;
        $data = array(self::WITHDREW => $withdrew);
        $update = $wpdb->update(
            self::USRCOINS,
            $data,
            array(self::ID => $this->Id),
            array('%d'),
            array('%d')
        );
        if ($update === false) {
            throw new ErrorException('Error: Withdrew Coins Update Failed. User ID: ' . self::ID . " Coins: " . $withdrew);
        }
        return $update;
    }


    function get_user_stock()
    {
        global $wpdb;
        $sql = $wpdb->prepare("SELECT " . self::POINTS . ", " . self::COINS . " FROM " . self::USRCOINS . " WHERE " . self::ID . " = %d", $this->Id);
        return $wpdb->get_row($sql);
    }
}



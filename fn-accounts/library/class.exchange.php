<?php

class FNAC_Exchange
{
    /**
     * @param $points
     * @return mixed
     */
    static function points($points)
    {
        $rate = get_option('fnac_point_value');
        return $points * $rate;
    }

    /**
     * @param $coins
     * @return float
     */
    static function coins($coins)
    {
        $rate = get_option('fnac_point_value');
        return round($coins / $rate);
    }

    static function rate()
    {
        return get_option('fnac_point_value');
    }
}
<?php

class FNCP_Cards_Factory
{
    /**
     * @param array $cards
     * @return array
     * @throws Exception
     */
    public static function build(array $cards)
    {
        if (!is_array($cards)) return array();
        $card_objs = array();
        foreach ($cards as $card) {
            $card_class = 'FNCP_Card';
            if (class_exists($card_class)) {
                $card_objs[] = new $card_class($card['type'], $card['ID']);
            } else {
                throw new Exception('Error 107!');
            }
        }
        if (count($card_objs) == 1) return $card_objs[0];
        return $card_objs;
    }
}
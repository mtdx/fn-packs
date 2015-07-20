<?php

class FNCP_Cards_Generator
{
    private $pack;

    public function __construct(array $pack_type)
    {
        $this->pack = FNCP_Packs_Factory::build($pack_type);
    }

    //main method, the cards_objs are what is sent to the outside
    /**
     * @return array
     * @throws Exception
     */
    public function get_cards()
    {
        if (empty($this->pack->settings)) return array();
        $cards_objs = array();

        $normalcards = $this->get_rand_cards($this->pack->settings['normal'], 'normal');
        $specialcards = $this->get_rand_cards($this->pack->settings['special'], 'special');
        $cards = array_merge($normalcards, $specialcards);

        $packvalue = FNCP_Card::get_cards_value($cards);
        $cards = $this->calibrate_value($cards, $packvalue);

        if (!empty($cards)) {
            //now we build the card objects
            foreach ($cards as $card) {
                $cards_objs[] = FNCP_Cards_Factory::build(array($card));
            }
        }
        return $cards_objs;
    }

    // based on the pack win/lose percentages we change the normal cards total value
    /**
     * @param array $cards_settings
     * @param $mode
     * @return array
     */
    private function get_rand_cards(array $cards_settings, $mode)
    {
        $cards = array();
        //loop through settings since we need the key too
        foreach ($cards_settings as $key => $value) {
            $cards[] = array('prob' => $value, 'type' => $key);
        }
        $cards = $this->sort_cards($cards);
        switch ($mode) {
            case 'normal':
                $cards_arr = $this->generate_rand_cards($this->pack->cards_nr['normal'], $cards);
                break;
            case 'special':
                $cards_arr = $this->generate_rand_cards($this->pack->cards_nr['special'], $cards);
                break;
            default:
                $cards_arr = array();
        }
        return $cards_arr;
    }

    /**
     * @param array $cards
     * @return array
     */
    private function sort_cards(array $cards)
    {
        usort($cards, function ($a, $b) {
            if ((int)$a['prob'] == (int)$b['prob']) return 0;
            return ((int)$a['prob'] == (int)$b['prob']) ? 1 : -1;
        });
        return $cards;
    }

    /**
     * @param $cards_nr
     * @param $cards
     * @return array
     */
    private function generate_rand_cards($cards_nr, $cards)
    {
        if (empty($cards_nr)) return array();
        if (!$this->check_settings($cards)) return array();
        $cards_arr = array(); //type, ID
        //we keep run until will fill the array;
        $i = 0;
        while (true) {
            if ($i >= $cards_nr) break;
            $rand_types = $this->generate_rand_types($cards);
            foreach ($rand_types as $rand_type) {
                if ($i >= $cards_nr) break;
                if (!empty($rand_type)) {
                    // the special card didn't pulled, we need to overwrite the "basic" pseudo-type
                    if ($rand_type == 'basic') {
                        $rand_type = $this->overwrite_basic_type();
                        $cards_arr[] = array('type' => $rand_type, 'ID' => FNCP_Card::get_random($rand_type));
                    } else
                        $cards_arr[] = array('type' => $rand_type, 'ID' => FNCP_Card::get_random($rand_type));
                    $i++;
                }
            }
        }
        return $cards_arr;
    }

    /**
     * @param array $cards
     * @return bool
     */
    private function check_settings(array $cards)
    {
        if (empty($cards)) return false;
        $result = false;
        foreach ($cards as $card) {
            $card['prob'] = (float)$card['prob'];
            if (!empty($card['prob'])) return true;
        }
        return $result;
    }

    /**
     * @param $cards
     * @return array
     */
    private function generate_rand_types($cards)
    {
        // We have 4 normal card types
        $rand_cards = array();
        $rand = mt_rand(1, 1000) / 10;
        foreach ($cards as $card) {
            if ($rand <= (float)$card['prob']) {
                $rand_cards[] = $card['type'];
            }
        }
        return $rand_cards;
    }

    // ugly, ugly monkey patch
    /**
     * @return null
     */
    private function overwrite_basic_type()
    {
        $cards_settings = $this->pack->settings['normal'];
        $cards = array();
        //loop through settings since we need the key too
        foreach ($cards_settings as $key => $value) {
            $cards[] = array('prob' => $value, 'type' => $key);
        }
        // we need this, so won't get a gap
        while (true) {
            $rand = mt_rand(1, 1000) / 10;
            foreach ($cards as $card) {
                if ($rand <= (float)$card['prob']) {
                    return $card['type'];
                }
            }
        }
        return null;
    }

    /**
     * @param array $cards
     * @param $packvalue
     * @return array
     */
    private function calibrate_value(array $cards, $packvalue)
    {
        if (empty($packvalue)) return $cards;
        while ($packvalue >= $this->pack->maxwin) {
            //get a new cards with a lower card values
            $cards = $this->downgrade_cards($cards);
            $packvalue = FNCP_Card::get_cards_value($cards);
        }
        return $cards;
    }

    /**
     * @param array $cards
     * @return array
     */
    private function downgrade_cards(array $cards)
    {
        $rand = mt_rand(0, count($cards) - 1);
        // get new lower type card
        $cards[$rand]['type'] = FNCP_Card::downgrade_type($cards[$rand]['type']);
        $cards[$rand]['ID'] = FNCP_Card::get_random($cards[$rand]['type']);

        return $cards;
    }
}
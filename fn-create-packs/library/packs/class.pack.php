<?php

abstract class FNCP_Pack
{
    static $PACKS = array(
        'fncp_25k_pack' => '25k Pack (%no basic, %sp special)',
        'fncp_50k_pack' => '50k Pack (%no basic, %sp special)',
        'fncp_100k_pack' => '100k Pack (%no basic, %sp special)',
        'fncp_totw_pack' => 'TOTW Pack (%no basic, %sp special)',
        'fncp_tots_pack' => 'TOTS Pack (%no basic, %sp special)',
        'fncp_toty_pack' => 'TOTY Pack (%no basic, %sp special)',
        'fncp_hero_pack' => 'Hero Pack (%no basic, %sp special)',
        'fncp_motm_pack' => 'Man of the Match Pack (%no basic, %sp special)',
        'fncp_ninja_pack' => 'Ninja Surprise Me Pack (%no basic, %sp special)',
        'fncp_legend_pack' => 'Legend Pack (%no basic, %sp special)',
    );
    public $fullname;
    public $name;
    public $settings = array();
    public $cards = array();
    public $value;
    public $stdValue;    // Ninja Points, our price
    public $maxwin;
    protected $maxwintimes = 5;

// eventually we could pull these from an admin option
    protected $cards_nr;

    /**
     * @throws ErrorException
     */
    public function set_cards()
    {
        $generator = new FNCP_Cards_Generator(array($this->name));
        $cards = $generator->get_cards();
        if (empty($cards)) throw new ErrorException('Error Code 104: Please try again!');

        foreach ($cards as $card) {
            $this->cards[] = $card;
            $this->value += $card->value;
        }
    }

    /**
     * @return null
     */
    public function get_expensive()
    {
        $value = 0;
        $i = 0;
        $expensive = null;
        foreach ($this->cards as $card) {
            if ($card->value > $value) {
                $value = $card->value;
                $expensive = $card;
                $i2 = $i;
            }
            $i++;
        }
        if (empty($expensive)) {
            $expensive = $this->cards[0];
            unset($this->cards[0]);
            return $expensive;
        }
        unset($this->cards[$i2]);
        return $expensive;
    }

    protected function set_settings()
    {
        $this->settings = get_option('fncp_' . $this->name . '_probs');
    }
}

<?php

class FNCP_25k_Pack extends FNCP_Pack
{
    public $fullname = '25.000 Coins Pack';
    public $name = '25k_pack';
    public $stdValue;
    public $cards_nr;

    function __construct()
    {
        $this->set_settings();
        $this->stdValue = $this->settings['price'];
        $this->cards_nr = $this->settings['cards_nr'];

        $this->maxwin = FNAC_Exchange::points($this->stdValue) * $this->maxwintimes;
    }
}
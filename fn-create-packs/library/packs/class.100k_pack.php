<?php

class FNCP_100k_Pack extends FNCP_Pack
{
    public $fullname = '100.000 Coins Pack';
    public $name = '100k_pack';
    public $stdValue;
    public $cards_nr;

    public function __construct()
    {
        $this->set_settings();
        $this->stdValue = $this->settings['price'];
        $this->cards_nr = $this->settings['cards_nr'];

         $this->maxwin = FNAC_Exchange::points($this->stdValue) * $this->maxwintimes;
    }
}
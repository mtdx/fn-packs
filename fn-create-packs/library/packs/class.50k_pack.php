<?php

class FNCP_50k_Pack extends FNCP_Pack
{
    public $fullname = '50.000 Coins Pack';
    public $name = '50k_pack';
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
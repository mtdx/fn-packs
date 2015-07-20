<?php

class FNCP_Motm_Pack extends FNCP_Pack
{
    public $fullname = 'Man of the Match Pack';
    public $name = 'motm_pack';
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
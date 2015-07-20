<?php

class FNCP_Totw_Pack extends FNCP_Pack
{
    public $fullname = 'Team of the Week Pack';
    public $name = 'totw_pack';
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
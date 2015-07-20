<?php

class FNCP_Tots_Pack extends FNCP_Pack
{
    public $fullname = 'Team of the Season Pack';
    public $name = 'tots_pack';
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
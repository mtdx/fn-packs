<?php

class FNCP_Ninja_Pack extends FNCP_Pack
{
    public $fullname = 'Ninja Surprise Me Pack';
    public $name = 'ninja_pack';
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
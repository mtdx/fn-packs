<?php

class FNCP_Packs_Factory
{
    /**
     * @param array $pack_types
     * @return array
     * @throws Exception
     */
    public static function build(array $pack_types)
    {
        if (!is_array($pack_types)) return array();
        $packs = array();
        foreach ($pack_types as $pack_type) {
            if (strpos($pack_type, 'fncp_') !== false) {
                $pack = str_replace('fncp_', 'FNCP_', ucwords($pack_type));
            } else {
                $pack = 'FNCP_' . ucwords($pack_type);
            }
            if (class_exists($pack)) {
                $packs[] = new $pack();
            } else {
                throw new Exception('Invalid Pack Types Given: ' . $pack);
            }
        }
        if (count($pack_types) == 1) return $packs[0];
        return $packs;
    }
}

<?php

class Temando_Temando_Model_System_Config_Source_Shipment_Class extends Temando_Temando_Model_System_Config_Source
{
    
    const GENERAL_GOODS = 0;
    const FREIGHT       = 1;
    const VEHICLE       = 2;
    const BOAT          = 3;
    const ANIMAL        = 4;
    const REFRIGERATED  = 5;
    const OTHER         = 6;
    
    protected function _setupOptions()
    {
        $this->_options = array(
            self::GENERAL_GOODS => 'General Goods',
            self::FREIGHT       => 'Freight',
            self::VEHICLE       => 'Vehicle',
            self::BOAT          => 'Boat',
            self::ANIMAL        => 'Animal',
            self::REFRIGERATED  => 'Refrigerated',
            self::OTHER         => 'Other',
        );
    }
    
}

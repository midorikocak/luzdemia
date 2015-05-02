<?php

class Temando_Temando_Model_System_Config_Source_Shipment_Service extends Temando_Temando_Model_System_Config_Source
{
    
    const SAME_DAY	= 1;
    const EXPRESS       = 2;
    const STANDARD      = 3;
    
    protected function _setupOptions()
    {
        $this->_options = array(
            self::SAME_DAY	=> Mage::helper('temando')->__('Same Day'),
            self::EXPRESS       => Mage::helper('temando')->__('Express'),
            self::STANDARD      => Mage::helper('temando')->__('Standard'),
        );
    }
    
}
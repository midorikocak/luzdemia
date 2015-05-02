<?php

class Temando_Temando_Model_System_Config_Source_Shipment_Packaging_Mode extends Temando_Temando_Model_System_Config_Source
{
    const USE_DEFAULTS	= 0;
    const AS_DEFINED	= 1;
    
    protected function _setupOptions()
    {
        $this->_options = array(
            self::USE_DEFAULTS	=> Mage::helper('temando')->__('Use Defaults'),
            self::AS_DEFINED	=> Mage::helper('temando')->__('As Defined'),
        );
    }
    
}

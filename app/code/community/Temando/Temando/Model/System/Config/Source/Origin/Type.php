<?php

class Temando_Temando_Model_System_Config_Source_Origin_Type extends Temando_Temando_Model_System_Config_Source
{
    
    const BUSINESS    = 'Business';
    const RESIDENTIAL = 'Residence';
    
    public function _setupOptions()
    {
        $this->_options = array(
            self::BUSINESS    => Mage::helper('temando')->__('Business'),
            self::RESIDENTIAL => Mage::helper('temando')->__('Residential'),
        );
    }
    
}
